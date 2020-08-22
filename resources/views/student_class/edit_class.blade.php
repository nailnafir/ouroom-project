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
        <form method="POST" action="{{ route('update-kelas', ['id_kelas'=>$id_kelas]) }}" class="upload-container">

			@csrf
            @foreach($data_kelas as $dk)
			<div id="customSegments" class="ui raised segments">
				<div class="ui segment">
                    <div class="top-attribute">
                        <a class="ui blue ribbon huge label">Atribut Kelas</a>
                        <span class="token">{{($dk->token)}}</span>
                    </div>
				</div>
				<div class="upload">
                    <input type="hidden" name="id_kelas" value="{{ $dk->id }}">
					<label>Nama Kelas</label>
                    <input type="text" class="form-control" id="judul" name="class_name" value="{{$dk->class_name}}">
                    <label>Angkatan</label>
                    <select type="text" class="form-control" id="judul" name="angkatan">
                        <option selected="true" disabled="disabled">{{$dk->angkatan}}</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}"> {{ $year }} </option>
                        @endforeach
                    </select>
                    <label>Jurusan</label>
                    <select id="jurusan" class="form-control" name="jurusan">
                        <option selected="true" disabled="disabled">{{$dk->jurusan}}</option> 
                        <option value="Pemasaran">Pemasaran</option>
                        <option value="Pariwisata">Pariwisata</option>
                        <option value="Peternakan">Peternakan</option>
                    </select>
                    <label>Catatan</label>
					<input type="text" class="form-control" id="note" name="note" value="{{$dk->catatan}}">

					<input type="hidden" name="id_kelas" value="">

				</div>
				<div class="ui segments">
                    <div class="ui two buttons">
                        <button type="submit" class="ui right attached huge primary button btn-bottom-right">
                            UPDATE
                        </button>
                        <a href="" type="button" class="ui left attached huge button btn-bottom-left">
                            HAPUS
                        </a>
                    </div>
				</div>
            </div>
            @endforeach
		</form>
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
                <tbody>
                </tbody>
            </table>
        </div>
@endsection

@section('modal')

@endsection

@push('scripts')
    <script type="text/javascript">
        var iduser;
        var table;

        function clearAll() {
            $('#nama').val('');
            $('#jurusan').val('');
            $('#angkatan').val('');
        }

        $(function() {
            table = $('#siswa_table').DataTable({
                processing: true,
                serverSide: true,
                rowReorder: {
                selector: 'td:nth-child(2)'
                },
                responsive: true,
                ajax: "{{ route('edit-class', ['id_kelas'=>$id_kelas]) }}",
                columns: [{
                    data: 'full_name',
                    name: 'nama'
                },
                {
                    data: 'jurusan',
                    name: 'jurusan'
                },
                {
                    data: 'angkatan',
                    name: 'angkatan'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
                ]
            });
        });

        function btnDel(id) {
        iduser = id;
        idkelas = "{{ route('delete-siswa', ['id_kelas'=>$id_kelas]) }}";
        swal({
            title: "Hapus User",
            text: 'Akan mengeluarkan Siswa ini dari kelas.',
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                type: 'POST',
                url: idkelas,
                data: {
                    iduser: iduser,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.status != false) {
                    swal(data.message, {
                        button: false,
                        icon: "success",
                        timer: 1000
                    });
                    } else {
                    swal(data.message, {
                        button: false,
                        icon: "error",
                        timer: 1000
                    });
                    }
                    table.ajax.reload();
                },
                error: function(error) {
                    swal('Terjadi kegagalan sistem', {
                    button: false,
                    icon: "error",
                    timer: 1000
                    });
                }
                });
            }
            });
        }
    </script>
@endpush