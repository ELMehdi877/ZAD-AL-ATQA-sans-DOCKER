@extends(\Wirechat\Wirechat\Facades\Wirechat::currentPanel()->getLayout())

@section('content')
    <div class="flex h-full min-h-0 w-full overflow-hidden bg-[#f7f3e8]">
        <div class="relative h-full w-full shrink-0 overflow-hidden border-r border-[#e8dfc4] bg-[#fffdf8] md:w-[360px] lg:w-[390px] xl:w-[420px]">
            <livewire:wirechat.chats :panel="$panel" />
        </div>


        <main class="relative hidden min-h-0 w-full flex-1 overflow-hidden bg-white md:grid" style="contain:content">
            <div class="m-auto flex max-w-md flex-col items-center gap-3 px-6 text-center text-[#6b644f]">
                <div class="rounded-full border border-[#e8dfc4] bg-[#f7f0df] px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-[#7a705c]">
                    Chat
                </div>
                <h4 class="text-lg font-semibold text-[#17212b]">@lang('wirechat::pages.chat.messages.welcome')</h4>
                <p class="text-sm leading-6">Choisissez une conversation dans la liste de gauche pour voir les messages.</p>
            </div>
        </main>
    </div>

@endsection
