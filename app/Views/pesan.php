<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<style>
/* ── Chat Layout ──────────────────────────────────────── */
.chat-wrapper {
    height: calc(100vh - 220px);
    min-height: 500px;
    display: flex;
    gap: 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}

/* ── Sidebar (contact list) ───────────────────────────── */
.chat-sidebar {
    width: 320px;
    min-width: 280px;
    background: #1a1a2e;
    display: flex;
    flex-direction: column;
    border-right: 1px solid rgba(255,255,255,0.08);
}

.chat-sidebar-header {
    padding: 20px 16px 12px;
    background: #16213e;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

.chat-sidebar-header h5 {
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.chat-search {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px;
    color: #fff;
    padding: 8px 12px;
    width: 100%;
    font-size: 0.85rem;
    outline: none;
    transition: border 0.2s;
}
.chat-search::placeholder { color: rgba(255,255,255,0.4); }
.chat-search:focus { border-color: #3b82f6; }

.chat-list {
    flex: 1;
    overflow-y: auto;
    padding: 8px 0;
}
.chat-list::-webkit-scrollbar { width: 4px; }
.chat-list::-webkit-scrollbar-track { background: transparent; }
.chat-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

.chat-contact {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    cursor: pointer;
    transition: background 0.18s;
    border-left: 3px solid transparent;
    text-decoration: none;
}
.chat-contact:hover { background: rgba(255,255,255,0.05); }
.chat-contact.active {
    background: rgba(59,130,246,0.18);
    border-left-color: #3b82f6;
}

.chat-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #fff;
    font-size: 1rem;
}
.chat-avatar img { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; }

.chat-contact-info { flex: 1; min-width: 0; }
.chat-contact-name {
    color: #fff;
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.chat-contact-last {
    color: rgba(255,255,255,0.45);
    font-size: 0.75rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 2px;
}
.chat-contact-badge {
    background: #3b82f6;
    color: #fff;
    border-radius: 50%;
    min-width: 20px;
    height: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}

.chat-section-title {
    padding: 10px 16px 4px;
    color: rgba(255,255,255,0.35);
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* ── Chat Main Area ───────────────────────────────────── */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f0f4f8;
    min-width: 0;
}

.chat-header {
    background: #fff;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.chat-header-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
}
.chat-header-avatar img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; }
.chat-header-name { font-weight: 700; color: #1a1a2e; font-size: 1rem; }
.chat-header-role { font-size: 0.75rem; color: #6b7280; }

.chat-no-partner {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    gap: 12px;
}

/* ── Messages Area ────────────────────────────────────── */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.chat-messages::-webkit-scrollbar { width: 5px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

.msg-bubble {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 18px;
    font-size: 0.875rem;
    line-height: 1.5;
    word-wrap: break-word;
    position: relative;
}
.msg-bubble.sent {
    align-self: flex-end;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.msg-bubble.received {
    align-self: flex-start;
    background: #fff;
    color: #1f2937;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.msg-time {
    font-size: 0.68rem;
    opacity: 0.65;
    margin-top: 3px;
    text-align: right;
}
.msg-received-name {
    font-size: 0.7rem;
    font-weight: 700;
    color: #6366f1;
    margin-bottom: 3px;
}
.msg-actions {
    position: absolute;
    right: 100%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    visibility: hidden;
    display: flex;
    gap: 4px;
    padding-right: 8px; /* jembatan transparan agar hover tidak terputus */
    transition: opacity 0.2s, visibility 0.2s;
}
.msg-bubble.sent:hover .msg-actions,
.msg-actions:hover {
    opacity: 1;
    visibility: visible;
}
.msg-action-btn {
    background: #fff;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: all 0.2s;
    padding: 0;
}
.msg-action-btn:hover { color: #3b82f6; border-color: #3b82f6; }
.msg-action-btn.delete:hover { color: #ef4444; border-color: #ef4444; }
.msg-action-icon { font-size: 14px; }

/* ── Date Separator ──────────────────────────────────── */
.chat-date-sep {
    text-align: center;
    font-size: 0.72rem;
    color: #9ca3af;
    margin: 8px 0;
    position: relative;
}
.chat-date-sep::before, .chat-date-sep::after {
    content: '';
    display: inline-block;
    width: 60px;
    height: 1px;
    background: #e5e7eb;
    vertical-align: middle;
    margin: 0 8px;
}

/* ── Input Area ──────────────────────────────────────── */
.chat-input-area {
    background: #fff;
    border-top: 1px solid #e5e7eb;
    padding: 12px 16px;
    display: flex;
    gap: 10px;
    align-items: flex-end;
}
#chatInput {
    flex: 1;
    resize: none;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.875rem;
    outline: none;
    max-height: 120px;
    transition: border 0.2s;
    line-height: 1.5;
}
#chatInput:focus { border-color: #3b82f6; }
.btn-send {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    border: none;
    border-radius: 12px;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: opacity 0.2s, transform 0.1s;
}
.btn-send:hover { opacity: 0.9; }
.btn-send:active { transform: scale(0.95); }

/* ── Mobile Responsive ───────────────────────────────── */
@media (max-width: 768px) {
    .chat-wrapper {
        height: calc(100vh - 160px);
        flex-direction: column;
    }
    .chat-sidebar {
        width: 100%;
        min-width: unset;
        height: 220px;
        min-height: unset;
        border-right: none;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    body.theme-siswa .chat-sidebar {
        border-right: none;
        border-bottom: 1px solid #9dd6b3;
    }
    body.theme-pembimbing .chat-sidebar {
        border-right: none;
        border-bottom: 1px solid #b6d5fb;
    }
    .chat-main { height: 1px; }
}

/* ── THEME SISWA (PASTEL SAGE / PLN GREEN) ─────────────── */
body.theme-siswa .chat-wrapper {
    box-shadow: 0 10px 30px rgba(13, 76, 44, 0.08), 0 2px 8px rgba(0,0,0,0.03);
    border: 1px solid #cce5d5;
}

body.theme-siswa .chat-sidebar {
    background: #b2e5c7; /* Match sidebar dashboard */
    border-right: 1px solid #9dd6b3;
}

body.theme-siswa .chat-sidebar-header {
    background: #9cdbb6;
    border-bottom: 1px solid #8ecda7;
}

body.theme-siswa .chat-sidebar-header h5 {
    color: #043319;
    font-weight: 800;
}

body.theme-siswa .chat-sidebar-header h5 .material-icons {
    color: #0a4524;
}

body.theme-siswa .chat-search {
    background: #ffffff;
    border: 1.5px solid #8ecda7;
    color: #0c361d;
}

body.theme-siswa .chat-search::placeholder {
    color: #3b6d4f;
}

body.theme-siswa .chat-search:focus {
    border-color: #15803d;
    box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
}

body.theme-siswa .chat-list::-webkit-scrollbar-thumb {
    background: rgba(16, 75, 42, 0.2);
}

body.theme-siswa .chat-section-title {
    color: #0d4a29;
    font-weight: 800;
    letter-spacing: 0.8px;
}

body.theme-siswa .chat-contact {
    border-left: 3px solid transparent;
}

body.theme-siswa .chat-contact:hover {
    background: rgba(21, 128, 61, 0.10);
}

body.theme-siswa .chat-contact.active {
    background: #7ece9d;
    border-left-color: #033318;
}

body.theme-siswa .chat-avatar {
    background: linear-gradient(135deg, #15803d, #22c55e);
    color: #fff;
    font-weight: 700;
}

body.theme-siswa .chat-contact-name {
    color: #08341b;
    font-weight: 700;
}

body.theme-siswa .chat-contact-last {
    color: #2b5c3e !important;
}

body.theme-siswa .chat-contact-badge {
    background: #15803d;
}

body.theme-siswa .chat-list .text-center {
    color: #2b5c3e !important;
}

/* Chat Main Area */
body.theme-siswa .chat-main {
    background: #f7faf8;
}

body.theme-siswa .chat-header {
    background: #ffffff;
    border-bottom: 1px solid #e1efe6;
}

body.theme-siswa .chat-header-avatar {
    background: linear-gradient(135deg, #15803d, #22c55e);
}

body.theme-siswa .chat-header-name {
    color: #08341b;
    font-weight: 700;
}

body.theme-siswa .chat-header-role {
    color: #3b6d4e;
}

body.theme-siswa .chat-no-partner span.material-icons {
    color: #9ccdb0 !important;
}

body.theme-siswa .msg-bubble.sent {
    background: linear-gradient(135deg, #15803d, #16a34a);
    color: #fff;
    box-shadow: 0 2px 6px rgba(21, 128, 61, 0.2);
}

body.theme-siswa .msg-bubble.received {
    background: #ffffff;
    border: 1px solid #e2efe7;
    color: #1f2937;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

body.theme-siswa .msg-received-name {
    color: #15803d;
    font-weight: 700;
}

body.theme-siswa #chatInput:focus {
    border-color: #15803d;
    box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
}

body.theme-siswa .btn-send {
    background: linear-gradient(135deg, #15803d, #16a34a);
    box-shadow: 0 2px 6px rgba(21, 128, 61, 0.25);
}

body.theme-siswa .msg-action-btn:hover {
    color: #15803d;
    border-color: #15803d;
}

/* ── THEME PEMBIMBING (SOFT BLUE / BIRU LEMBUT) ────────── */
body.theme-pembimbing .chat-wrapper {
    box-shadow: 0 10px 30px rgba(37, 99, 235, 0.08), 0 2px 8px rgba(0,0,0,0.03);
    border: 1px solid #bfdbfe;
}

body.theme-pembimbing .chat-sidebar {
    background: #cce3fe; /* Soft pastel blue */
    border-right: 1px solid #b6d5fb;
}

body.theme-pembimbing .chat-sidebar-header {
    background: #bad8fb;
    border-bottom: 1px solid #a7cbf8;
}

body.theme-pembimbing .chat-sidebar-header h5 {
    color: #0c386d;
    font-weight: 800;
}

body.theme-pembimbing .chat-sidebar-header h5 .material-icons {
    color: #0f4485;
}

body.theme-pembimbing .chat-search {
    background: #ffffff;
    border: 1.5px solid #a3c8f8;
    color: #0c386d;
}

body.theme-pembimbing .chat-search::placeholder {
    color: #4b73a3;
}

body.theme-pembimbing .chat-search:focus {
    background: #ffffff;
    color: #0c386d;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

body.theme-pembimbing .chat-list::-webkit-scrollbar-thumb {
    background: rgba(12, 56, 109, 0.2);
}

body.theme-pembimbing .chat-section-title {
    color: #12498a;
    font-weight: 800;
    letter-spacing: 0.8px;
    font-size: 0.72rem;
}

body.theme-pembimbing .chat-contact {
    border-left: 3px solid transparent;
}

body.theme-pembimbing .chat-contact:hover {
    background: rgba(37, 99, 235, 0.10);
}

body.theme-pembimbing .chat-contact.active {
    background: #9bc6fc;
    border-left-color: #0c386d;
}

body.theme-pembimbing .chat-avatar {
    background: linear-gradient(135deg, #1865cf, #3898ec);
    color: #fff;
    font-weight: 700;
}

body.theme-pembimbing .chat-contact-name {
    color: #0a2f5c;
    font-weight: 700;
}

body.theme-pembimbing .chat-contact-last {
    color: #2b5586 !important;
}

body.theme-pembimbing .chat-contact.active .chat-contact-last {
    color: #133c6d !important;
}

body.theme-pembimbing .chat-contact-badge {
    background: #2563eb;
}

body.theme-pembimbing .chat-list .text-center {
    color: #2b5586 !important;
}

/* Chat Main Area */
body.theme-pembimbing .chat-main {
    background: #f8fafc;
}

body.theme-pembimbing .chat-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

body.theme-pembimbing .chat-header-avatar {
    background: linear-gradient(135deg, #1865cf, #3898ec);
}

body.theme-pembimbing .chat-header-name {
    color: #0f172a;
    font-weight: 700;
}

body.theme-pembimbing .chat-header-role {
    color: #64748b;
}

body.theme-pembimbing .chat-no-partner span.material-icons {
    color: #93c5fd !important;
}

body.theme-pembimbing .msg-bubble.sent {
    background: linear-gradient(135deg, #1865cf, #2563eb);
    color: #fff;
    box-shadow: 0 2px 6px rgba(24, 101, 207, 0.25);
}

body.theme-pembimbing .msg-bubble.received {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #1f2937;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

body.theme-pembimbing .msg-received-name {
    color: #1865cf;
    font-weight: 700;
}

body.theme-pembimbing #chatInput:focus {
    border-color: #1865cf;
    box-shadow: 0 0 0 3px rgba(24, 101, 207, 0.15);
}

body.theme-pembimbing .btn-send {
    background: linear-gradient(135deg, #1865cf, #2563eb);
    box-shadow: 0 2px 6px rgba(24, 101, 207, 0.25);
}

body.theme-pembimbing .msg-action-btn:hover {
    color: #1865cf;
    border-color: #1865cf;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="chat-wrapper">

            <!-- ══ SIDEBAR ═══════════════════════════════════════════ -->
            <div class="chat-sidebar">
                <div class="chat-sidebar-header">
                    <h5>
                        <span class="material-icons" style="font-size:1.1rem;" translate="no">forum</span>
                        Pesan
                        <?php if ($unread > 0): ?>
                            <span class="badge bg-danger ms-auto" id="sidebar-unread-badge"><?= $unread ?></span>
                        <?php endif; ?>
                    </h5>
                    <input type="text" class="chat-search" id="contactSearch" placeholder="Cari kontak...">
                </div>

                <div class="chat-list" id="contactList">

                    <?php if (!empty($chatList)): ?>
                        <div class="chat-section-title">Percakapan</div>
                        <?php foreach ($chatList as $c): ?>
                            <a href="/pesan/<?= $c->partner_id ?>" class="chat-contact <?= ($partner_id == $c->partner_id) ? 'active' : '' ?>"
                               data-name="<?= htmlspecialchars($c->partner_nama ?? $c->partner_username) ?>">
                                <div class="chat-avatar">
                                    <?php if (!empty($c->partner_foto)): ?>
                                        <img src="<?= base_url('assets/img/foto_' . (str_contains($c->partner_role ?? '', 'siswa') ? 'siswa' : 'pembimbing') . '/' . $c->partner_foto) ?>" alt="" translate="no" class="notranslate">
                                    <?php else: ?>
                                        <?= strtoupper(substr($c->partner_nama ?? $c->partner_username, 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="chat-contact-info">
                                    <div class="chat-contact-name"><?= htmlspecialchars($c->partner_nama ?? $c->partner_username) ?></div>
                                    <div class="chat-contact-last"><?= htmlspecialchars(mb_substr($c->last_message ?? '', 0, 35)) ?></div>
                                </div>
                                <?php if ($c->unread_count > 0): ?>
                                    <span class="chat-contact-badge"><?= $c->unread_count ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($contacts)): ?>
                        <div class="chat-section-title">Kontak</div>
                        <?php foreach ($contacts as $ct): ?>
                            <?php
                            // Skip if already in chatList
                            $inChat = array_filter($chatList ?? [], fn($c) => $c->partner_id == $ct['user_id']);
                            if (!empty($inChat)) continue;
                            ?>
                            <a href="/pesan/<?= $ct['user_id'] ?>" class="chat-contact <?= ($partner_id == $ct['user_id']) ? 'active' : '' ?>"
                               data-name="<?= htmlspecialchars($ct['nama']) ?>">
                                <div class="chat-avatar">
                                    <?php if (!empty($ct['foto'])): ?>
                                        <img src="<?= base_url('assets/img/foto_' . (str_contains($ct['role'], 'Siswa') ? 'siswa' : 'pembimbing') . '/' . $ct['foto']) ?>" alt="" translate="no" class="notranslate">
                                    <?php else: ?>
                                        <?= strtoupper(substr($ct['nama'], 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="chat-contact-info">
                                    <div class="chat-contact-name"><?= htmlspecialchars($ct['nama']) ?></div>
                                    <div class="chat-contact-last" style="color: rgba(255,255,255,0.3);"><?= htmlspecialchars($ct['role']) ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (empty($chatList) && empty($contacts)): ?>
                        <div class="text-center py-5" style="color: rgba(255,255,255,0.35);">
                            <span class="material-icons d-block mb-2" style="font-size: 2rem;" translate="no">people</span>
                            <small>Belum ada kontak tersedia</small>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <!-- ══ END SIDEBAR ════════════════════════════════════════ -->

            <!-- ══ MAIN CHAT AREA ════════════════════════════════════ -->
            <div class="chat-main">

                <?php if (!$partner): ?>
                    <div class="chat-no-partner">
                        <span class="material-icons" style="font-size: 3.5rem; color: #d1d5db;" translate="no">chat_bubble_outline</span>
                        <div style="font-size:1rem; font-weight: 600; color: #6b7280;">Pilih kontak untuk mulai mengobrol</div>
                        <div style="font-size: 0.82rem;">Pesan akan terkirim beserta notifikasi email</div>
                    </div>
                <?php else: ?>

                    <!-- Header -->
                    <div class="chat-header">
                        <a href="/pesan" class="btn btn-sm btn-link p-0 me-1 d-md-none" style="color:#6b7280;">
                            <span class="material-icons" translate="no">arrow_back</span>
                        </a>
                        <div class="chat-header-avatar">
                            <?php if (!empty($partner->foto)): ?>
                                <?php $fdir = str_contains($partner->roles ?? '', 'siswa') ? 'siswa' : 'pembimbing'; ?>
                                <img src="<?= base_url('assets/img/foto_' . $fdir . '/' . $partner->foto) ?>" alt="" translate="no" class="notranslate">
                            <?php else: ?>
                                <?= strtoupper(substr($partner->nama ?? $partner->username, 0, 1)) ?>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="chat-header-name"><?= htmlspecialchars($partner->nama ?? $partner->username) ?></div>
                            <div class="chat-header-role"><?= htmlspecialchars($partner->roles ?? '') ?></div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="chat-messages" id="chatMessages">
                        <?php
                        $lastDate = null;
                        foreach ($conversation as $msg):
                            $msgDate = date('Y-m-d', strtotime($msg->created_at));
                            if ($msgDate !== $lastDate):
                                $lastDate = $msgDate;
                        ?>
                            <div class="chat-date-sep"><?= date('d M Y', strtotime($msg->created_at)) ?></div>
                        <?php endif; ?>
                        <?php
                            $isSent = ($msg->sender_id == $user_id);
                        ?>
                            <div class="msg-bubble <?= $isSent ? 'sent' : 'received' ?>" id="msg-<?= $msg->id ?>" data-text="<?= htmlspecialchars($msg->pesan) ?>">
                                <?php if (!$isSent): ?>
                                    <div class="msg-received-name"><?= htmlspecialchars($partner->nama ?? $partner->username) ?></div>
                                <?php endif; ?>
                                <span class="msg-text notranslate" translate="no"><?= nl2br(htmlspecialchars($msg->pesan)) ?></span>
                                <div class="msg-time"><?= date('H:i', strtotime($msg->created_at)) ?></div>
                                <?php if ($isSent): ?>
                                <div class="msg-actions">
                                    <button class="msg-action-btn edit" onclick="editMessage(<?= $msg->id ?>)" title="Edit"><span class="material-icons msg-action-icon" translate="no">edit</span></button>
                                    <button class="msg-action-btn delete" onclick="deleteMessage(<?= $msg->id ?>)" title="Hapus"><span class="material-icons msg-action-icon" translate="no">delete</span></button>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Input -->
                    <div class="chat-input-area">
                        <textarea id="chatInput" rows="1" placeholder="Tulis pesan..."></textarea>
                        <button class="btn-send" id="btnSend" title="Kirim">
                            <span class="material-icons" style="font-size:1.2rem;" translate="no">send</span>
                        </button>
                    </div>

                <?php endif; ?>

            </div>
            <!-- ══ END MAIN CHAT AREA ═════════════════════════════════ -->

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script>
(function() {
    'use strict';

    const partnerId  = <?= $partner_id ? (int)$partner_id : 'null' ?>;
    const userId     = <?= (int)$user_id ?>;
    let csrfToken    = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
    const csrfName   = '<?= csrf_token() ?>';

    // ── Contact search filter ──
    document.getElementById('contactSearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.chat-contact').forEach(el => {
            const name = el.getAttribute('data-name')?.toLowerCase() || '';
            el.style.display = name.includes(q) ? '' : 'none';
        });
    });

    // ── Auto resize textarea ──
    const chatInput = document.getElementById('chatInput');
    if (chatInput) {
        chatInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
        chatInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    // ── Scroll to bottom ──
    function scrollBottom() {
        const el = document.getElementById('chatMessages');
        if (el) el.scrollTop = el.scrollHeight;
    }
    scrollBottom();

    // ── Format time ──
    function formatTime(dateStr) {
        const d = new Date(dateStr);
        return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
    }

    // ── Append message bubble ──
    function appendBubble(msg) {
        const container = document.getElementById('chatMessages');
        if (!container || document.getElementById('msg-' + msg.id)) return;

        const isSent = (msg.sender_id == userId);
        const div = document.createElement('div');
        div.className = 'msg-bubble ' + (isSent ? 'sent' : 'received');
        div.id = 'msg-' + msg.id;
        div.dataset.text = msg.pesan || '';

        let html = '';
        if (!isSent) html += `<div class="msg-received-name"><?= htmlspecialchars($partner->nama ?? $partner->username ?? '') ?></div>`;
        html += `<span class="msg-text notranslate" translate="no">${(msg.pesan || '').replace(/\n/g, '<br>')}</span>`;
        html += `<div class="msg-time">${formatTime(msg.created_at)}</div>`;
        if (isSent) {
            html += `<div class="msg-actions">
                <button class="msg-action-btn edit" onclick="editMessage(${msg.id})" title="Edit"><span class="material-icons msg-action-icon" translate="no">edit</span></button>
                <button class="msg-action-btn delete" onclick="deleteMessage(${msg.id})" title="Hapus"><span class="material-icons msg-action-icon" translate="no">delete</span></button>
            </div>`;
        }

        div.innerHTML = html;
        container.appendChild(div);
        scrollBottom();

        // Update lastId
        const newId = parseInt(msg.id);
        if (newId > lastId) lastId = newId;
    }

    // ── Send message ──
    function sendMessage() {
        if (!partnerId || !chatInput) return;
        const text = chatInput.value.trim();
        if (!text) return;

        chatInput.value = '';
        chatInput.style.height = 'auto';

        const body = new FormData();
        body.append('receiver_id', partnerId);
        body.append('pesan', text);
        body.append(csrfName, csrfToken);

        fetch('/pesan/send', { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.csrf_hash) csrfToken = data.csrf_hash; // Update token!
                if (data.success) {
                    appendBubble(data);
                } else {
                    alert('Gagal mengirim pesan: ' + (data.message || 'Error tidak diketahui'));
                    chatInput.value = text; // Restore text
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengirim pesan. Periksa koneksi internet Anda.');
                chatInput.value = text; // Restore text
            });
    }

    document.getElementById('btnSend')?.addEventListener('click', sendMessage);

    window.editMessage = function(id) {
        const el = document.getElementById('msg-' + id);
        if (!el) return;
        const currentText = el.dataset.text;
        const newText = prompt('Edit pesan:', currentText);
        if (newText !== null && newText.trim() !== '' && newText !== currentText) {
            const body = new FormData();
            body.append('msg_id', id);
            body.append('pesan', newText.trim());
            body.append(csrfName, csrfToken);

            fetch('/pesan/updateMsg', { method: 'POST', body })
                .then(r => r.json())
                .then(data => {
                    if (data.csrf_hash) csrfToken = data.csrf_hash;
                    if (data.success) {
                        el.dataset.text = data.pesan;
                        el.querySelector('.msg-text').innerHTML = data.pesan.replace(/\n/g, '<br>');
                    } else {
                        alert(data.message || 'Gagal mengubah pesan');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal mengubah pesan');
                });
        }
    };

    window.deleteMessage = function(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?')) return;
        
        const body = new FormData();
        body.append('msg_id', id);
        body.append(csrfName, csrfToken);

        fetch('/pesan/deleteMsg', { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.csrf_hash) csrfToken = data.csrf_hash;
                if (data.success) {
                    const el = document.getElementById('msg-' + id);
                    if (el) el.remove();
                } else {
                    alert(data.message || 'Gagal menghapus pesan');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal menghapus pesan');
            });
    };

    let lastId = 0;
    document.querySelectorAll('.msg-bubble').forEach(el => {
        const id = parseInt(el.id.replace('msg-', ''));
        if (id > lastId) lastId = id;
    });

    // ── Polling for new messages ──
    function poll() {
        if (!partnerId) return;
        fetch(`/pesan/poll/${partnerId}?last_id=${lastId}`)
            .then(r => r.json())
            .then(data => {
                if (data.messages && data.messages.length) {
                    data.messages.forEach(msg => {
                        appendBubble(msg);
                    });
                }
                // Update navbar badge
                const badge = document.getElementById('navbar-unread-badge');
                if (badge) {
                    badge.textContent = data.unread || '';
                    badge.style.display = (data.unread && data.unread > 0) ? '' : 'none';
                }
            })
            .catch(console.error);
    }

    if (partnerId) setInterval(poll, 3000);

    // Unread badge polling (even without active conversation)
    function pollUnread() {
        fetch('/pesan/unread')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('navbar-unread-badge');
                if (badge) {
                    badge.textContent = data.count || '';
                    badge.style.display = data.count > 0 ? '' : 'none';
                }
            })
            .catch(() => {});
    }
    setInterval(pollUnread, 15000);
})();
</script>
<?= $this->endSection(); ?>
