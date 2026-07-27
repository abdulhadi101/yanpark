<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        return match (true) {
            $user->isAdmin() => redirect()->route('admin.dashboard'),
            $user->isCashier() => redirect()->route('cashier.counter'),
            default => redirect()->route('passenger.book'),
        };
    }
}
