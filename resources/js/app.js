import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('aiChatSystem', (userName, chatRoute) => ({
    open: false,
    userInput: '',
    loading: false,
    showSuggestion: false,
    currentSuggestion: '',
    messages: [{
        role: 'assistant',
        content: `Hello! 👋 I am your AI Financial Agent. Ask me anything about your budget, expenses or savings!`
    }],
    suggestions: [
        "💡 Tip: Tracking small daily spends can save you up to 15% monthly!",
        "📊 Your savings rate looks good! Want investment ideas?",
        "🛒 Don't forget to log your grocery expenses this week.",
        "📈 Tip: Follow the 50/30/20 rule for better financial health."
    ],

    init() {
        window.aiChat = this;
        console.log('AI Chat attached to window.aiChat');

        // Listen for global open event
        window.addEventListener('open-ai-chat', () => {
            this.open = true;
            this.showSuggestion = false;
            this.$nextTick(() => this.scrollToBottom());
        });

        // Show first suggestion after 15 seconds
        setTimeout(() => {
            if (!this.open) {
                this.currentSuggestion = this.suggestions[0];
                this.showSuggestion = true;
                setTimeout(() => {
                    this.showSuggestion = false;
                }, 8000);
            }
        }, 15000);

        // Then repeat every 60 seconds
        setInterval(() => {
            if (!this.open) {
                this.currentSuggestion = this.suggestions[Math.floor(Math.random() * this.suggestions.length)];
                this.showSuggestion = true;
                setTimeout(() => {
                    this.showSuggestion = false;
                }, 8000);
            }
        }, 60000);
    },

    toggleChat() {
        this.open = !this.open;
        this.showSuggestion = false;
        if (this.open) {
            this.$nextTick(() => this.scrollToBottom());
        }
    },

    clearChat() {
        this.messages = [{
            role: 'assistant',
            content: 'Chat cleared! How can I help you today?'
        }];
    },

    async sendMessage(text) {
        if (this.loading || !text.trim()) return;
        this.messages.push({
            role: 'user',
            content: text
        });
        this.scrollToBottom();
        await this.fetchReply(text);
    },

    async submitForm() {
        const text = this.userInput.trim();
        if (!text) return;
        this.userInput = '';
        await this.sendMessage(text);
    },

    async fetchReply(text) {
        this.loading = true;
        try {
            const response = await fetch(chatRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: text
                })
            });
            const data = await response.json();
            this.messages.push({
                role: 'assistant',
                content: data.reply
            });
        } catch (e) {
            this.messages.push({
                role: 'assistant',
                content: '❌ Connection issue. Please try again.'
            });
        } finally {
            this.loading = false;
            this.scrollToBottom();
        }
    },

    scrollToBottom() {
        setTimeout(() => {
            const el = document.getElementById('ai-chat-messages');
            if (el) el.scrollTop = el.scrollHeight;
        }, 60);
    }
}));

Alpine.data('reminderSystem', (initialReminders) => ({
    reminders: initialReminders || [],
    activeNotifications: [],
    shownIds: new Set(),

    initReminders() {
        if ("Notification" in window && Notification.permission === "default") {
            Notification.requestPermission();
        }

        this.checkReminders();
        setInterval(() => this.checkReminders(), 30000);
    },

    checkReminders() {
        const now = new Date();
        const h_now = now.getHours();
        const m_now = now.getMinutes();

        this.reminders.forEach(note => {
            if (!note.reminder_time || this.shownIds.has(note.id)) return;

            const parts = note.reminder_time.split(':').map(Number);
            const h_note = parts[0];
            const m_note = parts[1];

            if (h_now > h_note || (h_now === h_note && m_now >= m_note)) {
                this.showPopup(note);
            }
        });
    },

    showPopup(note) {
        this.activeNotifications.push(note);
        this.shownIds.add(note.id);

        if ("Notification" in window && Notification.permission === "granted") {
            new Notification("Expense Tracker Reminder", {
                body: note.content,
                icon: "/favicon.ico"
            });
        }

        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        audio.play().catch(e => console.log('Audio play failed'));

        setTimeout(() => {
            this.dismiss(note.id);
        }, 10000);
    },

    dismiss(id) {
        this.activeNotifications = this.activeNotifications.filter(n => n.id !== id);
    }
}));

Alpine.start();
