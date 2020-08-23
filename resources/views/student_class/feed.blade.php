@extends('master')

@section('title', '')

@section('alert')
    @if(Session::has('alert_success'))
        @component('components.alert')
            @slot('class')
                success
            @endslot
            @slot('title')
                Terimakasih
            @endslot
            @slot('message')
                {{ session('alert_success') }}
            @endslot
        @endcomponent
        @elseif(Session::has('alert_error'))
        @component('components.alert')
                @slot('class')
                    error
                @endslot
                @slot('title')
                    Cek Kembali
                @endslot
                @slot('message')
                    {{ session('alert_error') }}
                @endslot
        @endcomponent 
    @endif
@endsection

@section('content')
	<?php 
		use Yajra\Datatables\Datatables; 
        use App\Model\User\User;
        use Carbon\Carbon;

		// get user auth
		$user = Auth::user();
	?>
    @if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN || $user->account_type == User::ACCOUNT_TYPE_TEACHER)
    <form method="POST" action="{{ route('update-feed', ['id_kelas'=>$id_kelas, 'id_feed'=>$id_feed]) }}" class="upload-container" enctype="multipart/form-data">

        @csrf
        @foreach($feed as $f)
        <div id="customSegments" class="ui raised segments">
            <div class="ui segment">
                <a class="ui blue ribbon huge label">Edit Feed</a>
            </div>
            <div class="upload">
                <label>Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" value="{{$f->judul}}">

                <input type="hidden" name="id_kelas" value="{{ $id_kelas }}">

                <label>Kategori</label>
                <select class="form-control" name="kategori">
                    <option selected="true" disabled="disabled" value="{{$f->kategori}}">{{$f->kategori}}</option> 
                    <option value="Artikel">Artikel</option>
                    <option value="Tugas">Tugas</option>
                    <option value="Ujian">Ujian</option>
                </select>
            </div>
            <div class="ui segments">
                <textarea class="form-control" placeholder="Detail" rows="10" id="detail" name="detail">{{$f->detail}}</textarea>
            </div>
            <div class="upload">
                <label>Upload File</label>
                <input type="file" class="form-control" id="file" name="file">

                <label>Tenggat Waktu</label>
                <input type="date" min="2020-01-01" class="form-control" id="deadline" name="deadline">
            </div>
            <div class="ui segments">
                <div class="ui two buttons">
                    <button type="submit" class="ui right attached huge primary button btn-bottom-right">
                        UPDATE
                    </button>
                    <a href="/delete/{{$id_kelas}}/{{$f->id}}" type="button" class="ui left attached huge button btn-bottom-left">
                        HAPUS
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </form>
    @endif
	<fieldset>
    @foreach($feed as $f)
        <legend>{{ $f->judul }}</legend>
        <form method="POST" action="{{ route('upload-tugas') }}" class="upload-container" enctype="multipart/form-data">

        @csrf

            <div id="customSegments" class="ui raised segment">
                <input type="hidden" name="nama_kelas" value="{{$nama_kelas}}">
                <input type="hidden" name="nama_feed" value="{{$f->judul}}">
                <div class="top-attribute">
                    @if($f->kategori == 'Artikel')
                        <div class="ui green ribbon huge label">{{$f->kategori}}</div>
                    @endif
                    @if($f->kategori == 'Tugas')
                        <div class="ui orange ribbon huge label">{{$f->kategori}}</div>
                    @endif
                    @if($f->kategori == 'Ujian')
                        <div class="ui red ribbon huge label">{{$f->kategori}}</div>
                    @endif
                    @if($user->account_type == User::ACCOUNT_TYPE_SISWA)
                        <span class="judul" style="font-weight:bold">NILAI : {{ $nilai }}</span>
                    @endif
                    <div class="ui red large label deadline">{{ $f->deadline }}</div>
                    <a class="ui top right attached huge label">
                        <span class="date-post">{{ $f->created_at }}</span>
                    </a>
                </div>
                <pre class="detail-section2">{{ $f->detail }}</pre>
                @if($f->file != null)
                    <div class="ui blue segment">
                        <h5>
                            <a href="{{ url($nama_kelas.'/'.$f->judul.'/'.$f->file) }}" target="_blank">
                                <img height"80" width="80" src="{{ asset('asset/file_thumb.png') }}"> {{ $f->file }} </img>
                            </a>
                        </h5>
                    </div>
                @endif
                <div class="attached-files"><a href="{{ url('public/data_file'.'/'.$f->file) }}"></a></div>
                @if($user->account_type == User::ACCOUNT_TYPE_SISWA && $tugas == null)
                    <hr style="border-top: 1px solid #c6c6c6">
                    <label>Upload File</label>
                    <div class="ui segments sfile">
                        <input type="file" id="file" name="file">
                    </div>

                    <div class="ui bottom attached huge buttons">
                        <button type="submit" onclick="change()" class="ui button markbtn" id="mark" value="Belum Selesai">Tandai Selesai</button>
                    </div>
                @endif
            </div>
        </form>
    @endforeach
    </fieldset>
    @if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN || $user->account_type == User::ACCOUNT_TYPE_TEACHER)
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered data-table display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th style="text-align: center">Nama</th>
                        <th style="text-align: center">Tugas</th>
                        <th style="text-align: center">Tanggal</th>
                        <th style="text-align: center">Nilai</th>
                        <th style="text-align: center" width="90px">Action</th>
                    </tr>
                </thead>
                @foreach($data_tugas as $dt)
                    <tbody>
                        <tr>
                            <td>{{User::where('id', $dt->siswa_id)->value('full_name')}}</td>
                            <td>{{$dt->file}}</td>
                            <td>{{$dt->created_at}}</td>
                            <td>{{$dt->nilai}}</td>
                            <td style="text-align: center" width="90px">
                                <a href="{{ route('show-tugas', ['id_kelas'=>$id_kelas, 'feed_title'=>$feed_title, 'siswa_id'=>$dt->siswa_id]) }}" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    @endif
@endsection

@section('modal')

@endsection

@push ('scripts')
<script type="text/javascript">
    function change() {
        if (confirm('Tandai Selesai Tugas Ini ?')) {
            var btn = document.getElementById("mark")
            btn.value = 'Selesai';
            btn.innerHTML = 'Selesai';
            btn.style.background = '#c0c1c2';
        } else {
            console.log('Thing was not saved to the database.');
        }
    }
</script>
@endpush