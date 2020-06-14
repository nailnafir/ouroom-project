@extends('master')
 
@section('title', '')

@section('alert')

@endsection
 
@section('content')

	<?php 
		use Yajra\Datatables\Datatables; 
		use App\Model\User\User;

		// get user auth
		$user = Auth::user();
	?>

	<fieldset>
	<legend>Overview</legend>

	<!-- User Type Guru -->
	@if($user->account_type == User::ACCOUNT_TYPE_TEACHER)
		<div class="col-md-6">
			<div class="card">
				<div class="header">   
					<p style="text-align: center; font-weight: bold;"> Total Kelas </p>
				</div>
				<div class="content">
					<h3 style="text-align: center;"> {{ $class }} </h3>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="header">   
					<p style="text-align: center; font-weight: bold;"> Total Siswa </p>
				</div>
				<div class="content">
					<h3 style="text-align: center;"> {{ $siswa }} </h3>
				</div>
			</div>
		</div>
	@endif


	<!-- User Type Creator / Admin -->
	@if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN)
		<div class="col-md-4">
			<div class="card">
				<div class="header">   
					<p style="text-align: center; font-weight: bold;"> Total Kelas </p>
				</div>
				<div class="content">
					<h3 style="text-align: center;"> {{ $class }} </h3>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card">
				<div class="header">   
					<p style="text-align: center; font-weight: bold;"> Total Siswa </p>
				</div>
				<div class="content">
				<h3 style="text-align: center;"> {{ $siswa }} </h3>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card">
				<div class="header">   
					<p style="text-align: center; font-weight: bold;"> Total Guru </p>
				</div>
				<div class="content">
				<h3 style="text-align: center;"> {{ $teacher }} </h3>
				</div>
			</div>
		</div>
	@endif

	<!-- User Type Siswa -->
	@if($user->account_type == User::ACCOUNT_TYPE_SISWA)
		<h1>Hi :)</h1>
	@endif

	</fieldset>

	<hr>

	<fieldset>
		<legend>Informasi User</legend>
		<div class="form-group">
			<label>Tipe User</label>
			<input disabled="true" type="text" class="form-control" value="{{ User::getAccountMeaning(Auth::user()->account_type) }}" name="user_type">
		</div>
		<div class="form-group">
			<label>Terakhir Login</label>
			<input disabled="true" type="text" class="form-control" value="{{ $last_login }}" name="last_login">
		</div>
	</fieldset>

@endsection