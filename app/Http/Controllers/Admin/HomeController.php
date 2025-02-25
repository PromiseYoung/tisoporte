<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Ticket;
use Illuminate\Http\Request;
use DB;

class HomeController
{
    public function index(Request $request)
    {
        // Verificar permisos
        abort_if(Gate::denies('dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // KPIs generales
        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('ABIERTO');
        })->count();
        $closedTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('CERRADO');
        })->count();

        // Filtro de fechas
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Consultar tickets por categoría y analista
        $query = Ticket::select('category_id', 'assigned_to_user_id', DB::raw('COUNT(*) as total'))
            ->with(['category', 'assigned_to_user'])
            ->groupBy('category_id', 'assigned_to_user_id');

        // Aplicar filtro de fechas (si está presente)
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $ticketData = $query->get();

        // Formatear datos para Chart.js
        $categories = [];
        $data = [];
        $analysts = [];
        $categoriesByAnalyst = [];
        $analystsData = [];

        // Recorrer los datos de los tickets para formatearlos
        foreach ($ticketData as $ticket) {
            // Para la gráfica de pie, recolectamos categorías y sus totales
            $categories[] = $ticket->category->name;

            $data[] = $ticket->total;
            $analysts[] = $ticket->assigned_to_user->name ?? 'Sin Asignar';

            // También agrupamos por analista y categoría para la tabla
            $categoriesByAnalyst[] = [
                'analyst' => $ticket->assigned_to_user->name ?? 'Sin Asignar',
                'category' => $ticket->category->name,
                'count' => $ticket->total,
            ];
        }
        // Datos agrupados por analista y categorías para los gráficos
        $analystsData[$ticket->assigned_to_user->name ?? 'Sin Asignar'][$ticket->category->name] = $ticket->total;

        // KPI de porcentaje de tickets cerrados
        $closedPercentage = ($totalTickets > 0) ? ($closedTickets / $totalTickets) * 100 : 0;

        // Pasar los datos a la vista
        return view('home', compact(
            'totalTickets',
            'openTickets',
            'closedTickets',
            'categories',
            'categoriesByAnalyst',
            'data',
            'analysts',
            'startDate',
            'endDate',
            'closedPercentage'  // Agregar el KPI de porcentaje cerrado
        ));
    }
}
