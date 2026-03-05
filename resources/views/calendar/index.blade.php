<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .calendar-day {
            min-height: 140px;
            background: white;
            padding: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: #fafafa;
            z-index: 10;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        }

        .calendar-day.not-current {
            background: #f9fafb;
            opacity: 0.5;
        }

        .today-marker {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            font-weight: 800;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4);
        }

        .income-badge {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #d1fae5;
            border-radius: 0.6rem;
            padding: 0.2rem 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
        }

        .expense-badge {
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            border-radius: 0.6rem;
            padding: 0.2rem 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
        }

        .note-marker {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fef08a;
            border-radius: 0.5rem;
            padding: 0.2rem 0.5rem;
            font-size: 0.6rem;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .nav-btn {
            transition: all 0.2s;
            border-radius: 1rem;
            padding: 0.6rem;
        }

        .nav-btn:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        .nav-btn:active {
            transform: translateY(0) scale(0.95);
        }

        /* Modal Styles */
        .modal-bg {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 50;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto bg-[#f8fafc] min-h-screen">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ $date->format('F') }} <span class="text-indigo-600">{{ $date->format('Y') }}</span>
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <p class="text-slate-500 font-medium text-sm">Tap any day to add a note or view details.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 glass-card p-2 rounded-2xl shadow-sm">
                <a href="{{ route('calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
                    class="nav-btn text-slate-400 hover:text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <a href="{{ route('calendar.index', ['month' => now()->month, 'year' => now()->year]) }}"
                    class="px-6 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 uppercase tracking-widest">Today</a>
                <a href="{{ route('calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
                    class="nav-btn text-slate-400 hover:text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-10">
            <div class="glass-card p-6 rounded-3xl shadow-sm border-l-4 border-emerald-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Income</p>
                <p class="text-2xl font-black text-slate-800">
                    {{ auth()->user()->currency }}{{ number_format($incomes->flatten()->sum('amount'), 0) }}</p>
            </div>
            <div class="glass-card p-6 rounded-3xl shadow-sm border-l-4 border-rose-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Expense</p>
                <p class="text-2xl font-black text-slate-800">
                    {{ auth()->user()->currency }}{{ number_format($expenses->flatten()->sum('amount'), 0) }}</p>
            </div>
            <div class="glass-card p-6 rounded-3xl shadow-sm border-l-4 border-indigo-500 hidden md:block">
                @php $net = $incomes->flatten()->sum('amount') - $expenses->flatten()->sum('amount'); @endphp
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Net</p>
                <p class="text-2xl font-black {{ $net >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ auth()->user()->currency }}{{ number_format($net, 0) }}</p>
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="calendar-grid">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="bg-indigo-50/50 py-3 text-center border-b border-gray-100">
                    <span
                        class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">{{ $day }}</span>
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
                    <div class="flex justify-between items-start mb-3">
                        <span
                            class="{{ $isToday ? 'today-marker' : ($isCM ? 'text-slate-700 font-bold' : 'text-slate-300 font-medium') }} text-sm">
                            {{ $currentDate->day }}
                        </span>
                        @if ($note)
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                            </svg>
                        @endif
                    </div>

                    <div class="space-y-1.5 overflow-hidden">
                        @if ($tIn > 0)
                            <div class="income-badge"><span>+{{ number_format($tIn, 0) }}</span></div>
                        @endif
                        @if ($tEx > 0)
                            <div class="expense-badge"><span>-{{ number_format($tEx, 0) }}</span></div>
                        @endif
                        @if ($note)
                            <div class="note-marker">
                                <span>{{ $note->content }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @php $currentDate->addDay(); @endphp
            @endwhile
        </div>
    </div>

    {{-- Note Modal --}}
    <div id="noteModal" class="modal-bg" onclick="closeNoteModal(event)">
        <div class="glass-card w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl relative animate-in fade-in zoom-in duration-300"
            onclick="event.stopPropagation()">
            <h3 class="text-2xl font-black text-slate-800 mb-2" id="modalTitle">Add Note</h3>
            <p class="text-sm font-bold text-indigo-500 mb-6 uppercase tracking-widest" id="modalDateLabel"></p>

            <form action="{{ route('notes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" id="modalDate">
                <textarea name="content" id="modalContent" rows="4"
                    class="w-full border-gray-100 bg-gray-50/50 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 text-slate-700 font-medium placeholder-slate-300 transition-all"
                    placeholder="Write something for this day..."></textarea>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('noteModal').style.display='none'"
                        class="flex-1 py-4 text-slate-400 font-bold hover:bg-gray-100 rounded-2xl transition">Cancel</button>
                    <button type="submit"
                        class="flex-1 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition active:scale-95">Save
                        Note</button>
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
            document.getElementById('noteModal').style.display = 'flex';
        }

        function closeNoteModal(e) {
            if (e.target.id === 'noteModal') {
                document.getElementById('noteModal').style.display = 'none';
            }
        }
    </script>

</x-app-layout>
