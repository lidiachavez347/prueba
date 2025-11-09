<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = [];
        $bookings = Booking::all();

        foreach ($bookings as $booking) {
            $color = match ($booking->title) {
                'Test' => '#924ACE',
                'Test 1' => '#68B01A',
                default => $booking->color ?? '#000000', // Color predeterminado
            };

            $events[] = [
                'id'    => $booking->id,
                'title' => $booking->title,
                'start' => $booking->start_date,
                'end'   => $booking->end_date,
                'color' => $booking->color,
            ];
        }

        return view('profesor.calendario.index', ['events' => $events]);
    }
}
