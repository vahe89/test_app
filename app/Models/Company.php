<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\CompanyUsers;
class Company extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withPivot('connected_date');
    }
}
