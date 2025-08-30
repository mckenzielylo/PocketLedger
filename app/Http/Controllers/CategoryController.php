<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $categories = $user->categories()
            ->with(['children', 'parent'])
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');
            
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $categories = $user->categories()
            ->where('type', 'expense')
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();
            
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        
        // Check if category with same name and type already exists
        $existingCategory = $user->categories()
            ->where('name', $request->name)
            ->where('type', $request->type)
            ->first();
            
        if ($existingCategory) {
            return back()->withErrors(['name' => 'A category with this name and type already exists.'])->withInput();
        }

        $category = $user->categories()->create([
            'name' => $request->name,
            'type' => $request->type,
            'color' => $request->color,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        Gate::authorize('view', $category);
        
        $category->load(['children', 'parent', 'transactions' => function ($query) {
            $query->latest('occurred_on')->limit(10);
        }]);
        
        // Get spending statistics for the last 6 months
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('Y-m');
            $monthName = $date->format('M Y');
            
            $spending = $category->transactions()
                ->where('type', 'expense')
                ->whereYear('occurred_on', $date->year)
                ->whereMonth('occurred_on', $date->month)
                ->sum('amount');
                
            $income = $category->transactions()
                ->where('type', 'income')
                ->whereYear('occurred_on', $date->year)
                ->whereMonth('occurred_on', $date->month)
                ->sum('amount');
                
            $monthlyStats[] = [
                'month' => $month,
                'monthName' => $monthName,
                'spending' => $spending,
                'income' => $income,
                'net' => $income - $spending,
            ];
        }
        
        return view('categories.show', compact('category', 'monthlyStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);
        
        $user = Auth::user();
        $categories = $user->categories()
            ->where('type', $category->type)
            ->where('id', '!=', $category->id)
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();
            
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:500',
        ]);

        // Check if category with same name and type already exists (excluding current)
        $existingCategory = $category->user->categories()
            ->where('name', $request->name)
            ->where('type', $request->type)
            ->where('id', '!=', $category->id)
            ->first();
            
        if ($existingCategory) {
            return back()->withErrors(['name' => 'A category with this name and type already exists.'])->withInput();
        }

        // Prevent circular references in parent_id
        if ($request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.'])->withInput();
        }

        $category->update([
            'name' => $request->name,
            'type' => $request->type,
            'color' => $request->color,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);
        
        // Check if category has transactions
        if ($category->transactions()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete category with existing transactions.']);
        }
        
        // Check if category has children
        if ($category->children()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete category with subcategories.']);
        }
        
        // Check if category is used in budgets
        if ($category->budgetCategories()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete category used in budgets.']);
        }
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Toggle archive status of the category.
     */
    public function toggleArchive(Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);
        
        $category->update([
            'is_archived' => !$category->is_archived
        ]);

        $status = $category->is_archived ? 'archived' : 'activated';
        return redirect()->route('categories.index')
            ->with('success', "Category {$status} successfully.");
    }

    /**
     * Get categories for a specific type (AJAX endpoint).
     */
    public function getByType(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'type' => 'required|in:income,expense'
        ]);

        $user = Auth::user();
        $categories = $user->categories()
            ->where('type', $request->type)
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['id', 'name', 'color', 'icon']);

        return response()->json($categories);
    }
}
