<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .card-income::before {
            background-color: #10b981;
        }

        .card-expense-today::before {
            background-color: #f43f5e;
        }

        .card-expense-weekly::before {
            background-color: #6366f1;
        }

        .card-remaining::before {
            background-color: #f59e0b;
        }

        @media (max-width: 640px) {
            .mobile-scroll-container {
                display: flex;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                padding-bottom: 1rem;
                gap: 1rem;
                margin-left: -1rem;
                margin-right: -1rem;
                padding-left: 1rem;
                padding-right: 1rem;
                scrollbar-width: none;
            }

            .mobile-scroll-container::-webkit-scrollbar {
                display: none;
            }

            .stat-card {
                flex: 0 0 calc(85% - 1rem);
                scroll-snap-align: center;
                min-width: 250px;
            }
        }

        .action-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
        }

        .action-card:hover {
            border-color: #6366f1;
            background-color: #f5f3ff;
        }

        .chart-container {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            padding: 2rem;
        }
    </style>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Financial Overview</h1>
                    <p class="text-gray-500 mt-1">Hello, {{ auth()->user()->name }}. Here's what's happening with your
                        money.</p>
                </div>
                {{-- Quick AI Insight Tip --}}
                <div
                    class="bg-indigo-50 border border-indigo-100 rounded-2xl px-6 py-4 flex items-center gap-4 max-w-md shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white animate-pulse" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">AI Agent Quick Tip
                        </p>
                        <p class="text-xs text-slate-700 font-medium">Keep your savings rate above 20% this month to
                            reach your income goals faster!</p>
                    </div>
                </div>
            </div>

            {{-- Main Stats Grid (Carousel on Mobile) --}}
            <div x-data="{
                activeSlide: 0,
                slides: 4,
                scrollTo(index) {
                    this.activeSlide = index;
                    const container = this.$refs.carousel;
                    const slideWidth = container.firstElementChild.offsetWidth + 16;
                    container.scrollTo({ left: index * slideWidth, behavior: 'smooth' });
                },
                updateActiveSlide() {
                    const container = this.$refs.carousel;
                    const slideWidth = container.firstElementChild.offsetWidth + 16;
                    this.activeSlide = Math.round(container.scrollLeft / slideWidth);
                }
            }" class="relative">
                <div x-ref="carousel" @scroll.debounce.100ms="updateActiveSlide()"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mobile-scroll-container">
                    {{-- Current Month Income --}}
                    <div class="stat-card card-income">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Month Income</p>
                            <div class="bg-emerald-50 p-2 rounded-lg text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span
                                class="text-3xl font-bold text-gray-900 tracking-tight">{{ auth()->user()->currency }}{{ number_format($monthlyIncome, 0) }}</span>
                        </div>
                        <div
                            class="mt-4 flex items-center gap-2 text-emerald-600 text-[10px] font-bold bg-emerald-50 w-fit px-2 py-1 rounded-md">
                            <span>DEPOSITED THIS MONTH</span>
                        </div>
                    </div>

                    {{-- Today's Expenses --}}
                    <div class="stat-card card-expense-today">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Spent Today</p>
                            <div class="bg-rose-50 p-2 rounded-lg text-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span
                                class="text-3xl font-bold text-gray-900 tracking-tight">{{ auth()->user()->currency }}{{ number_format($dailyTotal, 0) }}</span>
                        </div>
                        <div
                            class="mt-4 flex items-center gap-2 text-rose-600 text-[10px] font-bold bg-rose-50 w-fit px-2 py-1 rounded-md">
                            <span>OUTGOING TODAY</span>
                        </div>
                    </div>

                    {{-- Weekly Expenses --}}
                    <div class="stat-card card-expense-weekly">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Weekly Total</p>
                            <div class="bg-indigo-50 p-2 rounded-lg text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span
                                class="text-3xl font-bold text-gray-900 tracking-tight">{{ auth()->user()->currency }}{{ number_format($weeklyTotal, 0) }}</span>
                        </div>
                        <div
                            class="mt-4 flex items-center gap-2 text-indigo-600 text-[10px] font-bold bg-indigo-50 w-fit px-2 py-1 rounded-md">
                            <span>LAST 7 DAYS TOTAL</span>
                        </div>
                    </div>

                    {{-- Remaining Balance --}}
                    <div class="stat-card card-remaining">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Remaining</p>
                            <div
                                class="{{ $remainingSalary >= 0 ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600' }} p-2 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 6l3 11h11l3-11H3z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span
                                class="text-3xl font-bold tracking-tight {{ $remainingSalary >= 0 ? 'text-amber-500' : 'text-rose-600' }}">
                                {{ auth()->user()->currency }}{{ number_format($remainingSalary, 0) }}
                            </span>
                        </div>
                        <div
                            class="mt-4 flex items-center gap-2 text-amber-600 text-[10px] font-bold bg-amber-50 w-fit px-2 py-1 rounded-md">
                            <span>AVAILABLE BALANCE</span>
                        </div>
                    </div>
                </div>

                {{-- Carousel Pagination Dots (Mobile Only) --}}
                <div class="flex justify-center gap-2 mt-4 md:hidden">
                    <template x-for="(i, index) in slides" :key="index">
                        <button @click="scrollTo(index)" class="w-2 h-2 rounded-full transition-all duration-300"
                            :class="activeSlide === index ? 'w-6 bg-indigo-600' : 'bg-gray-300'"></button>
                    </template>
                </div>
            </div>

            {{-- Chart & Secondary Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Chart --}}
                <div class="lg:col-span-2 chart-container">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-bold text-gray-900">Income vs Expenses</h3>
                        <div class="flex gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                                <span class="text-xs font-semibold text-gray-500">Income</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                <span class="text-xs font-semibold text-gray-500">Expense</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-72">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>

                {{-- Side Section: Quick Links & Recent Notes --}}
                <div class="space-y-6">
                    {{-- Quick Actions --}}
                    <div class="space-y-3">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Quick Actions</h3>
                        <a href="{{ route('incomes.create') }}" class="action-card">
                            <div
                                class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800">Add New Income</span>
                        </a>
                        <a href="javascript:void(0)" @click="window.dispatchEvent(new CustomEvent('open-ai-chat'))"
                            class="action-card">
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800">Chat with AI Agent</span>
                        </a>
                    </div>

                    {{-- Recent Notes --}}
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Recent Notes</h3>
                            <a href="{{ route('notes.index') }}"
                                class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800">View All</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentNotes as $note)
                                <div class="p-4 bg-white border border-gray-100 rounded-xl">
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase mb-1">
                                        {{ $note->date->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                        {{ $note->content }}</p>
                                </div>
                            @empty
                                <div class="text-center py-6 border-2 border-dashed border-gray-100 rounded-xl">
                                    <p class="text-xs text-gray-400">No recent notes</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('mainChart').getContext('2d');

            // Modern palette
            const incomeColor = '#6366f1'; // Indigo 500
            const expenseColor = '#f43f5e'; // Rose 500

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($last7Days) !!},
                    datasets: [{
                            label: 'Income',
                            data: {!! json_encode($last7DaysIncomeData) !!},
                            borderColor: incomeColor,
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: incomeColor,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4
                        },
                        {
                            label: 'Expense',
                            data: {!! json_encode($last7DaysExpenseData) !!},
                            borderColor: expenseColor,
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: expenseColor,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.dataset.label + ': ' +
                                        '{{ auth()->user()->currency }}' + context.raw
                                        .toLocaleString();
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
                                    size: 10,
                                    weight: '600'
                                },
                                color: '#94a3b8'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    weight: '600'
                                },
                                color: '#94a3b8',
                                padding: 10,
                                callback: value => '{{ auth()->user()->currency }}' + value
                                    .toLocaleString()
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
</x-app-layout>
