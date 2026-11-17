<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="LoginResource",
 *      title="Login Resource",
 *      description="Login Resource model",
 *      @OA\Property(property="user_id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="email", type="string"),
 *      @OA\Property(property="access_token", type="string"),
 *      @OA\Property(property="token_type", type="string"),
 *      @OA\Property(property="expires_at", type="string"),
 *  )
 */
class LoginResource extends JsonResource
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
            'user_id' => $this->id,
            'name' => $this->first_name,
            'email' => $this->email,
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_at' => $this->expires_at,
        ];
    }
}
