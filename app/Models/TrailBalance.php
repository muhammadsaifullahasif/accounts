<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrailBalance extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'account_code',
        'account_head',
        'group_code',
        'group_name',
        'opening_debit',
        'opening_credit',
        'movement_debit',
        'movement_credit',
        'closing_debit',
        'closing_credit',
        'modified_by'
    ];
}
