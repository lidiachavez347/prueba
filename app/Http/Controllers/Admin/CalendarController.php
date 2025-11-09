<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
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

        return view('admin.calendario.index', ['events' => $events]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'color' => 'nullable|string',
        ]);
    
        $booking = Booking::create([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'color' => $validated['color'] ?? '#000000', // Color por defecto
        ]);
    
        // Devolver respuesta JSON con los datos del evento
        return response()->json([
            'id' => $booking->id,
            'title' => $booking->title,
            'start' => $booking->start_date,
            'end' => $booking->end_date,
            'color' => $booking->color,
        ]);
    }
    


    public function update(Request $request, $id)
    {
        $evento = Booking::find($id);
    
        if (!$evento) {
            return response()->json(['error' => 'Evento no encontrado.'], 404);
        }
    
        $evento->start_date = $request->start_date;
        $evento->end_date = $request->end_date;
        $evento->color = $request->color;
    
        if ($evento->save()) {
            return response()->json(['success' => 'Evento actualizado correctamente.']);
        } else {
            return response()->json(['error' => 'Error al actualizar el evento.'], 500);
        }
    }
    

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (! $booking) {
            return response()->json(['error' => 'No se encontró el evento.'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Evento eliminado correctamente.', 'id' => $id]);
    }
}
