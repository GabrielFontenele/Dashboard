<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use DateTime;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $daysOfWeek = [ 'Mon', 'Tue', 'Wed', 'Thu', 'Fri' ];
        $daysOfWeekTime = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday' ];
        $hoursRange = self::createIntervalOfHours(9, 18);
        $reservations = DB::table('reservations')
            ->select('reservations.*', 'users.name')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->where('start_time', '>=', new DateTime('this week monday'))
            ->where('start_time', '<=', new DateTime('this week saturday'))
            ->where('status', '<>', 'cancelled')
            ->orderBy('start_time')
            ->get()->keyBy('start_time')
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray()
        ;

        foreach ($reservations as $key => $reservation) {
            $reservations[$key]['overFlow'] = 
                self::getOverflow($reservation['start_time'], $reservation['end_time']);
        }
        
        return view('dashboard', [
            'reservations' => $reservations,
            'hoursRange' => $hoursRange,
            'daysOfWeek' => $daysOfWeek,
            'daysOfWeekTime' => $daysOfWeekTime
        ]);
    }

    // returns the amount of 30 minutes between two dates
    function getOverflow($dt1 , $dt2) {
        $cssOverflow = ["one", "one", "two", "three"];
        if (!$dt2) {
            return "one";
        }
        $diff = round(abs(strtotime($dt2) - strtotime($dt1)) / 1800);

        return $cssOverflow[$diff] ?? "one";
    }

    // returns an array of hours with 30 minute intervals between 2 hour entries
    function createIntervalOfHours($start, $end) {
        $hours = [];
        foreach (range($start, $end) as $h) {
            $hours[] = $h.":00";
            if ($h !==  $end ) $hours[] = $h.":30";
        };
    
        return $hours;
      }

}
