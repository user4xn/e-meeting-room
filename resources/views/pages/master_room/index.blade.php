@extends('layouts.app')
@section('title')
Master Ruangan
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-datatable">
            <div class="col-md-12 p-3">
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-4 col-sm-12 my-1 d-flex">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="custom-row">Baris</label>
                            <select class="form-select" id="custom-row">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <button class="btn btn-primary btn-toggle-sidebar" onclick="resetForm()" id="addMasterRoom" data-bs-toggle="offcanvas" data-bs-target="#addRoom" aria-controls="addRoom">
                                <i class="ti ti-plus me-1"></i>
                                <span class="align-middle">Tambah Ruangan</span>
                            </button>
                        </div>
                    </div>
                    <div class="px-0 col-lg-4 col-md-8 col-sm-12 my-1">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" id="custom-search" class="form-control" placeholder="Cari..." aria-label="Cari..." />
                        </div>
                    </div>
                </div>
            </div>
            <table class="dt-multilingual table display nowarp" id="get-master-rooms">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NAMA RUANGAN</th>
                        <th>DESKRIPSI</th>
                        <th>KAPASITAS</th>
                        <th>LOKASI</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="card app-calendar-wrapper">
        <div class="row g-0">
            <div class="col app-calendar-sidebar" id="app-calendar-sidebar">
                <div class="border-bottom p-4 my-sm-0 mb-3">
                    <div class="d-grid">
                        
                    </div>
                </div>
            </div>

            @include('pages.master_room.add')
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/tables/datatable/responsive.dataTables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/tables/datatable/rowReorder.dataTables.min.css') }}" />
<style>
    .room-number-counter span {
        cursor: pointer;
    }

    .room-number-counter input {
        height: 34px;
        width: 100px;
        text-align: center;
        /* font-size: 26px; */
        border: 1px solid #ddd;
        border-radius: 4px;
        display: inline-block;
        vertical-align: middle;
    }
</style>
@endsection
@section('scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script>
    function resetForm() {
        var form = document.getElementById('eventForm');
        form.reset();
    }
</script>
<script>
    $(function() {
        var table = $('#get-master-rooms').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('master-room.datatable') !!}',
            columns: [
                {data: 'no'},
                {data: 'room_name'},
                {data: 'room_description'},
                {data: 'room_capacity'},
                {data: 'room_location'},
                {
                    data: 'id',
                    render: function(id) {
                        return '<button class="my-1 btn btn-primary btn-sm edit-master-room" data-bs-toggle="offcanvas" data-bs-target="#addRoom" aria-controls="addRoom" style="display: inline-block;" data-id="' + id + '"><i class="ti ti-edit me-1"></i> Edit</button> <button class="my-1 btn btn-danger btn-sm delete-master-room" style="display: inline-block;" data-id="' + id + '"><i class="ti ti-trash me-1"></i> Hapus</button>';
                    },
                },
            ],
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true,
        });

        $('#get-master-rooms_length, #get-master-rooms_filter').addClass('d-none');

        $('#custom-search').on('keyup change', function() {
          var val = $(this).val();
          table.search(val).draw();
        });

        $('#custom-row').on('change', function() {
          var val = $(this).val();
          table.page.len(val).draw();
        });
    });
</script>
<script>
    $('body').on('click', '.edit-master-room', function () {
        var master_room_id = $(this).data('id');
        $.get("{{ route('master-room') }}" +'/edit/' + master_room_id , function (data) {
            $('#addEventSidebarLabel').html("Edit Master Ruangan");
            $('#submitEventBtn').html("Edit");
            $('#master_room_id').val(data.id);
            $('#room_name').val(data.room_name);
            $('#room_location').val(data.room_location);
            $('#room_description').val(data.room_description);
            $('#room_capacity').val(data.room_capacity);
        })
    });
</script>
<script>
    $(document).ready(function() {
        $('.minus').click(function() {
            var $input = $(this).parent().find('input');
            var count = parseInt($input.val()) - 1;
            count = count < 1 ? 1 : count;
            $input.val(count);
            $input.change();
            return false;
        });
        $('.plus').click(function() {
            var $input = $(this).parent().find('input');
            $input.val(parseInt($input.val()) + 1);
            $input.change();
            return false;
        });
    });
</script>
<script>
    $('#get-master-rooms').on('click', '.delete-master-room', function() {
        var id = $(this).data('id');

        Swal.fire({
            text: 'Apakah kamu yakin mau menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('master-room.destroy', ':id') }}'.replace(':id', id),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    statusCode:{
                        200:function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Data kamu berhasil di hapus.',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then(function() {
                                $('#get-satker').DataTable().ajax.reload();
                            });
                        },
                        400:function(response){
                            Swal.fire({
                                title: 'Error!',
                                text: 'Maaf data ini tidak bisa dihapus!',
                                icon: 'error',
                                customClass: {
                                confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        },
                        500:function(response){
                            Swal.fire({
                                title: 'Error!',
                                text: 'Maaf ada kesalahan internal!',
                                icon: 'error',
                                customClass: {
                                confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    }
                });
            }
        });
    });
</script>

@endsection