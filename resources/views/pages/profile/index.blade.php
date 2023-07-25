@extends('layouts.app')
@section('title')
Setting Profile
@endsection
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <section id="page-account-settings">
        <div class="row">

            <div class="col-md-3 mb-4 mb-md-0">
                <ul class="nav nav-pills flex-column nav-left">
                    <li class="nav-item">
                        <a class="nav-link active" style="float: left;display: block;" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                            <i data-feather="user" class="menu-icon tf-icons ti ti-user"></i>
                            <span class="font-weight-bold">Profil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="float: left;display: block;" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
                            <i data-feather="lock" class="menu-icon tf-icons ti ti-lock"></i>
                            <span class="font-weight-bold">Ubah Sandi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="float: left;display: block;" id="account-pill-info" data-toggle="pill" href="#account-vertical-info" aria-expanded="false">
                            <i data-feather="info-octagon" class="menu-icon tf-icons ti ti-info-circle"></i>
                            <span class="font-weight-bold">Riwayat Login</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
                                <form id="formValidationExamples" method="POST" action="{{route('update-profile')}}">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
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
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="fullName" class="form-label">Nama lengkap</label>
                                            <input class="form-control" type="text" id="fullName" name="fullName" value="{{old('fullName') ? old('fullName') : $user_data->full_name}}" required />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input class="form-control" type="email" id="email" name="email" value="{{old('email') ? old('email') : $user_data->email}}" required />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">Nomor Handphone</label>
                                            <input class="form-control" type="number" id="phoneNumber" name="phoneNumber" value="{{old('phoneNumber') ? old('phoneNumber') : $user_data->phone_number}}" required />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="nip" class="form-label">NIP</label>
                                            <input class="form-control" type="text" id="nip" name="nip" value="{{old('nip') ? old('nip') : $user_data->nip}}" required />
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <button id="submit-profile" class="btn btn-primary w-100">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                <form id="formValidationCustom" method="POST" action="{{route('update-password')}}">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3 col-md-12">
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
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6 form-password-toggle">
                                            <label class="form-label" for="currentPassword">Kata Sandi Saat Ini</label>
                                            <div class="input-group input-group-merge">
                                                <input class="form-control" type="password" name="currentPassword" id="currentPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="mb-3 col-md-12 form-password-toggle">
                                                    <label class="form-label" for="newPassword">Kata Sandi Baru</label>
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
                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <h6>Ketentuan Sandi:</h6>
                                            <ul class="ps-3 mb-0">
                                                <li class="mb-1">Minimal 8 karakter - semakin banyak, semakin baik</li>
                                                <li class="mb-1">Setidaknya memiliki 1 karakter huruf kecil</li>
                                                <li>Setidaknya memiliki 1 angka, simbol, atau karakter spesial</li>
                                            </ul>
                                        </div>
                                        <div class="col-12">
                                            <div class="row d-flex">
                                                <div class="col-md-6 col-sm-12 mb-2">
                                                    <button id="submit-password" class="btn btn-primary w-100">Simpan Perubahan</button>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <button type="reset" class="btn btn-label-secondary w-100">Batal</button>
                                                </div>
                                                <div class="ms-auto align-self-center text-muted col-md-12 col-sm-12 mt-2 text-lg-right text-center">
                                                    Terakhir Diubah : {{$user_data->last_password_changed ? indoDate($user_data->last_password_changed) : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="account-vertical-info" role="tabpanel" aria-labelledby="account-pill-info" aria-expanded="false">
                                <table class="table table-borderless table-sm">
                                    <thead class="border-bottom">
                                        <tr>
                                            <th class="px-0">No</th>
                                            <th class="px-0">Waktu Akses / User Agent</th>
                                            <th class="px-0 text-end">Alamat IP / Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i = 1;
                                        @endphp
                                        @foreach($login_logs as $log)
                                        <tr class="mt-2">
                                            <td class="px-0 pt-4">{{$i}}.</td>
                                            <td class="px-0 pt-4">{{ indoDate($log->created_at) }}</td>
                                            <td class="px-0 pt-4 text-end align-top">
                                                <span class="badge bg-label-primary bg-glow">{{ $log->ip_address }}</span>
                                                <a href="https://whatismyipaddress.com/ip/{{$log->ip_address}}" target="_blank" class="badge bg-primary bg-glow">Info</a>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <td></td>
                                            <td class="px-0 pb-4" colspan="2">User Agent : <br> {{ $log->user_agent ? $log->user_agent : '-' }}</td>
                                        </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
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

<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/vendor/js/core.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>

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
            }
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

    const resultCustom = FormValidation.formValidation(formValidationCustom, {
        fields: {
            currentPassword: {
            validators: {
                notEmpty: {
                message: 'Tolong input sandi saat ini'
                },
                regexp: {
                regexp: /^(?=.*[a-z])(?=.*[\d\s\W]).{8,}$/,
                message: 'Minimal 8 karakter dengan 1 huruf kecil dan juga nomor / karakter spesial'
                },
            }
            },
            newPassword: {
            validators: {
                notEmpty: {
                message: 'Tolong input sandi baru'
                },
                regexp: {
                regexp: /^(?=.*[a-z])(?=.*[\d\s\W]).{8,}$/,
                message: 'Minimal 8 karakter dengan 1 huruf kecil dan juga nomor / karakter spesial'
                },
                identical: {
                compare: function () {
                    return formValidationCustom.querySelector('[name="newPassword"]').value;
                },
                message: 'Sandi tidak sesuai'
                },
            }
            },
            confirmPassword: {
            validators: {
                notEmpty: {
                message: 'Tolong konfirmasi sandi baru'
                },
                regexp: {
                regexp: /^(?=.*[a-z])(?=.*[\d\s\W]).{8,}$/,
                message: 'Minimal 8 karakter dengan 1 huruf kecil dan juga nomor / karakter spesial'
                },
                identical: {
                compare: function () {
                    return formValidationCustom.querySelector('[name="newPassword"]').value;
                },
                message: 'Sandi tidak sesuai'
                },
            }
            }
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
    
    document.getElementById('submit-profile').addEventListener('click', function(e) {
        e.preventDefault();
        handleFormSubmit(document.getElementById('formValidationExamples'), resultExamples);
    });

    document.getElementById('submit-password').addEventListener('click', function(e) {
        e.preventDefault();
        handleFormSubmit(document.getElementById('formValidationCustom'), resultCustom);
    });
    

</script>

@endsection