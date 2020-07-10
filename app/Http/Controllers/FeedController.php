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
        $class_id = $request->id;
        $data_feed = DB::table('tbl_feed')
            ->select('*')
            ->where('class_id', $class_id)
            ->get();
        $nama_kelas = DB::table('tbl_class')
            ->where('id', $class_id)
            ->value('class_name');
        return view('student_class.list', ['active'=>'student_class', 'class_id'=>$class_id, 'nama_kelas'=>$nama_kelas, 'data_feed'=>$data_feed]);
    }

    /**
     *
     */
    public function showFeed(Request $request) {
        $data_feed = Feed::all();
        return view('student_class.feed', ['active'=>'student_class', 'data_feed'=>$data_feed]);
    }

    public function showFeedData(Request $request) {
        $data_feed = Feed::all();
        return view('student_class.data_class', ['active'=>'student_class', 'data_feed'=>$data_feed]);
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
