<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Log",
 *     description="Log model",
 *     @OA\Xml(
 *         name="Log"
 *     )
 * )
 */
class Log extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logs';

    /** @var array */
    protected $fillable = ['app_id', 'user_id', 'brand_id', 'role_id',
        'ip_address', 'user_agent', 'route', 'method', 'action', 'objects',
        'login_at', 'logout_at'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'objects' => 'array',
    ];

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $app_id;

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

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $ip_address;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $user_agent;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $route;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $method;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $action;

    /**
     * @OA\Property(
     *      type="array",
     *      @OA\Items(
     *          type="object",
     *      ),
     * )
     */
    private $objects;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $login_at;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $logout_at;

    /**
     * @return int
     */
    public function getAppId(): int
    {
        return $this->app_id;
    }

    /**
     * @param int $app_id
     */
    public function setAppId(int $app_id): void
    {
        $this->app_id = $app_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * @param int $role_id
     */
    public function setRoleId(int $role_id): void
    {
        $this->role_id = $role_id;
    }

    /**
     * @return int
     */
    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    /**
     * @param int $brand_id
     */
    public function setBrandId(int $brand_id): void
    {
        $this->brand_id = $brand_id;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    /**
     * @param string $ip_address
     */
    public function setIpAddress(string $ip_address): void
    {
        $this->ip_address = $ip_address;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * @param string $user_agent
     */
    public function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @param mixed $objects
     */
    public function setObjects($objects): void
    {
        $this->objects = $objects;
    }

    /**
     * @return string
     */
    public function getLoginAt(): string
    {
        return $this->login_at;
    }

    /**
     * @param string $login_at
     */
    public function setLoginAt(string $login_at): void
    {
        $this->login_at = $login_at;
    }

    /**
     * @return string
     */
    public function getLogoutAt(): string
    {
        return $this->logout_at;
    }

    /**
     * @param string $logout_at
     */
    public function setLogoutAt(string $logout_at): void
    {
        $this->logout_at = $logout_at;
    }
}
