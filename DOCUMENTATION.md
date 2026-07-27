# YMP Connect — Yankari Motor Park Management System

**Project documentation** — overview, architecture, feature status, and
known gaps. Written to accompany the report *"Design and Implementation of
a Web-Based Motor Park Management System for Yankari Motor Park, Bauchi."*

---

## 1. Overview

YMP Connect digitizes the manual, paper-based operations of Yankari Motor
Park: passengers can search routes and book/pay for seats online, cashiers
can verify payments and register walk-in passengers at the counter, and
admins manage the park's routes, vehicles, drivers, trips, bookings, and
user accounts from one dashboard — with a dedicated reports page for
daily/weekly/monthly revenue breakdowns. A public welcome page shows
available routes and fares to unregistered visitors.

**Stack:** Laravel 13 (PHP) · Livewire 4 · Blade · Tailwind CSS · SQLite (dev) / MySQL (production)

**Why this stack, versus the report's original vanilla PHP/MySQL/Bootstrap:**
Laravel gives structured routing, an ORM (Eloquent), and built-in
validation/auth, while Livewire adds interactivity (live seat counts, live
forms) without writing separate JavaScript/API endpoints — closer to a
modern SPA feel while staying 100% PHP/Blade, which keeps the codebase
approachable for a single-developer academic project.

---

## 2. Roles & Modules

The report specifies three roles. The implementation follows its module
breakdown (Chapter 4.5) directly:

| Role | Report's listed functions | Implemented as |
|---|---|---|
| **Admin** | Dashboard, Manage Users, Manage Routes, Manage Trips, Manage Buses, Manage Bookings, Generate Reports | `Admin\*Controller`s + `Livewire\Admin\Dashboard` + `Livewire\Admin\TripManager` + `Livewire\Admin\Reports` + `HomeController` |
| **Cashier** | Verify Payment, Confirm Booking, Print Ticket, View Transactions | `Livewire\Cashier\Counter` |
| **Passenger** | Registration, Login, Search Route, View Schedule, Book Seat, Receive Digital Ticket, View Booking History | `Livewire\Passenger\BookingFlow` + `Livewire\Passenger\BookingHistory` |

Auth (registration/login/logout/profile) comes from the official Laravel
Livewire starter kit, not custom-built — a deliberate choice to avoid
reinventing well-tested auth code for an academic timeline.

---

## 3. Database schema

8 tables, all under standard Laravel migrations:

```
users        — id, name, email, password, role[admin|cashier|passenger], phone
parks        — id, name, location
trip_routes  — id, park_id, origin, destination, fare
               (named trip_routes, not routes — Route is Laravel's own facade)
drivers      — id, name, phone, license_no
vehicles     — id, park_id, driver_id, plate_no, type, capacity
trips        — id, vehicle_id, trip_route_id, departure_time,
               status[loading|dispatched|completed|cancelled], seats_booked
bookings     — id, trip_id, user_id (nullable), walk_in_name, walk_in_phone,
               reference, seat_no, status[pending|paid|cancelled]
payments     — id, booking_id, amount, method[cash|mock_card], status, reference
```

`bookings.user_id` is nullable specifically so the Cashier can register a
walk-in passenger who has no online account — `walk_in_name`/`walk_in_phone`
capture their details instead. `Booking::passengerName()` /
`passengerPhone()` read from whichever source applies, so every screen that
displays a passenger works for both cases without special-casing.

---

## 4. What's done

### Admin
- Dashboard: total users, total buses, total trips, total bookings, today's
  revenue, total revenue, 7-day revenue bar chart, most popular routes
- Reports: dedicated reports page with daily/weekly/monthly toggle, period
  summary (revenue, paid bookings, trips), and per-day breakdown table
- Manage Trips: open a trip for loading, dispatch it, mark it completed,
  cancel it — with active-trip guard that prevents double-booking a vehicle
  (a vehicle with a loading/dispatched trip can't be assigned a new one)
- Manage Parks / Routes / Vehicles / Drivers: full CRUD
- Manage Users: create accounts with a role (admin/cashier/passenger),
  edit name/email/phone/role, reset password, delete (can't delete self)
- Manage Bookings: view all bookings with status filter, cancel any
  still-pending (unpaid) booking

### Cashier
- Verify Payment / Confirm Booking: list of bookings made online that are
  still unpaid; one click collects cash and marks them paid
- Walk-in booking: pick a loading trip, enter a name + phone (no account
  needed), book the seat and collect cash in one step
- Print Ticket: link straight to the printable ticket after any payment
- View Transactions: today's completed payments at the selected park

### Passenger
- Register / login (via starter kit)
- Search trips by date and route/keyword
- Book a seat (with row-level locking so two people can't claim the same
  last seat)
- Pay (mock payment step — see §6)
- View/print a digital ticket
- View booking history; cancel a still-unpaid booking themselves

### Cross-cutting
- Public welcome page (`HomeController`): shows available routes with fares,
  Register/Login buttons for guests; logged-in users skip to their dashboard
- Role-based route protection (`role:admin`, `role:cashier` middleware)
- Seat-capacity enforcement at the database/service layer, not just the UI
- Active-trip guard: a vehicle with an active (loading/dispatched) trip
  cannot be assigned another — throws `RuntimeException`, caught by
  TripManager and shown as a form validation error
- Navigation adapts to role: admin sees Dashboard/Trips/.../Reports,
  cashier sees Counter, passenger sees Book/My Bookings; guests see
  Login/Register links
- Demo seeder: 1 park, 4 routes, 3 drivers, 3 vehicles, a completed trip
  with paid bookings, a dispatched trip, a loading trip, and one pending
  online booking — so every screen has real data on first run

---

## 5. Known limitations (not bugs, scope decisions)

| Gap | Why it matters | Effort to add |
|---|---|---|
| **No real payment gateway** | Payment is a mock step (`PaymentService::pay()`); the report itself says gateway integration "may not be fully implemented," so this matches scope, but be ready to explain it if asked | Medium — swap one method for a Paystack/Flutterwave call |
| **No live seat updates across browser tabs** | If two people have the same trip open, seat counts don't refresh until reload | Medium — needs Livewire polling or broadcasting |
| **No visual seat map** | Seats are assigned sequentially by number, not picked on a diagram | Medium |
| **Tickets are print-to-PDF via browser, not a generated PDF file** | Works fine for a demo, but isn't a downloadable PDF artifact | Small — could reuse the same Dompdf approach as your SRMIS project |
| **No automated tests** | Nothing verifies booking/payment logic beyond manual review | Medium — PHPUnit/Pest feature tests for the booking flow would be the highest-value addition |
| **No SMS/email notification on booking/payment** | Not mentioned as a hard requirement in the report, but common in real systems | Medium (needs a provider) |

---

## 6. Setup

The project has been fully configured and is ready to run:

- **Database:** SQLite (configured in `.env`, no MySQL setup needed for dev)
- **Migrations:** All 9 custom migrations + default Laravel tables created
- **Seeder:** DemoSeeder populated with demo data (3 users, 1 park, 4 routes, 3 drivers, 3 vehicles, trips with bookings/payments)
- **Frontend:** `npm install` + `npm run build` completed, Tailwind/Vite assets compiled
- **Middleware:** `role` middleware alias registered in `bootstrap/app.php`

**To start the dev server:**

```bash
cd yankari-motor-park
php artisan dev
```

Visit `http://localhost:8000`, log in as any of the demo users — each
role lands on its own home (Admin → dashboard, Cashier → counter,
Passenger → book a trip).

**Demo logins (password for all: `password`):**

| Role | Email |
|---|---|
| Admin | `admin@ymp.test` |
| Cashier | `cashier@ymp.test` |
| Passenger | `passenger@ymp.test` |

---

## 7. Demo script (for the defense)

1. **Admin** — show Parks/Routes/Vehicles/Drivers/Users already set up, open
   the dashboard (totals + today's revenue + 7-day chart + popular routes),
   then Trips: dispatch the pre-seeded "loading" trip. Open Reports: toggle
   between Daily/Weekly/Monthly to show the period breakdown table.
2. **Cashier** — open the counter: confirm the pre-seeded *pending* online
   booking (collect cash → ticket generated), then record a brand-new
   walk-in booking for a passenger with no account, and check it shows up
   under today's transactions.
3. **Passenger** — visit the public welcome page (available routes with fares),
   then log in, book a seat on a different trip, pay online (mock card),
   open the printable ticket, then check "My bookings" and cancel a
   still-unpaid one to show that flow too.
4. **Back to Admin** — refresh Bookings and the dashboard to show everything
   reflected in the numbers.

---

## 8. Suggested next steps, in priority order

1. Walk through the demo script above — this is where any real bugs will surface.
2. Add a handful of feature tests around `BookingService` (seat locking,
   full-trip rejection) — cheap, and it's the part most likely to get
   probed in a defense ("what if two people book at once?").
3. Decide whether a real payment gateway matters for your grade — if not,
   leave it mocked and just be ready to explain the decision (the report
   already frames it as optional).
4. If time allows: PDF ticket generation is the closest match to the
   original report's stated scope that isn't built yet.
