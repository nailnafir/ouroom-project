<?php

namespace App\Model\StudentClass;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $table = 'tbl_feed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul', 
        'kategori', 
        'detail',
        'file',
        'deadline',
    ];

    public static $rules = [
        'judul' => 'required',
        'kategori' => 'required',
        'detail' => 'string',
        'file' => 'string',
        'deadline' => 'date'
    ];

    /**
     * 
     */
}
