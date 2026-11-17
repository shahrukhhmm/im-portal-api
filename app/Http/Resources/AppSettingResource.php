<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="AppSettingResource",
 *      title="App Setting Resource",
 *      description="Application Setting Resource model",
 * 	    @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(
 *          property="settings",
 *          type="object",
 *          @OA\Property(property="app_url", type="string"),
 *          @OA\Property(property="api_url", type="string"),
 *          @OA\Property(property="ip", type="string"),
 *      ),
 *      @OA\Property(property="image", type="string")
 * )
 */
class AppSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $settings = [
            'app_url' => $this->app_url,
            'api_url' => $this->api_url,
            'ip' => $this->ip,
        ];
        return [
            'id' => $this->id,
            'name' => $this->name,
            'settings' => $settings,
            'image' => $this->image
        ];
    }
}
