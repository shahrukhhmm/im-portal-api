<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="BrandUserResource",
 *      title="Brand User Resource",
 *      description="Brand User Resource model",
 * 	    @OA\Property(property="brand_id", type="integer"),
 * 	    @OA\Property(property="brand_name", type="string"),
 *          @OA\Property(
 *                  property="users",
 *                  type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="user_id", type="integer"),
 *                      @OA\Property(property="user_name", type="string")
 *                  ),
 *          ),
 *  )
 */
class BrandUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $users = $this->users;
        return [
            'brand_id' => $this->id,
            'brand_name' => $this->name,
            'users' => $users
        ];
    }
}
