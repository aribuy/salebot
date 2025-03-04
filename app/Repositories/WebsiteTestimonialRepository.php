<?php

namespace App\Repositories;

use App\Models\WebsiteTestimonial;
use App\Models\TestimonialLanguage;
use App\Traits\ImageTrait;

class WebsiteTestimonialRepository
{
    use ImageTrait;

    public function all()
    {
        return WebsiteTestimonial::where('status', '=', '1')->with('language')->take(8)->latest()->get();
    }

    public function activeTestimonials($data = [])
    {
        return WebsiteTestimonial::where('status', 1)->when(arrayCheck('q', $data), function ($query) use ($data) {
            $query->where('name', 'like', '%'.$data['q'].'%')->orWhereHas('languages', function ($query) use ($data) {
                $query->where('name', 'like', '%'.$data['q'].'%');
            });
        })->latest()->get();
    }

    public function getByLang($id, $lang)
    {
        if (! $lang) {
            $testimonial = TestimonialLanguage::where('lang', 'en')->where('testimonial_id', $id)->first();
        } else {
            $testimonial = TestimonialLanguage::where('lang', $lang)->where('testimonial_id', $id)->first();
            if (! $testimonial) {
                $testimonial                     = TestimonialLanguage::where('lang', 'en')->where('testimonial_id', $id)->first();
                $testimonial['translation_null'] = 'not-found';
            }
        }
        return $testimonial;
    }
    public function find($id)
    {
        return WebsiteTestimonial::find($id);
    }
    public function store($request)
    {
        $testimonial                  = new WebsiteTestimonial;
        if (isset($request->image)) {
            $response                 = $this->saveImage($request->image);
            $images                   = $response['images'];
            $testimonial->image       = $images;
        }
        $testimonial->name            = $request->name;
        $testimonial->description     = $request->description;
        $testimonial->rating          = $request->rating;
        $testimonial->designation     = $request->designation;
        $testimonial->title           = $request->title;
        $testimonial->save();
        $this->langStore($request, $testimonial);
        return $testimonial;
    }

    public function update($request, $id)
    {
        $testimonial = WebsiteTestimonial::findOrfail($id);
        if (isset($request->image)) {
            $response                 = $this->saveImage($request->image);
            $images                   = $response['images'];
            $testimonial->image       = $images;
        }
        $testimonial->name            = $request->name;
        $testimonial->description     = $request->description;
        $testimonial->rating          = $request->rating;
        $testimonial->designation     = $request->designation;
        $testimonial->title           = $request->title;
        $testimonial->save();
        if (arrayCheck('lang', $request) && $request->lang != 'en') {
            $request->name = $testimonial->name;
        }if (arrayCheck('lang', $request) && $request->lang != 'en') {
            $request->name = $testimonial->name;
        }
        if ($request->translate_id) {
            $request->lang = $request->lang ? : 'en';
            $this->langUpdate($request);
        } else {
            $this->langStore($request, $testimonial);
        }


        return $testimonial;
    }

    public function destroy($id): int
    {
        return WebsiteTestimonial::destroy($id);
    }

    public function status($data)
    {
        $key         = WebsiteTestimonial::findOrfail($data['id']);
        $key->status = $data['status'];

        return $key->save();
    }

    public function langStore($request, $testimonial)
    {

        return TestimonialLanguage::create([
            'testimonial_id' => $testimonial->id,
            'title'          => $request->title,
            'name'           => $request->name,
            'designation'    => $request->designation,
            'lang'           => isset($request->lang) ? $request->lang : 'en',
            'description'    => $request->description,
        ]);
    }

    public function langUpdate($request)
    {
        return TestimonialLanguage::where('id', $request->translate_id)->update([
            'lang'           => $request->lang,
            'title'          => $request->title,
            'name'           => $request->name,
            'designation'    => $request->designation,
            'description'    => $request->description,
        ]);
    }
}
