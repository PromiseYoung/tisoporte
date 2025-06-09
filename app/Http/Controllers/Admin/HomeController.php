<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use DB;

class HomeController
{
    /**
     * Muestra la vista principal del Dashboard.
     */
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
        $closedPercentage = $this->calculateClosedPercentage($kpis);

        $analystsList = User::whereHas('tickets')->get();

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
            'analystsList' => $analystsList,
            'startDate' => $pieAndTableData['startDate'],
            'endDate' => $pieAndTableData['endDate']
        ]);
    }

    /**
     * Obtener KPIs generales
     */
    private function getKPIs()
    {
        return [
            'totalTickets' => Ticket::withoutGlobalScopes()->count(),
            'openTickets' => $this->getTicketsByStatus('ABIERTO'),
            'closedTickets' => $this->getTicketsByStatus('CERRADO'),
        ];
    }

    /**
     * Obtener tickets por estado
     */
    private function getTicketsByStatus($status)
    {
        return Ticket::whereRelation('status', 'name', $status)->count();
    }

    /**
     * Calcular el porcentaje de tickets cerrados
     */
    private function calculateClosedPercentage($kpis)
    {
        return $kpis['totalTickets'] > 0
            ? number_format(($kpis['closedTickets'] / $kpis['totalTickets']) * 100, 2)
            : 0;
    }
    /**
     * Obtener datos para la gráfica de líneas (Soportes por Mes y Analista)
     */
    private function getLineChartData()
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('title', ['Analista TI', 'ADMIN', 'MANTENIMIENTOS Y REPARACION']);
        })->with([
                    'tickets' => function ($query) {
                        $query->select(
                            'assigned_to_user_id',
                            DB::raw('MONTH(created_at) as month'),
                            DB::raw('COUNT(*) as count')
                        )
                            ->whereYear('created_at', Carbon::now()->year)
                            ->groupBy('assigned_to_user_id', 'month');
                    }
                ])->get();

        $analystsData = $admins->map(function ($analyst) {
            $monthlyTickets = $analyst->tickets->pluck('count', 'month')->toArray();
            return [
                'name' => $analyst->name,
                'data' => array_map(fn($m) => $monthlyTickets[$m] ?? 0, range(1, 12)),
            ];
        });

        return $analystsData->toArray();
    }
    /**
     * Obtener los tickets mensuales asignados a un analista
     */
    private function getMonthlyTicketsData($analystId)
    {
        // Optimización: Consultar todos los meses en una sola consulta
        $monthlyTickets = Ticket::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('assigned_to_user_id', $analystId)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Rellenar con ceros los meses sin datos
        return array_map(fn($m) => $monthlyTickets[$m] ?? 0, range(1, 12));
    }


    /**
     * Obtener datos para la gráfica de pie y tabla (Tickets por Categoría y Analista)
     */
    private function getPieAndTableData(Request $request)
    {
        // Filtros
        $startDate = $request->input('start_date') ?: Carbon::now()->startOfYear()->toDateString();
        $endDate = $request->input('end_date') ?: Carbon::now()->endOfYear()->toDateString();
        $analystId = $request->input('analyst_id');

        // Obtener tickets filtrados
        $ticketData = $this->getTicketsData($startDate, $endDate, $analystId);

        // Formatear datos para Chart.js y la tabla
        return $this->formatPieAndTableData($ticketData, $startDate, $endDate);
    }

    /**
     * Consultar los tickets por fecha y analista
     */
    private function getTicketsData($startDate, $endDate, $analystId)
    {
        $query = Ticket::select('category_id', 'assigned_to_user_id', DB::raw('COUNT(*) as total'))
            ->with(['category:id,name', 'assigned_to_user:id,name'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($analystId) {
            $query->where('assigned_to_user_id', $analystId);
        }

        return $query->groupBy('category_id', 'assigned_to_user_id')->get();
    }

    /**
     * Formatear datos para la gráfica de pie y la tabla
     */
    private function formatPieAndTableData($ticketData, $startDate, $endDate)
    {
        $categories = [];
        $data = [];
        $analysts = [];
        $categoriesByAnalyst = [];

        foreach ($ticketData as $ticket) {
            $categories[$ticket->category->name] = ($categories[$ticket->category->name] ?? 0) + $ticket->total;
            $data[] = $ticket->total;
            $analysts[$ticket->assigned_to_user->name ?? 'Sin Asignar'] = ($analysts[$ticket->assigned_to_user->name ?? 'Sin Asignar'] ?? 0) + $ticket->total;

            $categoriesByAnalyst[] = [
                'analyst' => $ticket->assigned_to_user->name ?? 'Sin Asignar',
                'category' => $ticket->category->name,
                'count' => $ticket->total,
            ];
        }

        return [
            'categories' => array_keys($categories),
            'data' => array_values($categories),
            'analysts' => array_keys($analysts),
            'categoriesByAnalyst' => $categoriesByAnalyst,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    /**
     * Mostrar el listado de analistas
     */
    public function showPieAndTableData(Request $request)
    {
        $analystsList = User::whereHas('tickets')->get();

        $data = $this->getPieAndTableData($request);

        return view('home', [
            'categoriesByAnalyst' => $data['categoriesByAnalyst'],
            'analystsList' => $analystsList,
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
        ]);
    }
}
