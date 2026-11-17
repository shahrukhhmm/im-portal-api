<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'level'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function applicationUsers()
    {
        return $this->hasMany(ApplicationUser::class);
    }
    public function applications()
    {
        return $this->belongsToMany(Application::class, 'application_user');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_user');
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
    private $first_name;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $last_name;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $password;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $level;
}
