<?php

namespace App\Http\Resources\Notice;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NoticeCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}