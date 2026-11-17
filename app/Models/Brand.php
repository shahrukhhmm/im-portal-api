<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Brand",
 *     description="Brand model",
 *     @OA\Xml(
 *         name="Brand"
 *     )
 * )
 */
class Brand extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'brands';

    /** @var array */
    protected $fillable = ['name', 'logo', 'application_id'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'brand_user');
    }

    function Languages(){
        return $this->belongsToMany(Language::class ,'brand_languages' ,'brand_id' , 'language_id')->withPivot('primary');
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
    private $logo;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $application_id;
}
