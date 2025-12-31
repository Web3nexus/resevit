<?php

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

trait BelongsToBranch
{
    protected static function bootBelongsToBranch()
    {
        static::creating(function (Model $model) {
            if (empty($model->branch_id) && Session::has('current_branch_id')) {
                $model->branch_id = Session::get('current_branch_id');
            }
        });

        static::addGlobalScope('branch', function (Builder $builder) {
            if (Session::has('current_branch_id')) {
                $builder->where('branch_id', Session::get('current_branch_id'));
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
