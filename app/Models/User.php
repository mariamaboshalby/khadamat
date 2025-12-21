<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use \Spatie\Permission\Traits\HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the technician profile associated with the user.
     */
    public function technician()
    {
        return $this->hasOne(Technician::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if user is a technician.
     */
    public function isTechnician(): bool
    {
        return $this->user_type === 'technician' || $this->hasRole('technician');
    }

    /**
     * Check if user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->user_type === 'customer' || $this->hasRole('customer');
    }
}
