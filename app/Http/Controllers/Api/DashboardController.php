<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function stats(Request $request)
    {
        $period = $request->period ?? 'month';
        
        // Calculate start date based on period
        $startDate = now();
        switch ($period) {
            case 'week':
                $startDate = $startDate->subWeek();
                break;
            case 'month':
                $startDate = $startDate->subMonth();
                break;
            case 'year':
                $startDate = $startDate->subYear();
                break;
        }
        
        // Get total patients
        $totalPatients = Patient::count();
        
        // Get appointments today
        $appointmentsToday = Appointment::whereDate('date', today())->count();
        
        // Calculate hours worked
        $hoursWorked = Appointment::where('status', 'completed')
            ->where('date', '>=', $startDate)
            ->sum(DB::raw('duration / 60'));
        
        // Get pending documents
        $pendingDocuments = Document::where('created_at', '>=', $startDate)->count();
        
        return response()->json([
            'total_patients' => $totalPatients,
            'appointments_today' => $appointmentsToday,
            'hours_worked' => round($hoursWorked, 1),
            'pending_documents' => $pendingDocuments,
        ]);
    }
    
    /**
     * Get recent appointments.
     */
    public function recentAppointments()
    {
        $appointments = Appointment::with('patient')
            ->where('date', '>=', today())
            ->orderBy('date')
            ->orderBy('time')
            ->limit(5)
            ->get();
            
        return AppointmentResource::collection($appointments);
    }
    
    /**
     * Get appointment chart data.
     */
    public function appointmentsChart(Request $request)
    {
        $period = $request->period ?? 'month';
        
        switch ($period) {
            case 'week':
                // Group by day of week
                $data = Appointment::select(
                    DB::raw('DAYNAME(date) as label'),
                    DB::raw('COUNT(*) as value')
                )
                    ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
                    ->groupBy('label')
                    ->orderBy(DB::raw('DAYOFWEEK(date)'))
                    ->get();
                break;
                
            case 'month':
                // Group by day of month
                $data = Appointment::select(
                    DB::raw('DAY(date) as label'),
                    DB::raw('COUNT(*) as value')
                )
                    ->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                break;
                
            case 'year':
                // Group by month
                $data = Appointment::select(
                    DB::raw('MONTHNAME(date) as label'),
                    DB::raw('COUNT(*) as value')
                )
                    ->whereYear('date', now()->year)
                    ->groupBy('label')
                    ->orderBy(DB::raw('MONTH(date)'))
                    ->get();
                break;
                
            default:
                $data = [];
        }
        
        return response()->json(['data' => $data]);
    }
}