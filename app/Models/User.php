<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use \Illuminate\Auth\Authenticatable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_country_id',
        'phone',
        'password',
        'permissions',
        'user_type',
        'firebase_auth_id',
        'language_id',
        'currency_id',
        'client_id',
        'images',
        'role_id',
        'address',
        'country_id',
        'about',
        'status',
        'is_newsletter_enabled',
        'is_notification_enabled',
        'email_verified_at',
        'is_user_banned',
        'onesignal_player_id',
        'is_onesignal_subscribed',
        'is_primary',
    ];

    protected $hidden   = [
        'password',
        'remember_token',
    ];

    protected $casts    = [
        'permissions'         => 'array',
        'images'              => 'array',
        'onesignal_player_id' => 'array',
    ];

    public function getNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getProfilePicAttribute(): string
    {
        return arrayCheck('image_40x40', $this->images) && is_file_exists($this->images['image_40x40'], $this->images['storage']) ?
            get_media($this->images['image_40x40'], $this->images['storage']) : static_asset('images/default/user.jpg');
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function lastActivity(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ActivityLog::class)->latest();
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function phoneCountry(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_country_id');
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function client_staff()
    {
        return $this->hasOne(ClientStaff::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeNotBanned($query)
    {
        return $query->where('is_user_banned', 0);
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientStaff()
    {
        return $this->hasOne(ClientStaff::class, 'user_id', 'id');
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'client_id', 'client_id')->latest()->where('purchase_date', '<=', now())
            ->where('expire_date', '>=', now())->where('status', 1);
    }

    public function pendingSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'client_id', 'client_id')->where('purchase_date', '<=', now())
            ->where('expire_date', '>=', now())->where('status', 0)->latest();
    }
}
