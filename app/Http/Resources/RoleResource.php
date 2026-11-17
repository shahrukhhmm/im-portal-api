<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="RoleResource",
 *      title="Role Resource",
 *      description="Role Resource model",
 * 	    @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *  )
 */
class RoleResource extends JsonResource
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
        ];
    }
}
