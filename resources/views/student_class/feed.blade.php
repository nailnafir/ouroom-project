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

    @foreach($data_feed as $df)
	<fieldset>
	<legend>Nama Kelas</legend>
    <form method="POST" action="{{ route('upload-feed') }}" class="upload-container" enctype="multipart/form-data">
        <div class="ui raised segment">
            <div class="top-attribute">
                <a class="ui red ribbon huge label">{{$df->kategori}}</a><span class="judul">Judul</span>
                <div class="ui red large label deadline">Deadline</div>
                <a class="ui top right attached huge label">
                    <span class="date-post">Date-Post</span>
                </a>
            </div>
            <p style="margin: 10px 0px">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident amet et illum nisi sequi esse odit labore facilis neque voluptatibus? Soluta quibusdam unde sunt quidem libero quis dolor perferendis minus?</p>
            <p style="margin: 10px 0px">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident amet et illum nisi sequi esse odit labore facilis neque voluptatibus? Soluta quibusdam unde sunt quidem libero quis dolor perferendis minus?</p>
            <div class="attached-files"><a href="{{ url('public/data_file'.'/'.$df->file) }}"></div>
            <hr>
            <label>Upload File</label>
            <div class="ui segments sfile">
                <input type="file" id="file" name="file[]">
            </div>
            <div class="ui bottom attached huge buttons">
                <button onclick="change()" class="ui button markbtn" id="mark" value="Belum Selesai">Tandai Selesai</button>
            </div>
        </div>
    </form>
    @endforeach
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