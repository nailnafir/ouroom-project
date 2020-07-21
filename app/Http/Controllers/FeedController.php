<?php

namespace App\Http\Controllers;

use App\Model\StudentClass\Feed;
use App\Model\StudentClass\StudentClass;
use Illuminate\Http\Request;
use App\Http\Requests\StudentClass\FeedRequest;
use Illuminate\Support\Facades\Storage;
use DB;

class FeedController extends Controller {
    /**
     *
     */
    public function showClass(Request $request) {
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
        return view('student_class.list', ['active'=>'student_class', 'id_kelas'=>$id_kelas, 'nama_kelas'=>$nama_kelas, 'data_feed'=>$data_feed]);
    }

    /**
     *
     */
    public function showFeed(Request $request) {
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
        return view('student_class.feed', ['active'=>'student_class', 'id_kelas'=>$id_kelas, 'nama_kelas'=>$nama_kelas, 'feed'=>$feed, 'feed_title'=>$feed_title]);
    }

    public function showFeedData(Request $request) {
        $class_id = $request->id;
        $data_feed = Feed::all();
        return view('student_class.data_class', ['active'=>'student_class', 'data_feed'=>$data_feed, 'class_id'=>$class_id]);
    }

    public function showSiswaClass(Request $request) {
        $class_id = $request->id;
        $data_feed = Feed::all();
        return view('student_class.data_siswa', ['active'=>'student_class', 'data_feed'=>$data_feed, 'class_id'=>$class_id]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadFeed(Request $request) {
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
        $files_name = now().'_'.$files->getClientOriginalName();
        $files->move(public_path('data_file'), $files_name);
        $feed->file = $files_name;
        $feed->deadline = $request->get('deadline');
        $feed->class_id = $request->get('id_kelas');
        $feed->save();
        if(!$feed->save()) {
            return redirect()->back()->with('alert_error', 'Gagal Disimpan');
        } else {
            return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteFeed()
    {
        $data = Feed::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success', 'Data is successfully deleted');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function show(Feed $feed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function edit(Feed $feed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feed $feed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feed $feed)
    {
        //
    }
}
