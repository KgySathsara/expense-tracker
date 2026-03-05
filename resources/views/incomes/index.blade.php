<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800">Your Incomes</h2>
            <a href="{{ route('incomes.create') }}"
                class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-full shadow transition-all active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto space-y-3">

        @if (session('success'))
            <div class="flex items-center gap-3 px-4 py-3 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm text-sm"
                role="alert">
                <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @forelse($incomes as $income)
            <div
                class="bg-white border border-gray-100 shadow-sm rounded-2xl px-4 py-3 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="h-11 w-11 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $income->source }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $income->date->format('M d, Y') }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-emerald-600">
                        +{{ auth()->user()->currency }}{{ number_format($income->amount, 2) }}</p>
                    <div class="flex items-center gap-2 mt-1 justify-end">
                        <a href="{{ route('incomes.edit', $income->id) }}"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                        <form action="{{ route('incomes.destroy', $income->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium"
                                onclick="return confirm('Delete this income?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="h-16 w-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-gray-500">No incomes yet</p>
                <a href="{{ route('incomes.create') }}"
                    class="mt-4 inline-flex items-center gap-1 px-5 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-full shadow-md hover:bg-indigo-700 transition">+
                    Add your first income</a>
            </div>
        @endforelse

    </div>
</x-app-layout>
