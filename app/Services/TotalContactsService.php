<?php

namespace App\Services;

use App\Models\Contact;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalContactsService
{
    private $data = [];

    public function execute(Request $request)
    {
        $query       = Contact::select(
            DB::raw('MONTHNAME(created_at) as month_name'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as data')
        )->withPermission()
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $startPeriod = Carbon::now()->addMonths();
        $endPeriod   = Carbon::now()->subYear()->addMonths();
        $period      = CarbonPeriod::create($endPeriod, '1 month', $startPeriod);

        $dates       = [];
        foreach ($period as $date) {
            $dates[] = $date;
        }

        $i           = 0;

        foreach ($dates as $date) {
            $enrol        = $query->where('month_name', $date->format('F'))->where('year', $date->format('Y'))->first();
            $data         = $enrol ? $enrol->data : 0;
            $this->data[] = $data + $i;
            $i += $data;
        }

        return [
            'labels' => array_map(function ($date) {
                return $date->format('F');
            }, $dates),
            'data'   => $this->data,
        ];
    }
}
