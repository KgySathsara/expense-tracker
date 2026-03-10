<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .note-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            transition: all 0.2s ease-in-out;
        }

        .note-card:hover {
            border-color: #6366f1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        .date-badge {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
        }

        .action-button {
            transition: all 0.2s;
            color: #94a3b8;
        }

        .action-button:hover {
            color: #ef4444;
        }

        .edit-action:hover {
            color: #6366f1;
        }

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

        .input-field {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-field:focus {
            border-color: #6366f1;
            ring: 2px solid #6366f1;
        }
    </style>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Daily Notes</h1>
                    <p class="text-gray-500 mt-1">Capture your thoughts and moments every day.</p>
                </div>
                <button onclick="openNoteModal()" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Entry
                </button>
            </div>

            {{-- Notes Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($notes as $note)
                    <div class="note-card p-6 flex flex-col h-full shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="date-badge">
                                {{ $note->date->format('M d, Y') }}
                            </div>
                            <div class="flex gap-2">
                                <button
                                    onclick="editNote('{{ $note->date->format('Y-m-d') }}', '{{ addslashes($note->content) }}', '{{ $note->reminder_time }}')"
                                    class="action-button edit-action">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('notes.destroy', $note) }}" method="POST"
                                    onsubmit="return confirm('Delete this note?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ $note->date->format('l') }}</h3>

                        <div class="flex-grow">
                            <p class="text-gray-600 leading-relaxed whitespace-pre-wrap mb-3">
                                {{ $note->content }}</p>
                            @if ($note->reminder_time)
                                <div
                                    class="mt-auto flex items-center gap-1.5 text-xs font-semibold text-indigo-500 bg-indigo-50 px-2.5 py-1 rounded-lg w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Reminder: {{ \Carbon\Carbon::parse($note->reminder_time)->format('h:i A') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-20 text-center bg-white border-2 border-dashed border-gray-200 rounded-2xl">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No notes yet</h3>
                        <p class="text-gray-500 mt-1">Start writing your thoughts today.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Entry Modal --}}
    <div id="noteModal" class="fixed inset-0 modal-overlay z-[100] hidden items-center justify-center p-4">
        <div class="modal-content w-full max-w-xl p-8 relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">Daily Reflection</h3>
                <button onclick="closeNoteModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('notes.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" id="inputDate" value="{{ date('Y-m-d') }}"
                                class="input-field font-semibold text-gray-800">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Reminder Time
                                (Optional)</label>
                            <input type="time" name="reminder_time" id="inputReminder"
                                class="input-field font-semibold text-gray-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                        <textarea name="content" id="inputContent" rows="6" required
                            class="input-field text-gray-700 leading-relaxed placeholder-gray-300" placeholder="What's on your mind?"></textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="button" onclick="closeNoteModal()"
                        class="flex-1 py-3 text-gray-500 font-semibold hover:bg-gray-50 rounded-xl transition">Cancel</button>
                    <button type="submit" class="flex-[2] btn-primary">
                        Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNoteModal() {
            const modal = document.getElementById('noteModal');
            document.getElementById('modalTitle').innerText = "Daily Reflection";
            document.getElementById('inputDate').value = "{{ date('Y-m-d') }}";
            document.getElementById('inputContent').value = "";

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function editNote(date, content, reminderTime) {
            const modal = document.getElementById('noteModal');
            document.getElementById('modalTitle').innerText = "Update Note";
            document.getElementById('inputDate').value = date;
            document.getElementById('inputContent').value = content;
            document.getElementById('inputReminder').value = reminderTime || "";

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeNoteModal() {
            const modal = document.getElementById('noteModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        window.onclick = function(e) {
            if (e.target.id === 'noteModal') closeNoteModal();
        }
    </script>
</x-app-layout>
