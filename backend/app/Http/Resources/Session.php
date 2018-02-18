<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Session extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'year' => $this->year,
          'team' => $this->team,
          'remark' => $this->remark,
          'one' => $this->one,
          'two' => $this->two,
          'three' => $this->three
        ];
    }

    public function with($request)
    {
       return [
          'status' => 'success',
          'status_code' => 200
       ];
    }



}
