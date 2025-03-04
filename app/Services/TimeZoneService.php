<?php

namespace App\Services;

use Illuminate\Http\Request;

class TimeZoneService
{
    protected $host   = 'http://www.geoplugin.net/json.gp';

    protected $filter = null;

    protected $ip     = null;

    protected $json   = null;

    private $data     = [];

    public function execute(Request $request)
    {
        $ip                     = $request->ip();
        $timezone               = $this->getTimezone($ip);
        $this->data['timezone'] = $timezone;

        return $this->data;
    }

    private function getTimezone($ip)
    {

        $host = "{$this->host}?ip={$ip}";
        if (function_exists('curl_init')) {
            // use cURL to fetch data
            $request = curl_init();
            // setting options
            curl_setopt($request, CURLOPT_URL, $host);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($request, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.1');
            // response
            $json    = curl_exec($request);
            // closing cURL
            curl_close($request);
        } elseif (ini_get('allow_url_fopen')) {
            // fall back to fopen()
            $json = file_get_contents($host, 'r');
        } else {
            trigger_error('geoPlugin Error: Cannot retrieve data.', E_USER_ERROR);

            return null;
        }
        // Convert the json string to php array
        $data = json_decode($json, true);

        // Return timezone
        return isset($data['geoplugin_timezone']) ? $data['geoplugin_timezone'] : null;
    }

    /**
     * convert the json string to php array
     * based on the filter property
     *
     * @return array
     */
    public function toArray()
    {
        // condition(s)
        if ($this->filter != true) {
            return json_decode($this->json, true);
        }

        // filtering & returning
        return $this->__filter();
    }

    /**
     * convert the json string to php object
     * based on the filter property
     *
     * @return object
     */
    public function toObject()
    {
        // condition(s)
        if ($this->filter != true) {
            return json_decode($this->json);
        }

        // filtering & returning
        return (object) $this->__filter();
    }

    /**
     * return collected location data in the form of json
     * based on the filter property
     *
     * @return string
     */
    public function toJson()
    {
        // condition(s)
        if ($this->filter != true) {
            return $this->json;
        }

        // filtering & returning
        return json_encode($this->__filter());
    }

    /**
     * filter the object keys
     *
     * @return array
     */
    private function __filter()
    {
        // applying filter
        foreach (json_decode($this->json, true) as $key => $item) {
            $return[str_replace('geoplugin_', '', $key)] = $item;
        }

        // returning
        return $return;
    }
}
