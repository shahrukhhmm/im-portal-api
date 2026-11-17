<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="PortalAccessToken",
 *     description="Portal Access Token model",
 *     @OA\Xml(
 *         name="PortalAccessToken"
 *     )
 * )
 */
class PortalAccessToken extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'portal_access_tokens';

    /** @var array */
    protected $fillable = ['user_id', 'user_token', 'public_key'];

    public $timestamps = false;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $user_id;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $user_token;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $public_key;
}
