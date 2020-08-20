<?php

namespace App\Http\Controllers;

use App\Model\User\User;
use App\Model\StudentClass\StudentClass;
use App\Model\StudentClass\Feed;
use App\Model\StudentClass\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StudentClass\UpdateTugasRequest;
use Illuminate\Support\Facades\Storage;
use Auth;
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
        $id_feed = DB::table('tbl_feed')
            ->where('judul', $feed_title)
            ->value('id');
        $data_tugas = DB::table('tbl_tugas')
            ->where('feed_id', $id_feed)
            ->get();
        $nilai_tugas = DB::table('tbl_tugas')
            ->where('feed_id', $id_feed)
            ->value('nilai');
        $tugas = DB::table('tbl_tugas')
            ->where('feed_id', $id_feed)
            ->value('file');
        return view('student_class.feed', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'feed' => $feed, 'feed_title' => $feed_title, 'data_tugas' => $data_tugas, 'tugas' => $tugas, 'nilai' => $nilai_tugas]);
    }

    public function showTugas(Request $request)
    {
        $class_name = $request->nama_kelas;
        $feed_title = $request->feed_title;
        $siswa_id = $request->siswa_id;
        $nama_kelas = DB::table('tbl_class')
            ->where('class_name', $class_name)
            ->value('class_name');
        $id_kelas = DB::table('tbl_class')
            ->where('class_name', $class_name)
            ->value('id');
        $id_feed = DB::table('tbl_feed')
            ->where('judul', $feed_title)
            ->value('id');
        $deadline = DB::table('tbl_feed')
            ->where('judul', $feed_title)
            ->value('deadline');
        $data_tugas = DB::table('tbl_tugas')
            ->where('siswa_id', $siswa_id)
            ->where('feed_id', $id_feed)
            ->get();
        return view('student_class.assessment', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'deadline' => $deadline, 'feed_title' => $feed_title, 'siswa_id' => $siswa_id, 'data_tugas' => $data_tugas]);
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

    public function deleteSiswaClass($id)
    {
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

        if ($request->has('file')) {
            $id = $request->id_kelas;
            $class_name = StudentClass::where('id', '=', $id)->value('class_name');
            $feed = new Feed();
            $feed->judul = $request->get('judul');
            $feed->kategori = $request->get('kategori');
            $feed->detail = $request->get('detail');
            $files = $request->file('file');
            $path = public_path($class_name . '/' . $feed->judul);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $files_name = $files->getClientOriginalName();
            $files->move($path, $files_name);
            $feed->file = $files_name;
            $feed->deadline = $request->get('deadline');
            $feed->class_id = $request->get('id_kelas');
        } else {
            $feed = new Feed();
            $feed->judul = $request->get('judul');
            $feed->kategori = $request->get('kategori');
            $feed->detail = $request->get('detail');
            $feed->deadline = $request->get('deadline');
            $feed->class_id = $request->get('id_kelas');
        }
        $feed->save();
        if (!$feed->save()) {
            return redirect()->back()->with('alert_error', 'Gagal Disimpan');
        } else {
            return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
        }
    }

    public function uploadTugas(Request $request)
    {
        $this->validate($request, [
            'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048',
        ]);

        $id_siswa = Auth::id();
        $nama_siswa = User::where('id', '=', $id_siswa)
            ->value('full_name');
        $nama_kelas = $request->nama_kelas;
        $nama_feed = $request->nama_feed;
        $id_class = StudentClass::where('class_name', '=', $nama_kelas)
            ->value('id');
        $id_feed = Feed::where('judul', '=', $nama_feed)
            ->value('id');
        $tugas = new Tugas();
        $files = $request->file('file');
        $path = public_path($nama_kelas . '/' . $nama_feed . '/' . $nama_siswa);
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $files_name = now() . '_' . $files->getClientOriginalName();
        $files->move($path, $files_name);
        $tugas->file = $files_name;
        $tugas->siswa_id = $id_siswa;
        $tugas->class_id = $id_class;
        $tugas->feed_id = $id_feed;
        $tugas->save();
        if (!$tugas->save()) {
            return redirect()->back()->with('alert_error', 'Gagal Disimpan');
        } else {
            return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
        }
    }

    public function updateTugas(UpdateTugasRequest $request)
    {
        $this->validate($request, [
            'nilai' => 'integer',
        ]);

        $siswa_id = $request->get('siswa_id');
        $tugas_id = DB::table('tbl_tugas')
            ->where('siswa_id', $siswa_id)
            ->value('id');
        DB::table('tbl_tugas')->where('id', $tugas_id)->update([
            'nilai' => $request->nilai
        ]);
        return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
    }

    public function deleteFeed()
    {
        $data = Feed::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success', 'Data is successfully deleted');
    }
}
