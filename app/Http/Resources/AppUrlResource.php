<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="AppUrlResource",
 *      title="Application Info Resource",
 *      description="Application Info Resource model",
 *      @OA\Property(property="app_id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="app_url", type="string"),
 *      @OA\Property(property="public_key", type="string"),
 * )
 */
class AppUrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
