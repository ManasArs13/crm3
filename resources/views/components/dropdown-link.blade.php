@props(['active'])

@php
$classes = ($active ?? false) 
    ? 'block bg-indigo-400 text-white hover:text-gray-700 w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-indigo-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out' 
    : 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-indigo-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out';

@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
