<?php

namespace App\Model\User;

use App\Model\StudentClass\StudentClass;
use App\Model\StudentClass\Tugas;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasRoles;
    use Notifiable;

    protected $table = 'tbl_user';
    protected $guard_name = 'web';

    const ACCOUNT_TYPE_CREATOR = "Creator";
    const ACCOUNT_TYPE_ADMIN = "Administrator";
    const ACCOUNT_TYPE_PARENT = "Parent";
    const ACCOUNT_TYPE_TEACHER = "Guru";
    const ACCOUNT_TYPE_SISWA = "Siswa";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'jenis_kelamin', 'email', 'address', 'full_name', 'jurusan', 'angkatan', 'account_type', 'password', 'status', 'profile_picture', 'last_login_at',
        'last_login_ip'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $attributes = [
        'account_type' => self::ACCOUNT_TYPE_PARENT,
    ];

    public static $rules = [
        'username' => 'required | unique',
        'email' => 'required | unique',
        'profile_picture' => 'string',
        'address' => 'string',
        'full_name' => 'required | string',
        'account_type' => 'required | string',
    ];

    /**
     * 
     */
    public static function getUser(){
        return self::whereNotIn('account_type', [User::ACCOUNT_TYPE_CREATOR, User::ACCOUNT_TYPE_SISWA])->get();
    }

    /**
     * 
     */
    public static function getTeacher($search = null){
        return self::where('account_type', User::ACCOUNT_TYPE_TEACHER)->where('full_name', 'like', '%' . $search . '%')->get();
    }

    /**
     * 
     */
    public static function getSiswa($search = null){
        return self::where('account_type', User::ACCOUNT_TYPE_SISWA)->where('full_name', 'like', '%' . $search . '%')->get();
    }

    /**
     * 
     */
    public static function passwordChangeValidation($old_password, $curent_password){
        if (Hash::check($old_password, $curent_password)){
            return true;
        }
        return false;
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * @var array
     */
    public static function userByUsername($username){
        $data = static::where('username', $username)->first();
        return $data;
    }

    /**
     * @var Bol
     */
    public static function checkIfParent($id){
        $data = static::where(['account_type' => static::ACCOUNT_TYPE_PARENT, 'id' => $id])->first();
        if ($data != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     */
    public static function getAccountMeaning($acount){
        switch ($acount) {
            case static::ACCOUNT_TYPE_CREATOR:
                return 'Creator';
            case static::ACCOUNT_TYPE_PARENT:
                return 'Orangtua';
            case static::ACCOUNT_TYPE_TEACHER:
                return 'Guru';
            case static::ACCOUNT_TYPE_ADMIN:
                return 'Administrator';
            case static::ACCOUNT_TYPE_SISWA:
                return 'Siswa';
            default:
                return '';
        }
    }


    /**
     * @var Bol
     */
    public static function checkIfTeacher($id){
        $data = static::where(['account_type' => static::ACCOUNT_TYPE_TEACHER, 'id' => $id])->first();

        if ($data != null) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkIfSiswa($id){
        $data = static::where(['account_type' => static::ACCOUNT_TYPE_SISWA, 'id' => $id])->first();

        if ($data != null) {
            return true;
        } else {
            return false;
        }
    }

    public function hasParent(){
        return $this->hasMany('App\Model\SiswaHasParent\SiswaHasParent', 'parent_id', 'id');
    }

    public function hasClass(){
        return $this->belongsToMany(StudentClass::class, 'tbl_class_user', 'user_id', 'class_id');
    }

    public function hasTugas(){
        return $this->hasMany(Tugas::class);
    }

    /**
     * Sudah ada hash function maka tidak perlu dihash
     * @param $value
     */
    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token){
        $this->notify(new ResetPasswordNotification($token));
    }
}
