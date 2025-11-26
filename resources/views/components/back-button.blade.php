@props(['route' => 'default', 'param1' => ''])

<a href="{{ $route == 'default' ? url()->previous() : route($route, $param1) }}">
    <x-breeze.primary-button type="button">
        Back
    </x-breeze.primary-button>
</a>
