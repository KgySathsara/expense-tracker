<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --card-border: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fdfeff;
            overflow-x: hidden;
        }

        .aura-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            overflow: hidden;
        }

        .aura-blob {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            animation: move 20s infinite alternate;
        }

        .aura-1 {
            background: #6366f1;
            top: -200px;
            right: -100px;
        }

        .aura-2 {
            background: #a855f7;
            bottom: -200px;
            left: -100px;
            animation-delay: -5s;
        }

        .aura-3 {
            background: #ec4899;
            top: 40%;
            left: 30%;
            animation-delay: -10s;
        }

        @keyframes move {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(100px, 100px) scale(1.1);
            }
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 2.5rem;
        }

        .note-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 2.2rem;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }

        .note-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .note-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.08);
            border-color: rgba(99, 102, 241, 0.2);
            background: rgba(255, 255, 255, 0.9);
        }

        .note-card:hover::before {
            opacity: 1;
        }

        .date-pill {
            background: #fff;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }

        .create-btn {
            background: #1e293b;
            color: white;
            padding: 1.25rem 2rem;
            border-radius: 1.5rem;
            font-weight: 800;
            transition: all 0.4s;
            box-shadow: 0 20px 40px -10px rgba(30, 41, 59, 0.3);
        }

        .create-btn:hover {
            background: #4f46e5;
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.4);
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .delete-btn-icon:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .edit-btn {
            background: #f8fafc;
            color: #6366f1;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.3s;
        }

        .edit-btn:hover {
            background: #6366f1;
            color: white;
        }

        /* Custom Modal */
        .modal-overlay {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background: #ffffff;
            border-radius: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.25);
        }
    </style>

    {{-- Aura Background Blobs --}}
    <div class="aura-bg">
        <div class="aura-blob aura-1"></div>
        <div class="aura-blob aura-2"></div>
        <div class="aura-blob aura-3"></div>
    </div>

    <div class="min-h-screen py-20 px-6 sm:px-12">
        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="glass-header p-10 mb-16 flex flex-col md:flex-row justify-between items-center gap-8 shadow-sm">
                <div>
                    <span class="text-[10px] font-black tracking-[0.3em] text-indigo-500 uppercase mb-2 block">Thought
                        Archive</span>
                    <h1 class="text-6xl font-black text-slate-900 tracking-tighter">
                        Life <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Notebook.</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-lg mt-3 tracking-tight">Your digital garden of daily
                        reflections and moments.</p>
                </div>

                <button onclick="openNoteModal()" class="create-btn group flex items-center gap-4">
                    <span class="text-sm uppercase tracking-widest">New Reflection</span>
                    <div class="bg-white/20 p-2 rounded-lg group-hover:bg-white/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </button>
            </div>

            {{-- Notes Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($notes as $note)
                    <div class="note-card p-10 flex flex-col h-full shadow-sm">

                        <div class="flex justify-between items-start mb-8">
                            <div class="date-pill">
                                <span
                                    class="text-xs font-black text-slate-800 uppercase tracking-widest">{{ $note->date->format('M d') }}</span>
                                <span class="mx-2 text-slate-300">|</span>
                                <span class="text-xs font-bold text-indigo-500">{{ $note->date->format('Y') }}</span>
                            </div>

                            <form action="{{ route('notes.destroy', $note) }}" method="POST"
                                onsubmit="return confirm('Delete this memory?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete-btn-icon text-slate-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <h3 class="text-2xl font-black text-slate-800 mb-6 tracking-tight">
                            {{ $note->date->format('l') }}</h3>

                        <div class="flex-grow">
                            <p class="text-slate-600 font-medium leading-relaxed whitespace-pre-wrap text-md">
                                {{ $note->content }}</p>
                        </div>

                        <div class="mt-10 pt-8 border-t border-slate-100/50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                <span
                                    class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Captured</span>
                            </div>
                            <button
                                onclick="editNote('{{ $note->date->format('Y-m-d') }}', '{{ addslashes($note->content) }}')"
                                class="edit-btn text-xs uppercase tracking-widest">
                                Expand
                            </button>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-40 text-center bg-white/40 backdrop-blur-md rounded-[4rem] border-2 border-dashed border-slate-100">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8">
                            <svg class="w-10 h-10 text-indigo-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">Blank Canvas.</h3>
                        <p class="text-slate-400 font-medium mt-3 text-lg">Your story is waiting to be written. Start
                            your first entry.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Advanced Entry Modal --}}
    <div id="noteModal"
        class="fixed inset-0 modal-overlay z-[100] hidden items-center justify-center p-6 animate-in fade-in duration-300">
        <div
            class="modal-content w-full max-w-2xl p-16 relative scale-95 opacity-0 transition-all duration-500 modal-anim-target">

            <div class="absolute -top-12 left-1/2 -translate-x-1/2">
                <div
                    class="w-24 h-24 bg-slate-900 border-[8px] border-white rounded-[2.5rem] shadow-2xl flex items-center justify-center text-white">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
            </div>

            <div class="text-center mb-12 mt-4">
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter" id="modalTitle">Daily Reflection</h3>
                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px] mt-4">Write your truth for
                    today.</p>
            </div>

            <form action="{{ route('notes.store') }}" method="POST">
                @csrf
                <div class="space-y-8">
                    <div class="relative group">
                        <label
                            class="absolute -top-3 left-6 bg-white px-2 text-[10px] font-black text-indigo-500 uppercase tracking-widest">Reflection
                            Date</label>
                        <input type="date" name="date" id="inputDate" value="{{ date('Y-m-d') }}"
                            class="w-full border-2 border-slate-50 bg-slate-50 rounded-2xl p-6 focus:bg-white focus:border-indigo-500 transition-all font-bold text-slate-800 text-lg">
                    </div>

                    <div class="relative group">
                        <label
                            class="absolute -top-3 left-6 bg-white px-2 text-[10px] font-black text-indigo-500 uppercase tracking-widest">Your
                            Narrative</label>
                        <textarea name="content" id="inputContent" rows="8" required
                            class="w-full border-2 border-slate-50 bg-slate-50 rounded-[2.5rem] p-8 focus:bg-white focus:border-indigo-500 transition-all font-medium text-slate-700 text-xl leading-relaxed placeholder-slate-300"
                            placeholder="Type your story..."></textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-12">
                    <button type="button" onclick="closeNoteModal()"
                        class="flex-1 py-6 text-slate-400 font-bold hover:bg-slate-50 rounded-2xl transition">Dismiss</button>
                    <button type="submit"
                        class="flex-[2] py-6 bg-slate-900 text-white font-black rounded-2xl shadow-2xl shadow-slate-200 hover:bg-slate-800 transition active:scale-95 text-lg uppercase tracking-widest">
                        Archive Moment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNoteModal() {
            const modal = document.getElementById('noteModal');
            const target = modal.querySelector('.modal-anim-target');
            document.getElementById('modalTitle').innerText = "Daily Reflection";
            document.getElementById('inputDate').value = "{{ date('Y-m-d') }}";
            document.getElementById('inputContent').value = "";

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                target.classList.remove('scale-95', 'opacity-0');
                target.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function editNote(date, content) {
            const modal = document.getElementById('noteModal');
            const target = modal.querySelector('.modal-anim-target');

            document.getElementById('modalTitle').innerText = "Update Narrative";
            document.getElementById('inputDate').value = date;
            document.getElementById('inputContent').value = content;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                target.classList.remove('scale-95', 'opacity-0');
                target.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeNoteModal() {
            const modal = document.getElementById('noteModal');
            const target = modal.querySelector('.modal-anim-target');

            target.classList.remove('scale-100', 'opacity-100');
            target.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 500);
        }

        window.onclick = function(e) {
            if (e.target.id === 'noteModal') closeNoteModal();
        }
    </script>
</x-app-layout>
