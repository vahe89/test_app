<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users')
            ->withPivot('connected_date');
    }

    public function getResult()
    {
        $countryName = 'Canada';
        $result=[];
        $usersInCountry = User::whereHas('companies.country', function ($query) use ($countryName) {
            $query->where('name', $countryName);
        })->with(['companies' => function ($query) {
            $query->select('companies.id', 'companies.name','connected_date');
        }])->get();
        foreach ($usersInCountry as $value){
            $result[]=['user_name'=>$value->name,'company_name'=>$value->companies[0]->name,'connected_date'=>$value->companies[0]->connected_date] ;


        }
        return $result;
    }
}
