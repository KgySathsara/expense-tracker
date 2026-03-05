<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-4 shadow-lg text-white">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Today</p>
                <p class="text-2xl font-bold mt-1">{{ auth()->user()->currency }}{{ number_format($dailyTotal, 2) }}</p>
                <p class="text-blue-200 text-xs mt-1">Expenses</p>
            </div>
            <div class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl p-4 shadow-lg text-white">
                <p class="text-xs font-semibold uppercase tracking-wider text-teal-200">This Week</p>
                <p class="text-2xl font-bold mt-1">{{ auth()->user()->currency }}{{ number_format($weeklyTotal, 2) }}
                </p>
                <p class="text-teal-200 text-xs mt-1">Expenses</p>
            </div>
            <div class="bg-gradient-to-br from-rose-500 to-rose-700 rounded-2xl p-4 shadow-lg text-white">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-200">This Month</p>
                <p class="text-2xl font-bold mt-1">{{ auth()->user()->currency }}{{ number_format($monthlyTotal, 2) }}
                </p>
                <p class="text-rose-200 text-xs mt-1">Expenses</p>
            </div>
            <div class="bg-gradient-to-br from-violet-500 to-violet-700 rounded-2xl p-4 shadow-lg text-white">
                <p class="text-xs font-semibold uppercase tracking-wider text-violet-200">Remaining</p>
                <p class="text-2xl font-bold mt-1">
                    {{ auth()->user()->currency }}{{ number_format($remainingSalary, 2) }}</p>
                <p class="text-violet-200 text-xs mt-1">Balance</p>
            </div>
        </div>

        {{-- Income Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Monthly Income</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-1">
                        {{ auth()->user()->currency }}{{ number_format($monthlyIncome, 2) }}</p>
                    <p class="text-sm text-gray-500 mt-1">Record your income to keep balance accurate</p>
                </div>
                <div class="h-14 w-14 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Recent Notes --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Recent Notes</h3>
                <a href="{{ route('notes.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">View
                    All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentNotes as $note)
                    <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-100/50 flex items-start gap-3">
                        <div class="mt-1">
                            <svg class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-amber-800">{{ $note->date->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-600 line-clamp-2 mt-0.5">{{ $note->content }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-4">No notes recently.</p>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('incomes.create') }}"
                class="flex flex-col items-center justify-center p-5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-2xl shadow-lg transition-all duration-150 text-center">
                <svg class="h-7 w-7 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="font-semibold text-sm">Add Income</span>
            </a>
            <a href="{{ route('expenses.create') }}"
                class="flex flex-col items-center justify-center p-5 bg-gray-800 hover:bg-gray-900 active:scale-95 text-white rounded-2xl shadow-lg transition-all duration-150 text-center">
                <svg class="h-7 w-7 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
                <span class="font-semibold text-sm">Add Expense</span>
            </a>
        </div>

        {{-- Expense Trend Chart --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Expense Trend (Last 7 Days)</h3>
            <div class="relative h-56 w-full">
                <canvas id="expenseChart"></canvas>
            </div>
        </div>

        {{-- Navigation Shortcuts --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('incomes.index') }}"
                class="flex items-center gap-3 bg-white border border-gray-100 shadow-sm rounded-2xl px-4 py-4 hover:bg-gray-50 active:scale-95 transition-all">
                <div class="h-10 w-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Incomes</p>
                    <p class="text-xs text-gray-400">View all records</p>
                </div>
            </a>
            <a href="{{ route('expenses.index') }}"
                class="flex items-center gap-3 bg-white border border-gray-100 shadow-sm rounded-2xl px-4 py-4 hover:bg-gray-50 active:scale-95 transition-all">
                <div class="h-10 w-10 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Expenses</p>
                    <p class="text-xs text-gray-400">View all records</p>
                </div>
            </a>
        </div>

    </div>

    <!-- Chart.js and script to initialize the chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('expenseChart').getContext('2d');
            var expenseChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($last7Days) !!},
                    datasets: [{
                        label: 'Daily Expenses',
                        data: {!! json_encode($last7DaysData) !!},
                        backgroundColor: 'rgba(225, 29, 72, 0.1)', // Rose 600 with opacity
                        borderColor: 'rgba(225, 29, 72, 1)', // Rose 600
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(225, 29, 72, 1)',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '{{ auth()->user()->currency }}' + parseFloat(context.raw)
                                        .toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                callback: function(value, index, values) {
                                    return '{{ auth()->user()->currency }}' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
