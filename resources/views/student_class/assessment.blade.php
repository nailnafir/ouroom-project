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

    <form method="post" action="{{ route('update-tugas', ['nama_kelas'=>$nama_kelas, 'feed_title'=>$feed_title, 'siswa_id'=>$siswa_id]) }}">

        @csrf

        <div class="form-group">
            <label>Kelas</label>
            <input type="text" class="form-control" value="{{ $nama_kelas }}" name="nama_kelas" disabled>
        </div>

        <div class="form-group">
            <label>Feed</label>
            <input type="text" class="form-control" value="{{ $feed_title }}" name="feed_title" disabled>
        </div>

        <div class="form-group">
            <label>Deadline</label>
            <input type="text" class="form-control" value="{{ $deadline }}" name="deadline" disabled>
        </div>
        <hr>

        @foreach($data_tugas as $dt)
            <div class="form-group">
                <input type="hidden" class="form-control" value="{{ $dt->id }}" name="id_tugas">
            </div>
            <div class="ui blue segment">
                <h5>
                    <a href="#" target="_blank">
                        <img height"100" width="100" src="{{ url('/data_file'.'/'.$dt->file) }}"> {{ $dt->file }} </img>
                    </a>
                </h5>
            </div>
            <div class="form-group">
                <label>Nama Siswa</label>
                <input type="text" class="form-control" value="{{User::where('id', $dt->siswa_id)->value('full_name')}}" name="nama_siswa" disabled>
            </div>

            <div class="form-group">
                <label>Tanggal Upload</label>
                <input type="text" class="form-control" value="{{ $dt->created_at }}" name="created_at" disabled>
            </div>

            <div class="form-group">
                <label>Nilai</label>
                <input type="text" class="form-control" value="{{$dt->nilai}}" name="nilai" min="0" max="100" placeholder="0-100">
                @if ($errors->has('nilai'))
                    <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('nilai') }}</p></div>
                @endif
            </div>
        @endforeach

        <div class="form-group">
            <button type="submit" class="btn btn-info"> NILAI TUGAS </button>
            <a href="{{ route('class-feed', ['nama_kelas'=>$nama_kelas, 'feed_title'=>$feed_title]) }}" class="btn btn-info" style="float:right"> KEMBALI </a>
        </div>

    </form>
@endsection

@section('modal')

@endsection

@push ('scripts')
<script type="text/javascript">
</script>
@endpush