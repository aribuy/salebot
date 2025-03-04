<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ContactsRequest;
use App\Repositories\Client\ContactRepository;
use App\Repositories\Client\SegmentRepository;
use App\Repositories\CountryRepository;
use App\Services\NewContactsService;
use App\Services\TotalContactsService;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request; 

class OverviewController extends Controller
{
    protected $segmentsRepo;
    protected $ContactsRepo;
    protected $country;

    public function __construct(ContactRepository $ContactsRepo, SegmentRepository $segmentsRepo, CountryRepository $country)
    {
        $this->segmentsRepo = $segmentsRepo;
        $this->ContactsRepo = $ContactsRepo;
        $this->country      = $country;
    }

    public function index(Request $request)
    {
        $allContacts = $this->ContactsRepo->all()->count();
        $data        = [
            'charts'     => [
                'total_contacts' => app(TotalContactsService::class)->execute($request),
                'new_contacts'   => app(NewContactsService::class)->execute($request),
            ],
            'allContact' => $allContacts,
        ];

        return view('backend.client.whatsapp.overview.index', $data);
    }

    public function create()
    {
        try {
            $segments = $this->segmentsRepo->activeSegments();
            $data     = [
                'segments' => $segments,
            ];

            return view('backend.client.whatsapp.contact.create', $data);
        }catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function segments(): \Illuminate\Http\JsonResponse
    {
        try {
            $segments = $this->segmentsRepo->activeSegments();
            foreach ($segments as $item) {
                $options[] = [
                    'text' => $item->lang_title,
                    'id'   => $item->id,
                ];
            }

            return response()->json($options);
        }catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }
}
