<?php

namespace App\Repositories;

use App\Models\WebsiteStory;
use App\Models\WebsiteStoryLanguage;
use App\Traits\ImageTrait;

class WebsiteStoryRepository
{
    use ImageTrait;

    public function all()
    {
        return WebsiteStory::where('status', '=', '1')->with('language')->take(7)->latest()->get();
    }

    public function find($id)
    {
        return WebsiteStory::find($id);
    }

    public function getByLang($id, $lang)
    {
        if (! $lang) {
            $story = WebsiteStoryLanguage::where('lang', 'en')->where('website_story_id', $id)->first();
        } else {
            $story = WebsiteStoryLanguage::where('lang', $lang)->where('website_story_id', $id)->first();
            if (! $story) {
                $story                     = WebsiteStoryLanguage::where('lang', 'en')->where('website_story_id', $id)->first();
                $story['translation_null'] = 'not-found';
            }
        }

        return $story;
    }

    public function store($request)
    {
        $story                      = new WebsiteStory;
        if (isset($request->image)) {
            $response               = $this->saveImage($request->image);
            $images                 = $response['images'];
            $story->image           = $images;
        }
        $story->description         = $request->description;
        $story->save();

        $this->langStore($request, $story);

        return $story;
    }

    public function update($request, $id)
    {
        $story = WebsiteStory::findOrfail($id);

        if (isset($request->image)) {
            $response               = $this->saveImage($request->image);
            $images                 = $response['images'];
            $story->image           = $images;
        }

        $story->description         = $request->description;
        $story->save();

        if (arrayCheck('lang', $request) && $request->lang != 'en') {
            $request->name = $story->name;
        }
        if ($request->translate_id) {
            $request->lang = $request->lang ?: 'en';
            $this->langUpdate($request);
        } else {
            $this->langStore($request, $story);
        }

        return $story;
    }

    public function destroy($id): int
    {
        return WebsiteStory::destroy($id);
    }

    public function status($data)
    {
        $key         = WebsiteStory::findOrfail($data['id']);
        $key->status = $data['status'];

        return $key->save();
    }

    public function langStore($request, $story)
    {
        return WebsiteStoryLanguage::create([
            'website_story_id' => $story->id,
            'lang'                      => isset($request->lang) ? $request->lang : 'en',
            'description'               => $request->description,
        ]);
    }

    public function langUpdate($request)
    {
        return WebsiteStoryLanguage::where('id', $request->translate_id)->update([
            'lang'           => $request->lang,
            'description'    => $request->description,
        ]);
    }

}
