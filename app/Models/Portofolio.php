<?php

namespace App\Models;

use App\ModelCaching;

class Portofolio extends ModelCaching
{
    protected $table = 'portofolio';
    protected $fillable = [
        'id', 'member_id', 'project_name', 'start_date', 'end_date', 'project_on_going',
        'thumbnail', 'description',
    ];

    public function scopeFindMember($query, $id)
    {
        $query->where('member_id', $id);
    }
}
