@use("Wirechat\Wirechat\Facades\Wirechat")

<header class="sticky top-0 z-10 w-full border-b border-[#e8dfc4] bg-[#fffdf8] px-6 py-5" dusk="header">


    {{-- heading/name and Icon --}}
    <section class="flex items-center justify-between gap-4">

        @if (isset($heading))
            <div class="flex items-center gap-2 truncate  " wire:ignore>
                <h2 class="text-[1.55rem] font-extrabold text-[#04371f] dark:text-white" dusk="heading">{{ $heading }}</h2>
            </div>
        @endif



        <div class="flex items-center gap-2">

            {{-- Widget-Action:Redirect to home --}}
            @php $homeUrl = $this->panel()->getHomeUrl(); @endphp
            @if ($redirectToHomeAction && $homeUrl)
            <a id="redirect-button" href="{{ $homeUrl }}" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-[#e8dfc4] bg-white text-[#04371f] shadow-sm transition-transform hover:scale-105">
                      <x-wirechat::icon
                                :icon="$this->panel()->redirectToHomeActionIcon()"
                                 default="wirechat::icons.logout"
                                class="size-5 sm:size-6.5"
                                :icon-attributes="$this->panel()->redirectToHomeActionIconAttributes()" 
                            />
            </a>
            @endif

            {{-- Panel-action:Create Chat Action--}}
            @if ($createChatAction)
            <x-wirechat::actions.new-chat widget="{{$this->isWidget()}}" panel="{{$this->panel}}" >
                <button id="open-new-chat-modal-button" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-[#e8dfc4] bg-white text-[#04371f] shadow-sm transition-transform hover:scale-105 focus:outline-hidden">
                         <x-wirechat::icon
                                :icon="$this->panel()->createChatActionIcon()"
                                default="wirechat::icons.messages-plus"
                                class="size-6"
                                :icon-attributes="$this->panel()->createChatActionIconAttributes()"
                            />
                    </button>
            </x-wirechat::actions.new-chat>
            @endif


        </div>



    </section>

    {{-- Search input --}}
    @if ($chatsSearch)
        <section class="mt-4">
            <div class="grid grid-cols-[auto_minmax(0,1fr)] items-center gap-3 rounded-[22px] border border-[#e8dfc4] bg-white px-4 py-3.5 shadow-sm transition-all focus-within:ring-1 focus-within:ring-[#c69b3d]">

                <label for="chats-search-field">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5 w-5 h-5 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </label>

                <input id="chats-search-field" name="chats_search" maxlength="100" type="search" wire:model.live.debounce='search'
                    placeholder="{{ __('wirechat::chats.inputs.search.placeholder')  }}" autocomplete="off"
                    class="wc-input w-full border-0 bg-transparent py-0 text-[15px] text-[#3b362a] outline-hidden focus:outline-hidden focus:ring-0 hover:ring-0">
            </div>

        </section>
    @endif

</header>
