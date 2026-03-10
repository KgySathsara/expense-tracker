<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .calendar-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: #e2e8f0;
        }

        .calendar-day {
            min-height: 120px;
            background: white;
            padding: 0.75rem;
            transition: background-color 0.2s;
            cursor: pointer;
        }

        .calendar-day:hover {
            background-color: #f8fafc;
        }

        .calendar-day.not-current {
            background-color: #f1f5f9;
            color: #94a3b8;
        }

        .today-marker {
            background-color: #6366f1;
            color: white;
            width: 1.75rem;
            height: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.125rem 0.5rem;
            border-radius: 0.375rem;
            display: block;
            margin-top: 2px;
        }

        .income-badge {
            background-color: #ecfdf5;
            color: #059669;
        }

        .expense-badge {
            background-color: #fff1f2;
            color: #e11d48;
        }

        .note-marker {
            background-color: #fefce8;
            color: #854d0e;
            font-size: 0.65rem;
            border-radius: 0.375rem;
            padding: 0.125rem 0.5rem;
            margin-top: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
        }

        .nav-btn {
            background: white;
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding: 0.5rem;
            border-radius: 0.75rem;
            transition: all 0.2s;
        }

        .nav-btn:hover {
            background: #f1f5f9;
            color: #4f46e5;
            border-color: #cbd5e1;
        }

        /* Modal Styles */
        .modal-overlay {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background-color: #6366f1;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
        }
    </style>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto min-h-screen bg-gray-50">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    {{ $date->format('F Y') }}
                </h2>
                <p class="text-gray-500 mt-1">Tap any day to manage notes or see activities.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
                    class="nav-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <a href="{{ route('calendar.index', ['month' => now()->month, 'year' => now()->year]) }}"
                    class="px-5 py-2 text-sm font-semibold border border-gray-200 bg-white rounded-xl hover:bg-gray-50 transition">Today</a>
                <a href="{{ route('calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
                    class="nav-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="stat-card">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Income</p>
                <p class="text-2xl font-bold text-emerald-600">
                    {{ auth()->user()->currency }}{{ number_format($incomes->flatten()->sum('amount'), 0) }}</p>
            </div>
            <div class="stat-card">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Expenses</p>
                <p class="text-2xl font-bold text-rose-600">
                    {{ auth()->user()->currency }}{{ number_format($expenses->flatten()->sum('amount'), 0) }}</p>
            </div>
            <div class="stat-card">
                @php $net = $incomes->flatten()->sum('amount') - $expenses->flatten()->sum('amount'); @endphp
                <p class="text-sm font-medium text-gray-500 mb-1">Net Balance</p>
                <p class="text-2xl font-bold {{ $net >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ auth()->user()->currency }}{{ number_format($net, 0) }}</p>
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="calendar-card">
            <div class="calendar-grid">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="bg-gray-50 py-3 text-center border-b border-gray-200">
                        <span
                            class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $day }}</span>
                    </div>
                @endforeach

                @php
                    $startOfCalendar = $date->copy()->startOfMonth()->startOfWeek(Carbon\Carbon::SUNDAY);
                    $endOfCalendar = $date->copy()->endOfMonth()->endOfWeek(Carbon\Carbon::SATURDAY);
                    $currentDate = $startOfCalendar->copy();
                @endphp

                @while ($currentDate <= $endOfCalendar)
                    @php
                        $ds = $currentDate->format('Y-m-d');
                        $isCM = $currentDate->month === $date->month;
                        $isToday = $currentDate->isToday();
                        $dEx = $expenses->get($ds, collect());
                        $dIn = $incomes->get($ds, collect());
                        $tEx = $dEx->sum('amount');
                        $tIn = $dIn->sum('amount');
                        $note = $notes->get($ds)?->first();
                    @endphp

                    <div class="calendar-day {{ $isCM ? '' : 'not-current' }}"
                        onclick="openNoteModal('{{ $ds }}', '{{ $note ? addslashes($note->content) : '' }}', '{{ $currentDate->format('M d, Y') }}')">
                        <div class="flex justify-between items-start mb-2">
                            <span
                                class="{{ $isToday ? 'today-marker' : ($isCM ? 'text-gray-700 font-bold' : 'text-gray-400 font-medium') }} text-sm">
                                {{ $currentDate->day }}
                            </span>
                            @if ($note)
                                <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            @endif
                        </div>

                        <div class="space-y-1">
                            @if ($tIn > 0)
                                <div class="badge income-badge">+{{ number_format($tIn, 0) }}</div>
                            @endif
                            @if ($tEx > 0)
                                <div class="badge expense-badge">-{{ number_format($tEx, 0) }}</div>
                            @endif
                            @if ($note)
                                <div class="note-marker">{{ $note->content }}</div>
                            @endif
                        </div>
                    </div>
                    @php $currentDate->addDay(); @endphp
                @endwhile
            </div>
        </div>
    </div>

    {{-- Note Modal --}}
    <div id="noteModal" class="fixed inset-0 modal-overlay z-[100] hidden items-center justify-center p-4">
        <div class="modal-content w-full max-w-lg p-8 relative">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">Daily Note</h3>
                    <p class="text-sm font-semibold text-indigo-500 mt-1" id="modalDateLabel"></p>
                </div>
                <button onclick="document.getElementById('noteModal').style.display='none'"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('notes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" id="modalDate">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                    <textarea name="content" id="modalContent" rows="5"
                        class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 leading-relaxed transition-all"
                        placeholder="Write something for this day..."></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="button"
                        onclick="document.getElementById('noteModal').classList.add('hidden'); document.getElementById('noteModal').classList.remove('flex');"
                        class="flex-1 py-3 text-gray-500 font-semibold hover:bg-gray-50 rounded-xl transition">Cancel</button>
                    <button type="submit" class="flex-[2] btn-primary">
                        Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNoteModal(date, content, dateLabel) {
            document.getElementById('modalDate').value = date;
            document.getElementById('modalContent').value = content;
            document.getElementById('modalDateLabel').innerText = dateLabel;
            document.getElementById('modalTitle').innerText = content ? 'Edit Note' : 'Add Note';
            const modal = document.getElementById('noteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        window.onclick = function(e) {
            const modal = document.getElementById('noteModal');
            if (e.target.id === 'noteModal') {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>

</x-app-layout>
