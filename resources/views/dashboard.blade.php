@php
    $user = auth()->user();
    $target = match (true) {
        $user->isAdmin() => route('admin.dashboard'),
        $user->isCashier() => route('cashier.counter'),
        default => route('passenger.book'),
    };
@endphp
<script>window.location.href = '{{ $target }}';</script>
<noscript><meta http-equiv="refresh" content="0;url={{ $target }}"></noscript>
