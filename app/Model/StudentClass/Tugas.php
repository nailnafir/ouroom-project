<?php

namespace App\Model\StudentClass;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tbl_tugas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file',
    ];

    public static $rules = [
        'file' => 'string',
    ];
}
