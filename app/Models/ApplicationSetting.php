<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="ApplicationSetting",
 *     description="Application Setting model",
 *     @OA\Xml(
 *         name="ApplicationSetting"
 *     )
 * )
 */
class ApplicationSetting extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'applications';

    /** @var array */
    protected $fillable = ['app_url', 'api_url', 'ip'];

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $app_url;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $api_url;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $ip;
}
