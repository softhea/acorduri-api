<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id;
 * @property int $no_of_artists;
 * @property int $no_of_tabs;
 * @property int $no_of_views;
 * @property int $no_of_chords;
 * @property int $role_id;
 * @property ?string $username;
 * @property string $name;
 * @property ?DateTime $email_verified_at;
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    public const TABLE = "users";
    public const ROLE_ID_ADMIN = 2;

    public const PRIMARY_KEY = 'id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_USERNAME = 'username';
    public const COLUMN_EMAIL_VERIFIED_AT = 'email_verified_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_USERNAME,
        'email',
        'password',
        'role_id',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::COLUMN_EMAIL_VERIFIED_AT => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeOnlyActive(Builder $query): Builder
    {
        return $query->whereNotNull(self::COLUMN_EMAIL_VERIFIED_AT);
    }

    public function increaseNoOfTabs(): void
    {
        $this->no_of_tabs++;
        $this->save();
    }

    public function decreaseNoOfTabs(): void
    {
        if ($this->no_of_tabs <= 0) {
            return;
        }

        $this->no_of_tabs--;
        $this->save();
    }

    public function increaseNoOfViews(): void
    {
        $this->no_of_views++;
		$this->save();
    }

    public function increaseNoOfArtists(): void
    {
        $this->no_of_artists++;
		$this->save();
    }

    public function decreaseNoOfArtists(): void
    {
        if ($this->no_of_artists <= 0) {
            return;
        }

        $this->no_of_artists--;
        $this->save();
    }

    public function isAdmin(): bool
    {
        return $this->role_id <= self::ROLE_ID_ADMIN;
    }

    public function isActive(): bool
    {
        return null !== $this->email_verified_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNoOfTabs(): int
    {
        return $this->no_of_tabs;
    }

    public function getNoOfArtists(): int
    {
        return $this->no_of_artists;
    }

    public function getNoOfViews(): int
    {
        return $this->no_of_views;
    }
}
