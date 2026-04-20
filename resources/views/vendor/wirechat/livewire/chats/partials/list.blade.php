@use('Wirechat\Wirechat\Facades\Wirechat')
@use('Wirechat\Wirechat\Support\Enums\UnreadIndicatorType')

@php
$unreadIndicatorType = $this->panel()->getUnreadIndicatorType();
@endphp

<ul wire:loading.delay.long.remove wire:target="search" class="grid w-full overflow-x-hidden">
    @foreach ($conversations as $key=> $conversation)
    @php
    //$receiver =$conversation->getReceiver();
    $group = $conversation->isGroup() ? $conversation->group : null;
    $receiver = $conversation->isGroup() ? null : ($conversation->isPrivate() ? $conversation->peer_participant?->participantable : $this->auth);
    //$receiver = $conversation->isGroup() ? null : ($conversation->isPrivate() ? $conversation->peerParticipant()?->participantable : $this->auth);
    $lastMessage = $conversation->lastMessage;
    $belongsToAuth = $lastMessage?->belongsToAuth();
    $unreadIndicatorCount = $unreadIndicatorType === UnreadIndicatorType::Count
        ? (int) $conversation->getAttribute('unread_messages_count')
        : null;
    $hasUnreadMessages = $unreadIndicatorType === UnreadIndicatorType::Count
        ? $unreadIndicatorCount > 0
        : (bool) $conversation->getAttribute('has_unread_messages');
    $unreadIndicatorKey = $unreadIndicatorType === UnreadIndicatorType::Count
        ? $unreadIndicatorCount
        : (int) $hasUnreadMessages;
    $showUnreadStatus = $this->panel()->hasUnreadIndicator()
        && $lastMessage != null
        && $hasUnreadMessages
        && $selectedConversationId != $conversation->id;
    $conversationTitle = $group ? $group?->name : $receiver?->wirechat_name;
    $conversationSubtitle = $conversation->isGroup()
        ? 'Groupe'
        : (string) str((string) ($receiver?->role ?: 'Contact'))->replace('_', ' ')->title();
    @endphp

    <li x-data="{
        conversationID: @js($conversation->id),
        handleChatOpened(event) {
            if (event.detail.conversation== this.conversationID) {
                selectedConversationId = event.detail.conversation;
            }
            $wire.selectedConversationId= event.detail.conversation;
        },
        handleChatClosed(event) {
            $wire.selectedConversationId = null;
            selectedConversationId = null;
        }
    }"
    data-show-unread-status="{{ $showUnreadStatus ? '1' : '0' }}"

    id="conversation-{{ $conversation->id }}"
        wire:key="conversation-em-{{ $conversation->id }}-{{ $lastMessage?->id ?? 'none' }}-{{ $unreadIndicatorKey }}"
        x-on:chat-opened.window="handleChatOpened($event)"
        x-on:chat-closed.window="handleChatClosed($event)">
        <a @if ($widget) tabindex="0"
        role="button"
        dusk="openChatWidgetButton"
        @click="$dispatch('open-chat',{conversation:@js($conversation->id)})"
        @keydown.enter="$dispatch('open-chat',{conversation:@js($conversation->id)})"
        @else
        wire:navigate href="{{ $this->panel()->chatRoute($conversation->id)}}" @endif

            class="relative flex w-full min-w-0 cursor-pointer items-start gap-4 overflow-hidden border-b border-[#ece2c9] px-6 py-5 transition-all duration-200 hover:bg-[#faf5ea] dark:hover:bg-[var(--wc-dark-secondary)]"
            :class="$wire.selectedConversationId == conversationID &&
                'bg-[#eaf3ed] dark:bg-[var(--wc-dark-secondary)]'">

            <div class="mt-0.5 shrink-0">
                <x-wirechat::avatar key="chat-list-conversation-{{$key}}" wire:key="chatslist-key-{{$key}}" disappearing="{{ $conversation->hasDisappearingTurnedOn() }}"
                    group="{{ $conversation->isGroup() }}"
                    :src="$group ? $group?->cover_url : $receiver?->wirechat_avatar_url ?? null" class="h-14 w-14 border-0 bg-[#d2ac46] text-[#04371f] shadow-sm" />
            </div>

            <aside class="flex min-w-0 flex-1 items-start justify-between gap-3">
                <div class="min-w-0 flex-1 overflow-hidden">

                    {{-- name --}}
                    <div class="flex min-w-0 w-full items-center gap-1">
                        <h6 class="min-w-0 flex-1 truncate text-[16px] font-semibold text-[#17212b] dark:text-white">
                            {{ $conversationTitle }}
                        </h6>

                        @if ($conversation->isSelfConversation())
                            <span class="text-[12px] font-medium text-[#7b735f] dark:text-white">({{ __('wirechat::chats.labels.you') }})</span>
                        @endif

                    </div>
                    <p class="mt-1 truncate text-[12px] font-medium text-[#94886f]">{{ $conversationSubtitle }}</p>
                    {{-- Message body --}}
                    @if ($lastMessage != null)
                        <div class="mt-1.5">
                            @include('wirechat::livewire.chats.partials.message-body')
                        </div>
                    @endif

                </div>

                {{-- Read status --}}
                {{-- Only show if AUTH is NOT onwer of message --}}
                @if ($showUnreadStatus)
                    @if ($unreadIndicatorType === UnreadIndicatorType::Count)
                        <div x-show="selectedConversationId != conversationID" dusk="unreadMessagesCount" class="flex min-w-[2rem] justify-end pt-1">
                            <span class="sr-only">unread messages count</span>
                            <span
                                @style(['background-color:var(--wc-brand-primary)'])
                                class="inline-flex min-w-6 items-center justify-center rounded-full px-2 py-1 text-xs font-semibold leading-none text-white"
                            >
                                {{ $unreadIndicatorCount }}
                            </span>
                        </div>
                    @else
                        <div x-show="selectedConversationId != conversationID" dusk="unreadMessagesDot" class="flex min-w-[2rem] justify-end pt-1">
                            {{-- Dots icon --}}
                            <span dusk="unreadDotItem" class="sr-only">unread dot</span>
                            <svg @style(['color:var(--wc-brand-primary)']) xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-dot w-10 h-10 text-blue-500" viewBox="0 0 16 16">
                                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                            </svg>

                        </div>
                    @endif
                @endif


            </aside>
        </a>

    </li>
    @endforeach

</ul>
