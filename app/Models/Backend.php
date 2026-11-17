<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Backend",
 *     description="Backend model",
 *     @OA\Xml(
 *         name="Backend"
 *     )
 * )
 */
class Backend extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'backends';

    /** @var array */
    protected $fillable = ['title', 'token', 'expires_at'];

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $token;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $expires_at;
}
