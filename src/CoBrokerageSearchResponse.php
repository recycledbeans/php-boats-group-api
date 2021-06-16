<?php

namespace BoatsAPI;

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
        return $this->response->json()['data']['results'];
    }

    public function isSuccess()
    {
        try {
            return $this->response->isOk() && $this->response->json()['status'] === 'success';
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

        $raw_results = $this->response->json();
        $rows = $raw_results['data']['numResults'] ?? 0;

        return ceil($rows / $this->api->getLimit());
    }
}