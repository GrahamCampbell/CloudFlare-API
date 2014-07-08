<?php

/**
 * This file is part of Laravel CloudFlare API by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\CloudFlareAPI\Models;

/**
 * This is the zone ip model class.
 *
 * @package    Laravel-CloudFlare-API
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-CloudFlare-API/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-CloudFlare-API
 */
class ZoneIp extends Ip
{
    /**
     * The zone object.
     *
     * @var \GrahamCampbell\CloudFlareAPI\Models\Zone
     */
    protected $zone;

    /**
     * Create a new model instance.
     *
     * @param  \GuzzleHttp\Command\Guzzle\GuzzleClient  $client
     * @param  string  $ip
     * @param  \GrahamCampbell\CloudFlareAPI\Models\Zone  $zone
     * @param  array  $cache
     * @return void
     */
    public function __construct(GuzzleClient $client, $ip, Zone $zone, array $cache = array())
    {
        parent::__construct($client, $ip, $cache);

        $this->zone = $zone;
    }

    /**
     * Get the threat classification.
     *
     * @return string
     */
    public function getClassification()
    {
        return (string) $this->lookup('classification');
    }

    /**
     * Get the number of hits.
     *
     * @return int
     */
    public function getHits()
    {
        return (int) $this->lookup('hits');
    }

    /**
     * Get the latitude.
     *
     * @return int
     */
    public function getLatitude()
    {
        return (int) $this->lookup('latitude');
    }

    /**
     * Get the longitude.
     *
     * @return int
     */
    public function getLongitude()
    {
        return (int) $this->lookup('longitude');
    }

    /**
     * Lookup information.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function lookup($key)
    {
        $data = array('z' => $this->zone->getZone());
        $data = $this->data($data);

        if (!$this->cache['zoneIp']) {
            $ips = $this->client->zoneIps($data)->toArray()['response']['ips'];
            foreach($ips as $ip) {
                if ($ip['name'] == $this->name) {
                    $this->cache['zoneIp'] = $ip;
                    break;
                }
            }
        }

        return $this->cache['zoneIp'][$key];
    }

    /**
     * Get the zone instance.
     *
     * @return \GrahamCampbell\CloudFlareAPI\Models\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }
}