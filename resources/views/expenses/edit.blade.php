<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800">Edit Expense</h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 max-w-lg mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('expenses.update', $expense->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full rounded-xl text-sm" type="text"
                        name="description" :value="old('description', $expense->description)" required autofocus />
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="amount" :value="__('Amount')" />
                    <div class="relative mt-1">
                        <span
                            class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">{{ auth()->user()->currency }}</span>
                        <x-text-input id="amount" class="block w-full pl-7 rounded-xl text-sm" type="number"
                            step="0.01" name="amount" :value="old('amount', $expense->amount)" required />
                    </div>
                    <x-input-error :messages="$errors->get('amount')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="date" :value="__('Date')" />
                    <x-text-input id="date" class="block mt-1 w-full rounded-xl text-sm" type="date"
                        name="date" :value="old('date', $expense->date->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('date')" class="mt-1" />
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('expenses.index') }}"
                        class="flex-1 text-center py-2.5 px-4 border border-gray-300 rounded-full text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit"
                        class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-full shadow transition active:scale-95">Update
                        Expense</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
