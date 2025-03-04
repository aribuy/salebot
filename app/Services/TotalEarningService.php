<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalEarningService
{
    private $months = [
        'January'   => 'Jan',
        'February'  => 'Feb',
        'March'     => 'Mar',
        'April'     => 'Apr',
        'May'       => 'May',
        'June'      => 'Jun',
        'July'      => 'Jul',
        'August'    => 'Aug',
        'September' => 'Sep',
        'October'   => 'Oct',
        'November'  => 'Nov',
        'December'  => 'Dec',
    ];

    private $data   = [];

    public function execute(Request $request)
    {
        $query = Subscription::whereNotIn('status', [0, 2])->select(
            DB::raw('MONTHNAME(created_at) as month_name'),
            DB::raw('SUM(price) as total_earning')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($this->months as $full_month => $sort_month) {
            $enrol        = $query->where('month_name', $full_month)->first();
            $this->data[] = $enrol ? $enrol->total_earning : 0;
        }

        return $this->data;
    }
}
