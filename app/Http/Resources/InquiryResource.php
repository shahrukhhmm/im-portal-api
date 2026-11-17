<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="InquiryResource",
 *      title="Inquiry Resource",
 *      description="Inquiry Resource model",
 *      @OA\Property(property="user_name", type="string"),
 *      @OA\Property(property="role", type="string"),
 *      @OA\Property(property="level", type="string"),
 *      @OA\Property(property="application", type="string"),
 *      @OA\Property(
 *              property="brands",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(
 *                      property="brand",
 *                      type="object",
 *                      @OA\Property(property="name", type="string"),
 *                      @OA\Property(property="logo", type="string")
 *                  ),
 *              ),
 *      ),
 * )
 */
class InquiryResource extends JsonResource
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
