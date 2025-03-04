<?php

namespace App\Repositories;

use App\Models\WebsitePartnerLogo;
use App\Models\WebsitePartnerLogoLanguage;
use App\Traits\ImageTrait;

class WebsitePartnerLogoRepository
{
    use ImageTrait;

    public function all()
    {
        return WebsitePartnerLogo::where('status', '=', '1')->take(10)->latest()->get();
    }

    public function find($id)
    {
        return WebsitePartnerLogo::find($id);
    }

    public function getByLang($id, $lang)
    {
        if (! $lang) {
            $partner_logo = WebsitePartnerLogoLanguage::where('lang', 'en')->where('website_partner_logo_id', $id)->first();
        } else {
            $partner_logo = WebsitePartnerLogoLanguage::where('lang', $lang)->where('website_partner_logo_id', $id)->first();
            if (! $partner_logo) {
                $partner_logo                     = WebsitePartnerLogoLanguage::where('lang', 'en')->where('website_partner_logo_id', $id)->first();
                $partner_logo['translation_null'] = 'not-found';
            }
        }

        return $partner_logo;
    }

    public function store($request)
    {
        $partner_logo                     = new WebsitePartnerLogo;
        if (isset($request->image)) {
            $response                     = $this->saveImage($request->image);
            $images                       = $response['images'];
            $partner_logo->image          = $images;
        }
        $partner_logo->name               = $request->name;
        $partner_logo->save();

        $this->langStore($request, $partner_logo);

        return $partner_logo;
    }

    public function update($request, $id)
    {

        $partner_logo = WebsitePartnerLogo::findOrfail($id);

        if (isset($request->image)) {
            $response                     = $this->saveImage($request->image);
            $images                       = $response['images'];
            $partner_logo->image          = $images;
        }
        $partner_logo->name               = $request->name;
        $partner_logo->save();

        if (arrayCheck('lang', $request) && $request->lang != 'en') {
            $request->name = $partner_logo->name;
        }
        if ($request->translate_id) {
            $request->lang = $request->lang ?: 'en';
            $this->langUpdate($request);
        } else {
            $this->langStore($request, $partner_logo);
        }

        return $partner_logo;
    }

    public function destroy($id): int
    {
        return WebsitePartnerLogo::destroy($id);
    }

    public function status($data)
    {
        $key         = WebsitePartnerLogo::findOrfail($data['id']);
        $key->status = $data['status'];

        return $key->save();
    }

    public function langStore($request, $partner_logo)
    {
        return WebsitePartnerLogoLanguage::create([
            'website_partner_logo_id'        => $partner_logo->id,
            'lang'                           => isset($request->lang) ? $request->lang : 'en',
            'name'                           => $request->name,
        ]);
    }

    public function langUpdate($request)
    {
        return WebsitePartnerLogoLanguage::where('id', $request->translate_id)->update([
            'lang'           => $request->lang,
            'name'           => $request->name,
        ]);
    }
}
