<?php

namespace App\Http\Controllers\Client;

use App\DataTables\Client\ContactsListDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ContactListRequest;
use App\Http\Requests\Client\ContactListUpdateRequest;
use App\Imports\ContactImport;
use App\Repositories\Client\ContactListRepository;
use App\Repositories\Client\ContactRepository;
use App\Repositories\Client\SegmentRepository;
use App\Repositories\CountryRepository;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ContactsListController extends Controller
{
    protected $contactsListRepo;

    protected $segmentsRepo;

    protected $ContactsRepo;

    protected $country;

    public function __construct(ContactListRepository $contactsListRepo, ContactRepository $ContactsRepo, SegmentRepository $segmentsRepo, CountryRepository $country)
    {
        $this->contactsListRepo = $contactsListRepo;

        $this->segmentsRepo     = $segmentsRepo;

        $this->ContactsRepo     = $ContactsRepo;

        $this->country          = $country;
    }

    public function index(ContactsListDataTable $contactsListDataTable)
    {

        $segments = $this->segmentsRepo->activeSegments();
        $list     = $this->contactsListRepo->activeList();
        $data     = [
            'segments'  => $segments,
            'lists'     => $list,
            'countries' => $this->country->combo(),
        ];

        return $contactsListDataTable->render('backend.client.whatsapp.contacts_list.index', $data);
    }

    public function store(ContactListRequest $request): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        DB::beginTransaction();
        try {
            $this->contactsListRepo->store($request->all());
            DB::commit();

            Toastr::success(__('create_successful'));

            return response()->json(['success' => __('create_successful')]);
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function edit($id)
    {
        try {
            $list = $this->contactsListRepo->find($id);
            $data = [
                'contact_list' => $list,
            ];

            return view('backend.client.whatsapp.contacts_list.edit', $data);
        } catch (Exception $e) {
            Toastr::error('something_went_wrong_please_try_again');

            return back();
        }
    }

    public function update(ContactListUpdateRequest $request, $id)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $this->contactsListRepo->update($request, $id);
            Toastr::success(__('update_successful'));

            return redirect()->route('client.contacts_list.index');
        } catch (Exception $e) {
            Toastr::error($e->getMessage());
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            return back()->withInput();
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
        } catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function importStore(Request $request)
    {
        try {
            if (isDemoMode()) {
                throw new \Exception(__('this_function_is_disabled_in_demo_server'));
            }

            if (!$request->hasFile('file')) {
                throw new \Exception(__('file_not_uploaded'));
            }

            $extension = $request->file('file')->getClientOriginalExtension();

            if ($extension != 'xlsx' && $extension != 'csv') {
                throw new \Exception(__('file_type_not_supported'));
            }

            $filePath  = $request->file('file')->store('Imports');

            $import    = new ContactImport($request);
            $import->import($filePath);
            Storage::delete($filePath);

            return redirect()->route('client.contacts.index')->with('success', __('successfully_imported'));
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            if (config('app.debug')) {
                dd($e->getMessage());
            }

            return back();
        }
    }

    public function downloadSample()
    {
        $file = public_path('client/excel/contacts-sample.xlsx');

        return Response::download($file);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->contactsListRepo->destroy($id);
            Toastr::success(__('delete_successful'));
            $data = [
                'status'    => 'success',
                'message'   => __('delete_successful'),
                'title'     => __('success'),
                'is_reload' => true,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'status'  => 'danger',
                'message' => __('something_went_wrong_please_try_again'),
                'title'   => __('error'),
            ];

            return response()->json($data);
        }
    }

    public function contactList()
    {
        try {
            $options = [];
            $contact_lists = $this->contactsListRepo->combo();
            foreach ($contact_lists as $key => $item) {
                $options[] = [
                    'text' => $item,
                    'id'   => $key,
                ];
            }
            return response()->json($options);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }
}
