<?php

namespace BoatsAPI;

use Zttp\Zttp;

class CoBrokerageAPI
{
    protected $domain = 'https://services.boats.com/pls/boats/';
    protected $key;
    protected $filters = [];
    protected $fields = [];
    protected $offset = 0;
    protected $limit = 20;

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
        $json = $this->get('details', ['id' => $id])->json();

        if (!isset($json['data'])) {
            return null;
        }

        return new Boat($json['data']);
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

    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function limit($limit = 20)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function page(int $page)
    {
        $this->offset = ($page * $this->limit) - $this->limit;

        return $this;
    }

    public function search()
    {
        $params = $this->filters;
        $params['fields'] = implode(',', $this->fields);
        $params['offset'] = $this->offset;
        $params['rows'] = $this->limit;

        return new CoBrokerageSearchResponse($this->get('search', $params), $this);
    }
}