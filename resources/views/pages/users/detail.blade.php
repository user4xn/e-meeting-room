@extends('layouts.app')
@section('title')
Detail User
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">

        <div class="card-header d-flex">
            <h5 class="align-self-center m-0"><a href="{{route('users')}}" class="btn btn-primary"><i class="fa fa-chevron-left"></i></a> &NonBreakingSpace;Detail Pengguna</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())

            @foreach ($errors->all() as $error)
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <span class="alert-icon text-danger me-2">
                    <i class="ti ti-ban ti-xs"></i>
                </span>
                {{ $error }}
            </div>
            @endforeach

            @endif
            <form id="formValidationExamples" method="post" action="{{ route('update-user', $user->id) }}">
                @csrf
                @method('put')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="fullName" class="form-label">Nama lengkap</label>
                        <input class="form-control" type="text" id="fullName" name="fullName" value="{{old('fullName') ? old('fullName') : $user->full_name }}" required disabled />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{old('email') ? old('email') : $user->email }}" required disabled />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Nomor Handphone</label>
                        <input class="form-control" type="number" id="phoneNumber" name="phoneNumber" value="{{old('phoneNumber') ? old('phoneNumber') : $user->phone_number }}" required disabled />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="nip" class="form-label">NIP</label>
                        <input class="form-control" type="text" id="nip" name="nip" value="{{old('nip') ? old('nip') : $user->nip }}" required disabled />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="selectRole">Role</label>
                        <select id="selectRole" name="selectRole" class="form-select" data-allow-clear="true" disabled>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role['id'] }}" {{ $role['unit'] == 'Admin' ? 'disabled' : '' }} {{$user->role_id == $role['id'] ? 'selected' : ''}}>{{ $role['unit']  }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="noSk" class="form-label">Nomor SK</label>
                        <input class="form-control" type="text" id="noSk" name="noSk" value="{{old('noSk') ? old('noSk') : $user->no_sk }}" required disabled />
                    </div>
                    <div id="password-area" class="mb-3 col-md-6 d-none form-password-toggle">
                        <label class="form-label" for="newPassword">Ganti Paksa Sandi</label>
                        <div class="input-group input-group-merge">
                            <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" disabled/>
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="statusUser">Status Pengguna</label>
                        <select id="statusUser" name="statusUser" class="form-select" data-allow-clear="true" disabled>
                            <option value="active" {{($user->status == "active") ? 'selected' : ''}}>Aktif</option>
                            <option value="nonactive" {{($user->status == "nonactive") ? 'selected' : ''}}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <table class="table table-flush-spacing" id="abilities">
                    <tbody>
                        @foreach($abilityMenus as $menu)
                            @if(count($menu->subMenu) > 0)
                                @if(count($data_menus) > 0)
                                    <tr>
                                        <td class="text-nowrap fw-semibold">
                                            {{ $menu->menu }}
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                                        </td>
                                    </tr>
                                    @foreach($menu->subMenu as $submenu)
                                        @php
                                            $key_menu = array_search($menu->menu, array_column($data_menus, 'menu'));

                                            $search_view = strtolower(str_replace(' ', '', $submenu->menu)).'View';
                                            $search_create = strtolower(str_replace(' ', '', $submenu->menu)).'Create';
                                            $search_edit = strtolower(str_replace(' ', '', $submenu->menu)).'Edit';
                                            $search_delete = strtolower(str_replace(' ', '', $submenu->menu)).'Delete';

                                            $key_view = array_search($search_view, array_column($data_menus[$key_menu]['sub_menu'], 'menu'));
                                            $key_create = array_search($search_create, array_column($data_menus[$key_menu]['sub_menu'], 'menu'));
                                            $key_edit = array_search($search_edit, array_column($data_menus[$key_menu]['sub_menu'], 'menu'));
                                            $key_delete = array_search($search_delete, array_column($data_menus[$key_menu]['sub_menu'], 'menu'));
                                        @endphp
                                        <tr class="border-bottom-0">
                                            <td class="border-bottom-0">
                                                <div class="row">
                                                    <div class="col-md-4 col-12 mb-3 text-nowrap">
                                                        {{ $submenu->menu }}
                                                    </div>
                                                    <div class="col-md-4 col-6">
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox" {{ ($key_view !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View"> Lihat Data </label>
                                                        </div>
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox" {{ ($key_create !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create"> Tambah Data </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" {{ ($key_edit !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit"> Edit Data </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" {{ ($key_delete !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" name> Hapus Data </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
                
                <div id="submit-area" class="mt-2 d-none">
                    <button id="submit-button" type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary me-2" onclick="disableEdit()">Batal</button>
                </div>
            </form>
            @if(!empty($menuAccessEdit))
            <div id="action-area" class="mt-2">
                <button class="btn btn-primary me-2" onclick="enableEdit()">Edit</button>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<style>
    .none{
        display: none;
    }
</style>
@endsection

@section('scripts')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>

<script>
    function enableEdit() {
        var form = document.getElementById('formValidationExamples');
        const inputs = form.querySelectorAll('input');
        const select = form.querySelectorAll('select');
        
        document.getElementById('abilities').classList.add('d-none');
        document.getElementById('password-area').classList.remove('d-none');
        document.getElementById('submit-area').classList.remove('d-none');
        document.getElementById('action-area').classList.add('d-none');

        inputs.forEach(input => {
            input.removeAttribute('disabled');
        });

        var selectElement = document.getElementById('selectRole');
        selectElement.dispatchEvent(new Event('change'));
    }

    function disableEdit() {
        var form = document.getElementById('formValidationExamples');
        const inputs = form.querySelectorAll('input');
        const select = form.querySelectorAll('select');

        document.getElementById('abilities').classList.remove('d-none');
        document.getElementById('password-area').classList.add('d-none');
        document.getElementById('submit-area').classList.add('d-none');
        document.getElementById('action-area').classList.remove('d-none');

        inputs.forEach(input => {
            input.setAttribute('disabled', true);
        });
    }

    function handleFormSubmit(form, result) {
        result.validate().then((status) => {
            if (status === 'Valid') {
                Swal.fire({
                    title: 'Simpan perubahan?',
                    text: "Anda yakin ingin menyimpan perubahan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Tidak',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false,
                    preConfirm: () => {
                        Swal.getConfirmButton().disabled = true;
                        return true;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else if (result.isDenied) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Perubahan Dibatalkan',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    const resultExamples = FormValidation.formValidation(formValidationExamples, {
        fields: {
            fullName: {
            validators: {
                notEmpty: {
                message: 'Tolong input nama lengkap'
                }
            }
            },
            email: {
            validators: {
                notEmpty: {
                message: 'Tolong input email'
                },
                emailAddress: {
                message: 'Email yang diinput tidak valid'
                }
            }
            },
            phoneNumber: {
            validators: {
                notEmpty: {
                message: 'Tolong input nomor handphone'
                },
                regexp: {
                regexp: /^[0-9]+$/,
                message: 'Hanya bisa diinputkan nomor'
                }
            }
            },
            nip: {
            validators: {
                notEmpty: {
                message: 'Tolong input NIP'
                }
            }
            },
            selectRole: {
            validators: {
                notEmpty: {
                message: 'Pastikan sudah memilih role user'
                }
            }
            },
            newPassword: {
            validators: {
                regexp: {
                regexp: /^(?=.*[a-z])(?=.*[\d\s\W]).{8,}$/,
                message: 'Minimal 8 karakter dengan 1 huruf kecil dan juga nomor / karakter spesial'
                }
            }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: function (field, ele) {
                switch (field) {
                case 'formValidationName':
                case 'formValidationEmail':
                case 'formValidationPass':
                case 'formValidationConfirmPass':
                case 'formValidationFile':
                case 'formValidationDob':
                case 'formValidationSelect2':
                case 'formValidationLang':
                case 'formValidationTech':
                case 'formValidationHobbies':
                case 'formValidationBio':
                case 'formValidationGender':
                    return '.col-md-6';
                case 'formValidationPlan':
                    return '.col-xl-3';
                case 'formValidationSwitch':
                case 'formValidationCheckbox':
                    return '.col-12';
                default:
                    return '.row';
                }
            }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
            instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
                e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
            if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
            }
            });
        }
    });

    document.getElementById('submit-button').addEventListener('click', function(e) {
        e.preventDefault();
        handleFormSubmit(document.getElementById('formValidationExamples'), resultExamples);
    });
</script>
@endsection