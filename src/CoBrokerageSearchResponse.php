<?php

namespace BoatsAPI;

class CoBrokerageSearchResponse
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
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
}