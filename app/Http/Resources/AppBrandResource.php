<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="AppBrandResource",
 *      title="Application Brand Resource",
 *      description="Application Brand Resource model",
 * 	    @OA\Property(property="app_id", type="integer"),
 *      @OA\Property(property="app_name", type="string"),
 *      @OA\Property(
 *              property="brands",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="brand_id", type="integer"),
 *                  @OA\Property(property="brand_name", type="string")
 *              ),
 *      ),
 * )
 */
class AppBrandResource extends JsonResource
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
            'app_id' => $this->id,
            'app_name' => $this->name,
            'brands' => $brands
        ];
    }
}
