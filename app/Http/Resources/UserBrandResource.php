<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="UserBrandResource",
 *      title="User Brand Resource",
 *      description="User Brand Resource model",
 * 	    @OA\Property(property="user_id", type="integer"),
 *      @OA\Property(property="user_name", type="string"),
 *      @OA\Property(
 *              property="brands",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="brand_id", type="integer"),
 *                  @OA\Property(property="brand_name", type="string")
 *              ),
 *      ),
 *  )
 */
class UserBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $brands = $this->brands;
        return [
            'user_id' => $this->id,
            'user_name' => $this->name,
            'brands' => $brands
        ];
    }
}
