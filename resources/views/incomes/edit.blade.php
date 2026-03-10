<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .form-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            padding: 2.5rem;
        }

        .btn-primary {
            background-color: #6366f1;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
        }

        .btn-secondary {
            color: #64748b;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background-color: #f8fafc;
        }
    </style>

    <div class="py-12 px-4 sm:px-6 max-w-xl mx-auto min-h-screen">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Income</h1>
            <p class="text-gray-500 mt-2">Update the details of this income source.</p>
        </div>

        <div class="form-card">
            <form method="POST" action="{{ route('incomes.update', $income->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="source"
                        class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Income
                        Source</label>
                    <input id="source"
                        class="block w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all p-3"
                        type="text" name="source" :value="old('source', $income->source)" required autofocus />
                    <x-input-error :messages="$errors->get('source')" class="mt-2" />
                </div>

                <div>
                    <label for="amount"
                        class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Amount
                        ({{ auth()->user()->currency }})</label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold text-sm">{{ auth()->user()->currency }}</span>
                        <input id="amount"
                            class="block w-full pl-10 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all p-3"
                            type="number" step="0.01" name="amount" :value="old('amount', $income->amount)"
                            required />
                    </div>
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>

                <div>
                    <label for="date"
                        class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Date
                        Received</label>
                    <input id="date"
                        class="block w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all p-3"
                        type="date" name="date" :value="old('date', $income->date->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4">
                    <a href="{{ route('incomes.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Update Income</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
