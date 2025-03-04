<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LanguageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Repositories\LanguageRepository;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Masterminds\HTML5\Parser\Scanner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Artisan;

class LanguageController extends Controller
{
    private $translation;

    protected $repo;

    public function __construct(LanguageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(LanguageDataTable $dataTable)
    {
        Gate::authorize('languages.index');
        try {
            $data = [
                'flags' => $this->repo->flags(),
            ];

            return $dataTable->render('backend.admin.language.all-language', $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        Gate::authorize('languages.create');
        try {
            $flags = $this->repo->flags();

            return view('backend.admin.language.add-language', compact('flags'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(LanguageRequest $request): JsonResponse
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
            $this->repo->store($request->all());

            cache()->forget('languages');
            DB::commit();

            return response()->json(['success' => __('create_successful')]);
        } catch (Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit($id): JsonResponse
    {
        Gate::authorize('languages.edit');
        try {
            $language = $this->repo->get($id);

            $data     = [
                'id'             => $language->id,
                'name'           => $language->name,
                'locale'         => $language->locale,
                'flag'           => $language->flag,
                'is_default'     => $language->is_default,
                'text_direction' => $language->text_direction == 'rtl',
                'status'         => (bool) $language->status,
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function update(LanguageRequest $request, $id): JsonResponse|RedirectResponse
    {

        Gate::authorize('languages.update');
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
            $this->repo->update($request->all(), $id);
            cache()->forget('languages');
            DB::commit();

            return response()->json(['success' => __('update_successful')]);

        } catch (Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id): JsonResponse
    {
        Gate::authorize('languages.destroy');
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
        try {
            $this->repo->destroy($id);
            cache()->forget('languages');

            $data = [
                'status'  => 'success',
                'message' => __('delete_successful'),
                'title'   => __('success'),
            ];

            return response()->json($data);
        } catch (Exception $e) {
            dd($e->getMessage());
            $data = [
                'status'  => 400,
                'message' => $e->getMessage(),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function statusChange(Request $request): JsonResponse
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
            $this->repo->statusChange($request->all());
            cache()->forget('languages');

            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 400,
                'message' => $e->getMessage(),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function directionChange(Request $request): JsonResponse
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
            $this->repo->directionChange($request->all());
            $data = [
                'status'  => 200,
                'message' => __('update_successful'),
                'title'   => 'success',
            ];

            return response()->json($data);
        } catch (Exception $e) {
            $data = [
                'status'  => 400,
                'message' => $e->getMessage(),
                'title'   => 'error',
            ];

            return response()->json($data);
        }
    }

    public function translationPage(Request $request): View|Factory|RedirectResponse|Application
    {
        try {
            $language     = $this->repo->get($request->lang);
            $file         = base_path('lang/'.$language->locale.'.json');
            if (! file_exists($file)) {
                $en_file = base_path('lang/en.json');
                copy($en_file, $file);
            }
            $translations = json_decode(file_get_contents($file), true);

            if ($request->q) {
                $translations = array_filter($translations, function ($key) use ($request) {
                    return str_contains($key, $request->q);
                }, ARRAY_FILTER_USE_KEY);
            }

            $data         = [
                'language'     => $language,
                'languages'    => $this->repo->activeLanguage(),
                'translations' => $translations,
                'search_query' => $request->q,
            ];


            return view('backend.admin.language.translation', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return back();
        }
    }

    public function updateTranslations(Request $request, $language): JsonResponse
    {
        try {
            // Decode request data
            $translations = json_decode($request->translations, true);
            $keys = array_column(json_decode(trim($request->keys), true), 'value');
            $values = array_column($translations, 'value');
            $translation_keys = array_combine($keys, $values);
                // Load existing translations
            $language = $this->repo->get($language);
            $path = base_path('lang/'.$language->locale.'.json');
            $old_values = json_decode(file_get_contents($path), true);
                // Merge new translations with existing ones
            foreach ($translation_keys as $key => $value) {
                $old_values[$key] = $value; // Overwrite or add new key-value pair
            }
            // Write updated translations back to JSON file
            file_put_contents($path, json_encode($old_values, JSON_PRETTY_PRINT));
            Toastr::success(__('update_successful'));
            return response()->json([
                'success' => __('update_successful'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }
    


    // public function updateTranslations(Request $request, $language): JsonResponse
    // {
    //     try {
    //         $translations     = json_decode($request->translations, true);
    //         $keys             = array_column(json_decode(trim($request->keys), true), 'value');
    //         $values           = array_column($translations, 'value');
    //         $translation_keys = array_combine($keys, $values);
    //         $language         = $this->repo->get($language);
    //         $path             = base_path('lang/'.$language->locale.'.json');
    //         $old_values       = json_decode(file_get_contents($path), true);
    //         //Okay untill
    //         $translation_keys = array_merge($old_values, $translation_keys);
    //         dd($translation_keys);
    //         file_put_contents($path, json_encode($translation_keys, JSON_PRETTY_PRINT));
    //         Toastr::success(__('update_successful'));
    //         return response()->json([
    //             'success' => __('update_successful'),
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function removeLanguageKey(Request $request, $id)
    {
        $this->validate($request, [
            'key' => 'required',
            // 'value' => 'required'
        ]);
        return $this->repo->removeLanguageKey($request,$id);
    }

    public function storeLanguageKeyword(Request $request, $id)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);
        return $this->repo->storeLanguageKeyword($request,$id);
    }

    public function keywordSearchAndReplace(Request $request)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);
        return $this->repo->keywordSearchAndReplace($request);
    }

    public function scanAndStore($id)
    {
        $result = $this->repo->scanAndStore($id);
        return redirect()->route('language.translations.page',['lang'=>$id])->with($result->redirect_class, $result->message);
        
    }

    public function findMissingKeys($id)
    {
        $result = $this->repo->findMissingKeys($id);
        return redirect()->route('language.translations.page',['lang'=>$id])->with($result->redirect_class, $result->message);
        
    }



}
