<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $appends = ['company_meta']; // Ensures this is included in JSON

    public function company_meta() {
        return $this->hasMany(CompanyMeta::class, 'company_id', 'id');
    }

    public function getCompanyMetaAttribute() {
        return $this->company_meta()
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->meta_key => $item->meta_value];
            });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function modified() {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
