<?php

namespace App\Http\Resources\ServiceDetail;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceDetailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}