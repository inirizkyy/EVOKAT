@extends('layouts.admin')

@section('title', 'Live Chat')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex w-full" style="height: calc(100vh - 120px); min-height: 600px;">
    
    <!-- Sidebar: Chat List -->
    <div class="w-[320px] lg:w-[350px] border-r border-gray-100 flex flex-col bg-[#fcfcfc] shrink-0">
        <!-- Sidebar Header -->
        <div class="p-5 border-b border-gray-100">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">Messages</h2>
            </div>
            
            <!-- Filters -->
            <div class="flex space-x-2 mb-4">
                <button class="px-4 py-1 text-xs font-semibold rounded-full text-white bg-brand chat-filter-btn transition-colors" data-status="aktif">All Chats</button>
                <button class="px-4 py-1 text-xs font-semibold rounded-full text-gray-500 bg-gray-100 hover:bg-gray-200 chat-filter-btn transition-colors" data-status="selesai">Selesai</button>
            </div>

            <!-- Search -->
            <div class="relative mb-2">
                <input type="text" id="chat-search" placeholder="Cari percakapan..." class="w-full bg-white border border-gray-200 rounded-full px-4 py-2 text-xs focus:ring-brand focus:border-brand transition-colors outline-none shadow-sm">
            </div>
        </div>
        
        <!-- Sessions List -->
        <div id="chat-sessions-list" class="flex-1 overflow-y-auto min-h-0 bg-[#fcfcfc] py-2">
            <!-- Items will be loaded via JS -->
            <div class="p-4 text-center text-body-subtle text-sm">Memuat...</div>
        </div>
    </div>

    <!-- Main Content: Chat Area -->
    <div class="flex-1 flex flex-col bg-slate-50 hidden relative" style="height: 100%;" id="chat-active-area">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-10 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg" id="active-chat-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h3 id="active-chat-name" class="font-bold text-gray-800 text-base leading-tight">Nama Pemohon</h3>
                    <div class="flex items-center text-[11px] text-green-500 font-medium mt-0.5">
                        <span id="active-chat-email" class="text-gray-500 font-normal hidden">email@domain.com</span>
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                        <span>Online</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 text-gray-500">
                <button id="btn-mark-done" class="flex items-center gap-2 text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded-full transition-colors text-xs font-semibold" title="Selesaikan Chat">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Selesai</span>
                </button>
                <button id="btn-delete-chat" class="flex items-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-full transition-colors text-xs font-semibold" title="Hapus Chat">
                    <i class="fa-solid fa-trash text-xs"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>

        <!-- Chat Status Alert -->
        <div id="active-chat-status" class="hidden bg-yellow-100 text-yellow-800 text-sm px-6 py-2 text-center border-b border-yellow-200 font-medium">
            Percakapan ini telah selesai.
        </div>

        <!-- Messages Area -->
        <div id="admin-chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50 flex flex-col min-h-0">
            <!-- Messages load here -->
        </div>

        <!-- Input Area -->
        <div class="px-6 py-3 bg-white shrink-0 border-t border-gray-100" id="admin-chat-input-area">
            <form id="admin-chat-form" class="flex items-center border border-gray-200 rounded-2xl bg-[#f8f9fa] p-2 focus-within:border-brand focus-within:ring-1 focus-within:ring-brand transition-all shadow-sm">
                <input type="text" id="admin-chat-input" placeholder="Type a message..." class="flex-1 bg-transparent border-none focus:ring-0 px-4 py-2 text-sm text-gray-700 outline-none" required autocomplete="off">
                
                <div class="flex items-center pr-2 shrink-0">
                    <button type="submit" id="admin-send-btn" class="bg-brand hover:bg-brand-soft text-white w-9 h-9 rounded-xl flex items-center justify-center transition-colors shadow-sm focus:outline-none disabled:opacity-50">
                        <i class="fa-solid fa-paper-plane text-xs -translate-x-[1px] translate-y-[1px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Empty State -->
    <div class="flex-1 flex flex-col justify-center items-center bg-[#fcfcfc] text-gray-400" id="chat-empty-state">
        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
            <i class="fa-regular fa-comments text-4xl text-gray-300"></i>
        </div>
        <p class="font-medium text-lg">Pilih percakapan untuk mulai merespon</p>
    </div>

</div>
@endsection

@push('styles')
<style>
@keyframes adminMsgFadeUp {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.admin-msg-anim {
    animation: adminMsgFadeUp 0.2s ease forwards;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStatus = 'aktif';
    let searchQuery = '';
    let activeSessionId = null;
    let sessionsInterval = null;
    let messagesInterval = null;

    const sessionsList = document.getElementById('chat-sessions-list');
    const filterBtns = document.querySelectorAll('.chat-filter-btn');
    const searchInput = document.getElementById('chat-search');
    const activeArea = document.getElementById('chat-active-area');
    const emptyState = document.getElementById('chat-empty-state');
    
    // Active chat elements
    const activeName = document.getElementById('active-chat-name');
    const activeEmail = document.getElementById('active-chat-email');
    const activeStatusAlert = document.getElementById('active-chat-status');
    const messagesArea = document.getElementById('admin-chat-messages');
    const inputArea = document.getElementById('admin-chat-input-area');
    const chatForm = document.getElementById('admin-chat-form');
    const chatInput = document.getElementById('admin-chat-input');
    const btnDone = document.getElementById('btn-mark-done');
    const btnDelete = document.getElementById('btn-delete-chat');

    // 1. Fetch Sessions
    function loadSessions() {
        const url = `{{ route('admin.chat.sessions') }}?status=${currentStatus}&search=${encodeURIComponent(searchQuery)}`;
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderSessions(data.sessions);
                }
            })
            .catch(err => console.error('Error load sessions:', err));
    }

    function renderSessions(sessions) {
        if (sessions.length === 0) {
            sessionsList.innerHTML = `<div class="p-8 text-center text-body-subtle text-sm">Tidak ada percakapan.</div>`;
            return;
        }

        let html = '';
        sessions.forEach(session => {
            const isActive = session.id === activeSessionId;
            const bgClass = isActive ? 'bg-blue-50/50 border border-brand/20' : 'bg-transparent border border-transparent hover:bg-gray-50';
            const nameColor = isActive ? 'text-gray-900 font-bold' : 'text-gray-700 font-semibold';
            const unreadBadge = session.unread_count > 0 
                ? `<span class="bg-brand text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full shrink-0">${session.unread_count}</span>` 
                : '';
                
            let lastMessage = 'Belum ada pesan';
            let lastTime = '';
            if (session.messages && session.messages.length > 0) {
                lastMessage = session.messages[0].message;
                if(lastMessage.length > 30) lastMessage = lastMessage.substring(0, 30) + '...';
                
                const date = new Date(session.last_message_at || session.created_at);
                const now = new Date();
                if (date.toDateString() === now.toDateString()) {
                    lastTime = date.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                } else {
                    lastTime = date.toLocaleDateString('id-ID', {day: 'numeric', month: 'short'});
                }
            }

            const initial = session.nama ? session.nama.charAt(0).toUpperCase() : '?';

            html += `
                <div class="cursor-pointer px-3 py-2.5 mx-2 my-0.5 rounded-lg transition-all flex items-center space-x-3 ${bgClass}" onclick="openSession(${session.id}, '${session.nama}', '${session.email}', '${session.no_hp || '-'}', '${session.status}')">
                    <div class="relative shrink-0">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shadow-sm ${isActive ? 'bg-brand text-white' : 'bg-gray-100 text-gray-500'}">
                            ${initial}
                        </div>
                        ${isActive ? '<span class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></span>' : ''}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center">
                            <span class="${nameColor} text-sm truncate">${session.nama}</span>
                            <span style="font-size:10px;" class="text-gray-400 whitespace-nowrap ml-2 shrink-0">${lastTime}</span>
                        </div>
                        <div class="flex justify-between items-center mt-0.5">
                            <p style="font-size:11px;" class="text-gray-400 truncate pr-2">${lastMessage}</p>
                            ${unreadBadge}
                        </div>
                    </div>
                </div>
            `;
        });
        sessionsList.innerHTML = html;
    }

    // Polling sessions every 10 seconds
    sessionsInterval = setInterval(loadSessions, 10000);
    loadSessions();

    // 2. Filter & Search
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update UI
            filterBtns.forEach(b => {
                b.classList.remove('bg-brand', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-500');
            });
            this.classList.remove('bg-gray-100', 'text-gray-500');
            this.classList.add('bg-brand', 'text-white');
            
            currentStatus = this.dataset.status;
            
            // Clear active session if switching tabs
            closeActiveSession();
            loadSessions();
        });
    });

    searchInput.addEventListener('input', function() {
        searchQuery = this.value.trim();
        loadSessions();
    });

    // 3. Open Session
    window.openSession = function(id, nama, email, no_hp, status) {
        activeSessionId = id;
        
        activeName.textContent = nama;
        activeEmail.textContent = `${email} • ${no_hp}`;
        
        const avatar = document.getElementById('active-chat-avatar');
        avatar.innerHTML = `<span class="uppercase">${nama.charAt(0)}</span>`;
        
        emptyState.classList.add('hidden');
        activeArea.classList.remove('hidden');
        activeArea.classList.add('flex');
        
        updateActiveStatusUI(status);
        
        // Highlight active session on sidebar
        loadSessions(); 

        // Load Messages immediately
        loadMessages();
        
        // Start polling messages
        if (messagesInterval) clearInterval(messagesInterval);
        messagesInterval = setInterval(loadMessages, 5000); // 5 sec for messages
    };

    function closeActiveSession() {
        activeSessionId = null;
        activeArea.classList.add('hidden');
        activeArea.classList.remove('flex');
        emptyState.classList.remove('hidden');
        if (messagesInterval) clearInterval(messagesInterval);
    }

    function updateActiveStatusUI(status) {
        if (status === 'selesai') {
            activeStatusAlert.classList.remove('hidden');
            inputArea.classList.add('hidden');
            btnDone.classList.add('hidden');
        } else {
            activeStatusAlert.classList.add('hidden');
            inputArea.classList.remove('hidden');
            btnDone.classList.remove('hidden');
        }
    }

    // 4. Load Messages
    function loadMessages() {
        if (!activeSessionId) return;

        fetch(`/admin/chat/messages/${activeSessionId}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderMessages(data.messages);
                    updateActiveStatusUI(data.status);
                    
                    // Force refresh sidebar to clear badges if any
                    loadSessions();
                } else {
                    closeActiveSession();
                    loadSessions();
                }
            })
            .catch(err => console.error('Error load messages:', err));
    }

    function renderMessages(messages) {
        let html = '';
        let lastDate = null;

        messages.forEach(msg => {
            const dateObj = new Date(msg.created_at);
            const dateStr = dateObj.toLocaleDateString('id-ID', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'});
            
            if (lastDate !== dateStr) {
                html += `<div class="text-center my-4"><span class="bg-gray-200 text-gray-600 text-[10px] uppercase font-bold px-3 py-1 rounded-full tracking-wider">${dateStr}</span></div>`;
                lastDate = dateStr;
            }            
            
            const timeStr = dateObj.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
            const isAdmin = msg.sender === 'admin';
            const isSystem = msg.sender === 'system';
            const initial = activeName.textContent ? activeName.textContent.charAt(0).toUpperCase() : '?';
            
            let bubbleHtml = '';

            if (isAdmin) {
                bubbleHtml = `
                    <div class="flex justify-end mb-3 admin-msg-anim">
                        <div class="flex flex-col items-end max-w-[70%]">
                            <div class="bg-brand text-white rounded-xl py-2 px-3 text-[13px] whitespace-pre-wrap leading-relaxed shadow-sm w-fit inline-block break-words">${msg.message}</div>
                            <div class="flex items-center space-x-1 mt-1">
                                <span style="font-size:9px;" class="text-gray-400 font-medium">${timeStr}</span>
                                <i class="fa-solid fa-check-double text-[9px] text-blue-400"></i>
                            </div>
                        </div>
                    </div>
                `;
            } else if (isSystem) {
                bubbleHtml = `
                    <div class="flex justify-center mb-3 admin-msg-anim">
                        <div class="bg-gray-50 text-gray-500 rounded-full text-center text-[10px] font-bold uppercase tracking-wider border border-gray-200 py-1 px-4 shadow-sm">
                            ${timeStr} • System
                        </div>
                    </div>
                `;
            } else {
                bubbleHtml = `
                    <div class="flex items-start mb-3 admin-msg-anim">
                        <div class="flex flex-col items-start max-w-[70%]">
                            <div class="bg-white text-gray-800 rounded-xl py-2 px-3 text-[13px] whitespace-pre-wrap leading-relaxed shadow-sm border border-gray-100 w-fit inline-block break-words">${msg.message}</div>
                            <span style="font-size:9px;" class="text-gray-400 font-medium mt-1 ml-1">${timeStr}</span>
                        </div>
                    </div>
                `;
            }

            html += bubbleHtml;
        });

        const isScrolledToBottom = messagesArea.scrollHeight - messagesArea.clientHeight <= messagesArea.scrollTop + 50;
        messagesArea.innerHTML = html;
        
        if (isScrolledToBottom || !messagesArea.dataset.scrolled) {
            messagesArea.scrollTop = messagesArea.scrollHeight;
            messagesArea.dataset.scrolled = 'true';
        }
    }

    // 5. Send Message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text || !activeSessionId) return;

        const btn = document.getElementById('admin-send-btn');
        chatInput.disabled = true;
        btn.disabled = true;

        const formData = new FormData();
        formData.append('message', text);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(`/admin/chat/send/${activeSessionId}`, {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                loadMessages();
                loadSessions(); // move this session to top
            } else {
                Swal.fire('Error', data.message || 'Gagal mengirim pesan.', 'error');
            }
        })
        .catch(err => console.error(err))
        .finally(() => {
            chatInput.disabled = false;
            btn.disabled = false;
            chatInput.focus();
        });
    });

    // 6. Mark Done
    btnDone.addEventListener('click', function() {
        if (!activeSessionId) return;
        
        Swal.fire({
            title: 'Selesaikan Percakapan?',
            text: "Pemohon tidak akan bisa membalas lagi di percakapan ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, selesaikan!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/chat/done/${activeSessionId}`, {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Selesai!', 'Percakapan telah diselesaikan.', 'success');
                        loadMessages();
                        loadSessions();
                    }
                });
            }
        });
    });

    // 7. Delete Chat
    btnDelete.addEventListener('click', function() {
        if (!activeSessionId) return;
        
        Swal.fire({
            title: 'Hapus Percakapan?',
            text: "Apakah Anda yakin ingin menghapus percakapan ini? Data yang dihapus tidak dapat dikembalikan.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/chat/${activeSessionId}`, {
                    method: 'DELETE',
                    headers: { 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Dihapus!', 'Percakapan telah dihapus.', 'success');
                        closeActiveSession();
                        loadSessions();
                    }
                });
            }
        });
    });
});
</script>
@endpush
