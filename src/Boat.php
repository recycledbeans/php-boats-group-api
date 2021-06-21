<?php

namespace BoatsAPI;

use Illuminate\Support\Collection;

class Boat
{
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function images()
    {
        $images = array_map(function ($image) {
            $img = new Image();
            $img->url = $image['Uri'];
            $img->order = $image['Priority'];
            $img->caption = $image['Caption'];
            $img->date = $image['LastModifiedDateTime'];

            return $img;
        }, $this->data['Images']);

        return new Collection($images);
    }
}