<div id="live-chat-widget" class="fixed bottom-6 right-6 z-50 font-sans flex flex-col items-end">
    <!-- Chat Window -->
    <div id="chat-window" class="hidden flex-col bg-white w-[280px] sm:w-[340px] rounded-xl shadow-2xl mb-4 overflow-hidden border border-gray-100 origin-bottom-right" style="transition: opacity 0.25s ease, transform 0.25s ease;">
        <!-- Header -->
        <div class="bg-brand p-4 flex justify-between items-center shadow-md z-10 relative">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-[15px] text-white leading-none mb-1">Live Chat</div>
                    <div class="text-[10px] leading-none uppercase tracking-wider" style="color: rgba(255,255,255,0.75) !important;">Admin EVOKAT</div>
                    <div class="flex items-center space-x-1 mt-1">
                        <span id="admin-status-dot" class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                        <span id="admin-status-label" style="font-size:9px; color: rgba(255,255,255,0.7) !important;">Mengecek...</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-1">
                <button id="chat-finish-btn" class="hidden text-[10px] font-semibold bg-white/20 hover:bg-white/30 text-white border border-white/30 px-3 py-1.5 rounded-full transition-colors focus:outline-none" style="box-shadow: none !important;">
                    Akhiri
                </button>
                <button id="chat-close-btn" class="text-white/80 hover:text-white p-1 hover:bg-white/10 rounded-lg transition-colors focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Initial Form Container -->
        <div id="chat-init-container" class="bg-white flex-1 flex flex-col" style="max-height: 420px;">
            <div class="p-5 flex-1 overflow-y-auto">
                <p class="text-gray-600 text-sm mb-5 leading-relaxed">Silakan isi data diri Anda untuk memulai percakapan.</p>
                <form id="chat-init-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1.5">Nama Lengkap <span class="text-brand">*</span></label>
                        <input type="text" id="chat-nama" required placeholder="Nama lengkap Anda" class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-1 focus:ring-brand focus:border-brand outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1.5">Email <span class="text-brand">*</span></label>
                        <input type="email" id="chat-email" required placeholder="email@contoh.com" class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:ring-1 focus:ring-brand focus:border-brand outline-none transition-colors">
                    </div>
                    <button type="submit" class="w-full bg-brand hover:bg-brand-soft text-white font-medium py-2.5 px-4 rounded-md transition-colors flex justify-center items-center shadow-sm text-sm mt-2">
                        <span>Mulai Chat</span>
                    </button>
                </form>
                <div class="flex items-center justify-center mt-4 space-x-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                    <span class="text-[10px] text-gray-400 font-mono tracking-tight">Agents typically reply in under 2m</span>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages-container" class="hidden flex-col" style="height: 350px;">
            <!-- Info Status -->
            <div id="chat-status-banner" class="hidden bg-yellow-100 text-yellow-800 text-xs p-2 text-center border-b border-yellow-200 shrink-0">
                Percakapan ini telah selesai.
            </div>
            
            <!-- Messages Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 bg-gray-50 flex flex-col" style="min-height:0; overflow-y: auto;">
                <!-- Messages will be injected here via JS -->
            </div>

            <!-- Input Area -->
            <div class="p-3 bg-white border-t border-gray-100">
                <form id="chat-message-form" class="flex space-x-2">
                    @csrf
                    <input type="text" id="chat-message-input" autocomplete="off" placeholder="Ketik pesan..." class="flex-1 border-gray-200 bg-gray-50 rounded-full shadow-inner focus:ring-brand focus:border-brand focus:bg-white text-sm px-4 py-2 transition-colors" required>
                    <button type="submit" id="chat-send-btn" class="bg-brand hover:bg-brand-soft text-white rounded-full p-2 w-10 h-10 flex items-center justify-center transition-colors shadow focus:outline-none disabled:opacity-50 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform rotate-90 translate-x-[1px]" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </form>
                <button id="chat-restart-btn" class="hidden w-full mt-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2 px-4 rounded-lg transition-colors">
                    Mulai Percakapan Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Chat Button -->
    <button id="chat-toggle-btn" class="bg-brand hover:bg-brand-soft text-white rounded-full p-4 shadow-lg transition-transform transform hover:scale-105 flex items-center justify-center focus:outline-none relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <span id="chat-badge" class="hidden absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center -mt-1 -mr-1">
            !
        </span>
    </button>
</div>

<style>
@keyframes chatSlideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0)   scale(1); }
}
@keyframes chatSlideDown {
    from { opacity: 1; transform: translateY(0)   scale(1); }
    to   { opacity: 0; transform: translateY(20px) scale(0.95); }
}
@keyframes msgFadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.chat-msg-anim {
    animation: msgFadeUp 0.2s ease forwards;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('chat-toggle-btn');
        const closeBtn = document.getElementById('chat-close-btn');
        const chatWindow = document.getElementById('chat-window');
        const statusDot = document.getElementById('admin-status-dot');
        const statusLabel = document.getElementById('admin-status-label');
        const initContainer = document.getElementById('chat-init-container');
        const messagesContainer = document.getElementById('chat-messages-container');
        const initForm = document.getElementById('chat-init-form');
        const messageForm = document.getElementById('chat-message-form');
        const messagesArea = document.getElementById('chat-messages');
        const messageInput = document.getElementById('chat-message-input');
        const statusBanner = document.getElementById('chat-status-banner');
        const restartBtn = document.getElementById('chat-restart-btn');
        const badge = document.getElementById('chat-badge');
        const sendBtn = document.getElementById('chat-send-btn');
        const finishBtn = document.getElementById('chat-finish-btn');
        
        let chatUuid = localStorage.getItem('chat_session_uuid');
        let chatStatus = 'aktif';
        let pollingInterval = null;
        let isWindowOpen = false;
        let lastMessageCount = 0;

        // Admin Online Status
        function checkAdminStatus() {
            fetch('/chat/admin-status', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (data.online) {
                        statusDot.style.backgroundColor = '#22c55e'; // green
                        statusLabel.textContent = 'Online';
                    } else {
                        statusDot.style.backgroundColor = 'rgba(255,255,255,0.4)';
                        statusLabel.textContent = 'Offline';
                    }
                })
                .catch(() => {
                    statusLabel.textContent = 'Offline';
                    statusDot.style.backgroundColor = 'rgba(255,255,255,0.4)';
                });
        }
        checkAdminStatus();
        setInterval(checkAdminStatus, 30000); // refresh every 30s

        // Toggle chat window
        toggleBtn.addEventListener('click', () => {
            isWindowOpen = !isWindowOpen;

            if (isWindowOpen) {
                chatWindow.classList.remove('hidden');
                chatWindow.classList.add('flex');
                chatWindow.style.animation = 'chatSlideUp 0.25s ease forwards';
                badge.classList.add('hidden');
                if (chatUuid) {
                    loadMessages();
                    startPolling();
                } else {
                    showInitForm();
                }
            } else {
                chatWindow.style.animation = 'chatSlideDown 0.2s ease forwards';
                setTimeout(() => {
                    chatWindow.classList.add('hidden');
                    chatWindow.classList.remove('flex');
                }, 200);
                stopPolling();
            }
        });

        closeBtn.addEventListener('click', () => {
            isWindowOpen = false;
            chatWindow.style.animation = 'chatSlideDown 0.2s ease forwards';
            setTimeout(() => {
                chatWindow.classList.add('hidden');
                chatWindow.classList.remove('flex');
            }, 200);
            stopPolling();
        });

        function showInitForm() {
            initContainer.classList.remove('hidden');
            messagesContainer.classList.add('hidden');
            messagesContainer.classList.remove('flex');
            finishBtn.classList.add('hidden');
        }

        function showMessages() {
            initContainer.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            messagesContainer.classList.add('flex');
            if (chatStatus !== 'selesai') {
                finishBtn.classList.remove('hidden');
            }
            scrollToBottom();
        }

        function scrollToBottom() {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        // Initialize chat
        initForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const btn = initForm.querySelector('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="animate-pulse">Memproses...</span>';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('nama', document.getElementById('chat-nama').value);
            formData.append('email', document.getElementById('chat-email').value);
            formData.append('no_hp', ''); // No longer requested from user
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('/chat/init', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    chatUuid = data.session.uuid;
                    localStorage.setItem('chat_session_uuid', chatUuid);
                    showMessages();
                    loadMessages();
                    startPolling();
                } else {
                    alert('Gagal memulai chat. Silakan coba lagi.');
                }
            } catch (error) {
                console.error('Error init chat:', error);
                alert('Terjadi kesalahan koneksi.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // Send message
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text || !chatUuid) return;

            messageInput.disabled = true;
            sendBtn.disabled = true;

            const formData = new FormData();
            formData.append('uuid', chatUuid);
            formData.append('message', text);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });
                
                const data = await response.json();
                if (data.success) {
                    messageInput.value = '';
                    loadMessages();
                } else {
                    if (data.message) alert(data.message);
                }
            } catch (error) {
                console.error('Error send message:', error);
            } finally {
                messageInput.disabled = false;
                sendBtn.disabled = false;
                messageInput.focus();
            }
        });

        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        // Load messages
        async function loadMessages() {
            if (!chatUuid) return;

            try {
                const response = await fetch(`/chat/fetch?uuid=${chatUuid}`, {
                    headers: { 'Accept': 'application/json' }
                });
                
                const data = await response.json();
                if (data.success) {
                    renderMessages(data.messages);
                    
                    chatStatus = data.status;
                    handleChatStatus(chatStatus);
                    
                    // Show notification badge if window is closed and new messages arrived
                    if (!isWindowOpen && data.messages.length > lastMessageCount) {
                        const newMessages = data.messages.slice(lastMessageCount);
                        const hasAdminReply = newMessages.some(m => m.sender === 'admin' || m.sender === 'system');
                        if (hasAdminReply) {
                            badge.classList.remove('hidden');
                        }
                    }
                    lastMessageCount = data.messages.length;
                } else {
                    // Session not found or deleted
                    localStorage.removeItem('chat_session_uuid');
                    chatUuid = null;
                    stopPolling();
                    if(isWindowOpen) showInitForm();
                }
            } catch (error) {
                console.error('Error fetch messages:', error);
            }
        }

        function handleChatStatus(status) {
            if (status === 'selesai') {
                statusBanner.classList.remove('hidden');
                messageForm.classList.add('hidden');
                restartBtn.classList.remove('hidden');
                finishBtn.classList.add('hidden');
                stopPolling();
            } else {
                statusBanner.classList.add('hidden');
                messageForm.classList.remove('hidden');
                messageForm.classList.add('flex');
                restartBtn.classList.add('hidden');
                finishBtn.classList.remove('hidden');
            }
        }

        finishBtn.addEventListener('click', async () => {
            if (!chatUuid) return;
            if (!confirm('Akhiri percakapan ini?')) return;
            
            const formData = new FormData();
            formData.append('uuid', chatUuid);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('/chat/done', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.success) {
                    chatStatus = 'selesai';
                    handleChatStatus('selesai');
                }
            } catch (error) {
                console.error('Error finish chat:', error);
            }
        });

        restartBtn.addEventListener('click', () => {
            localStorage.removeItem('chat_session_uuid');
            chatUuid = null;
            chatStatus = 'aktif';
            messagesArea.innerHTML = '';
            lastMessageCount = 0;
            showInitForm();
        });

        function renderMessages(messages) {
            let html = '';
            messages.forEach(msg => {
                const isPemohon = msg.sender === 'pemohon';
                const senderName = isPemohon ? 'Anda' : (msg.sender === 'system' ? 'System' : 'Admin EVOKAT');
                const bubbleClass = isPemohon 
                    ? 'bg-brand text-white rounded-xl shadow-sm' 
                    : 'bg-white border border-gray-100 text-gray-800 rounded-xl shadow-sm';
                
                const readStatus = isPemohon ? (msg.is_read ? '<span class="text-[10px] text-gray-300 ml-1">✓✓</span>' : '<span class="text-[10px] text-gray-300 ml-1">✓</span>') : '';

                html += `
                    <div class="flex flex-col max-w-[85%] chat-msg-anim ${isPemohon ? 'self-end items-end' : 'self-start items-start'} mb-2">
                        <span style="font-size:9px;" class="text-gray-400 mb-0.5 px-1 font-medium">${senderName}</span>
                        <div class="${bubbleClass} py-2 px-3 text-[13px] whitespace-pre-wrap leading-relaxed w-fit inline-block break-words" ${isPemohon ? 'style="color: white !important;"' : ''}>${msg.message}</div>
                        <div style="font-size:9px;" class="text-gray-400 mt-0.5 px-1 flex items-center">
                            ${formatTime(msg.created_at)}
                            ${readStatus}
                        </div>
                    </div>
                `;
            });
            
            const isScrolledToBottom = messagesArea.scrollHeight - messagesArea.clientHeight <= messagesArea.scrollTop + 50;
            messagesArea.innerHTML = html;
            
            // Auto scroll to bottom only if user was already at bottom or just opened
            if (isScrolledToBottom || !messagesArea.dataset.scrolled) {
                scrollToBottom();
                messagesArea.dataset.scrolled = 'true';
            }
        }

        function startPolling() {
            if (!pollingInterval && chatStatus !== 'selesai') {
                pollingInterval = setInterval(() => {
                    loadMessages();
                }, 5000); // Poll every 5 seconds
            }
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        // Init check on load
        if (chatUuid) {
            loadMessages(); // Will fetch and optionally show badge if admin replied while offline
            // We start polling even if window closed to get badge notifications
            startPolling();
        }
    });
</script>
