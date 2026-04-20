@use('Wirechat\Wirechat\Facades\Wirechat')

@php
    $group = $conversation->group;
    $conversationLabel = $conversation->isGroup()
        ? 'Groupe'
        : (string) str((string) ($receiver?->role ?: 'Conversation'))->replace('_', ' ')->title();
@endphp

<header class="sticky inset-x-0 top-0 z-10 flex w-full border-b border-[#e8dfc4] bg-[#f8f5ee]/95 shadow-sm backdrop-blur">

    <div class="flex w-full items-center gap-3 px-5 py-4 lg:px-7">

        {{-- Return --}}
        @if ($this->isWidget())
            <button
                type="button"
                aria-label="{{ __('wirechat::chat.actions.close_chat.label') }}"
                @click="$dispatch('close-chat',{conversation: {{json_encode($conversation->id)}} })"
                dusk="return_to_home_button_dispatch"
                class="shrink-0 rounded-full border border-[#e8dfc4] bg-[#fbf7eb] p-2 text-[#5e5543] shadow-sm"
                id="chatReturn">
                <x-wirechat::icons.chevron-left />
            </button>
        @else
            <a wire:navigate
                href="{{ $this->panel()->chatsRoute() }}"
                aria-label="{{ __('wirechat::chat.actions.close_chat.label') }}"
                dusk="return_to_home_button_link"
                class="shrink-0 rounded-full border border-[#e8dfc4] bg-[#fbf7eb] p-2 text-[#5e5543] shadow-sm lg:hidden"
                id="chatReturn">
                <x-wirechat::icons.chevron-left />
            </a>
        @endif

        {{-- Receiver wirechat::Avatar --}}
        <section class="grid w-full grid-cols-[minmax(0,1fr)_auto] items-center gap-3">
            <div class="min-w-0">

                {{-- Group --}}
                @if ($conversation->isGroup())
                    <x-wirechat::actions.show-group-info
                        conversation="{{ $conversation->id }}"
                        widget="{{ $this->isWidget() }}"
                        panel="{{$this->panel}}"
                    >
                        <div class="flex cursor-pointer items-center gap-3">
                            <x-wirechat::avatar disappearing="{{ $conversation->hasDisappearingTurnedOn() }}"
                                :group="true" :src="$group?->cover_url ?? null "
                                class="h-12 w-12 border-0 bg-[#d2ac46] text-[#04371f] shadow-sm" />
                            <div class="min-w-0">
                                <h6 class="w-full truncate text-[17px] font-semibold text-[#17212b] dark:text-white">
                                    {{ $group?->name }}
                                </h6>
                                <span class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-[#e8f5ea] px-3 py-1 text-[12px] font-medium text-[#21884c]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#28a35a]"></span>
                                    {{ $conversationLabel }}
                                </span>
                            </div>
                        </div>
                    </x-wirechat::actions.show-group-info>
                @else
                    {{-- Not Group --}}
                    <x-wirechat::actions.show-chat-info
                        conversation="{{ $conversation->id }}"
                        widget="{{ $this->isWidget() }}"
                        panel="{{$this->panel}}">
                        <div class="flex cursor-pointer items-center gap-3">
                            <x-wirechat::avatar disappearing="{{ $conversation->hasDisappearingTurnedOn() }}"
                                :group="false" :src="$receiver?->wirechat_avatar_url ?? null"
                                class="h-12 w-12 border-0 bg-[#d2ac46] text-[#04371f] shadow-sm" />
                            <div class="min-w-0">
                                <h6 class="w-full truncate text-[17px] font-semibold text-[#17212b] dark:text-white">
                                    {{ $receiver?->wirechat_name }} @if ($conversation->isSelfConversation())
                                        ({{ __('wirechat::chat.labels.you') }})
                                    @endif
                                </h6>
                                <span class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-[#e8f5ea] px-3 py-1 text-[12px] font-medium text-[#21884c]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#28a35a]"></span>
                                    {{ $conversationLabel }}
                                </span>
                            </div>
                        </div>
                    </x-wirechat::actions.show-chat-info>
                @endif


            </div>

            {{-- Header Actions --}}
            <div class="ml-auto flex items-center gap-2">
                <x-wirechat::dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex cursor-pointer rounded-full border border-[#e8dfc4] bg-[#fbf7eb] p-2.5 text-[#655b49] shadow-sm dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.9" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                            </svg>

                        </button>
                    </x-slot>
                    <x-slot name="content">


                        @if ($conversation->isGroup())
                            {{-- Open group info button --}}
                            <x-wirechat::actions.show-group-info conversation="{{ $conversation->id }}"
                                widget="{{ $this->isWidget() }}">
                                <button class="w-full text-start">
                                    <x-wirechat::dropdown-link>
                                        {{ __('wirechat::chat.actions.open_group_info.label') }}
                                    </x-wirechat::dropdown-link>
                                </button>
                            </x-wirechat::actions.show-group-info>
                        @else
                            {{-- Open chat info button --}}
                            <x-wirechat::actions.show-chat-info conversation="{{ $conversation->id }}"
                                widget="{{ $this->isWidget() }}">
                                <button class="w-full text-start">
                                    <x-wirechat::dropdown-link>
                                        {{ __('wirechat::chat.actions.open_chat_info.label') }}
                                    </x-wirechat::dropdown-link>
                                </button>
                            </x-wirechat::actions.show-chat-info>
                        @endif


                        @if ($this->isWidget())
                            <x-wirechat::dropdown-link @click="$dispatch('close-chat',{conversation: {{json_encode($conversation->id)}} })">
                                @lang('wirechat::chat.actions.close_chat.label')
                            </x-wirechat::dropdown-link>
                        @else
                            <x-wirechat::dropdown-link href="{{ $this->panel()->chatsRoute()  }}" class="shrink-0">
                                @lang('wirechat::chat.actions.close_chat.label')
                            </x-wirechat::dropdown-link>
                        @endif


                        {{-- Only show delete and clear if conversation is NOT group --}}
                        @if (!$conversation->isGroup())
                            @if($this->panel()->hasClearChatAction())
                            <button dusk="clear-chat-action" class="w-full" wire:click="clearConversation"
                                wire:confirm="{{ __('wirechat::chat.actions.clear_chat.confirmation_message') }}">

                                <x-wirechat::dropdown-link>
                                    @lang('wirechat::chat.actions.clear_chat.label')
                                </x-wirechat::dropdown-link>
                            </button>
                            @endif

                           @if($this->panel()->hasDeleteChatAction())
                            <button dusk="delete-chat-action" wire:click="deleteConversation"
                                wire:confirm="{{ __('wirechat::chat.actions.delete_chat.confirmation_message') }}"
                                class="w-full text-start">

                                <x-wirechat::dropdown-link class="text-red-500 dark:text-red-500">
                                    @lang('wirechat::chat.actions.delete_chat.label')
                                </x-wirechat::dropdown-link>

                            </button>
                           @endif

                        @endif


                        @if ($conversation->isGroup() && !$this->auth->isOwnerOf($conversation))
                            <button wire:click="exitConversation"
                                wire:confirm="{{ __('wirechat::chat.actions.exit_group.confirmation_message') }}"
                                class="w-full text-start ">

                                <x-wirechat::dropdown-link class="text-red-500 dark:text-gray-500">
                                    @lang('wirechat::chat.actions.exit_group.label')
                                </x-wirechat::dropdown-link>

                            </button>
                        @endif

                    </x-slot>
                </x-wirechat::dropdown>

            </div>
        </section>


    </div>

</header>
