<x-filament-panels::page>
    <style>
        .reverb-console { display: flex; flex-direction: column; gap: 0; height: calc(100vh - 9rem); }
        .rc-toolbar { display: flex; align-items: center; gap: 12px; padding: 10px 16px; background: var(--fi-bg); border: 1px solid color-mix(in srgb, currentColor 12%, transparent); border-radius: 12px 12px 0 0; border-bottom: none; flex-shrink: 0; }
        .rc-status-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; transition: background .3s; }
        .rc-status-dot.idle { background: #6b7280; }
        .rc-status-dot.connecting { background: #f59e0b; animation: pulse 1s infinite; }
        .rc-status-dot.connected { background: #22c55e; }
        .rc-status-dot.disconnected { background: #ef4444; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .rc-status-label { font-size: 13px; font-weight: 600; text-transform: capitalize; color: var(--fi-color-gray-900); }
        .rc-socket-id { font-size: 11px; font-family: monospace; color: var(--fi-color-gray-500); }
        .rc-spacer { flex: 1; }
        .rc-btn { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; border: none; transition: opacity .15s, background .15s; }
        .rc-btn:disabled { opacity: .4; cursor: not-allowed; }
        .rc-btn-primary { background: #f97316; color: #fff; }
        .rc-btn-primary:hover:not(:disabled) { background: #ea6c0a; }
        .rc-btn-danger { background: #ef4444; color: #fff; }
        .rc-btn-danger:hover:not(:disabled) { background: #dc2626; }
        .rc-btn-ghost { background: color-mix(in srgb, currentColor 8%, transparent); color: var(--fi-color-gray-700); }
        .rc-btn-ghost:hover:not(:disabled) { background: color-mix(in srgb, currentColor 14%, transparent); }
        .rc-body { display: flex; flex: 1; min-height: 0; border: 1px solid color-mix(in srgb, currentColor 12%, transparent); border-radius: 0 0 12px 12px; overflow: hidden; }
        .rc-sidebar { width: 280px; flex-shrink: 0; display: flex; flex-direction: column; border-right: 1px solid color-mix(in srgb, currentColor 10%, transparent); background: var(--fi-bg); overflow-y: auto; }
        .rc-sidebar-section { padding: 14px 14px 12px; border-bottom: 1px solid color-mix(in srgb, currentColor 8%, transparent); flex-shrink: 0; }
        .rc-sidebar-channels { padding: 14px 14px 12px; flex: 1; overflow: hidden; display: flex; flex-direction: column; }
        .rc-section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--fi-color-gray-400); margin-bottom: 8px; }
        .rc-auth-tabs { display: flex; gap: 3px; margin-bottom: 10px; }
        .rc-auth-tab { flex: 1; padding: 5px 0; border-radius: 5px; font-size: 11px; font-weight: 500; text-align: center; cursor: pointer; border: none; transition: background .15s, color .15s; background: color-mix(in srgb, currentColor 7%, transparent); color: var(--fi-color-gray-600); }
        .rc-auth-tab.active { background: #f97316; color: #fff; }
        .rc-type-tabs { display: flex; gap: 3px; margin-bottom: 8px; }
        .rc-type-tab { flex: 1; padding: 4px 0; border-radius: 5px; font-size: 11px; font-weight: 500; text-align: center; cursor: pointer; border: none; transition: background .15s, color .15s; background: color-mix(in srgb, currentColor 7%, transparent); color: var(--fi-color-gray-600); }
        .rc-type-tab.active { background: #f97316; color: #fff; }
        .rc-input-row { display: flex; gap: 6px; }
        .rc-input { flex: 1; min-width: 0; padding: 6px 10px; border-radius: 6px; border: 1px solid color-mix(in srgb, currentColor 15%, transparent); background: color-mix(in srgb, currentColor 4%, transparent); color: var(--fi-color-gray-900); font-size: 12px; outline: none; }
        .rc-input:focus { border-color: #f97316; box-shadow: 0 0 0 2px rgba(249,115,22,.15); }
        .rc-input-full { width: 100%; padding: 6px 10px; border-radius: 6px; border: 1px solid color-mix(in srgb, currentColor 15%, transparent); background: color-mix(in srgb, currentColor 4%, transparent); color: var(--fi-color-gray-900); font-size: 12px; outline: none; box-sizing: border-box; }
        .rc-input-full:focus { border-color: #f97316; box-shadow: 0 0 0 2px rgba(249,115,22,.15); }
        .rc-input-token { width: 100%; padding: 6px 10px; border-radius: 6px; border: 1px solid color-mix(in srgb, currentColor 15%, transparent); background: color-mix(in srgb, currentColor 4%, transparent); color: var(--fi-color-gray-900); font-size: 11px; font-family: monospace; outline: none; box-sizing: border-box; }
        .rc-input-token:focus { border-color: #f97316; box-shadow: 0 0 0 2px rgba(249,115,22,.15); }
        .rc-token-badge { margin-top: 6px; font-size: 10px; display: flex; align-items: center; gap: 5px; }
        .rc-token-badge.set { color: #22c55e; }
        .rc-token-badge.unset { color: #6b7280; }
        .rc-user-search-results { margin-top: 6px; border: 1px solid color-mix(in srgb, currentColor 12%, transparent); border-radius: 6px; overflow: hidden; }
        .rc-user-row { display: flex; align-items: center; gap: 8px; padding: 7px 10px; cursor: pointer; transition: background .1s; border-bottom: 1px solid color-mix(in srgb, currentColor 6%, transparent); }
        .rc-user-row:last-child { border-bottom: none; }
        .rc-user-row:hover { background: color-mix(in srgb, currentColor 6%, transparent); }
        .rc-user-row.selected { background: rgba(249,115,22,.1); }
        .rc-user-avatar { width: 24px; height: 24px; border-radius: 50%; background: #f97316; color: #fff; font-size: 10px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .rc-user-info { flex: 1; min-width: 0; }
        .rc-user-name { font-size: 11px; font-weight: 600; color: var(--fi-color-gray-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .rc-user-sub { font-size: 10px; color: var(--fi-color-gray-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .rc-user-check { color: #f97316; font-size: 12px; flex-shrink: 0; }
        .rc-active-user { margin-top: 6px; padding: 7px 10px; border-radius: 6px; background: rgba(249,115,22,.08); border: 1px solid rgba(249,115,22,.2); display: flex; align-items: center; gap: 8px; }
        .rc-active-user-info { flex: 1; min-width: 0; }
        .rc-active-user-label { font-size: 10px; color: var(--fi-color-gray-400); text-transform: uppercase; letter-spacing: .05em; font-weight: 600; }
        .rc-active-user-name { font-size: 11px; font-weight: 600; color: #f97316; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .rc-clear-user { border: none; background: none; cursor: pointer; color: var(--fi-color-gray-400); padding: 2px; line-height: 1; font-size: 14px; }
        .rc-clear-user:hover { color: #ef4444; }
        .rc-warn { margin-top: 6px; font-size: 10px; color: #f59e0b; display: flex; align-items: center; gap: 4px; }
        .rc-filter-input { width: 100%; padding: 6px 10px; border-radius: 6px; border: 1px solid color-mix(in srgb, currentColor 15%, transparent); background: color-mix(in srgb, currentColor 4%, transparent); color: var(--fi-color-gray-900); font-size: 12px; outline: none; box-sizing: border-box; }
        .rc-filter-input:focus { border-color: #f97316; }
        .rc-channel-list { flex: 1; overflow-y: auto; }
        .rc-channel-item { display: flex; align-items: center; gap: 8px; padding: 7px 14px; cursor: pointer; transition: background .1s; font-size: 12px; }
        .rc-channel-item:hover { background: color-mix(in srgb, currentColor 5%, transparent); }
        .rc-channel-item.active-filter { background: rgba(249,115,22,.1); }
        .rc-channel-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .rc-channel-dot.ok { background: #22c55e; }
        .rc-channel-dot.pending { background: #f59e0b; animation: pulse 1s infinite; }
        .rc-channel-dot.error { background: #ef4444; }
        .rc-channel-name { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: monospace; }
        .rc-channel-remove { opacity: 0; color: var(--fi-color-gray-400); cursor: pointer; padding: 2px; border: none; background: none; line-height: 1; font-size: 14px; }
        .rc-channel-item:hover .rc-channel-remove { opacity: 1; }
        .rc-channel-remove:hover { color: #ef4444; }
        .rc-empty { padding: 24px 14px; text-align: center; font-size: 12px; color: var(--fi-color-gray-400); }
        .rc-main { flex: 1; min-width: 0; display: flex; flex-direction: column; background: #0d1117; color: #cdd9e5; }
        .rc-log { flex: 1; overflow-y: auto; }
        .rc-log-header { display: grid; grid-template-columns: 72px 1fr 1fr 28px; gap: 12px; padding: 8px 16px; background: #161b22; border-bottom: 1px solid rgba(255,255,255,.08); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #adbac7; position: sticky; top: 0; z-index: 10; }
        .rc-log-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 60px 20px; color: #4d5566; }
        .rc-log-empty svg { opacity: .25; }
        .rc-log-empty p { font-size: 12px; }
        .rc-row { display: grid; grid-template-columns: 72px 1fr 1fr 28px; gap: 12px; align-items: center; padding: 6px 16px; border-bottom: 1px solid rgba(255,255,255,.04); border-left: 2px solid transparent; cursor: pointer; transition: background .1s; font-size: 12px; font-family: monospace; }
        .rc-row:hover { background: rgba(255,255,255,.05); }
        .rc-row.selected { background: rgba(249,115,22,.12); border-left-color: #f97316; }
        .rc-row.system { opacity: .7; }
        .rc-time { color: #8b949e; font-size: 11px; }
        .rc-ch-public { color: #79c0ff; }
        .rc-ch-private { color: #e3b341; }
        .rc-ch-presence { color: #d2a8ff; }
        .rc-ch-none { color: #8b949e; }
        .rc-event-name { color: #56d364; }
        .rc-event-system { color: #adbac7; }
        .rc-copy-btn { opacity: 0; border: none; background: none; color: #6e7681; cursor: pointer; padding: 2px; line-height: 1; }
        .rc-row:hover .rc-copy-btn { opacity: 1; }
        .rc-copy-btn:hover { color: #cdd9e5; }
        .rc-detail { border-top: 1px solid rgba(255,255,255,.08); background: #0d1117; flex-shrink: 0; max-height: 220px; display: flex; flex-direction: column; }
        .rc-detail-header { display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: #161b22; border-bottom: 1px solid rgba(255,255,255,.06); flex-shrink: 0; }
        .rc-detail-tag { padding: 2px 7px; border-radius: 4px; font-size: 11px; font-family: monospace; }
        .rc-detail-tag.event { background: rgba(249,115,22,.15); color: #f97316; }
        .rc-detail-tag.channel { background: rgba(88,166,255,.12); color: #58a6ff; }
        .rc-detail-tag.channel.private { background: rgba(210,153,34,.12); color: #d29922; }
        .rc-detail-tag.channel.presence { background: rgba(188,140,255,.12); color: #bc8cff; }
        .rc-detail-body { overflow-y: auto; padding: 12px 16px; flex: 1; }
        .rc-detail-body pre { font-size: 11px; line-height: 1.6; color: #cdd9e5; margin: 0; white-space: pre-wrap; word-break: break-all; }
        .rc-statusbar { display: flex; align-items: center; gap: 16px; padding: 6px 16px; background: #161b22; border-top: 1px solid rgba(255,255,255,.06); font-size: 11px; color: #6e7681; flex-shrink: 0; font-family: monospace; }
        .rc-statusbar-num { color: #cdd9e5; font-weight: 600; }
        .rc-statusbar-warn { color: #f59e0b; }
    </style>

    <div
        x-data="reverbConsole({
            host: @js($reverbHost),
            port: @js($reverbPort),
            scheme: @js($reverbScheme),
            appKey: @js($appKey),
        })"
        x-init="init()"
        class="reverb-console"
    >
        {{-- Toolbar --}}
        <div class="rc-toolbar">
            <span class="rc-status-dot" :class="status"></span>
            <span class="rc-status-label" x-text="status"></span>
            <span class="rc-socket-id" x-show="socketId" x-text="socketId"></span>

            <div class="rc-spacer"></div>

            <button x-show="status==='idle'||status==='disconnected'" @click="connect()" class="rc-btn rc-btn-primary">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                Connect
            </button>
            <button x-show="status==='connecting'||status==='connected'" @click="disconnect()" class="rc-btn rc-btn-danger">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="6" width="12" height="12"/></svg>
                Disconnect
            </button>
            <button @click="paused=!paused" class="rc-btn rc-btn-ghost">
                <template x-if="!paused">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                </template>
                <template x-if="paused">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                </template>
                <span x-text="paused ? 'Resume' : 'Pause'"></span>
            </button>
            <button @click="events=[]; selectedEvent=null" class="rc-btn rc-btn-ghost">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                Clear
            </button>
        </div>

        {{-- Body --}}
        <div class="rc-body">

            {{-- Sidebar --}}
            <div class="rc-sidebar">

                {{-- Auth Section --}}
                <div class="rc-sidebar-section">
                    <div class="rc-section-title">Auth Identity</div>

                    @if($selectedUserId)
                        @php
                            $activeUser = \App\Models\User::query()->find($selectedUserId);
                        @endphp
                        <div class="rc-active-user">
                            <div class="rc-user-avatar">{{ strtoupper(substr($activeUser?->name ?? '?', 0, 1)) }}</div>
                            <div class="rc-active-user-info">
                                <div class="rc-active-user-label">Acting as</div>
                                <div class="rc-active-user-name">{{ $activeUser?->name ?? $activeUser?->email ?? 'User #'.$selectedUserId }}</div>
                            </div>
                            <button wire:click="$set('selectedUserId', null)" class="rc-clear-user" title="Clear user">×</button>
                        </div>
                    @else
                        <input
                            wire:model.live="userQuery"
                            type="text"
                            placeholder="Search by name, email or phone…"
                            class="rc-input-full"
                            autocomplete="off"
                        />
                        @if(strlen($userQuery) >= 2)
                            @if($this->matchingUsers->isEmpty())
                                <div style="margin-top:6px;font-size:11px;color:var(--fi-color-gray-400);text-align:center;padding:8px 0;">No users found</div>
                            @else
                                <div class="rc-user-search-results">
                                    @foreach($this->matchingUsers as $matchingUser)
                                        <div
                                            wire:click="$set('selectedUserId', {{ $matchingUser->id }})"
                                            class="rc-user-row"
                                        >
                                            <div class="rc-user-avatar">{{ strtoupper(substr($matchingUser->name ?? '?', 0, 1)) }}</div>
                                            <div class="rc-user-info">
                                                <div class="rc-user-name">{{ $matchingUser->name ?? '(no name)' }}</div>
                                                <div class="rc-user-sub">{{ $matchingUser->email ?? $matchingUser->phone_number ?? '#'.$matchingUser->id }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @elseif(strlen($userQuery) > 0)
                            <div style="margin-top:6px;font-size:10px;color:var(--fi-color-gray-400);">Type at least 2 characters</div>
                        @endif
                    @endif

                    <div class="rc-token-badge {{ $selectedUserId ? 'set' : 'unset' }}" style="margin-top:6px;">
                        <svg width="7" height="7" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
                        <span>{{ $selectedUserId ? 'Identity set — private/presence ready' : 'No identity — public channels only' }}</span>
                    </div>
                </div>

                {{-- Subscribe --}}
                <div class="rc-sidebar-section">
                    <div class="rc-section-title">Subscribe</div>
                    <div class="rc-type-tabs">
                        <template x-for="t in ['public','private','presence']" :key="t">
                            <button
                                @click="newChannelType=t"
                                :class="newChannelType===t ? 'rc-type-tab active' : 'rc-type-tab'"
                                x-text="t"
                            ></button>
                        </template>
                    </div>
                    <div class="rc-input-row">
                        <input
                            x-model="newChannelName"
                            @keydown.enter="subscribeChannel()"
                            type="text"
                            placeholder="channel-name"
                            class="rc-input"
                        />
                        <button
                            @click="subscribeChannel()"
                            :disabled="!newChannelName.trim()"
                            class="rc-btn rc-btn-primary"
                            style="padding:6px 10px;"
                        >+</button>
                    </div>
                    @if(!$selectedUserId)
                        <div
                            x-show="newChannelType==='presence'"
                            class="rc-warn"
                        >
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                            Select a user for accurate presence member data
                        </div>
                    @endif
                </div>

                {{-- Filter --}}
                <div class="rc-sidebar-section" style="padding-bottom:10px;">
                    <div class="rc-section-title">Filter</div>
                    <input x-model="filter" type="text" placeholder="channel or event…" class="rc-filter-input" />
                </div>

                {{-- Channels --}}
                <div class="rc-sidebar-channels">
                    <div class="rc-section-title" style="display:flex;align-items:center;justify-content:space-between;">
                        <span>Channels</span>
                        <span x-text="channels.length" style="font-size:11px;background:color-mix(in srgb,currentColor 10%,transparent);padding:1px 6px;border-radius:8px;font-weight:600;"></span>
                    </div>
                    <div class="rc-channel-list">
                        <template x-if="channels.length===0">
                            <div class="rc-empty">No channels yet</div>
                        </template>
                        <template x-for="ch in channels" :key="ch.name">
                            <div
                                @click="filterByChannel(ch.name)"
                                :class="filter===ch.name ? 'rc-channel-item active-filter' : 'rc-channel-item'"
                            >
                                <span :class="ch.error ? 'rc-channel-dot error' : ch.subscribed ? 'rc-channel-dot ok' : 'rc-channel-dot pending'"></span>
                                <span
                                    class="rc-channel-name"
                                    :class="chColorClass(ch.name)"
                                    x-text="ch.name"
                                    :title="ch.error || ch.name"
                                ></span>
                                <button @click.stop="unsubscribeChannel(ch.name)" class="rc-channel-remove">×</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Main log --}}
            <div class="rc-main">
                <div class="rc-log" x-ref="logContainer">
                    <div class="rc-log-header">
                        <span>Time</span>
                        <span>Channel</span>
                        <span>Event</span>
                        <span></span>
                    </div>

                    <template x-if="filteredEvents.length===0">
                        <div class="rc-log-empty">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8.111 16.404a5.5 5.5 0 0 1 7.778 0M12 20h.01M4.929 9.929C7.243 7.614 10.5 6.5 12 6.5s4.757 1.114 7.071 3.429M1.5 6.5C4.614 3.385 8.07 2 12 2s7.386 1.385 10.5 4.5"/></svg>
                            <p>Waiting for events — connect and subscribe to a channel</p>
                        </div>
                    </template>

                    <template x-for="ev in filteredEvents" :key="ev.id">
                        <div
                            @click="selectEvent(ev)"
                            :class="[
                                'rc-row',
                                selectedEvent&&selectedEvent.id===ev.id ? 'selected' : '',
                                ev.type!=='event' ? 'system' : '',
                            ]"
                        >
                            <span class="rc-time" x-text="ev.time"></span>
                            <span :class="'rc-channel-name '+chColorClass(ev.channel)" x-text="ev.channel||'—'"></span>
                            <span :class="ev.type==='event' ? 'rc-event-name' : 'rc-event-system'" x-text="ev.event"></span>
                            <button x-show="ev.data" @click.stop="copyPayload(ev)" class="rc-copy-btn" title="Copy">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Detail pane --}}
                <div class="rc-detail" x-show="selectedEvent" x-transition>
                    <div class="rc-detail-header">
                        <span class="rc-detail-tag event" x-text="selectedEvent?.event"></span>
                        <span
                            :class="[
                                'rc-detail-tag channel',
                                selectedEvent?.channel?.startsWith('presence-') ? 'presence' :
                                selectedEvent?.channel?.startsWith('private-') ? 'private' : ''
                            ]"
                            x-text="selectedEvent?.channel||'—'"
                        ></span>
                        <span style="font-size:11px;color:#6e7681;font-family:monospace;" x-text="selectedEvent?.time"></span>
                        <div style="flex:1"></div>
                        <button @click="copyPayload(selectedEvent)" class="rc-copy-btn" style="opacity:1;color:#6e7681;" title="Copy payload">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        </button>
                        <button @click="selectedEvent=null" style="border:none;background:none;color:#6e7681;cursor:pointer;padding:2px;font-size:16px;line-height:1;">×</button>
                    </div>
                    <div class="rc-detail-body">
                        <pre x-text="selectedEvent ? JSON.stringify(tryParse(selectedEvent.data), null, 2) : ''"></pre>
                    </div>
                </div>

                {{-- Status bar --}}
                <div class="rc-statusbar">
                    <span><span class="rc-statusbar-num" x-text="events.length"></span> events</span>
                    <span x-show="filter"><span class="rc-statusbar-num" x-text="filteredEvents.length"></span> visible</span>
                    <span x-show="paused" class="rc-statusbar-warn">⏸ paused</span>
                    <div style="flex:1"></div>
                    <span x-show="activeUserName" style="color:#f97316;" x-text="'👤 ' + activeUserName"></span>
                    <span x-text="wsUrl()"></span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function reverbConsole({ host, port, scheme, appKey }) {
        return {
            host, port, scheme, appKey,
            ws: null,
            status: 'idle',
            socketId: null,
            paused: false,
            channels: [],
            newChannelName: '',
            newChannelType: 'public',
            events: [],
            filter: '',
            selectedEvent: null,
            _counter: 0,

            get filteredEvents() {
                const q = this.filter.trim().toLowerCase();
                const src = q
                    ? this.events.filter(e =>
                        (e.channel||'').toLowerCase().includes(q) ||
                        (e.event||'').toLowerCase().includes(q))
                    : this.events;
                return [...src].reverse();
            },

            init() {
                this.$watch('events', () => {
                    if (this.paused) return;
                    this.$nextTick(() => {
                        const el = this.$refs.logContainer;
                        if (el) el.scrollTop = 0;
                    });
                });
            },

            wsUrl() {
                const secure = scheme === 'https' || scheme === 'wss' || window.location.protocol === 'https:';
                return `${secure ? 'wss' : 'ws'}://${host}:${port}/app/${appKey}`;
            },

            connect() {
                if (this.ws) this.ws.close();
                this.status = 'connecting';
                this.ws = new WebSocket(this.wsUrl() + '?protocol=7&client=js&version=8.0.0');

                this.ws.onopen = () => this._sys(null, 'WebSocket opened');

                this.ws.onmessage = ({ data: raw }) => {
                    let p; try { p = JSON.parse(raw); } catch { return; }
                    const { event = '', channel = null, data = null } = p;

                    if (event === 'pusher:connection_established') {
                        const d = typeof data === 'string' ? JSON.parse(data) : data;
                        this.socketId = d.socket_id;
                        this.status = 'connected';
                        this.resubscribeAll();
                        this._sys(null, `Connected · ${this.socketId}`);
                        return;
                    }
                    if (event === 'pusher_internal:subscription_succeeded') {
                        const ch = this.channels.find(c => c.name === channel);
                        if (ch) { ch.subscribed = true; ch.error = null; }
                        this._sys(channel, '✓ Subscribed');
                        return;
                    }
                    if (event === 'pusher_internal:member_added')  { this._sys(channel, '+ Member joined'); return; }
                    if (event === 'pusher_internal:member_removed') { this._sys(channel, '− Member left'); return; }
                    if (event === 'pusher:error') {
                        const d = typeof data === 'string' ? JSON.parse(data) : (data ?? {});
                        const msg = d.message ?? JSON.stringify(d);
                        if (channel) {
                            const ch = this.channels.find(c => c.name === channel);
                            if (ch) ch.error = msg;
                        }
                        this._sys(channel, `✕ ${msg}`);
                        return;
                    }
                    if (event === 'pusher:pong') return;

                    this._push({ event, channel, data, type: 'event' });
                };

                this.ws.onerror = () => this._sys(null, '✕ WebSocket error');
                this.ws.onclose = e => {
                    this.status = 'disconnected';
                    this.socketId = null;
                    this.channels.forEach(c => { c.subscribed = false; });
                    this._sys(null, `Disconnected (${e.code})`);
                };
            },

            disconnect() { this.ws?.close(); },
            send(p) { if (this.ws?.readyState === WebSocket.OPEN) this.ws.send(JSON.stringify(p)); },

            subscribeChannel() {
                let name = this.newChannelName.trim();
                if (!name) return;
                if (this.newChannelType === 'private'  && !name.startsWith('private-'))  name = 'private-'  + name;
                if (this.newChannelType === 'presence' && !name.startsWith('presence-')) name = 'presence-' + name;
                if (this.channels.find(c => c.name === name)) return;
                this.channels.push({ name, subscribed: false, error: null });
                this.newChannelName = '';
                if (this.status === 'connected') this._subscribe(name);
            },

            async _subscribe(name) {
                const isPrivate  = name.startsWith('private-');
                const isPresence = name.startsWith('presence-');

                if (isPrivate || isPresence) {
                    try {
                        const authData = await this.$wire.signChannelAuth(name, this.socketId);
                        const payload = { channel: name, auth: authData.auth };
                        if (authData.channel_data) payload.channel_data = authData.channel_data;
                        this.send({ event: 'pusher:subscribe', data: payload });
                    } catch (err) {
                        const msg = err.message ?? 'Channel auth failed';
                        const ch = this.channels.find(c => c.name === name);
                        if (ch) ch.error = msg;
                        this._sys(name, `✕ Auth: ${msg}`);
                    }
                    return;
                }

                this.send({ event: 'pusher:subscribe', data: { channel: name, auth: '' } });
            },

            resubscribeAll() { this.channels.forEach(c => { c.subscribed = false; c.error = null; this._subscribe(c.name); }); },
            unsubscribeChannel(name) {
                this.channels = this.channels.filter(c => c.name !== name);
                this.send({ event: 'pusher:unsubscribe', data: { channel: name } });
                if (this.filter === name) this.filter = '';
            },

            filterByChannel(name) { this.filter = this.filter === name ? '' : name; },

            _push(entry) {
                if (this.paused) return;
                this.events.push({ id: ++this._counter, time: new Date().toLocaleTimeString('en-US', { hour12: false }), ...entry });
                if (this.events.length > 500) this.events.shift();
            },
            _sys(channel, message) { this._push({ event: message, channel, data: null, type: 'system' }); },

            selectEvent(ev) { this.selectedEvent = this.selectedEvent?.id === ev.id ? null : ev; },
            tryParse(d) { if (!d) return null; if (typeof d === 'object') return d; try { return JSON.parse(d); } catch { return d; } },
            async copyPayload(ev) {
                if (!ev?.data) return;
                await navigator.clipboard.writeText(JSON.stringify(this.tryParse(ev.data), null, 2));
            },
            chColorClass(ch) {
                if (!ch) return 'rc-ch-none';
                if (ch.startsWith('presence-')) return 'rc-ch-presence';
                if (ch.startsWith('private-'))  return 'rc-ch-private';
                return 'rc-ch-public';
            },
        };
    }
    </script>
    @endpush
</x-filament-panels::page>
