<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Models\FaqLanguage;

class WebsiteFaqRepository
{
    public function all()
    {
        $theme = active_theme();
        $limit = $theme == 'martex' ? 6 : 4;
        return Faq::active()->take($limit)->latest()->get();
    }
    
    public function activeFaqs($data = [])
    {
        return Faq::active()->orderBy('ordering')->get();
    }

    public function store($request)
    {
        $data = $request;
        $faq  = new Faq;
        $faq->question = $request->question;
        $faq->answer   = $request->answer;
        $faq->ordering = $request->ordering;
        $faq->save();

        $this->langStore($request, $faq);

        return $faq;
    }

    public function find($id)
    {
        return Faq::find($id);
    }

    public function getByLang($id, $lang)
    {
        if (! $lang) {
            $faq = FaqLanguage::where('lang', 'en')->where('faq_id', $id)->first();
        } else {
            $faq = FaqLanguage::where('lang', $lang)->where('faq_id', $id)->first();
            if (! $faq) {
                $faq                     = FaqLanguage::where('lang', 'en')->where('faq_id', $id)->first();
                $faq['translation_null'] = 'not-found';
            }
        }

        return $faq;
    }

    public function update($request, $id)
    {
        $faq           = Faq::find($id);
        $faq->question = $request->question;
        $faq->answer   = $request->answer;
        $faq->ordering = $request->ordering;
        $faq->save();

        if (arrayCheck('lang', $request) && $request->lang != 'en') {
            $request->name = $faq->name;
        }
        if ($request->translate_id) {
            $request->lang = $request->lang ?: 'en';
            $this->langUpdate($request);
        } else {
            $this->langStore($request, $faq);
        }

        return $faq;
    }

    public function status($data)
    {
        $faq         = Faq::find($data['id']);
        $faq->status = $data['status'];
        $faq->save();

        return $faq;
    }

    public function destroy($id)
    {
        return Faq::destroy($id);
    }

    public function langStore($request, $faq)
    {
        return FaqLanguage::create([
            'faq_id'                    => $faq->id,
            'lang'                      => isset($request->lang) ? $request->lang : 'en',
            'question'                  => $request->question,
            'answer'                    => $request->answer,
        ]);
    }

    public function langUpdate($request)
    {
        return FaqLanguage::where('id', $request->translate_id)->update([
            'lang'           => $request->lang,
            'question'       => $request->question,
            'answer'         => $request->answer,
        ]);
    }
}
