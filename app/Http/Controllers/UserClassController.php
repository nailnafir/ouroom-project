<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Model\User\User;
use App\Model\StudentClass\StudentClass;
use Auth;
use DB;

class UserClassController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function join() {
        return view('student_class.join', ['active'=>'student_class']);
    }

    public function joinClass(Request $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user_id = User::findOrFail(Auth::user()->id);
        DB::beginTransaction();
        
        $user->username = $request->get('username');
        $data_siswa = User::findOrFail($request->get('username'));
        $data_siswa->token = $request->get('token');
        $data_siswa->save();
        $data_kelas = DB::table('tbl_class')
            ->join('tbl_user', 'tbl_class.teacher_id', '=', 'tbl_user.id')
            // ->where('username', $user)
            ->select('tbl_class.*', 'tbl_user.*')
            ->get();
        dd($data_kelas);
        if(!$data_siswa->save()) {
            return redirect()->back()->with('alert_error', 'Token Tidak Terdaftar');
        } else {
            return redirect('student-class', ['active'=>'student_class', 'data_kelas'=>$data_kelas])->with('alert_success', 'Berhasil Join Kelas');
        }
    }
}
