# Yankari Motor Park Management System

Laravel + Livewire implementation of the Motor Park Management System project
(Yankari Motor Park, Bauchi), built to match the actual project report:
**Admin, Cashier, and Passenger** roles, with Admin handling trip scheduling
and Cashier handling payment verification and walk-in bookings at the counter.

The report's own stack was vanilla PHP + MySQL + Bootstrap + XAMPP — this
rebuilds the same scope in Laravel 11 + Livewire 3 + Tailwind, kept
deliberately simple so it stays demoable.

## What's in this zip

Only the **app-specific** code — models, migrations, service classes,
controllers, Livewire components, Blade views, routes, and a demo seeder.
Auth (login/register/logout/profile) is **not** included here — you get that
for free from the official Livewire starter kit in step 1 below.

## Modules (matches the report's Chapter 4.5)

| Module | Functions |
|---|---|
| **Passenger** | Registration, login, search route, view schedule, book seat, receive digital ticket, view booking history (with cancel while unpaid) |
| **Admin** | Dashboard (totals + revenue), manage users, manage routes, manage trips (open/dispatch/complete/cancel), manage buses, manage bookings (with cancel), manage drivers |
| **Cashier** | Verify payment / confirm booking (for online bookings paid in cash at the counter), record a walk-in booking + cash payment, print ticket, view today's transactions |

## 1. Generate the base project

You need PHP 8.2+, Composer, MySQL (or MariaDB), and Node.js installed locally
(XAMPP or Laragon both work fine, matching your report's environment).

```bash
composer create-project laravel/laravel yankari-motor-park
cd yankari-motor-park
php artisan livewire:starter-kit
```

If your Laravel version doesn't have `livewire:starter-kit`, use instead:

```bash
composer require livewire/livewire laravel/breeze --dev
php artisan breeze:install livewire
```

Either path gives you: Livewire 3 wired up, Tailwind CSS configured with
Vite, and Login/Register/Profile pages already working.

**Also add Doctrine DBAL**, needed for one migration that alters an existing
column:

```bash
composer require doctrine/dbal --dev
```

## 2. Copy in this project's files

Extract this zip and copy its folders into your generated project, **merging**
(don't overwrite `app/Models/User.php` blindly — see note below):

```
app/Models/          → app/Models/        (merge User.php, add the rest)
app/Services/         → app/Services/
app/Http/Controllers/ → app/Http/Controllers/
app/Http/Middleware/  → app/Http/Middleware/
app/Livewire/         → app/Livewire/
resources/views/      → resources/views/   (merge, don't delete auth views)
database/migrations/  → database/migrations/
database/seeders/      → database/seeders/
routes/web.php         → merge into your routes/web.php (keep the auth routes
                          the starter kit already put there, add the block
                          from this file below them)
```

**`User.php` note:** the starter kit already generated a `User` model with
auth-related traits. Open both side by side and add: the `role`/`phone`
fields to `$fillable`, and the `isAdmin()`/`isCashier()`/`isPassenger()`/
`bookings()` methods from this project's `User.php` into yours. Don't just
overwrite the generated file.

## 3. Register the `role` middleware

Laravel 11+ registers middleware aliases in `bootstrap/app.php`. Open it and
add the alias inside `->withMiddleware(...)`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
})
```

## 4. Configure the database and migrate

Update `.env` with your MySQL credentials, then:

```bash
php artisan migrate
php artisan db:seed --class=DemoSeeder
```

This creates one park, 4 routes, 3 drivers, 3 vehicles, and a completed /
dispatched / loading trip each (plus one pending online booking, ready for
the cashier to confirm) — so the dashboard chart and every screen have data
on day one.

**Demo logins (password for all: `password`):**

| Role | Email |
|---|---|
| Admin | `admin@ymp.test` |
| Cashier | `cashier@ymp.test` |
| Passenger | `passenger@ymp.test` |

## 5. Run it

```bash
npm install && npm run build   # or: npm run dev
php artisan serve
```

Visit `http://localhost:8000`, log in as any of the demo users above — each
role lands on its own home (Admin → dashboard, Cashier → counter,
Passenger → book a trip).

## Demo script (for the defense)

1. **Admin** — show Parks/Routes/Vehicles/Drivers/Users already set up, open
   the dashboard (totals + today's revenue + 7-day chart + popular routes),
   then Trips: dispatch the pre-seeded "loading" trip.
2. **Cashier** — open the counter: confirm the pre-seeded *pending* online
   booking (collect cash → ticket generated), then record a brand-new
   walk-in booking for a passenger with no account, and check it shows up
   under today's transactions.
3. **Passenger** — book a seat on a different trip, pay online (mock card),
   open the printable ticket, then check "My bookings" and cancel a
   still-unpaid one to show that flow too.
4. **Back to Admin** — refresh Bookings and the dashboard to show everything
   reflected in the numbers.

## Notes on scope decisions

- Payment is a **mock/demo step** (`PaymentService::pay()`) — no real gateway
  is called, matching the report's own statement that "integration with
  online payment gateways may not be fully implemented but may be
  recommended for future enhancement." To go further, swap that method's
  body for a Paystack/Flutterwave API call.
- The routes table is named `trip_routes` (model `TripRoute`) instead of
  `routes`, since `Route` is already Laravel's own routing facade.
- Walk-in bookings (`BookingService::bookWalkIn()`) have no `user_id` — the
  cashier records a name/phone instead. `Booking::passengerName()` and
  `passengerPhone()` handle both cases everywhere they're displayed.
- `TripManager::dispatch_()` (the Admin trip screen) is named with a
  trailing underscore because Livewire's base `Component` class already
  defines a `dispatch()` method for firing browser events.
- Admin's booking cancellation and the passenger's own "My bookings"
  cancellation both call the same `BookingService::cancel()` — only
  `pending` (unpaid) bookings can be cancelled this way; a paid booking
  needs a manual refund/void at the park office, which is out of scope here.

## Known limitations (not bugs, scope decisions)

- **Payment is a mock/demo step** — no real gateway is called, matching the
  report's own statement that "integration with online payment gateways may
  not be fully implemented." To go further, swap `PaymentService::pay()` for
  a Paystack/Flutterwave API call.
- **No live seat updates across browser tabs** — seat counts refresh on page
  load only. Adding Livewire polling or broadcasting would fix this.
- **No visual seat map** — seats are assigned sequentially by number, not
  picked on a diagram.
- **Tickets are browser-print HTML** — works for demo purposes, but isn't a
  downloadable PDF artifact.
- **No automated tests** — PHPUnit/Pest feature tests for the booking flow
  would be the highest-value addition if time allows.
- **No separate "Generate Reports" page** — the dashboard covers today +
  7-day trend + totals, but not a dedicated filterable reports screen the
  report chapter describes.
