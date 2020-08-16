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

<div class="table-responsive">
    <table id="siswa_table" class="table table-bordered data-table display nowrap" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: center">Nama</th>
                <th style="text-align: center">Jurusan</th>
                <th style="text-align: center">Angkatan</th>
                <th style="text-align: center" width="90px">Action</th>
            </tr>
        </thead>
        @foreach($data_siswa as $ds)
            @foreach($ds->hasUser as $u)
                <tbody>
                    <tr>
                        <td>{{$u->full_name}}</td>
                        <td>{{$u->jurusan}}</td>
                        <td>{{$u->angkatan}}</td>
                        <td style="text-align: center" width="90px">
                            <a href="/delete/{{ $u->id }}" class="btn btn-info"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                </tbody>
            @endforeach
        @endforeach
    </table>
</div>

@endsection

@push('scripts')

@endpush