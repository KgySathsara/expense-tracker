<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Reminder System Wrapper -->
    <div class="min-h-screen bg-gray-100" x-data="reminderSystem({{ json_encode($todayReminders ?? []) }})" x-init="initReminders()">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Toast Notification Container (Moved to Left) -->
        <div class="fixed bottom-5 left-5 z-[200] space-y-3">
            <template x-for="note in activeNotifications" :key="note.id">
                <div x-show="true" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform translate-y-4"
                    class="bg-white border-l-4 border-indigo-600 shadow-2xl rounded-xl p-5 w-80 relative overflow-hidden group">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 bg-indigo-100 p-2 rounded-lg text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Reminder!</h4>
                            <p class="text-xs text-gray-600 leading-relaxed" x-text="note.content"></p>
                        </div>
                        <button @click="dismiss(note.id)" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="absolute bottom-0 left-0 h-1 bg-indigo-600 animate-[progress_5s_linear_forwards]"></div>
                </div>
            </template>
        </div>

    </div>
    <!-- END: Reminder System Wrapper -->

    <!-- Floating AI Assistant Bubble (Independent Alpine Component) -->
    @auth
        <div id="ai-chat-bubble-container" class="fixed bottom-6 right-6 z-[9999]" x-data="aiChatSystem('{{ auth()->user()->name }}', '{{ route('ai-advisor.chat') }}')"
            x-init="init()">
            <!-- Proactive Suggestion Tooltip -->
            <div x-show="showSuggestion && !open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                class="absolute bottom-16 right-0 w-64 bg-white p-4 rounded-2xl shadow-xl border border-indigo-100 mb-2">
                <div class="flex items-center gap-2 mb-2">
                    <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">AI Agent</span>
                    <button @click="showSuggestion = false" class="ml-auto text-gray-300 hover:text-gray-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-700 leading-relaxed" x-text="currentSuggestion"></p>
            </div>

            <!-- Chat Toggle Button with Pulse -->
            <button @click="toggleChat()"
                class="w-14 h-14 bg-indigo-600 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-110 active:scale-95 transition-all duration-300 relative group animate-bounce-subtle">
                <!-- Chat Icon (closed) -->
                <div x-show="!open" class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <!-- X Icon (open) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <!-- Notification Dot -->
                <span class="absolute -top-1 -right-1 flex h-4 w-4" x-show="!open">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-indigo-500 border-2 border-white"></span>
                </span>
            </button>

            <!-- AI Chat Panel -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                class="absolute bottom-20 right-0 w-80 sm:w-[360px] bg-white rounded-[1.75rem] shadow-2xl border border-gray-100 overflow-hidden flex flex-col"
                style="max-height: min(580px, calc(100vh - 120px));">

                <!-- Header -->
                <div class="p-5 bg-gradient-to-br from-indigo-600 to-violet-700 text-white shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div
                                    class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <span
                                    class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></span>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold leading-none">AI Financial Agent</h3>
                                <p class="text-[10px] text-indigo-100/80 mt-0.5">{{ auth()->user()->name }} &bull; Online
                                </p>
                            </div>
                        </div>
                        <button @click="clearChat()" title="Clear chat"
                            class="text-white/50 hover:text-white transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50" id="ai-chat-messages">
                    <template x-for="(msg, index) in messages" :key="index">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start items-end gap-2'">
                            <!-- AI Avatar -->
                            <div x-show="msg.role === 'assistant'"
                                class="w-6 h-6 rounded-full bg-indigo-100 flex-shrink-0 flex items-center justify-center mb-0.5">
                                <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z" />
                                </svg>
                            </div>
                            <div :class="msg.role === 'user' ?
                                'bg-indigo-600 text-white rounded-2xl rounded-tr-sm max-w-[80%]' :
                                'bg-white text-gray-800 rounded-2xl rounded-tl-sm border border-gray-100 shadow-sm max-w-[80%]'"
                                class="px-4 py-2.5 text-xs leading-relaxed">
                                <p x-text="msg.content" class="whitespace-pre-wrap"></p>
                            </div>
                        </div>
                    </template>
                    <!-- Typing indicator -->
                    <div x-show="loading" class="flex justify-start items-end gap-2">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 flex-shrink-0 flex items-center justify-center">
                            <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z" />
                            </svg>
                        </div>
                        <div
                            class="bg-white px-4 py-3 rounded-2xl rounded-tl-sm border border-gray-100 shadow-sm flex gap-1">
                            <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce"></span>
                            <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce"
                                style="animation-delay:0.15s"></span>
                            <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce"
                                style="animation-delay:0.3s"></span>
                        </div>
                    </div>
                </div>

                <!-- Quick Chips -->
                <div class="px-3 pt-3 flex gap-2 overflow-x-auto no-scrollbar shrink-0 bg-white border-t border-gray-50">
                    <button @click="sendMessage('Give me a savings tip')"
                        class="whitespace-nowrap px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-[10px] font-semibold border border-indigo-100 hover:bg-indigo-100 transition-colors shrink-0">
                        💡 Savings Tip
                    </button>
                    <button @click="sendMessage('How are my expenses this month?')"
                        class="whitespace-nowrap px-3 py-1.5 bg-rose-50 text-rose-700 rounded-full text-[10px] font-semibold border border-rose-100 hover:bg-rose-100 transition-colors shrink-0">
                        📊 My Expenses
                    </button>
                    <button @click="sendMessage('Am I on track with my budget?')"
                        class="whitespace-nowrap px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-semibold border border-emerald-100 hover:bg-emerald-100 transition-colors shrink-0">
                        ✅ Budget Check
                    </button>
                </div>

                <!-- Input -->
                <div class="p-3 bg-white shrink-0">
                    <form @submit.prevent="submitForm" class="flex gap-2 items-center">
                        <input type="text" x-model="userInput" @keydown.enter.prevent="submitForm"
                            placeholder="Type a message..."
                            class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all placeholder:text-gray-400">
                        <button type="submit" :disabled="loading || !userInput.trim()"
                            class="w-9 h-9 flex-shrink-0 bg-indigo-600 text-white rounded-full flex items-center justify-center hover:bg-indigo-700 disabled:opacity-40 transition-all active:scale-90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <style>
        @keyframes progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes bounce-subtle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }
    </style>

</body>

</html>
