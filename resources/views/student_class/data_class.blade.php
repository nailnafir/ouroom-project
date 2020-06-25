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
    <legend>Nama Kelas</legend>
    @foreach ($data_feed as $df)
		<div class="ui raised segment">
			<div class="top-attribute">
				@if($df->kategori == 'Artikel')
					<a class="ui green ribbon huge label">{{$df->kategori}}
				@endif
				@if($df->kategori == 'Tugas')
					<a class="ui orange ribbon huge label">{{$df->kategori}}
				@endif
				@if($df->kategori == 'Ujian')
					<a class="ui red ribbon huge label">{{$df->kategori}}
				@endif
				</a><span class="judul">{{$df->judul}}</span>
				<div class="ui red large label deadline">{{$df->deadline}}</div>
				<a class="ui top right attached huge image label">
					<span class="date-post">{{$df->created_at}}</span>
				</a>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered data-table display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th style="text-align: center">Nama</th>
                        <th style="text-align: center">Jurusan</th>
                        <th style="text-align: center">Angkatan</th>
                        <th style="text-align: center">Tugas</th>
                        <th style="text-align: center" width="90px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
		</div>
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