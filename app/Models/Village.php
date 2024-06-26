<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $table = "indonesia_villages";

    protected $fillable = [
        'name',
        'code',
        'district_code',
        'meta',
        'created_at',
        'updated_at',
    ];

}
