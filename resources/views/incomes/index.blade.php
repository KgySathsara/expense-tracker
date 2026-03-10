<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .income-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem;
            transition: all 0.2s;
        }

        .income-card:hover {
            border-color: #6366f1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: #6366f1;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
        }
    </style>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto min-h-screen">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Incomes</h1>
                <p class="text-gray-500 mt-1">Manage your revenue and deposits.</p>
            </div>
            <a href="{{ route('incomes.create') }}" class="btn-primary">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Income
            </a>
        </div>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($incomes as $income)
                <div class="income-card flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $income->source }}</p>
                            <p class="text-xs text-gray-400 font-medium">{{ $income->date->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-sm font-black text-emerald-600">
                            +{{ auth()->user()->currency }}{{ number_format($income->amount, 0) }}</p>
                        <div class="flex gap-3 justify-end mt-1">
                            <a href="{{ route('incomes.edit', $income->id) }}"
                                class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700 uppercase tracking-wider">Edit</a>
                            <form action="{{ route('incomes.destroy', $income->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-[10px] font-bold text-gray-300 hover:text-rose-500 uppercase tracking-wider"
                                    onclick="return confirm('Delete this record?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white border-2 border-dashed border-gray-100 rounded-2xl">
                    <p class="text-gray-400 font-medium">No income records yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
