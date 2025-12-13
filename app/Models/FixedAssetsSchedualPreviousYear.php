<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedAssetsSchedualPreviousYear extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'account_code',
        'account_head',
        'depreciation_account_code',
        'depreciation_account_head',
        'opening',
        'addition',
        'addition_no_of_days',
        'deletion',
        'deletion_no_of_days',
        'closing',
        'rate',
        'depreciation_opening',
        'depreciation_addition',
        'depreciation_deletion',
        'depreciation_closing',
        'wdv',
        'modified_by'
    ];
}
