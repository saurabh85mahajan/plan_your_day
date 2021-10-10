@props(['label', 'column'])

<div {{$attributes->merge(['class' => 'flex items-center']) }}>
    <button wire:click="sortBy('{{$column}}')">{{$label}}</button>
    {{$slot}}
</div>