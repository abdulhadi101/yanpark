@props([
    'name',
    'solid' => false,
])

@if(view()->exists("icons.{$name}"))
    @include("icons.{$name}", ['solid' => $solid])
@endif
