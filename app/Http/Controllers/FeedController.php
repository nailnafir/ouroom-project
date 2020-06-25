<?php

namespace App\Http\Controllers;

use App\Model\StudentClass\Feed;
use App\Model\StudentClass\StudentClass;
use Illuminate\Http\Request;
use App\Http\Requests\StudentClass\FeedRequest;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller {
    /**
     *
     */
    public function showClass(Request $request) {
        $data_feed = Feed::all();
        $class_id = $request->id;
        $feed_id = $request->id;
        $detail_kelas = array (
            'kelas' => StudentClass::find($class_id),
            'feed' => Feed::find($feed_id),
        );
        return view('student_class.list', ['active'=>'student_class', 'data_feed'=>$data_feed, 'detail_kelas'=>$detail_kelas]);
    }

    /**
     *
     */
    public function showFeed(Request $request) {
        return view('student_class.feed', ['active'=>'student_class']);
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
            'kategori' => 'required',
            'detail' => 'required',
            'file' => 'mimes:jped,jpg,png,pdf,word|max:2048',
        ]);
        $feed = new Feed();
        $feed->judul = $request->get('judul');
        $feed->kategori = $request->get('kategori');
        $feed->detail = $request->get('detail');
        $files = $request->file('file');
        $files_name = time().'_'.$files->getClientOriginalName();
        $files->move(public_path('data_file'), $files_name);
        $feed->file = $files_name;
        $feed->deadline = $request->get('deadline');
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
