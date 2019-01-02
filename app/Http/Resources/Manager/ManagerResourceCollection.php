<?php

namespace App\Http\Resources\Manager;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ManagerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}