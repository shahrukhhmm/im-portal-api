<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="ProfileResource",
 *      title="Profile Resource",
 *      description="Profile Resource model",
 * 	    @OA\Property(property="user_id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="email", type="string"),
 *      @OA\Property(property="avatar", type="string"),
 *      @OA\Property(
 *          property="applications",
 *          type="array",
 *          @OA\Items(
 *              @OA\Property(property="id", type="integer"),
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(property="logo", type="string"),
 *              @OA\Property(
 *                  property="brands",
 *                  type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="name", type="string"),
 *                      @OA\Property(property="logo", type="string")
 *                  ),
 *              ),
 *          ),
 *      ),
 *      @OA\Property(
 *          property="active_brand",
 *          type="object",
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="logo", type="string")
 *      ),
 *  )
 */
class ProfileResource extends JsonResource
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
