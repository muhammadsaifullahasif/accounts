<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAccountingPolicy extends Model
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modified()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
