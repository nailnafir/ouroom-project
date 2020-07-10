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
    // public static function validateFeed($class_name, $guru, $feed) {
    //     $data = self::where('class_name',$class_name)->where('teacher_id',$guru)->where('feed_id',$feed)->first();
    //     if($data != null) {
    //         return true;
    //     }
    //     return false;
    // }

    // public function getClass() {
    //     return $this->hasMany('App\Model\StudentClass\Feed','id','class_id');
    // }
}
