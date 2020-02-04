<?php

namespace App\Http\Resources\InfoDianxin;

use Illuminate\Http\Resources\Json\Resource;

class InfoDianxinResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /*dd($this);
        foreach ($this as $key => $value) {
            dd($key);
        }*/

        /*return [
            'id' => $this->id,
            'name' => $this->name,
            'roles' => ['admin', 'editer'],
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'introduction' => '我是超级管理员',
        ];*/
        return parent::toArray($request);
    }
}
