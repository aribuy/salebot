<?php

namespace App\Repositories;

use App\Models\WebsiteAdvantageLanguage;
use App\Models\WebsiteAdvantage;
use App\Traits\ImageTrait;

class WebsiteAdvantageRepository
{
    use ImageTrait;

    public function all()
    {
        return WebsiteAdvantage::where('status', '=', '1')->with('language')->take(4)->latest()->get();
    }

    public function find($id)
    {
        return WebsiteAdvantage::find($id);
    }

    public function getByLang($id, $lang)
    {
        if (! $lang) {
            $advantage = WebsiteAdvantageLanguage::where('lang', 'en')->where('website_advantage_id', $id)->first();
        } else {
            $advantage = WebsiteAdvantageLanguage::where('lang', $lang)->where('website_advantage_id', $id)->first();
            if (! $advantage) {
                $advantage                     = WebsiteAdvantageLanguage::where('lang', 'en')->where('website_advantage_id', $id)->first();
                $advantage['translation_null'] = 'not-found';
            }
        }

        return $advantage;
    }

    public function store($request)
    {
        $advantage                  = new WebsiteAdvantage;
        if (isset($request->image)) {
            $response               = $this->saveImage($request->image);
            $images                 = $response['images'];
            $advantage->image       = $images;
        }
        $advantage->title           = $request->title;
        $advantage->description     = $request->description;
        $advantage->save();
        $this->langStore($request, $advantage);
        return $advantage;
    }

    public function update($request, $id)
    {
        $advantage = WebsiteAdvantage::findOrfail($id);

        if (isset($request->image)) {
            $response               = $this->saveImage($request->image);
            $images                 = $response['images'];
            $advantage->image       = $images;
        }
        $advantage->title           = $request->title;
        $advantage->description     = $request->description;
        $advantage->save();

        if (arrayCheck('lang', $request) && $request->lang != 'en') {
            $request->name = $advantage->name;
        }
        if ($request->translate_id) {
            $request->lang = $request->lang ?: 'en';
            $this->langUpdate($request);
        } else {
            $this->langStore($request, $advantage);
        }
        return $advantage;
    }

    public function destroy($id): int
    {
        return WebsiteAdvantage::destroy($id);
    }

    public function status($data)
    {
        $key         = WebsiteAdvantage::findOrfail($data['id']);
        $key->status = $data['status'];

        return $key->save();
    }

    public function langStore($request, $advantage)
    {
        return WebsiteAdvantageLanguage::create([
            'website_advantage_id' => $advantage->id,
            'title'          => $request->title,
            'lang'           => isset($request->lang) ? $request->lang : 'en',
            'description'    => $request->description,
        ]);
    }

    public function langUpdate($request)
    {
        return WebsiteAdvantageLanguage::where('id', $request->translate_id)->update([
            'lang'           => $request->lang,
            'title'          => $request->title,
            'description'    => $request->description,
        ]);
    }
}
