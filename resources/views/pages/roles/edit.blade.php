@extends('layouts.app')
@section('title')
Edit Role
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">

        <div class="card-header d-flex">
            <h5 class="align-self-center m-0"><a href="{{route('roles')}}" class="btn btn-primary"><i class="fa fa-chevron-left"></i></a> &NonBreakingSpace;Edit Role</h5>
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
            <form id="formValidationExamples" method="POST" action="{{ route('update-role', $role_edit->id) }}">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="modalTypeRole">Type Role</label>
                        <input type="text" id="modalTypeRole" name="modalTypeRole" value="{{ old('modalTypeRole') ? old('modalTypeRole') :  $role_edit->type }}" class="form-control" placeholder="Masukan Type Role" {{ ($disable_edit == true) ? 'disabled' : 'disabled' }} required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="modalUnitRole">Unit Role</label>
                        <input type="text" id="modalUnitRole" name="modalUnitRole" value="{{ old('modalTypeRole') ? old('modalTypeRole') :  $role_edit->unit }}" class="form-control" placeholder="Masukan Unit Role" {{ ($disable_edit == true) ? 'disabled' : 'disabled' }} required />
                    </div>
                </div>

                <table class="table table-flush-spacing" aria-disabled="true">
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
                                            $key_master = $key_view !== false || $key_create !== false || $key_edit !== false || $key_delete !== false ? true : false;
                                        @endphp
                                        <tr class="border-bottom-0">
                                            <td class="border-bottom-0">
                                                <div class="row">
                                                    <div class="col-md-4 col-12 mb-3 text-nowrap">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Master" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)"  {{ ($key_master !== false) ? 'checked' : '' }} disabled/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Master"> {{ $submenu->menu }} </label>
                                                    </div>
                                                    <div class="col-md-4 col-6">
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View" value="{{$submenu->id}}-1" {{ ($key_view !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View"> Lihat Data </label>
                                                        </div>
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create" value="{{$submenu->id}}-2" {{ ($key_create !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create"> Tambah Data </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit" value="{{$submenu->id}}-3" {{ ($key_edit !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit"> Edit Data </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" value="{{$submenu->id}}-4" {{ ($key_delete !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" name> Hapus Data </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @else
                                @if(count($menu->subMenu) < 1)

                                    @php
                                        $key_menu = array_search($menu->menu, array_column($data_menus, 'menu'));

                                        $search_view = strtolower(str_replace(' ', '', $menu->menu)).'View';
                                        $search_create = strtolower(str_replace(' ', '', $menu->menu)).'Create';
                                        $search_edit = strtolower(str_replace(' ', '', $menu->menu)).'Edit';
                                        $search_delete = strtolower(str_replace(' ', '', $menu->menu)).'Delete';
                                        $search_verify = strtolower(str_replace(' ', '', $menu->menu)).'Verify';

                                        $key_view = array_search($search_view, array_column($data_menus[$key_menu]['value'], 'menu'));
                                        $key_create = array_search($search_create, array_column($data_menus[$key_menu]['value'], 'menu'));
                                        $key_edit = array_search($search_edit, array_column($data_menus[$key_menu]['value'], 'menu'));
                                        $key_delete = array_search($search_delete, array_column($data_menus[$key_menu]['value'], 'menu'));
                                        $key_verify = array_search($search_verify, array_column($data_menus[$key_menu]['value'], 'menu'));
                                        $key_master = $key_view !== false || $key_create !== false || $key_edit !== false || $key_delete !== false || $key_verify !== false ? true : false;
                                    @endphp
                                    <tr>
                                        <td class="text-nowrap fw-semibold">
                                            {{ $menu->menu }}
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom-0">
                                        <td class="border-bottom-0">
                                            <div class="row">
                                                <div class="col-md-4 col-12 mb-3 text-nowrap">
                                                    <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Master" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)"  {{ ($key_master !== false) ? 'checked' : '' }} disabled/>
                                                    <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Master"> {{ $menu->menu }} </label>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <div class="form-check me-3 me-lg-5">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View" value="{{$menu->id}}-1" {{ ($key_view !== false) ? 'checked' : '' }} disabled/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View"> Lihat Data </label>
                                                    </div>
                                                    <div class="form-check me-3 me-lg-5">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create" value="{{$menu->id}}-2" {{ ($key_create !== false) ? 'checked' : '' }} disabled/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create"> Tambah Data </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit" value="{{$menu->id}}-3" {{ ($key_edit !== false) ? 'checked' : '' }} disabled/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit"> Edit Data </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" value="{{$menu->id}}-4" {{ ($key_delete !== false) ? 'checked' : '' }} disabled/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" name> Hapus Data </label>
                                                    </div>
                                                    @if($menu->menu == "Realisasi")
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Verify" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Verify" value="{{$menu->id}}-5" {{ ($key_verify !== false) ? 'checked' : '' }} disabled/>
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Verify" name> Verify Data </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </form>
            <div id="submit-area" class="mt-2 d-none">
                <button id="submit-button" type="submit" class="btn btn-primary me-2"  onclick="comfirmEdit(document.getElementById('formValidationExamples'))">Simpan Perubahan</button>
                <button type="button" class="btn btn-secondary me-2" onclick="disableEdit()">Batal</button>
            </div>
            @if(!empty($menuAccessEdit))
            <div id="action-area" class="mt-2">
                <button class="btn btn-primary me-2" onclick="enableEdit()">Edit</button>
            </div>
            @endif
        </div>
    </div>
</div>
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
<script src="{{ asset('assets/utils/js/abilityCheckboxHandler.js') }}"></script>
<script src="{{ asset('assets/js/form-validation.js') }}"></script>

<script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
<script>

    function enableEdit() {
        let disabled_edit = "<?php echo $disable_edit ?>";
        if(disabled_edit != 1){
            var form = document.getElementById('formValidationExamples');
            const inputs = form.querySelectorAll('input');
            
            document.getElementById('submit-area').classList.remove('d-none');
            document.getElementById('action-area').classList.add('d-none');
    
            inputs.forEach(input => {
                input.removeAttribute('disabled');
            });
    
            select.forEach(input => {
                input.removeAttribute('disabled');
            });
            
            textarea.forEach(input => {
                input.removeAttribute('disabled');
            });
        }else{
            var form = document.getElementById('formValidationExamples');
            const inputs = form.querySelectorAll('input[type=checkbox]');
            
            document.getElementById('submit-area').classList.remove('d-none');
            document.getElementById('action-area').classList.add('d-none');
    
            inputs.forEach(input => {
                input.removeAttribute('disabled');
            });
    
            select.forEach(input => {
                input.removeAttribute('disabled');
            });
            
            textarea.forEach(input => {
                input.removeAttribute('disabled');
            });
        }
    }

    function disableEdit() {
        var form = document.getElementById('formValidationExamples');
        const inputs = form.querySelectorAll('input');

        document.getElementById('submit-area').classList.add('d-none');
        document.getElementById('action-area').classList.remove('d-none');

        inputs.forEach(input => {
            input.setAttribute('disabled', true);
        });

        select.forEach(input => {
            input.setAttribute('disabled', true);
        });
        
        textarea.forEach(input => {
            input.setAttribute('disabled', true);
        });
    }

    function comfirmEdit(form) {
        Swal.fire({
            title: 'Konfirmasi Data',
            text: "Anda yakin ingin menyimpan data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
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
</script>
@endsection