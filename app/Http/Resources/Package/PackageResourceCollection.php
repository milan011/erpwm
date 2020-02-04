<?php

namespace App\Http\Resources\Package;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PackageCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}