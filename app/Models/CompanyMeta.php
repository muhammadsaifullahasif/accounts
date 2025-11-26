<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMeta extends Model
{
    protected $fillable = ['company_id', 'meta_key', 'meta_value'];

    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
