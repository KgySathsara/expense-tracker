<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --ai-primary: #6366f1;
            --ai-secondary: #a855f7;
            --ai-bg: #0f172a;
        }

        .ai-body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--ai-bg);
            color: #f8fafc;
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
        }

        .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-glow {
            box-shadow: 0 0 40px -10px rgba(99, 102, 241, 0.3);
        }

        .ai-orb {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, rgba(15, 23, 42, 0) 70%);
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
            animation: float 20s infinite alternate;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(100px, 50px);
            }
        }

        .card-premium {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-premium:hover {
            border-color: rgba(99, 102, 241, 0.5);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.03) 100%);
            transform: scale(1.02);
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.5);
        }

        .nav-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-dot.active {
            background: var(--ai-primary);
            box-shadow: 0 0 10px var(--ai-primary);
        }
    </style>

    <div class="ai-body min-h-screen relative overflow-hidden pb-12">
        <!-- Floating Elements -->
        <div class="ai-orb top-[-100px] right-[-100px]"></div>
        <div class="ai-orb bottom-[-200px] left-[-100px]"
            style="background: radial-gradient(circle, rgba(168, 85, 247, 0.15) 0%, rgba(15, 23, 42, 0) 70%);"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-12">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div class="max-w-xl">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M11.3 1.047a1 1 0 01.897.95V4.6a5.002 5.002 0 00-3.35 4.54l-1.077 1.077A1 1 0 016.3 11a1 1 0 01-1.414-1.414l.707-.707A3 3 0 016 7V2.5a1 1 0 011.3-1.453zm6.4 6.4a1 1 0 01-.253 1.453l-1.453.727A3 3 0 0114 7V2.5a1 1 0 011.3-1.453A1 1 0 0117.7 7.447zM11 18a2 2 0 11-4 0h4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-[0.2em]">Neural
                            Intelligence Engine v2.0</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">Your Financial <span
                            class="gradient-text">Future, Predicted.</span></h1>
                    <p class="text-slate-400 text-lg leading-relaxed">Advanced machine learning analysis of your cash
                        flow patterns with actionable strategic advice.</p>
                </div>

                <div class="hidden md:flex gap-4">
                    <div class="glass-panel p-6 text-center stat-glow">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Health Score</p>
                        <p class="text-3xl font-bold {{ $savingsRate > 20 ? 'text-emerald-400' : 'text-amber-400' }}">
                            {{ round($savingsRate) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Forecast Matrix -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                <div class="lg:col-span-2">
                    <h2
                        class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] mb-6 flex items-center gap-3">
                        <span class="w-12 h-[1px] bg-slate-800"></span>
                        Next Month Projections
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="card-premium p-8 rounded-[2rem]">
                            <p class="text-[10px] font-bold text-slate-500 mb-4 tracking-widest">INCOME PREDICTION</p>
                            <h3 class="text-3xl font-bold">
                                {{ auth()->user()->currency }}{{ number_format($prediction['income'], 0) }}</h3>
                            <div class="mt-4 flex items-center gap-2 text-emerald-400 text-[10px] font-bold uppercase">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 10l7-7 7 7M5 14l7 7 7-7" stroke-width="3" />
                                </svg>
                                Stable Trend
                            </div>
                        </div>
                        <div class="card-premium p-8 rounded-[2rem]">
                            <p class="text-[10px] font-bold text-slate-500 mb-4 tracking-widest">EXPECTED OUTFLOW</p>
                            <h3 class="text-3xl font-bold">
                                {{ auth()->user()->currency }}{{ number_format($prediction['expenses'], 0) }}</h3>
                            <div class="mt-4 flex items-center gap-2 text-rose-400 text-[10px] font-bold uppercase">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 14l-7 7-7-7" stroke-width="3" />
                                </svg>
                                Buffer Included
                            </div>
                        </div>
                        <div class="card-premium p-8 rounded-[2rem] bg-indigo-600/10 border-indigo-500/30">
                            <p class="text-[10px] font-bold text-indigo-300 mb-4 tracking-widest">NET PROJECTION</p>
                            <h3 class="text-3xl font-bold text-indigo-400">
                                @if ($prediction['savings'] < 0)
                                    -
                                @endif
                                {{ auth()->user()->currency }}{{ number_format(abs($prediction['savings']), 0) }}
                            </h3>
                            <div
                                class="mt-4 flex items-center gap-2 {{ $prediction['savings'] >= 0 ? 'text-indigo-400' : 'text-rose-400' }} text-[10px] font-bold uppercase">
                                {{ $prediction['savings'] >= 0 ? 'Target Surplus' : 'Possible Deficit' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-premium p-8 rounded-[2rem] flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold mb-4">Strategic Balance</h4>
                        <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden mb-6">
                            <div class="h-full bg-indigo-500 transition-all duration-1000"
                                style="width: {{ min(100, $savingsRate * 2) }}%"></div>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed italic">"Your current pattern suggests a strong
                            foundation. Optimization in minor categories could lead to an additional 12% growth over the
                            next quarter."</p>
                    </div>
                    <button
                        class="mt-8 py-4 bg-white text-slate-950 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-indigo-400 hover:text-white transition-all">Download
                        Report</button>
                </div>
            </div>

            <!-- Strategy Cards -->
            <div class="space-y-4">
                <h2 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                    <span class="w-12 h-[1px] bg-slate-800"></span>
                    AI Derived Strategies
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($insights as $insight)
                        <div class="card-premium p-8 rounded-[2.5rem] flex gap-6 items-start">
                            <div
                                class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center 
                                @if ($insight['type'] == 'success') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                @elseif($insight['type'] == 'warning') bg-rose-500/10 text-rose-400 border border-rose-500/20
                                @elseif($insight['type'] == 'info') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                @else bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 @endif">

                                @if ($insight['icon'] == 'sparkles')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                @elseif($insight['icon'] == 'trending-up')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                @elseif($insight['icon'] == 'lightning-bolt')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @elseif($insight['icon'] == 'chart-pie')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                        <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                    </svg>
                                @else
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-bold mb-3 tracking-tight">{{ $insight['title'] }}</h3>
                                <p class="text-slate-400 text-sm leading-relaxed">{{ $insight['message'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Monthly Summary Table (Glass) -->
            <div class="mt-16 glass-panel p-10">
                <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                    <div>
                        <h3 class="text-2xl font-bold mb-1">Financial Data Core</h3>
                        <p class="text-sm text-slate-500 uppercase tracking-widest font-bold">Authenticated User Report
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="border-l border-slate-800 ps-6">
                        <p class="text-[10px] font-black text-slate-500 mb-2 uppercase tracking-widest">Total Deposits
                        </p>
                        <p class="text-2xl font-bold text-emerald-400">
                            {{ auth()->user()->currency }}{{ number_format($income, 0) }}</p>
                    </div>
                    <div class="border-l border-slate-800 ps-6">
                        <p class="text-[10px] font-black text-slate-500 mb-2 uppercase tracking-widest">Total
                            Withdrawals</p>
                        <p class="text-2xl font-bold text-rose-400">
                            {{ auth()->user()->currency }}{{ number_format($expenses, 0) }}</p>
                    </div>
                    <div class="border-l border-slate-800 ps-6">
                        <p class="text-[10px] font-black text-slate-500 mb-2 uppercase tracking-widest">Efficiency Rate
                        </p>
                        <p class="text-2xl font-bold text-indigo-400">
                            {{ round((($income - $expenses) / max(1, $income)) * 100) }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
