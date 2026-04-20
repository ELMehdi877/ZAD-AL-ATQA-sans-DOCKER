<main
    x-ref="main-chat-body"
    x-data="{
        el: null,

        // paging guards
        loadingOlder: false,
        loadingNewer: false,
        pendingPrependRestore: false,

        // prepend anchor
        anchorId: null,
        anchorOffset: 0,

        // jump target (only valid for a short time)
        jumpTargetId: null,
        jumpLockUntil: 0,

        // cancels old retries
        jumpSeq: 0,

        // prevent infinite spam on load events
        mediaFixQueued: false,

        // manual scroll detection
        lastScrollTop: 0,

        // initial load guard
        initializing: true,

        captureAnchor() {
            const container = this.el;
            if (!container) return;

            const containerTop = container.getBoundingClientRect().top;
            const nodes = container.querySelectorAll('[data-message-id]');

            for (const node of nodes) {
                const rect = node.getBoundingClientRect();
                if (rect.bottom >= containerTop + 1) {
                    this.anchorId = node.getAttribute('data-message-id');
                    this.anchorOffset = rect.top - containerTop;
                    return;
                }
            }

            this.anchorId = null;
            this.anchorOffset = 0;
        },

        restoreToAnchor() {
            const container = this.el;
            if (!container || !this.anchorId) return;

            const containerTop = container.getBoundingClientRect().top;
            const node = container.querySelector(`[data-message-id='${this.anchorId}']`);
            if (!node) return;

            const rect = node.getBoundingClientRect();
            const newOffset = rect.top - containerTop;

            container.scrollTop += (newOffset - this.anchorOffset);
        },

        restoreAfterOlderLoaded() {
            if (!this.pendingPrependRestore) return;
            this.pendingPrependRestore = false;
            this.loadingOlder = false;

            requestAnimationFrame(() => {
                this.restoreToAnchor();
                requestAnimationFrame(() => this.restoreToAnchor());
            });
        },

        isNearTop() {
            const c = this.el;
            return c && c.scrollTop <= 10 && !this.loadingOlder && !this.pendingPrependRestore;
        },

        isNearBottom() {
            const c = this.el;
            return c && (c.scrollHeight - (c.scrollTop + c.clientHeight)) <= 30 && !this.loadingNewer;
        },

        async loadOlderWithStableScroll() {
            if (this.loadingOlder || this.pendingPrependRestore) return;
            if (!$wire.canLoadOlder) return;

            this.jumpTargetId = null;
            this.jumpLockUntil = 0;
            this.loadingOlder = true;
            this.captureAnchor();
            this.pendingPrependRestore = true;

            try {
                await $wire.loadOlder();

                if (!this.pendingPrependRestore) {
                    this.loadingOlder = false;
                }
            } catch (error) {
                this.pendingPrependRestore = false;
                this.loadingOlder = false;
            }
        },

        loadNewerIfNeeded() {
            if (this.loadingNewer) return;
            if (!$wire.canLoadNewer) return;

            this.loadingNewer = true;

            $wire.loadNewer().finally(() => {
                this.loadingNewer = false;
            });
        },

        scrollToMessageCenter(id) {
            const container = this.el;
            if (!container) return false;

            const node = container.querySelector(`[data-message-id='${id}']`);
            if (!node) return false;

            const nodeTop = node.offsetTop;
            const nodeH = node.offsetHeight;
            const cH = container.clientHeight;

            const target = Math.max(0, nodeTop - (cH / 2) + (nodeH / 2));
            container.scrollTop = target;

            node.classList.add('ring-2', 'ring-offset-2', 'ring-primary-500');
            setTimeout(() => node.classList.remove('ring-2', 'ring-offset-2', 'ring-primary-500'), 1200);

            return true;
        },

        scrollToMessageCenterWithRetry(id, tries = 40) {
            this.jumpSeq++;
            const seq = this.jumpSeq;

            this.jumpTargetId = id;
            this.jumpLockUntil = Date.now() + 1200;

            const tick = () => {
                if (seq !== this.jumpSeq) return;

                if (this.scrollToMessageCenter(id)) return;
                if (tries <= 0) return;

                tries--;
                requestAnimationFrame(tick);
            };

            requestAnimationFrame(tick);
        },

        onAnyMediaLoad() {
            if (this.mediaFixQueued) return;
            this.mediaFixQueued = true;

            requestAnimationFrame(() => {
                this.mediaFixQueued = false;

                if (this.pendingPrependRestore || this.loadingOlder) {
                    this.restoreToAnchor();
                    requestAnimationFrame(() => this.restoreToAnchor());
                    return;
                }

                if (this.initializing) {
                    this.scrollToBottom();
                    requestAnimationFrame(() => this.scrollToBottom());
                    return;
                }

                if (this.jumpTargetId && Date.now() < this.jumpLockUntil) {
                    this.scrollToMessageCenter(this.jumpTargetId);
                    requestAnimationFrame(() => this.scrollToMessageCenter(this.jumpTargetId));
                }
            });
        },

        scrollToBottom() {
            if (!this.el) return;
            this.el.scrollTop = this.el.scrollHeight;
            this.lastScrollTop = this.el.scrollTop;
        },

        onScroll() {
            const c = this.el;
            if (!c) return;

            const currentTop = c.scrollTop;
            const delta = Math.abs(currentTop - this.lastScrollTop);

            if (delta > 8) {
                this.jumpTargetId = null;
                this.jumpLockUntil = 0;
            }

            this.lastScrollTop = currentTop;

            if (this.isNearTop()) this.loadOlderWithStableScroll();
            if (this.isNearBottom()) this.loadNewerIfNeeded();
        },
    }"
        x-init="
        el = $el;
        lastScrollTop = el.scrollTop;

        setTimeout(() => {
            requestAnimationFrame(() => {
                scrollToBottom();
            });
        }, 100);

        setTimeout(() => {
            initializing = false;
        }, 220);
        "
    @scroll="onScroll()"
    x-on:load.capture="$data.onAnyMediaLoad()"
    x-on:error.capture="$data.onAnyMediaLoad()"

        @scroll-bottom.window="
        requestAnimationFrame(() => {
            el.style.overflowY = 'hidden';
            scrollToBottom();
            el.style.overflowY = 'auto';
        });
    "

        @scroll-to-message.window="$data.scrollToMessageCenterWithRetry($event.detail.id)"
        @older-loaded.window="$data.restoreAfterOlderLoaded()"

    x-cloak
    x-bind:class="{'opacity-0 pointer-events-none': initializing}"
     class='relative flex min-h-0 w-full grow flex-col gap-4 overflow-x-hidden overflow-y-auto bg-[#f8f5ee] px-3 py-4 transition-opacity duration-150 overscroll-contain [background-image:radial-gradient(circle_at_1px_1px,rgba(198,155,61,0.12)_1px,transparent_0)] [background-size:24px_24px] md:px-5 md:py-5 lg:px-7'
    style="contain: layout paint"
>

    <div x-cloak wire:loading.delay.class.remove="invisible" wire:target="loadOlder" class="invisible transition-all duration-300 ">
        <x-wirechat::loading-spin />
    </div>

    {{-- Define previous message outside the loop --}}
    @php
        $previousMessage = null;
    @endphp

    <!--Message-->
    @if ($loadedMessages)
        {{-- @dd($loadedMessages) --}}
        @foreach ($loadedMessages as $date => $messageGroup)

            {{-- Date  --}}
            <div wire:key="group-{{ md5($date) }}" class="sticky top-4 z-10 mx-auto w-fit rounded-full border border-[#e8dfc4] bg-[#f7f0df]/95 px-4 py-1 text-center text-[11px] font-semibold uppercase tracking-[0.16em] text-[#7a705c] shadow-sm backdrop-blur">
                {{ $date }}
            </div>

            @foreach ($messageGroup as $key => $message)
                {{-- @dd($message) --}}
                @php
                    $belongsToAuth = $message->belongsToAuth();
                    $parent = $message->parent ?? null;
                    $attachment = $message->attachment ?? null;
                    $isEmoji = $message->isEmoji();


                    // keep track of previous message
                    // The ($key -1 ) will get the previous message from loaded
                    // messages since $key is directly linked to $message
                    if ($key > 0) {
                        $previousMessage = $messageGroup->get($key - 1);
                    }

                    // Get the next message
                    $nextMessage = $key < $messageGroup->count() - 1 ? $messageGroup->get($key + 1) : null;
                @endphp
                <div
                    @class([
                        'flex w-full gap-2',
                        'justify-end' => $belongsToAuth,
                    ])
                    data-message-id="{{ $message->id }}"
                    id="message-{{ $message->id }}"
                    wire:key="msg-{{ $message->id }}"
                >

                    {{-- Message user Avatar --}}
                    {{-- Hide avatar if message belongs to auth --}}
                    @if (!$belongsToAuth && !$isPrivate)
                        <div @class([
                            'shrink-0 self-end pb-1',
                            // Hide avatar if the next message is from the same user
                            'invisible' =>
                                $previousMessage &&
                                $message?->sendable?->is($previousMessage?->sendable),
                        ])>
                            <x-wirechat::avatar src="{{ $message->sendable?->wirechat_avatar_url ?? null }}" class="h-9 w-9" />
                        </div>
                    @endif

                    {{-- Message content --}}
                    <div class="flex-1">
                        <div @class([
                            'flex max-w-[88%] flex-col gap-y-2 md:max-w-[72%]',
                            'ml-auto' => $belongsToAuth])>



                            {{-- Show parent/reply message --}}
                            @if ($parent != null)
                                <div @class([
                                    'flex max-w-fit flex-col gap-y-2',
                                    'ml-auto' => $belongsToAuth,
                                    // 'ml-9 sm:ml-10' => !$belongsToAuth,
                                ])>


                                    @php
                                    $sender = $message?->ownedBy($this->auth)
                                        ? __('wirechat::chat.labels.you')
                                        : ($message->sendable?->wirechat_name ?? __('wirechat::chat.labels.user'));

                                    $receiver = $parent?->ownedBy($this->auth)
                                        ? __('wirechat::chat.labels.you')
                                        : ($parent->sendable?->wirechat_name ?? __('wirechat::chat.labels.user'));
                                    @endphp

                                    <h6 class="px-2 text-[12px] text-[#8a806b] dark:text-gray-300">
                                        @if ($parent?->ownedBy($this->auth) && $message?->ownedBy($this->auth))
                                            {{ __('wirechat::chat.labels.you_replied_to_yourself') }}
                                        @elseif ($parent?->ownedBy($this->auth))
                                            {{ __('wirechat::chat.labels.participant_replied_to_you', ['sender' => $sender]) }}
                                        @elseif ($message?->ownedBy($parent->sendable))
                                            {{ __('wirechat::chat.labels.participant_replied_to_themself', ['sender' => $sender]) }}
                                        @else
                                            {{ __('wirechat::chat.labels.participant_replied_other_participant', ['sender' => $sender, 'receiver' => $receiver]) }}
                                        @endif
                                    </h6>



                                    <div @class([
                                        'overflow-hidden px-1',
                                        'ml-auto border-r-4 border-[#d8c58f]' => $belongsToAuth,
                                        'mr-auto border-l-4 border-[#d8c58f]' => !$belongsToAuth,
                                    ])>
                                        <p
                                            class="max-w-fit rounded-2xl bg-[#f7f1e4] px-3 py-2 text-[13px] text-[#4f493c] line-clamp-1 break-all dark:bg-[var(--wc-dark-secondary)] dark:text-white">
                                            {{ $parent?->body != '' ? $parent?->body : ($parent->hasAttachment() ?  __('wirechat::chat.labels.attachment') : '') }}
                                        </p>
                                    </div>


                                </div>
                            @endif



                            {{-- Body section --}}
                            <div @class([
                                'group flex gap-2 transition-transform md:gap-3',
                                'justify-end' => $belongsToAuth,
                            ])>

                                {{-- Message Actions --}}
                                @if (($isGroup && $conversation->group?->allowsMembersToSendMessages()) || $authParticipant->isAdmin())
                                <div dusk="message_actions" @class([ 'my-auto flex w-auto items-center gap-1.5', 'order-1' => !$belongsToAuth, ])>
                                    {{-- reply button --}}
                                    <button wire:click="setReply('{{ encrypt($message->id) }}')"
                                        class="invisible rounded-full bg-white/70 p-1.5 text-[#6f6655] shadow-sm group-hover:visible hover:scale-110 transition-transform">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-reply-fill w-4 h-4 dark:text-white"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                                        </svg>
                                    </button>
                                    {{-- Dropdown actions button --}}
                                    <x-wirechat::dropdown class="w-40" align="{{ $belongsToAuth ? 'right' : 'left' }}"
                                        width="48">
                                        <x-slot name="trigger">
                                            {{-- Dots --}}
                                            <button class="invisible rounded-full bg-white/70 p-1.5 text-[#6f6655] shadow-sm group-hover:visible hover:scale-110 transition-transform">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor"
                                                    class="bi bi-three-dots h-3 w-3 text-gray-700 dark:text-white"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">

                                            @if (($message->ownedBy($this->auth)|| ($authParticipant->isAdmin() && $isGroup)) && $this->panel()->hasDeleteMessageActions())
                                                <button dusk="delete_message_for_everyone" wire:click="deleteForEveryone('{{ encrypt($message->id) }}')"
                                                    wire:confirm="{{ __('wirechat::chat.actions.delete_for_everyone.confirmation_message') }}" class="w-full text-start">
                                                    <x-wirechat::dropdown-link>
                                                        @lang('wirechat::chat.actions.delete_for_everyone.label')
                                                    </x-wirechat::dropdown-link>
                                                </button>
                                            @endif


                                            {{-- Dont show delete for me if is group --}}
                                            @if (!$isGroup && $this->panel()->hasDeleteMessageActions())
                                            <button dusk="delete_message_for_me" wire:click="deleteForMe('{{ encrypt($message->id) }}')"
                                                wire:confirm="{{ __('wirechat::chat.actions.delete_for_me.confirmation_message') }}" class="w-full text-start">
                                                <x-wirechat::dropdown-link>
                                                    @lang('wirechat::chat.actions.delete_for_me.label')
                                                </x-wirechat::dropdown-link>
                                            </button>
                                            @endif


                                            <button dusk="reply_to_message_button" wire:click="setReply('{{ encrypt($message->id) }}')"class="w-full text-start">
                                                <x-wirechat::dropdown-link>
                                                    @lang('wirechat::chat.actions.reply.label')
                                                </x-wirechat::dropdown-link>
                                            </button>


                                        </x-slot>
                                    </x-wirechat::dropdown>

                                </div>
                                @endif


                                {{-- Message body --}}
                                <div class="relative flex max-w-full flex-col gap-2">
                                    {{-- Show sender name is message does not belong to auth and conversation is group --}}


                                    {{-- -------------------- --}}
                                    {{-- Attachment section --}}
                                    {{-- -------------------- --}}
                                    @if ($attachment)
                                        @if (!$belongsToAuth && $isGroup)
                                            <div style="color:  var(--wc-brand-primary);" @class([
                                                'shrink-0 font-medium text-sm sm:text-base',
                                                // Hide avatar if the next message is from the same user
                                                'hidden' => $message?->sendable?->is($previousMessage?->sendable),
                                            ])>
                                                {{ $message->sendable?->wirechat_name }}
                                            </div>
                                        @endif

                                        {{-- Attachemnt is Video/ --}}
                                        @if ($attachment->isVideo())
                                            <x-wirechat::video height="max-h-[400px]" :cover="false" source="{{ $attachment?->url }}" />

                                        {{-- Attachemnt is image/ --}}
                                        @elseif($attachment->isImage())
                                            @include('wirechat::livewire.chat.partials.image', [ 'previousMessage' => $previousMessage, 'message' => $message, 'nextMessage' => $nextMessage, 'belongsToAuth' => $belongsToAuth, 'attachment' => $attachment ])
                                        @else
                                         {{-- Attachemnt is Application/ --}}
                                          @include('wirechat::livewire.chat.partials.file', [ 'attachment' => $attachment ])
                                        @endif

                                    @endif

                                    {{-- if message is emoji then don't show the styled messagebody layout --}}
                                    @if ($isEmoji)
                                        <p class="text-5xl dark:text-white ">
                                            {{ $message->body }}
                                        </p>
                                    @endif

                                    {{-- -------------------- --}}
                                    {{-- Message body section --}}
                                    {{-- If message is not emoji then show the message body styles --}}
                                    {{-- -------------------- --}}

                                    @if ($message->body && !$isEmoji)
                                    @include('wirechat::livewire.chat.partials.message', [ 'previousMessage' => $previousMessage, 'message' => $message, 'nextMessage' => $nextMessage, 'belongsToAuth' => $belongsToAuth, 'isGroup' => $isGroup, 'attachment' => $attachment])
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        @endforeach


    @endif

</main>
