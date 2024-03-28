@props(['align' => 'right', 'width' => 'default', 'contentClasses' => 'bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
    case '64':
        $width = 'w-[64rem]';
        break;
    case 'default':
        $width = 'w-[164rem]';
        break;
}
@endphp

<div class="" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed mx-auto z-50 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }} max-w-7xl"
            style="display: none;transform: translate(-50%, -50%); top:50%; left:50%; width:95%">
        <div class="rounded-md mx-auto ring-1 ring-black p-9 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>