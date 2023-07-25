@extends('layouts.app')
@section('title')
List Users
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>Session</span>
                  <div class="d-flex align-items-center my-2">
                    <h3 class="mb-0 me-2">21,459</h3>
                    <p class="text-success mb-0">(+29%)</p>
                  </div>
                  <p class="mb-0">Total Users</p>
                </div>
                <div class="avatar">
                  <span class="avatar-initial rounded bg-label-primary">
                    <i class="ti ti-user ti-sm"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>Paid Users</span>
                  <div class="d-flex align-items-center my-2">
                    <h3 class="mb-0 me-2">4,567</h3>
                    <p class="text-success mb-0">(+18%)</p>
                  </div>
                  <p class="mb-0">Last week analytics </p>
                </div>
                <div class="avatar">
                  <span class="avatar-initial rounded bg-label-danger">
                    <i class="ti ti-user-plus ti-sm"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>Active Users</span>
                  <div class="d-flex align-items-center my-2">
                    <h3 class="mb-0 me-2">19,860</h3>
                    <p class="text-danger mb-0">(-14%)</p>
                  </div>
                  <p class="mb-0">Last week analytics</p>
                </div>
                <div class="avatar">
                  <span class="avatar-initial rounded bg-label-success">
                    <i class="ti ti-user-check ti-sm"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>Pending Users</span>
                  <div class="d-flex align-items-center my-2">
                    <h3 class="mb-0 me-2">237</h3>
                    <p class="text-success mb-0">(+42%)</p>
                  </div>
                  <p class="mb-0">Last week analytics</p>
                </div>
                <div class="avatar">
                  <span class="avatar-initial rounded bg-label-warning">
                    <i class="ti ti-user-exclamation ti-sm"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex">
            <h5 class="align-self-center m-0">Data Pengguna</h5>
            @if(!empty($menuAccessCreate))
            <a href="{{ route('add-user') }}" class="btn btn-primary ms-auto"><i class="fa fa-plus"></i> &NonBreakingSpace;Tambah User</a>
            @endif
        </div>
        <hr class="mt-0">
        <div class="card-datatable">
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
            <table class="dt-multilingual table display nowarp" id="get-user">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Nip</th>
                        <th>Nomor Handphone</th>
                        <th>Unit</th>
                        @if(!empty($menuAccessEdit) || !empty($menuAccessDelete))
                            <th>Aksi</th>
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
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/tables/datatable/rowReorder.dataTables.min.css') }}" />
<style>
    @media screen and (max-width: 767px){
        #get-user_filter label input{
            min-width:232px!important;
            max-width:341px!important;
        }
        #get-user_length select{
            min-width:186px!important;
            max-width:296px!important;
        }
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
    $(function() {
        var table = $('#get-user').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('get-users') !!}',
            columns: [
                { data: 'full_name' },
                { data: 'email' },
                { data: 'nip' },
                { data: 'phone_number' },
                { 
                    data: 'unit',
                    searchable: false
                },
                {
                    data: 'id',
                    orderable: false,
                    render: function(id) {
                        return '@if(!empty($menuAccessEdit))<button class="my-1 btn btn-warning btn-sm detail-user"  style="display: inline-block;" data-id="' + id + '"><i class="ti ti-list me-1"></i> Detail</button>@endif @if(!empty($menuAccessDelete)) <button class="my-1 btn btn-danger btn-sm delete-post" style="display: inline-block;" data-id="' + id + '"><i class="ti ti-trash me-1"></i> Hapus</button>@endif';
                    },
                },
            ],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true
        });

        $('#get-user').on('click', '.detail-user', function() {
            var id = $(this).data('id');
            window.location = '/data-pengguna/detail/' + id
        });

        $('#get-user').on('click', '.delete-post', function() {
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
                        url: '{{ route('delete-user', ':id') }}'.replace(':id', id),
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
                                    $('#get-user').DataTable().ajax.reload();
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

        $('#get-user_length, #get-user_filter').addClass('d-none');

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