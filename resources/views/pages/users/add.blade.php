@extends('layouts.app')
@section('title')
Tambah User
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">

        <div class="card-header d-flex">
            <h5 class="align-self-center m-0"><a href="{{route('users')}}" class="btn btn-primary"><i class="fa fa-chevron-left"></i></a> &NonBreakingSpace;Tambah Pengguna</h5>
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
            <form id="formValidationExamples" method="POST" action="{{ route('store-user') }}">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="fullName" class="form-label">Nama lengkap</label>
                        <input class="form-control" type="text" id="fullName" name="fullName" value="{{old('fullName') ? old('fullName') : '' }}" required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{old('email') ? old('email') : '' }}" required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Nomor Handphone</label>
                        <input class="form-control" type="number" id="phoneNumber" name="phoneNumber" value="{{old('phoneNumber') ? old('phoneNumber') : '' }}" required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="nip" class="form-label">NIP</label>
                        <input class="form-control" type="text" id="nip" name="nip" value="{{old('nip') ? old('nip') : '' }}" required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="selectRole">Role</label>
                        <select id="selectRole" name="selectRole" class="form-select" data-allow-clear="true">
                            <option value="">Pilih Role</option>
                            @foreach($role_menus as $role)
                            <option value="{{ $role['role_id'] }}" {{ old('selectRole') == $role['role_id'] ? 'selected' : '' }} {{ $role['role_unit'] == 'Admin' ? 'disabled' : '' }}>{{ $role['role_unit']  }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="noSk" class="form-label">No SK</label>
                        <input class="form-control" type="text" id="noSk" name="noSk" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="mb-3 col-md-12 form-password-toggle">
                                <label class="form-label" for="newPassword">Kata Sandi</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="mb-3 col-md-12 form-password-toggle">
                                <label class="form-label" for="confirmPassword">Konfirmasi Kata Sandi</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('pages.users.select')
            </form>
            <div class="mt-2">
                <button id="submit-button" type="submit" class="btn btn-primary me-2">Tambah</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<style>
    .tab-content-none{
        display: none;
    }
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

<script src="{{ asset('assets/vendor/libs/bloodhound/bloodhound.js') }}"></script>
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/js/forms-tagify.js') }}"></script>
<script src="{{ asset('assets/js/forms-typeahead.js') }}"></script>

<script>
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
            noSk: {
            validators: {
                notEmpty: {
                message: 'Tolong input nomor SK'
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
                notEmpty: {
                message: 'Tolong input sandi'
                },
                regexp: {
                regexp: /^(?=.*[a-z])(?=.*[\d\s\W]).{8,}$/,
                message: 'Minimal 8 karakter dengan 1 huruf kecil dan juga nomor / karakter spesial'
                },
                identical: {
                compare: function () {
                    return formValidationExamples.querySelector('[name="newPassword"]').value;
                },
                message: 'Sandi tidak sesuai'
                },
            }
            },
            confirmPassword: {
            validators: {
                notEmpty: {
                message: 'Tolong konfirmasi sandi'
                },
                regexp: {
                regexp: /^(?=.*[a-z])(?=.*[\d\s\W]).{8,}$/,
                message: 'Minimal 8 karakter dengan 1 huruf kecil dan juga nomor / karakter spesial'
                },
                identical: {
                compare: function () {
                    return formValidationExamples.querySelector('[name="newPassword"]').value;
                },
                message: 'Sandi tidak sesuai'
                },
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
<script>
$('#selectRole').change(function () {
    dropdown = $('#selectRole').val();
    $('.tab-content').hide();
    $('#' + "tab-" + dropdown).show().removeClass('tab-content-none');                                    
});
</script>
@endsection