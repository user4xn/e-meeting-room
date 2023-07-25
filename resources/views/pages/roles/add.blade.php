@extends('layouts.app')
@section('title')
Tambah Role
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">

        <div class="card-header d-flex">
            <h5 class="align-self-center m-0"><a href="{{route('roles')}}" class="btn btn-primary"><i class="fa fa-chevron-left"></i></a> &NonBreakingSpace;Tambah Role</h5>
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
            <form id="formValidationExamples" method="POST" action="{{ route('store-role') }}">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="modalTypeRole">Type Role</label>
                        <input type="text" id="modalTypeRole" name="modalTypeRole" value="{{old('modalTypeRole') ? old('modalTypeRole') : ''}}" class="form-control" placeholder="Masukan Type Role" required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="modalUnitRole">Unit Role</label>
                        <input type="text" id="modalUnitRole" name="modalUnitRole" value="{{old('modalUnitRole') ? old('modalUnitRole') : ''}}" class="form-control" placeholder="Masukan Unit Role" required />
                    </div>
                </div>
                
                <div class="">
                    <table class="table table-flush-spacing">
                        <tbody>
                            @foreach($abilityMenus as $menu)

                                @if(count($menu->subMenu) > 0)
                                    
                                    <tr>
                                        <td class="text-nowrap fw-semibold border-bottom-0">
                                            {{ $menu->menu }}
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Memberi akses penuh ke sistem"></i>
                                        </td>
                                    </tr>
                                    @foreach($menu->subMenu as $submenu)
                                    
                                        <tr>
                                            <td class="border-top">
                                                <div class="row">
                                                    <div class="col-md-4 col-12 mb-2 text-nowrap">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Master" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" />
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Master"> {{ $submenu->menu }} </label>
                                                    </div>
                                                    <div class="col-md-4 col-7">
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View" />
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View"> Lihat Data </label>
                                                        </div>
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create" />
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create"> Tambah Data </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-5">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit" />
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit"> Edit Data </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $submenu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" />
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" name> Hapus Data </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                              
                                    @endforeach
                                @else
                                    @if($menu->parent_id == 0)
                                    <tr>
                                        <td class="text-nowrap fw-semibold border-bottom-0">
                                            {{ $menu->menu }}
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Memberi akses penuh ke sistem"></i>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="border-top">
                                            <div class="row">
                                                <div class="col-md-4 col-12 mb-2 text-nowrap">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Master" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" />
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Master"> {{ $menu->menu }} </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-7">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View" value="{{$menu->id}}-1"/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View"> Lihat Data</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create" value="{{$menu->id}}-2"/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create"> Tambah Data</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-5">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit" value="{{$menu->id}}-3"/>
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit"> Edit Data </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" value="{{$menu->id}}-4" />
                                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" name> Hapus Data </label>
                                                    </div>
                                                    @if($menu->menu == "Realisasi")
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Verify" onchange="handleCheckboxClick('{{ strtolower(str_replace(' ', '', $menu->menu)) }}', this)" name="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Verify" value="{{$menu->id}}-5" />
                                                            <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Verify" name> Verify Data </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-2">
                    <button id="submit-button" type="submit" class="btn btn-primary me-2">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
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
@endsection