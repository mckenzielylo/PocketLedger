<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Debt;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get accounts with balances
        $accounts = $user->accounts()->active()->get();
        $totalBalance = $accounts->sum('current_balance');
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with(['account', 'category'])
            ->orderBy('occurred_on', 'desc')
            ->limit(10)
            ->get();
        
        // Get current month budget
        $currentMonth = now()->format('Y-m');
        $budget = $user->budgets()->forMonth($currentMonth)->first();
        
        // Get all budgets for overview
        $budgets = $user->budgets()->orderBy('month', 'desc')->get();
        
        // Get debts summary
        $debts = $user->debts()->active()->get();
        $totalDebt = $debts->sum('current_balance');
        
        // Get assets summary
        $assets = $user->assets()->get();
        $totalAssets = $assets->sum('current_value');
        
        // Calculate net worth
        $netWorth = $totalBalance + $totalAssets - $totalDebt;
        
        // Calculate monthly income and expenses for current month
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        
        $monthlyIncome = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('occurred_on', [$monthStart, $monthEnd])
            ->sum('amount');
            
        $monthlyExpenses = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('occurred_on', [$monthStart, $monthEnd])
            ->sum('amount');
        
        return view('dashboard.index', compact(
            'accounts',
            'totalBalance',
            'recentTransactions',
            'budget',
            'budgets',
            'debts',
            'totalDebt',
            'assets',
            'totalAssets',
            'netWorth',
            'monthlyIncome',
            'monthlyExpenses'
        ));
    }

    /**
     * Show the reports page.
     */
    public function reports(): View
    {
        try {
            $user = Auth::user();
            
            // Get date range for reports (last 12 months)
            $endDate = now();
            $startDate = now()->subMonths(11)->startOfMonth();
            
            // Get cashflow data
            $cashflowData = $this->getCashflowData($user, $startDate, $endDate);
            
            // Get category breakdown for current month
            $categoryBreakdown = $this->getCategoryBreakdown($user, now()->format('Y-m'));
            
            // Get net worth data
            $netWorthData = $this->getNetWorthData($user, $startDate, $endDate);
            
            return view('dashboard.reports', compact(
                'cashflowData',
                'categoryBreakdown',
                'netWorthData'
            ));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Reports page error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a simple error view or redirect
            return redirect()->route('dashboard')->with('error', 'Unable to load reports. Please try again later.');
        }
    }

    /**
     * Get cashflow data for charts.
     */
    private function getCashflowData($user, $startDate, $endDate): array
    {
        try {
            $months = [];
            $income = [];
            $expenses = [];
            
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $monthKey = $current->format('Y-m');
                $months[] = $current->format('M Y');
                
                $monthStart = $current->startOfMonth();
                $monthEnd = $current->copy()->endOfMonth();
                
                $monthIncome = $user->transactions()
                    ->where('type', 'income')
                    ->whereBetween('occurred_on', [$monthStart, $monthEnd])
                    ->sum('amount');
                    
                $monthExpenses = $user->transactions()
                    ->where('type', 'expense')
                    ->whereBetween('occurred_on', [$monthStart, $monthEnd])
                    ->sum('amount');
                
                $income[] = $monthIncome ?? 0;
                $expenses[] = $monthExpenses ?? 0;
                
                $current->addMonth();
            }
            
            return [
                'months' => $months,
                'income' => $income,
                'expenses' => $expenses,
            ];
        } catch (\Exception $e) {
            \Log::error('Cashflow data error: ' . $e->getMessage());
            return [
                'months' => [],
                'income' => [],
                'expenses' => [],
            ];
        }
    }

    /**
     * Get category breakdown for a specific month.
     */
    private function getCategoryBreakdown($user, $month): array
    {
        try {
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $categories = $user->categories()
                ->where('type', 'expense')
                ->with(['transactions' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('occurred_on', [$startDate, $endDate]);
                }])
                ->get();
            
            $data = [];
            foreach ($categories as $category) {
                $total = $category->transactions->sum('amount');
                if ($total > 0) {
                    $data[] = [
                        'name' => $category->name,
                        'value' => $total,
                        'color' => $category->color,
                    ];
                }
            }
            
            return $data;
        } catch (\Exception $e) {
            \Log::error('Category breakdown error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get net worth data for charts.
     */
    private function getNetWorthData($user, $startDate, $endDate): array
    {
        try {
            $months = [];
            $netWorth = [];
            
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $monthKey = $current->format('Y-m');
                $months[] = $current->format('M Y');
                
                $monthEnd = $current->copy()->endOfMonth();
                
                // Get account balances at month end
                $accountBalances = $user->accounts()
                    ->where('created_at', '<=', $monthEnd)
                    ->get()
                    ->sum('current_balance');
                
                // Get assets at month end
                $assetValues = $user->assets()
                    ->where('created_at', '<=', $monthEnd)
                    ->get()
                    ->sum('current_value');
                
                // Get debts at month end
                $debtBalances = $user->debts()
                    ->where('created_at', '<=', $monthEnd)
                    ->whereNull('closed_on')
                    ->get()
                    ->sum('current_balance');
                
                $monthNetWorth = ($accountBalances ?? 0) + ($assetValues ?? 0) - ($debtBalances ?? 0);
                $netWorth[] = $monthNetWorth;
                
                $current->addMonth();
            }
            
            return [
                'months' => $months,
                'netWorth' => $netWorth,
            ];
        } catch (\Exception $e) {
            \Log::error('Net worth data error: ' . $e->getMessage());
            return [
                'months' => [],
                'netWorth' => [],
            ];
        }
    }
}
