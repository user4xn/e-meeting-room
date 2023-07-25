@extends('layouts.app')
@section('title')
Roles
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex">
            <h5 class="align-self-center m-0">Data Role</h5>
            @if(!empty($menuAccessCreate))
                <a href="{{ route('add-role') }}" class="btn btn-primary ms-auto"><i class="fa fa-plus"></i> &NonBreakingSpace;Tambah Role</a>
            @endif
        </div>
        <hr class="mt-0">
        <div class="card-datatable table-responsive">
            <div class="col-md-12">
            <div class="row d-flex justify-content-between">
              <div class="px-0 col-xl-2 col-lg-3 col-md-4 col-sm-12 my-1  align-items-center d-flex justify-content-between">
                <div class="input-group mb-3">
                  <label class="input-group-text" for="custom-row">Baris</label>
                  <select class="form-select" id="custom-row">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select>
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
            <table class="dt-advanced-search table" id="get-roles">
                <thead>
                    <tr>
                        <th>Type Role</th>
                        <th>Unit Role</th>
                        @if(!empty($menuAccessEdit) || !empty($menuAccessDelete))
                            <th width="220">Aksi</th>
                        @else
                            <th></th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/tables/datatable/responsive.dataTables.min.css') }}" />
<link rel="stylesheet" href="../../assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
@endsection

@section('scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script>
    $(function() {
        var table = $('#get-roles').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatable-roles') !!}',
            columns: [
                { data: 'type'},
                { data: 'unit'},
                { 
                    data: 'id',
                    orderable: false,
                    render: function(id) {
                        return '@if(!empty($menuAccessEdit))<button class="my-1 btn btn-primary btn-sm edit-role"  style="display: inline-block;" data-id="' + id + '"><i class="ti ti-edit me-1"></i> Edit</button>@endif @if(!empty($menuAccessDelete)) <button class="my-1 btn btn-danger btn-sm delete-role" style="display: inline-block;" data-id="' + id + '"><i class="ti ti-trash me-1"></i> Hapus</button>@endif';
                    },
                },
            ],
            responsive: true
        });

        $('#get-roles').on('click', '.edit-role', function() {
            var id = $(this).data('id');
            window.location = '/role/edit/' + id
        });

        $('#get-roles').on('click', '.delete-role', function() {
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
                        url: '{{ route('delete-role', ':id') }}'.replace(':id', id),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        statusCode:{
                            200:function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then(function() {
                                    $('#get-roles').DataTable().ajax.reload();
                                });
                            },
                            400:function(response){
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.responseJSON.message,
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
                                    text: response.responseJSON.message,
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

        $('#get-roles_length, #get-roles_filter').addClass('d-none');

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
@endsection