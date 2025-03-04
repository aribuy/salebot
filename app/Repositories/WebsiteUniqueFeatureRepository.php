<?php

namespace App\Repositories;

use App\Models\WebsiteUniqueFeature;
use App\Models\WebsiteUniqueFeatureLanguage;
use App\Traits\ImageTrait;

class WebsiteUniqueFeatureRepository
{
    use ImageTrait;

    public function all()
    {
        return WebsiteUniqueFeature::where('status', '=', '1')->with('language')->take(5)->latest()->get();
    }

    public function find($id)
    {
        return WebsiteUniqueFeature::find($id);
    }

    public function getByLang($id, $lang)
    {
        if (! $lang) {
            $feature = WebsiteUniqueFeatureLanguage::where('lang', 'en')->where('website_unique_feature_id', $id)->first();
        } else {
            $feature = WebsiteUniqueFeatureLanguage::where('lang', $lang)->where('website_unique_feature_id', $id)->first();
            if (! $feature) {
                $feature                     = WebsiteUniqueFeatureLanguage::where('lang', 'en')->where('website_unique_feature_id', $id)->first();
                $feature['translation_null'] = 'not-found';
            }
        }

        return $feature;
    }


    public function store($request)
    {
        $feature = new WebsiteUniqueFeature;

        if (isset($request->icon)) {
            $response                = $this->saveImage($request->icon);
            $images                  = $response['images'];
            $feature->icon           = $images;
        }
        $feature->title              = $request->title;
        $feature->description        = $request->description;
        $feature->save();

        $this->langStore($request, $feature);

        return $feature;
    }

    public function update($request, $id)
    {

        $feature = WebsiteUniqueFeature::findOrfail($id);

        if (isset($request->icon)) {
            $response                = $this->saveImage($request->icon);
            $images                  = $response['images'];
            $feature->icon           = $images;
        }

        $feature->title              = $request->title;
        $feature->description        = $request->description;
        $feature->save();

        if (arrayCheck('lang', $request) && $request->lang != 'en') {
            $request->name = $feature->name;
        }
        if ($request->translate_id) {
            $request->lang = $request->lang ?: 'en';
            $this->langUpdate($request);
        } else {
            $this->langStore($request, $feature);
        }
        
        return $feature;
    }

    public function destroy($id): int
    {
        return WebsiteUniqueFeature::destroy($id);
    }

    public function status($data)
    {
        $key         = WebsiteUniqueFeature::findOrfail($data['id']);
        $key->status = $data['status'];

        return $key->save();
    }

    public function langStore($request, $feature)
    {
        return WebsiteUniqueFeatureLanguage::create([
            'website_unique_feature_id' => $feature->id,
            'title'                     => $request->title,
            'lang'                      => isset($request->lang) ? $request->lang : 'en',
            'description'               => $request->description,
        ]);
    }

    public function langUpdate($request)
    {
        return WebsiteUniqueFeatureLanguage::where('id', $request->translate_id)->update([
            'lang'           => $request->lang,
            'title'          => $request->title,
            'description'    => $request->description,
        ]);
    }

}
