<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="ApplicationUser",
 *     description="Application User model",
 *     @OA\Xml(
 *         name="ApplicationUser"
 *     )
 * )
 */
class ApplicationUser extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'application_user';

    /** @var array */
    protected $fillable = ['application_id', 'user_id', 'role_id', 'brand_id'];

    public $timestamps = false;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $application_id;

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
     * @var integer
     */
    private $role_id;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $brand_id;
}
