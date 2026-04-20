@extends(\Wirechat\Wirechat\Facades\Wirechat::currentPanel()->getLayout())

@section('content')

    <div class="flex h-full min-h-0 w-full overflow-hidden bg-[#f7f3e8]">
        <aside class="hidden h-full shrink-0 md:flex md:w-[360px] lg:w-[390px] xl:w-[420px]">
            <div class="relative h-full w-full overflow-hidden border-r border-[#e8dfc4] bg-[#fffdf8]">
                <livewire:wirechat.chats :panel="$panel" />
            </div>
        </aside>


        <main class="relative flex min-h-0 w-full flex-1 overflow-hidden bg-white" style="contain:content">
            <livewire:wirechat.chat :panel="$panel" conversation="{{request()->conversation}}"/>
        </main>

    </div>
@endsection
