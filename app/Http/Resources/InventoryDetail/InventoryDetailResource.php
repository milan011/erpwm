<?php

namespace App\Http\Resources\InventoryDetail;

use Illuminate\Http\Resources\Json\Resource;

class InventoryDetailResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this);

        /*return [
            'id' => $this->id,
            'name' => $this->name,
            'month_nums' => $this->month_nums,
            'package_price' => $this->package_price,
            'remark' => $this->remark,       
            'created_at' => $this->created_at,       
        ];*/
        return parent::toArray($request);
    }

    /*public function with($request)
    {
        return [
            'return_month_price' => $this->hasManyInventoryDetailInfo(),
        ];
    }*/
}
