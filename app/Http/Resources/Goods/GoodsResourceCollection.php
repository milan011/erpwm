<?php

namespace App\Http\Resources\Goods;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}