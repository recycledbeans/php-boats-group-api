<?php

namespace BoatsAPI;

use Zttp\Zttp;

class CoBrokerageAPI
{
    protected $domain = 'https://services.boats.com/pls/boats/';
    protected $key;
    protected $filters = [];
    protected $fields = [];

    public function __construct($key = null)
    {
        if (!$key) {
            $key = getenv('BOATS_CO_BROKERAGE_API_KEY');
        }

        $this->key = $key;
    }

    public function get($uri, $parameters)
    {
        $parameters['key'] = $this->key;
        $query = http_build_query($parameters);

        return Zttp::get("{$this->domain}{$uri}?{$query}");
    }

    public function details($id)
    {
        return $this->get('details', ['id' => $id])->json();
    }

    public function fields($fields = [])
    {
        if (empty($fields)) {
            return $this->fields;
        }

        $this->fields = $fields;

        return $this;
    }

    public function filters($filters = [])
    {
        if (empty($filters)) {
            return $this->filters;
        }

        $this->filters = $filters;

        return $this;
    }

    public function search()
    {
        $params = $this->filters;
        $params['fields'] = implode(',', $this->fields);

        return $this->get('search', $params);
    }
}