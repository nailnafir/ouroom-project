<?php

namespace App\Http\Controllers;

use App\Model\User\User;
use App\Model\StudentClass\StudentClass;
use App\Model\StudentClass\Feed;
use Illuminate\Http\Request;
use App\Http\Requests\StudentClass\FeedRequest;
use Illuminate\Support\Facades\Storage;
use DB;

class FeedController extends Controller
{
    public function showClass(Request $request)
    {
        $class_name = $request->nama_kelas;
        $data_feed = DB::table('tbl_feed')
            ->join('tbl_class', 'tbl_feed.class_id', '=', 'tbl_class.id')
            ->where('class_name', $class_name)
            ->get();
        $nama_kelas = DB::table('tbl_class')
            ->where('class_name', $class_name)
            ->value('class_name');
        $id_kelas = DB::table('tbl_class')
            ->where('class_name', $class_name)
            ->value('id');
        return view('student_class.list', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'data_feed' => $data_feed]);
    }

    public function showFeed(Request $request)
    {
        $class_name = $request->nama_kelas;
        $feed_title = $request->feed_title;
        $nama_kelas = DB::table('tbl_class')
            ->where('class_name', $class_name)
            ->value('class_name');
        $id_kelas = DB::table('tbl_class')
            ->where('class_name', $class_name)
            ->value('id');
        $feed = DB::table('tbl_feed')
            ->where('judul', $feed_title)
            ->select('*')
            ->get();
        // dd($feed);
        return view('student_class.feed', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'feed' => $feed, 'feed_title' => $feed_title]);
    }

    public function showSiswaClass(Request $request)
    {
        $class_name = $request->nama_kelas;
        $id_kelas = StudentClass::where('class_name', $class_name)->value('id');
        $data_siswa = StudentClass::where('id', '=', $id_kelas)
            ->with('hasUser')
            ->get();
        if ($this->getUserPermission('index class')) {
            return view('student_class.data_siswa', ['active' => 'student_class', 'data_siswa' => $data_siswa]);
        } else {
            return view('error.unauthorized', ['active' => 'student_class']);
        }
    }

    public function deleteSiswaClass($id) {
        DB::table('tbl_user')->where('id', $id)->delete();
        return redirect()->back()->with('alert_success', 'Data Berhasil Hapus');
    }

    public function uploadFeed(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'kategori' => 'required',
            'detail' => 'required',
            'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048',
        ]);
        $feed = new Feed();
        $feed->judul = $request->get('judul');
        $feed->kategori = $request->get('kategori');
        $feed->detail = $request->get('detail');
        $files = $request->file('file');
        $files_name = now() . '_' . $files->getClientOriginalName();
        $files->move(public_path('data_file'), $files_name);
        $feed->file = $files_name;
        $feed->deadline = $request->get('deadline');
        $feed->class_id = $request->get('id_kelas');
        $feed->save();
        if (!$feed->save()) {
            return redirect()->back()->with('alert_error', 'Gagal Disimpan');
        } else {
            return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
        }
    }

    public function deleteFeed()
    {
        $data = Feed::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success', 'Data is successfully deleted');
    }
}
