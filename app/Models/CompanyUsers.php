<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'connected_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->first();
    }
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id')->first();
    }
}
