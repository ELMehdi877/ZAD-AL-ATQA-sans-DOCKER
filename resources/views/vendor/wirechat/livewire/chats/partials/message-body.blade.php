<div class="grid min-w-0 grid-cols-[minmax(0,1fr)_auto] items-center gap-3 overflow-hidden">

    {{-- Only show if AUTH is onwer of message --}}
    <div class="flex min-w-0 items-center gap-2 overflow-hidden">
        @if ($belongsToAuth)
            <span class="shrink-0 text-[12px] font-bold text-[#17212b] dark:text-white/90 dark:font-normal">
                @lang('wirechat::chats.labels.you'):
            </span>
        @elseif(!$belongsToAuth && $group !== null)
            <span class="shrink-0 text-[12px] font-bold text-[#17212b] dark:text-white/80 dark:font-normal">
                {{ $lastMessage->sendable?->wirechat_name }}:
            </span>
        @endif

        <p
            dusk="messagePreviewBody"
            class="min-w-0 flex-1 truncate text-[12px] dark:text-white"
            @class([
                'font-semibold text-[#17212b]' => $showUnreadStatus && ! $belongsToAuth,
                'font-normal text-[#7e7662]' => ! ($showUnreadStatus && ! $belongsToAuth),
            ])
        >
            {{ $lastMessage->body != '' ? $lastMessage->body : ($lastMessage->isAttachment() ? 'Attachment' : '') }}
        </p>
    </div>

    <span
        dusk="messagePreviewTime"
        class="shrink-0 text-[12px] text-right dark:text-gray-50"
        @class([
            'font-medium text-[#4f493c]' => $showUnreadStatus && ! $belongsToAuth,
            'font-normal text-[#9b927d]' => ! ($showUnreadStatus && ! $belongsToAuth),
        ])
    >
        @if ($lastMessage->created_at->diffInMinutes(now()) < 1)
          @lang('wirechat::chats.labels.now')
        @else
            {{ $lastMessage->created_at->shortAbsoluteDiffForHumans() }}
        @endif
    </span>


</div>
