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
	
    @endif
	<fieldset>
    @foreach($feed as $f)
        <legend>{{ $f->judul }}</legend>
        <form method="POST" action="{{ route('upload-feed') }}" class="upload-container" enctype="multipart/form-data">
            <div id="customSegments" class="ui raised segment">
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
                    <div class="ui red large label deadline">{{ $f->deadline }}</div>
                    <a class="ui top right attached huge label">
                        <span class="date-post">{{ $f->created_at }}</span>
                    </a>
                </div>
                <pre class="detail-section2">{{ $f->detail }}</pre>
                <div class="ui blue segment">
                    <h5> 
                        <a href="{{ url('/data_file'.'/'.$f->file) }}">
                            <img height"100" width="100" src="{{ url('/data_file'.'/'.$f->file) }}" target="_blank"> {{ $f->file }} </img>
                        </a>
                    </h5>
                </div>
                <div class="attached-files"><a href="{{ url('public/data_file'.'/'.$f->file) }}"></div>
                @if($user->account_type == User::ACCOUNT_TYPE_SISWA)
                    <hr>
                    <label>Upload File</label>
                    <div class="ui segments sfile">
                        <input type="file" id="file" name="file[]">
                    </div>

                    <div class="ui bottom attached huge buttons">
                        <button onclick="change()" class="ui button markbtn" id="mark" value="Belum Selesai">Tandai Selesai</button>
                    </div>
                @endif
                
            </div>
        </form>
    @endforeach
    </fieldset>
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
            <tbody>
            </tbody>
        </table>
    </div>
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