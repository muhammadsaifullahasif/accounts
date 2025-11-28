<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAuditReport extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function modified()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
