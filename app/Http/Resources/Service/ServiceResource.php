<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\Resource;

class ServiceResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /*public function with($request)
    {
        return [
            'return_month_price' => $this->hasManyGoodsInfo(),
        ];
    }*/
}
