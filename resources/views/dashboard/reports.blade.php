@extends('layouts.app', ['currentPage' => 'reports'])

@section('content')
<x-page-layout 
    :title="'Reports & Analytics'" 
    :description="'Financial insights and trends'"
    :icon="'<svg class=\"w-6 h-6 text-primary\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\"></path></svg>'"
    :breadcrumbs="'<a href=\"' . route('dashboard') . '\" class=\"text-muted-foreground hover:text-foreground\">Dashboard</a> / <span class=\"text-foreground\">Reports</span>'"
>
    <!-- Cashflow Chart -->
    <x-ui.card class="mb-6">
        <x-ui.card-header>
            <x-ui.card-title>Cashflow (Last 12 Months)</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="h-64">
                <canvas id="cashflowChart"></canvas>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Category Breakdown Chart -->
    <x-ui.card class="mb-6">
        <x-ui.card-header>
            <x-ui.card-title>Category Breakdown ({{ now()->format('M Y') }})</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Net Worth Chart -->
    <x-ui.card class="mb-6">
        <x-ui.card-header>
            <x-ui.card-title>Net Worth Trend (Last 12 Months)</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="h-64">
                <canvas id="netWorthChart"></canvas>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Export Options -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Export Data</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-ui.button variant="outline" class="flex items-center justify-center p-4 h-auto">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Transactions
                </x-ui.button>
                <x-ui.button variant="outline" class="flex items-center justify-center p-4 h-auto">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Assets
                </x-ui.button>
                <x-ui.button variant="outline" class="flex items-center justify-center p-4 h-auto">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Debts
                </x-ui.button>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</x-page-layout>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
// Cashflow Chart
const cashflowCtx = document.getElementById('cashflowChart').getContext('2d');
new Chart(cashflowCtx, {
    type: 'bar',
    data: {
        labels: @json($cashflowData['months']),
        datasets: [
            {
                label: 'Income',
                data: @json($cashflowData['income']),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1
            },
            {
                label: 'Expenses',
                data: @json($cashflowData['expenses']),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '{{ Auth::user()->preferred_currency_symbol }} ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': {{ Auth::user()->preferred_currency_symbol }} ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        }
    }
});

// Category Breakdown Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: @json(array_column($categoryBreakdown, 'name')),
        datasets: [{
            data: @json(array_column($categoryBreakdown, 'value')),
            backgroundColor: @json(array_column($categoryBreakdown, 'color')),
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': {{ Auth::user()->preferred_currency_symbol }} ' + new Intl.NumberFormat('id-ID').format(context.parsed) + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Net Worth Chart
const netWorthCtx = document.getElementById('netWorthChart').getContext('2d');
new Chart(netWorthCtx, {
    type: 'line',
    data: {
        labels: @json($netWorthData['months']),
        datasets: [{
            label: 'Net Worth',
            data: @json($netWorthData['netWorth']),
            borderColor: 'rgba(59, 130, 246, 1)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    callback: function(value) {
                        return '{{ Auth::user()->preferred_currency_symbol }} ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Net Worth: {{ Auth::user()->preferred_currency_symbol }} ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        }
    }
});
</script>
@endsection
