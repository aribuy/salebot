<?php

namespace App\Repositories;

use App\Models\WebsiteFeature;
use App\Models\WebsiteFeatureLanguage;
use App\Traits\ImageTrait;

class WebsiteFeatureRepository
{
    use ImageTrait;

    public function all()
    {
        return WebsiteFeature::with('language')->where('status', '=', '1')->take(6)->latest()->get();
    }

    public function whatsapp()
    {
        return WebsiteFeature::where('type', 'whatsapp')->with('language')->where('status', '=', '1')->take(3)->latest()->get();
    }

    public function telegram()
    {
        return WebsiteFeature::where('type', 'telegram')->with('language')->where('status', '=', '1')->take(3)->latest()->get();
    }

    public function find($id)
    {
        return WebsiteFeature::find($id);
    }

    public function getByLang($id, $lang)
    {
        if (! $lang) {
            $feature = WebsiteFeatureLanguage::where('lang', 'en')->where('website_feature_id', $id)->first();
        } else { 

            $feature = WebsiteFeatureLanguage::where('lang', $lang)->where('website_feature_id', $id)->first();
            if (! $feature) {
                $feature                     = WebsiteFeatureLanguage::where('lang', 'en')->where('website_feature_id', $id)->first();
                $feature['translation_null'] = 'not-found';
            }
        }
        if(empty($feature)){
            $feature                     = WebsiteFeature::where('id', $id)->first();

        }

        // dd($feature);

        return $feature;
    }

    public function store($request)
    {
        $feature                    = new WebsiteFeature;
        if (isset($request->image)) {
            $response               = $this->saveImage($request->image);
            $images                 = $response['images'];
            $feature->image         = $images;
        }
        $feature->title             = $request->title;
        $feature->icon             = $request->icon;
        $lines                      = explode("\n",$request->description);
        $feature->description       = $lines;
        $feature->type              = $request->type;
        $feature->save();
        $this->langStore($request, $feature);

        return $feature;
    }

    public function update($request, $id)
    {
        $feature = WebsiteFeature::findOrfail($id);

        if (isset($request->image)) {
            $response               = $this->saveImage($request->image);
            $images                 = $response['images'];
            $feature->image         = $images;
        }
        $feature->title             = $request->title;
        $feature->icon              = $request->icon;
        $lines                      = explode("\n",$request->description);
        $feature->description       = $lines;
        $feature->type              = $request->type;
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
        return WebsiteFeature::destroy($id);
    }

    public function status($data)
    {
        $key         = WebsiteFeature::findOrfail($data['id']);
        $key->status = $data['status'];

        return $key->save();
    }

    public function langStore($request, $feature)
    {
        $lines                      = explode("\n",$request->description);
        return WebsiteFeatureLanguage::create([
            'website_feature_id'        => $feature->id,
            'lang'                      => isset($request->lang) ? $request->lang : 'en',
            'title'                     => $request->title,
            'description'               => $lines,
        ]);
    }

    public function langUpdate($request)
    {
        $lines                      = explode("\n",$request->description);
        return WebsiteFeatureLanguage::where('id', $request->translate_id)->update([
            'lang'           => $request->lang,
            'title'          => $request->title,
            'description'    => $lines,
        ]);
    }

}
