<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Organization",
 *     description="Organization model",
 *     @OA\Xml(
 *         name="Organization"
 *     )
 * )
 */
class Organization extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'organizations';

    /** @var array */
    protected $fillable = ['name', 'logo'];

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
    private $name;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $logo;
}
