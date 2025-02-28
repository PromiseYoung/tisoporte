<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
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

        // Obtener KPIs generales
        $kpis = $this->getKPIs();

        // Obtener datos para la gráfica de líneas
        $analystsData = $this->getLineChartData();

        // Obtener datos para la gráfica de pie y tabla
        $pieAndTableData = $this->getPieAndTableData($request);

        // KPI de porcentaje de tickets cerrados
        $closedPercentage = ($kpis['totalTickets'] > 0)
            ? ($kpis['closedTickets'] / $kpis['totalTickets']) * 100
            : 0;

        // Pasar los datos a la vista
        return view('home', [
            'totalTickets' => $kpis['totalTickets'],
            'openTickets' => $kpis['openTickets'],
            'closedTickets' => $kpis['closedTickets'],
            'closedPercentage' => $closedPercentage,
            'categories' => $pieAndTableData['categories'],
            'data' => $pieAndTableData['data'],
            'analysts' => $pieAndTableData['analysts'],
            'categoriesByAnalyst' => $pieAndTableData['categoriesByAnalyst'],
            'analystsData' => $analystsData,
            'startDate' => $pieAndTableData['startDate'],
            'endDate' => $pieAndTableData['endDate']
        ]);
    }

    /**
     * Obtener KPIs generales
     */
    private function getKPIs()
    {
        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('ABIERTO');
        })->count();
        $closedTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('CERRADO');
        })->count();

        return [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'closedTickets' => $closedTickets,
        ];
    }

    /**
     * Obtener datos para la gráfica de líneas (Soportes por Mes y Analista)
     */
    private function getLineChartData()
    {
        $admins = \App\User::whereHas('roles', function ($query) {
            $query->where('title', 'Analista TI','ADMIN');
        })->get();

        $analystsData = [];

        foreach ($admins as $analyst) {
            $dataLine = [];

            // Recorrer cada mes del año actual
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth = Carbon::now()->startOfYear()->addMonths($month - 1)->startOfMonth();
                $endOfMonth = Carbon::now()->startOfYear()->addMonths($month - 1)->endOfMonth();

                // Contar los tickets asignados al analista en ese mes
                $monthlyTickets = Ticket::where('assigned_to_user_id', $analyst->id)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count();

                $dataLine[] = $monthlyTickets;
            }

            $analystsData[] = [
                'name' => $analyst->name,
                'data' => $dataLine,
            ];
        }

        return $analystsData;
    }

    /**
     * Obtener datos para la gráfica de pie y tabla (Tickets por Categoría y Analista)
     */
    private function getPieAndTableData(Request $request)
    {
        // Filtro de fechas
        $startDate = $request->input('start_date') ?: Carbon::now()->startOfYear()->toDateString();
        $endDate = $request->input('end_date') ?: Carbon::now()->endOfYear()->toDateString();

        // Consultar tickets por categoría y analista
        $ticketData = Ticket::select('category_id', 'assigned_to_user_id', DB::raw('COUNT(*) as total'))
            ->with(['category', 'assigned_to_user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('category_id', 'assigned_to_user_id')
            ->get();

        // Formatear datos para Chart.js
        $categories = [];
        $data = [];
        $analysts = [];
        $categoriesByAnalyst = [];

        foreach ($ticketData as $ticket) {
            // Para la gráfica de pie, recolectamos categorías y sus totales
            if (!in_array($ticket->category->name, $categories)) {
                $categories[] = $ticket->category->name;
            }

            $data[] = $ticket->total;
            $analysts[] = $ticket->assigned_to_user->name ?? 'Sin Asignar';

            // Agrupamos por analista y categoría para la tabla
            $categoriesByAnalyst[] = [
                'analyst' => $ticket->assigned_to_user->name ?? 'Sin Asignar',
                'category' => $ticket->category->name,
                'count' => $ticket->total,
            ];
        }

        return [
            'categories' => $categories,
            'data' => $data,
            'analysts' => $analysts,
            'categoriesByAnalyst' => $categoriesByAnalyst,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
