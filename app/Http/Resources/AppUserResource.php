<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/**
 *  @OA\Schema(
 *      schema="AppUserResource",
 *      title="Application User Resource",
 *      description="Application User Resource model",
 *      @OA\Property(property="user_id", type="integer"),
 *      @OA\Property(property="first_name", type="string"),
 *      @OA\Property(property="last_name", type="string"),
 *      @OA\Property(property="email", type="string"),
 *      @OA\Property(property="level", type="string"),
 *      @OA\Property(
 *              property="applications",
 *              type="array",
 *              @OA\Items(
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="name", type="string"),
 *                      @OA\Property(property="app_url", type="string"),
 *                      @OA\Property(property="public_key", type="string"),
 *                      @OA\Property(property="image", type="string")
 *              ),
 *      ),
 * )
 */
class AppUserResource extends JsonResource
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
