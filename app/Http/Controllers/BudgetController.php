<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $budgets = $user->budgets()
            ->with(['budgetCategories.category'])
            ->orderBy('month', 'desc')
            ->get();
            
        return view('budgets.index', compact('budgets'));
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
            
        return view('budgets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'categories' => 'required|array|min:1',
            'categories.*.category_id' => 'required|exists:categories,id',
            'categories.*.amount' => 'required|numeric|min:0.01',
        ], [
            'categories.required' => 'At least one budget category is required.',
            'categories.min' => 'At least one budget category is required.',
            'categories.*.category_id.required' => 'Category selection is required.',
            'categories.*.amount.required' => 'Amount is required for each category.',
            'categories.*.amount.min' => 'Amount must be greater than 0.',
        ]);

        $user = Auth::user();
        
        // Check if budget already exists for this month/year
        $month = sprintf('%04d-%02d', $request->year, $request->month);
        $existingBudget = $user->budgets()
            ->where('month', $month)
            ->first();
            
        if ($existingBudget) {
            return back()->withErrors(['month' => 'A budget already exists for this month and year.']);
        }
        
        // Filter and validate categories before processing
        $validCategories = collect($request->categories ?? [])->filter(function ($category) {
            return isset($category['category_id']) && 
                   isset($category['amount']) && 
                   !empty($category['category_id']) && 
                   !empty($category['amount']) && 
                   is_numeric($category['amount']) && 
                   $category['amount'] > 0;
        });
        
        if ($validCategories->isEmpty()) {
            return back()->withErrors(['categories' => 'At least one category with a valid amount is required.'])->withInput();
        }

        DB::transaction(function () use ($request, $user, $validCategories) {
            $month = sprintf('%04d-%02d', $request->year, $request->month);
            
            $budget = $user->budgets()->create([
                'month' => $month,
                'total_limit' => $validCategories->sum('amount'),
            ]);
            
            foreach ($validCategories as $categoryData) {
                $budget->budgetCategories()->create([
                    'category_id' => (int) $categoryData['category_id'],
                    'limit_amount' => (float) $categoryData['amount'],
                ]);
            }
        });

        return redirect()->route('budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget): View
    {
        Gate::authorize('view', $budget);
        
        $budget->load(['budgetCategories.category']);
        
        // Calculate spending for each category
        $budget->budgetCategories->each(function ($budgetCategory) use ($budget) {
            $monthParts = explode('-', $budget->month);
            $year = $monthParts[0];
            $month = $monthParts[1];
            
            $spending = $budgetCategory->category->transactions()
                ->where('type', 'expense')
                ->whereYear('occurred_on', $year)
                ->whereMonth('occurred_on', $month)
                ->sum('amount');
                
            $budgetCategory->spending = $spending;
            $budgetCategory->remaining = $budgetCategory->limit_amount - $spending;
            $budgetCategory->percentage = $budgetCategory->limit_amount > 0 ? ($spending / $budgetCategory->limit_amount) * 100 : 0;
        });
        
        return view('budgets.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget): View
    {
        Gate::authorize('update', $budget);
        
        $user = Auth::user();
        $categories = $user->categories()
            ->where('type', 'expense')
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();
            
        $budget->load('budgetCategories');
        
        return view('budgets.edit', compact('budget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget): RedirectResponse
    {
        Gate::authorize('update', $budget);
        
        $request->validate([
            'categories' => 'required|array|min:1',
            'categories.*.category_id' => 'required|exists:categories,id',
            'categories.*.amount' => 'required|numeric|min:0.01',
        ]);

        // Filter and validate categories before processing
        $validCategories = collect($request->categories ?? [])->filter(function ($category) {
            return isset($category['category_id']) && 
                   isset($category['amount']) && 
                   !empty($category['category_id']) && 
                   !empty($category['amount']) && 
                   is_numeric($category['amount']) && 
                   $category['amount'] > 0;
        });
        
        if ($validCategories->isEmpty()) {
            return back()->withErrors(['categories' => 'At least one category with a valid amount is required.'])->withInput();
        }

        DB::transaction(function () use ($budget, $validCategories) {
            // Delete existing budget categories
            $budget->budgetCategories()->delete();
            
            // Create new budget categories
            foreach ($validCategories as $categoryData) {
                $budget->budgetCategories()->create([
                    'category_id' => (int) $categoryData['category_id'],
                    'limit_amount' => (float) $categoryData['amount'],
                ]);
            }
            
            // Update total limit
            $budget->update([
                'total_limit' => $validCategories->sum('amount'),
            ]);
        });

        return redirect()->route('budgets.show', $budget)
            ->with('success', 'Budget updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget): RedirectResponse
    {
        Gate::authorize('delete', $budget);
        
        $budget->budgetCategories()->delete();
        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }

    /**
     * Display budget categories overview.
     */
    public function categories(): View
    {
        $user = Auth::user();
        
        // Get all budget categories with relationships - simplified approach
        $budgetCategories = BudgetCategory::with(['budget', 'category'])
            ->whereHas('budget', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();
        
        // Calculate spending for each category
        $budgetCategories->each(function ($budgetCategory) {
            $monthParts = explode('-', $budgetCategory->budget->month);
            $year = $monthParts[0];
            $month = $monthParts[1];
            
            $spending = $budgetCategory->category->transactions()
                ->where('type', 'expense')
                ->whereYear('occurred_on', $year)
                ->whereMonth('occurred_on', $month)
                ->sum('amount');
                
            $budgetCategory->spending = $spending;
            $budgetCategory->remaining = $budgetCategory->limit_amount - $spending;
            $budgetCategory->percentage = $budgetCategory->limit_amount > 0 ? ($spending / $budgetCategory->limit_amount) * 100 : 0;
        });
        
        // Get available months and categories for filters
        $months = $user->budgets()->pluck('month')->unique()->sort()->values();
        $categories = $user->categories()
            ->where('type', 'expense')
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();
        
        // Debug: Log the data
        \Log::info('Budget Categories Count: ' . $budgetCategories->count());
        \Log::info('First Budget Category: ', $budgetCategories->first() ? $budgetCategories->first()->toArray() : 'null');
        
        return view('budgets.categories', compact('budgetCategories', 'months', 'categories'));
    }

    /**
     * Copy budget from previous month.
     */
    public function copyFromPrevious(Request $request): RedirectResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $user = Auth::user();
        
        // Calculate previous month
        $date = \Carbon\Carbon::create($request->year, $request->month, 1)->subMonth();
        $prevYear = $date->year;
        $prevMonth = $date->month;
        $prevMonthStr = sprintf('%04d-%02d', $prevYear, $prevMonth);
        
        $previousBudget = $user->budgets()
            ->where('month', $prevMonthStr)
            ->with('budgetCategories')
            ->first();
            
        if (!$previousBudget) {
            return back()->withErrors(['copy' => 'No budget found for the previous month to copy from.']);
        }
        
        // Check if budget already exists for target month
        $targetMonth = sprintf('%04d-%02d', $request->year, $request->month);
        $existingBudget = $user->budgets()
            ->where('month', $targetMonth)
            ->first();
            
        if ($existingBudget) {
            return back()->withErrors(['month' => 'A budget already exists for this month and year.']);
        }
        
        DB::transaction(function () use ($request, $user, $previousBudget, $targetMonth) {
            $budget = $user->budgets()->create([
                'month' => $targetMonth,
                'total_limit' => $previousBudget->total_limit,
            ]);
            
            foreach ($previousBudget->budgetCategories as $budgetCategory) {
                $budget->budgetCategories()->create([
                    'category_id' => $budgetCategory->category_id,
                    'limit_amount' => $budgetCategory->limit_amount,
                ]);
            }
        });

        return redirect()->route('budgets.index')
            ->with('success', 'Budget copied from previous month successfully.');
    }
}
