<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="OrganizationResource",
 *      title="Organization Resource",
 *      description="Organization Resource model",
 * 	    @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="logo", type="string"),
 *      @OA\Property(property="thumbnail", type="string")
 * )
 */
class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fileName = $this->logo;
        if ($fileName == "") {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'logo' => "",
                'thumbnail' => "",
            ];
        } else {
            $logoUrl = asset('storage/orgs/' . $fileName);
            $thumbUrl = asset('storage/thumbnails/thumb_' . $fileName);
            return [
                'id' => $this->id,
                'name' => $this->name,
                'logo' => $logoUrl,
                'thumbnail' => $thumbUrl,
            ];
        }
    }
}
