<table {{$attributes->merge(['class' => 'w-full whitespace-no-wrap mt-4 shadow-2xl',  'wire:loading.class.delay' => "opacity-50"]) }}>
    <thead>
        <tr class="text-left font-bold bg-blue-400">
            {{$header}}
        </tr>
    </thead>
    <tbody class="divide-y divide-blue-400">
        {{$slot}}
    </tbody>
</table>