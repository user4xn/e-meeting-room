<div id="tab-0" class="tab-content-none">
    <table class="table table-flush-spacing">
        <tbody>
            @foreach($abilityMenus as $menu)
                @if(count($menu->subMenu) > 0)
                    @if(count($role['data_menu']) > 0)
                        <tr>
                            <td class="text-nowrap fw-semibold">
                                {{ $menu->menu }}
                                <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                            </td>
                        </tr>
                        @foreach($menu->subMenu as $submenu)
                            <tr class="border-bottom-0">
                                <td class="border-bottom-0">
                                    <div class="row">
                                        <div class="col-md-4 col-12 mb-3 text-nowrap">
                                            {{ $submenu->menu }}
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input" type="checkbox" disabled/>
                                                <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}View"> Lihat Data </label>
                                            </div>
                                            <div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input" type="checkbox" disabled/>
                                                <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Create"> Tambah Data </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled/>
                                                <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Edit"> Edit Data </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled/>
                                                <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $submenu->menu)) }}Delete" name> Hapus Data </label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    @php
                        $key_menu = array_search($menu->menu, array_column($role['data_menu'], 'menu'));
                        $search_view = strtolower(str_replace(' ', '', $menu->menu)).'View';
                        $search_create = strtolower(str_replace(' ', '', $menu->menu)).'Create';
                        $search_edit = strtolower(str_replace(' ', '', $menu->menu)).'Edit';
                        $search_delete = strtolower(str_replace(' ', '', $menu->menu)).'Delete';
                        $search_verify = strtolower(str_replace(' ', '', $menu->menu)).'Verify';

                        $key_view = array_search($search_view, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_create = array_search($search_create, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_edit = array_search($search_edit, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_delete = array_search($search_delete, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_verify = array_search($search_verify, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
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
                                    {{ $menu->menu }}
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-check me-3 me-lg-5">
                                        <input class="form-check-input" type="checkbox" {{ ($key_view !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View"> Lihat Data </label>
                                    </div>
                                    <div class="form-check me-3 me-lg-5">
                                        <input class="form-check-input" type="checkbox" {{ ($key_create !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create"> Tambah Data </label>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ ($key_edit !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit"> Edit Data </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ ($key_delete !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" name> Hapus Data </label>
                                    </div>
                                    @if($menu->menu == "Realisasi")
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" {{ ($key_verify !== false) ? 'checked' : '' }} disabled/>
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
@foreach($role_menus as $role)
<div id="tab-{{$role['role_id']}}" class="tab-content px-0 tab-content-none">
    <table class="table table-flush-spacing" id="abilities" class="select-role">
        <tbody>
            @foreach($abilityMenus as $menu)
                @if(count($menu->subMenu) > 0)
                    @if(count($role['data_menu']) > 0)
                        <tr>
                            <td class="text-nowrap fw-semibold">
                                {{ $menu->menu }}
                                <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                            </td>
                        </tr>
                        @foreach($menu->subMenu as $submenu)
                            @php
                                $key_menu = array_search($menu->menu, array_column($role['data_menu'], 'menu'));
                                $search_view = strtolower(str_replace(' ', '', $submenu->menu)).'View';
                                $search_create = strtolower(str_replace(' ', '', $submenu->menu)).'Create';
                                $search_edit = strtolower(str_replace(' ', '', $submenu->menu)).'Edit';
                                $search_delete = strtolower(str_replace(' ', '', $submenu->menu)).'Delete';

                                $key_view = array_search($search_view, array_column($role['data_menu'][$key_menu]['sub_menu'], 'menu'));
                                $key_create = array_search($search_create, array_column($role['data_menu'][$key_menu]['sub_menu'], 'menu'));
                                $key_edit = array_search($search_edit, array_column($role['data_menu'][$key_menu]['sub_menu'], 'menu'));
                                $key_delete = array_search($search_delete, array_column($role['data_menu'][$key_menu]['sub_menu'], 'menu'));
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
                @else

                    @php
                        $key_menu = array_search($menu->menu, array_column($role['data_menu'], 'menu'));
                        $search_view = strtolower(str_replace(' ', '', $menu->menu)).'View';
                        $search_create = strtolower(str_replace(' ', '', $menu->menu)).'Create';
                        $search_edit = strtolower(str_replace(' ', '', $menu->menu)).'Edit';
                        $search_delete = strtolower(str_replace(' ', '', $menu->menu)).'Delete';
                        $search_verify = strtolower(str_replace(' ', '', $menu->menu)).'Verify';

                        $key_view = array_search($search_view, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_create = array_search($search_create, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_edit = array_search($search_edit, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_delete = array_search($search_delete, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
                        $key_verify = array_search($search_verify, array_column($role['data_menu'][$key_menu]['value'], 'menu'));
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
                                    {{ $menu->menu }}
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-check me-3 me-lg-5">
                                        <input class="form-check-input" type="checkbox" {{ ($key_view !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}View"> Lihat Data </label>
                                    </div>
                                    <div class="form-check me-3 me-lg-5">
                                        <input class="form-check-input" type="checkbox" {{ ($key_create !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Create"> Tambah Data </label>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ ($key_edit !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Edit"> Edit Data </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ ($key_delete !== false) ? 'checked' : '' }} disabled/>
                                        <label class="form-check-label" for="{{ strtolower(str_replace(' ', '', $menu->menu)) }}Delete" name> Hapus Data </label>
                                    </div>
                                    @if($menu->menu == "Realisasi")
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ ($key_verify !== false) ? 'checked' : '' }} disabled/>
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
@endforeach