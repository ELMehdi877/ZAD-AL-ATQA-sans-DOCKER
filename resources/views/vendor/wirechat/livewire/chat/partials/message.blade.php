@use('Wirechat\Wirechat\Facades\Wirechat')


@php

   $isSameAsNext = ($message?->sendable_id === $nextMessage?->sendable_id) && ($message?->sendable_type === $nextMessage?->sendable_type);
   $isSameAsPrevious = ($message?->sendable_id === $previousMessage?->sendable_id) && ($message?->sendable_type === $previousMessage?->sendable_type);
   $canParseMessageUrls = $this->panel()->canParseMessageUrls();
   $body = (string) ($message?->body ?? '');
   $segments = ($canParseMessageUrls && Wirechat::containsLink($body))
        ? Wirechat::linkifyMessage($body)
        : [[
            'text' => $body,
            'href' => null,
            'is_link' => false,
        ]];
   $messageTextClasses = 'whitespace-pre-wrap break-all text-[10px] leading-[1.55] lg:text-[12px]';
   $receiver = $belongsToAuth ? $conversation->getReceiver() : null;
   $isRead = $belongsToAuth && $receiver && $conversation->participant($receiver)?->conversation_read_at >= $message->created_at;
@endphp

<div
@class([
    'relative flex min-w-[140px] max-w-fit flex-col px-3.5 py-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.08)]',
    'self-end rounded-[15px] rounded-tr-[1px] bg-[#175c37] text-white' => $belongsToAuth,
    'self-start rounded-[15px] rounded-bl-[1px] border-[1px] border-[#A8A8A8] bg-[#FFFFFF] text-[#253E7E]' => !$belongsToAuth,
])
>
@if (!$isSameAsNext)
    <span
        aria-hidden="true"
        @class([
            'absolute bottom-0 h-3.5 w-3.5',
            'right-[-5px] bg-[#175c37] [clip-path:polygon(0_0,100%_100%,0_100%)]' => $belongsToAuth,
            'left-[-5px] bg-[#f4ecd8] [clip-path:polygon(100%_0,100%_100%,0_100%)]' => !$belongsToAuth,
        ])></span>
@endif

@if (!$belongsToAuth && $isGroup && !$isSameAsPrevious)
<div
    @class([
        'shrink-0 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#1f8f4d]',
    ])>
    {{ $message?->sendable?->wirechat_name }}
</div>
@endif

<pre
    dusk="message-text"
    class="{{ $messageTextClasses }} {{ $belongsToAuth ? 'text-white' : 'text-gray-700' }}"
    dir="auto"
    style="font-family: inherit;">@foreach ($segments as $segment)@if ($segment['is_link'])<a
                dusk="message-link"
                target="_blank"
                rel="noopener noreferrer"
                class="break-all underline"
                href="{{ $segment['href'] }}">{{ $segment['text'] }}</a>@else{{ $segment['text'] }}@endif@endforeach</pre>

{{-- Display the created time --}}
<div class="mt-0.5 flex items-center justify-end gap-0.5">
    <span
        @class([
            'text-[11px] leading-none',
            'text-[#786f5d] opacity-70' => !$belongsToAuth,
            'text-[#9fd4ff]' => $belongsToAuth && $isRead,
            'text-white/70' => $belongsToAuth && !$isRead,
        ])>
        {{ $message?->created_at?->format('H:i') }}
    </span>
    @if($belongsToAuth)
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" @class(['bi bi-check2-all', 'text-blue-500' => $isRead, 'text-white/50' => !$isRead]) viewBox="0 0 16 16">
            <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0"/>
            <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708"/>
        </svg>
    @endif
</div>

</div>
