@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        {{ $slot }}
    </main>
@endsection
