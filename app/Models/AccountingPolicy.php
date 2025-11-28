<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingPolicy extends Model
{
    protected $fillable = [
        'user_id',
        'size',
        // 'index',
        'title',
        'content',
        'policy_heading',
        'modified_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
