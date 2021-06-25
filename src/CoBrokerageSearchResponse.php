<?php

namespace BoatsAPI;

use Illuminate\Support\Collection;
use Zttp\ZttpResponse;

class CoBrokerageSearchResponse
{
    protected $response;
    protected $api;

    public function __construct(ZttpResponse $response, CoBrokerageAPI $api)
    {
        $this->response = $response;
        $this->api = $api;
    }

    public function data()
    {
        return new Collection($this->rawData()['data']['results']);
    }

    public function isSuccess()
    {
        try {
            return $this->response->isOk() && $this->rawData()['status'] === 'success';
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    public function pages()
    {
        if (!$this->isSuccess()) {
            return 0;
        }

        $raw_results = $this->rawData();
        $rows = $raw_results['data']['numResults'] ?? 0;

        return ceil($rows / $this->api->getLimit());
    }

    public function rawData()
    {
        return $this->response->json();
    }

    public function totalResults()
    {
        return $this->rawData()['data']['numResults'];
    }
}