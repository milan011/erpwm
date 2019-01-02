<?php

namespace App\Http\Resources\InventoryDetail;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryDetailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}