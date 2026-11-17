<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="UserResource",
 *      title="User Resource",
 *      description="User Resource model",
 * 	    @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="first_name", type="string"),
 *      @OA\Property(property="last_name", type="string"),
 *      @OA\Property(property="email", type="string"),
 *      @OA\Property(property="level", type="string"),
 *  )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'level' => $this->level
        ];
    }
}
