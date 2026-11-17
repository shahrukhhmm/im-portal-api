<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Application",
 *     description="Application model",
 *     @OA\Xml(
 *         name="Application"
 *     )
 * )
 */
class Application extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'applications';

    /** @var array */
    protected $fillable = ['name', 'image'];

    public function brands()
    {
        return $this->hasMany(Brand::class)->select(['id', 'name', 'logo']);;
    }

    public function users() {
        return $this->belongsToMany(User::class, 'application_user');
    }

    public function entities() {
        return $this->hasMany(Entity::class)->select(['id', 'name']);
    }

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
    private $image;
}
