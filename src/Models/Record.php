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

use GrahamCampbell\CoreAPI\Models\AbstractModel;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

/**
 * This is the zone model class.
 *
 * @package    Laravel-CloudFlare-API
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-CloudFlare-API/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-CloudFlare-API
 */
class Record extends AbstractModel
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id;

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
     * @param  int  $id
     * @param  \GrahamCampbell\CloudFlareAPI\Models\Zone  $zone
     * @param  array  $cache
     * @return void
     */
    public function __construct(GuzzleClient $client, $id, Zone $zone, array $cache = array())
    {
        parent::__construct($client, $cache);

        $this->id = (int) $id;
        $this->zone = $zone;
    }

    /**
     * Get the id
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName()
    {
        return (string) $this->lookup('name');
    }

    /**
     * Get the type.
     *
     * @return string
     */
    public function getType()
    {
        return (string) $this->lookup('type');
    }

    /**
     * Get the priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return (int) $this->lookup('prio');
    }

    /**
     * Get the content.
     *
     * @return string
     */
    public function getContent()
    {
        return (string) $this->lookup('content');
    }

    /**
     * Get the time to live.
     *
     * @return int
     */
    public function getTtl()
    {
        return (int) $this->lookup('ttl_ceil');
    }

    /**
     * Is the time to live automatic?
     *
     * @return bool
     */
    public function isTtlAutomatic()
    {
        return (bool) $this->lookup('auto_ttl');
    }

    /**
     * Get the ssl id.
     *
     * @return int
     */
    public function getSslId()
    {
        return (int) $this->lookup('ssl_id');
    }

    // TODO: ssl methods are incomplete

    /**
     * Are we in service?
     *
     * @return bool
     */
    public function isInService()
    {
        return (bool) $this->lookup('service_mode');
    }

    /**
     * Are we proxiable?
     *
     * @return bool
     */
    public function isProxiable()
    {
        return (bool) $this->lookup('props')['proxiable'];
    }

    /**
     * Is the cloud on?
     *
     * @return bool
     */
    public function isTheCloudOn()
    {
        return (bool) $this->lookup('props')['cloud_on'];
    }

    /**
     * Are we open?
     *
     * @return bool
     */
    public function isOpen()
    {
        return (bool) $this->lookup('props')['cf_open'];
    }

    /**
     * Are we vanity locked?
     *
     * @return bool
     */
    public function isVanityLocked()
    {
        return (bool) $this->lookup('props')['vanity_lock'];
    }

    /**
     * Dump all data.
     *
     * @param  array  $data
     * @return self
     */
    public function modify(array $data)
    {
        $data['id'] = $this->getId();

        $this->action('recEdit', $data, 'recLoad');

        return $this;
    }

    /**
     * Lookup information.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function lookup($key)
    {
        $data = $this->data($data);

        if (!$this->cache['recLoad']) {
            $records = $this->client->recLoadAll($data)->toArray()['response']['recs']['objs'];
            foreach($records as $record) {
                if ((int) $record['rec_id'] === $this->id) {
                    $this->cache['recLoad'] = $record;
                    break;
                }
            }
        }

        return $this->cache['recLoad'][$key];
    }

    /**
     * Get the data to make a request.
     *
     * @param  array  $data
     * @return array
     */
    protected function data(array $data = array())
    {
        return array_merge(array('z' => $this->zone->getZone()), $data);
    }

    /**
     * Clear the request cache.
     *
     * This method overrides the method in the parent class.
     *
     * @param  array|string  $methods
     * @return self
     */
    public function clearCache($methods = null)
    {
        if ($methods === null || $methods === 'all') {
            $this->cache = array();
        } else {
            foreach ((array) $methods as $method) {
                $this->cache[$method] = array();
                // we may need to clear out the record cache in the zone model
                // to avoid unintuitive behaviour
                if (($method == 'recLoad' || !$method) && $method !== false) {
                    $this->zone->clearRecordCache();
                }
            }
        }

        return $this;
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
