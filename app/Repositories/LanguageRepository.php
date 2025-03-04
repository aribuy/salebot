<?php

namespace App\Repositories;

use App\Models\FlagIcon;
use App\Models\Language;
use App\Traits\ImageTrait;
use App\Traits\RepoResponse;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class LanguageRepository
{
    use ImageTrait,RepoResponse,SendNotification;

    public function all()
    {
        return Language::orderByDesc('id')->paginate(setting('paginate'));
    }

    public function get($id)
    {
        return Language::findOrfail($id);
    }

    public function activeLanguage()
    {
        return Language::where('status', 1)->get();
    }

    public function store($request)
    {
        $language  = Language::create($request);
        $base_path = base_path("lang/$language->locale.json");
        if (! File::exists($base_path)) {
            $translation_keys = file_get_contents(base_path('lang/en.json'));
            file_put_contents(base_path("lang/$language->locale.json"), $translation_keys);
        }

        return $language;
    }

    public function update($request, $id)
    {
        if (! arrayCheck('text_direction', $request)) {
            $request['text_direction'] = 'ltr';
        }
        if (! arrayCheck('status', $request)) {
            $request['status'] = 0;
        }
        $language          = $this->get($id);
        $request['locale'] = $language->locale;
        $language->update($request);

        return $language;
    }

    public function destroy($id): int
    {
        $language  = $this->get($id);

        $json_file = base_path("lang/$language->locale.json");

        if (File::exists($json_file)) {
            unlink($json_file);
        }

        return $language->delete($id);
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return Language::find($id)->update($request);
    }

    public function directionChange($request)
    {
        $id = $request['id'];
        if ($request['status'] == 1) {
            $request['text_direction'] = 'rtl';
        } elseif ($request['status'] == 0) {
            $request['text_direction'] = 'ltr';
        }

        return Language::find($id)->update([
            'text_direction' => $request['text_direction'],
        ]);
    }

    public function flags(): Collection
    {
        return FlagIcon::all();
    }

    public function generateTranslationFolders($locale): bool
    {
        $path            = base_path('lang/'.$locale);
        $translationPath = base_path('lang/vendor/translation/'.$locale);
        $json_file       = 'lang/'.$locale.'.json';

        //make file if not exist
        if (! File::isDirectory($path)) {

            File::makeDirectory($path, 0777, true, true);
            File::copyDirectory(base_path('lang/en'), $path);
        }
        //make file if not exist
        if (! File::isDirectory($translationPath)) {

            File::makeDirectory($translationPath, 0777, true, true);
            File::copyDirectory(base_path('lang/vendor/translation/en'), $translationPath);
        }

        // Write json
        if (! File::exists($json_file)) {
            $newJsonString = file_get_contents(base_path('lang/en.json'));
            file_put_contents(base_path($json_file), $newJsonString);
        }

        return true;
    }

    public function removeLanguageKey($request, $id)
    {
        try {
            $key = $request->key;
            $language = Language::find($id);
            $data = file_get_contents(base_path('lang/') . $language->locale . '.json');
            $json_arr = json_decode($data, true);
            unset($json_arr[$key]);
            file_put_contents(base_path('lang/' . $language->locale . '.json'), json_encode($json_arr));
            return $this->formatResponse(true, __('language_key_has_been_deleted_successfully'), 'client.templates.index', []);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            \Log::info('Delete Language', [$e->getMessage()]);
            return $this->formatResponse(false, $e->getMessage(), 'client.templates.index', []);
        }
    }

    public function storeLanguageKeyword($request, $id)
    {
        try {
            $language = Language::find($id);
            $localePath = base_path('lang/') . $language->locale . '.json';
            $currentTranslations = file_get_contents($localePath);

            $newKey = trim($request->key);
            $newValue = trim($request->value);

            $translationsArray = json_decode($currentTranslations, true);

            if (array_key_exists($newKey, $translationsArray)) {
                return $this->formatResponse(false, __('key_already_exist'), 'client.templates.index', []);
            } else {
                $newTranslation = [$newKey => $newValue];
                $updatedTranslations = array_merge($translationsArray, $newTranslation);

                file_put_contents($localePath, json_encode($updatedTranslations, JSON_PRETTY_PRINT));

                return $this->formatResponse(true, __('language_key_has_been_added_successfully'), 'client.templates.index', []);
            }
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage());
            }
            \Log::info('Delete Language', [$e->getMessage()]);
            return $this->formatResponse(false, $e->getMessage(), 'client.templates.index', []);
        }
    }

    public function keywordSearchAndReplace($request)
    {
        try {
            $language = Language::findOrFail($request->id);
            $key = trim($request->key);
            $reqValue = $request->value;
            $data = file_get_contents(base_path('lang/') . $language->locale . '.json');
            $json_arr = json_decode($data, true);
            if (array_key_exists($key, $json_arr)) {
                $json_arr[$key] = $reqValue;
            } else {
                return $this->formatResponse(false, __('your_search_keyword_not_found'), 'client.templates.index', []);
            }
            file_put_contents(base_path('lang/') . $language->locale . '.json', json_encode($json_arr));
            return $this->formatResponse(true, __('language_value_has_been_replaced_successfully'), 'client.templates.index', []);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                dd($e->getMessage()); 
            }
            \Log::info('lang Search Replace', [$e->getMessage()]);
            return $this->formatResponse(false, $e->getMessage(), 'client.templates.index', []);
        }
    }

    public function scanAndStore($id)
    {
        try {
            $language = Language::findOrFail($id);
            // $exitCode = Artisan::call('langscanner');
            // $exitCode = Artisan::call('langscanner', ['languages' => 'en']);
            $exitCode = Artisan::call('langscanner', ['language' => $language->locale]);
            // Check if the command ran successfully
            if ($exitCode !== 0) {
                throw new \Exception('Langscanner command failed');
            }
            Artisan::call('all:clear');
            return $this->formatResponse(true, __('scanning_and_storing_completed_successfully'), '', []);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->formatResponse(false, $e->getMessage(), 'language.translations.page', []);
        }
    }

    public function findMissingKeys($id)
    {
        try {
             // Find the language by ID
             $language = Language::findOrFail($id);
             // Load and parse JSON files
             $enJsonFile = File::get(base_path('lang/en.json'));
             $bnJsonFilePath = base_path('lang/') . $language->locale . '.json';
              // Decode JSON files into associative arrays
             $enTranslations = json_decode($enJsonFile, true);
             $bnTranslations = File::exists($bnJsonFilePath) ? json_decode(File::get($bnJsonFilePath), true) : [];
              // Get missing keys from en.json to bn.json
             $missingKeys = array_diff_key($enTranslations, $bnTranslations);
              // Merge missing keys into bnTranslations
             foreach ($missingKeys as $key => $value) {
                 // Check if the value contains Unicode escape sequences
                 if (preg_match('/\\\\u[0-9A-Fa-f]{4}/', $value)) {
                     // Convert Unicode escape sequences to actual Unicode characters
                     $value = json_decode('"'.$value.'"');
                 }
                 $bnTranslations[$key] = $value;
             }
              // Save updated bn.json file
             File::put($bnJsonFilePath, json_encode($bnTranslations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
              // Clear all cached data
             Artisan::call('cache:clear');
             Artisan::call('config:clear');
             Artisan::call('view:clear');
            return $this->formatResponse(true, __('scanning_and_storing_completed_successfully'), '', []);
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Handle exceptions
            return $this->formatResponse(false, $e->getMessage(), '', []);

        }
    }

}
