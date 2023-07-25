<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/app/').'/'.appDetail()->logo_image }}" alt="logo_image">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder" style="font-size: 17px!important;  font-family: 'Montserrat', sans-serif!important;">{{appDetail()->app_name}}</span>
        </a>

        <a href="javascript:void(0);" style="margin-left:85px!important" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ ($dashboard_active !== false) ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Manajemen</span>
        </li>
        @foreach(listMenu() as $menu)
        @if(count($menu['sub_menu']) < 1) 
            <li class="menu-item {{ $parent_menu_active == $menu['menu'] ? 'active' : '' }}">
                <a href="/{{ strtolower($menu['menu']) }}" class="menu-link">
                    <i class="menu-icon tf-icons {{ $menu['icon'] }}"></i>
                    <div>{{$menu['menu']}}</div>
                </a>
            </li>
        @else
            @php
                if($parent_menu_active == $menu['menu']){
                    $parent_active = true;
                }else{
                    $parent_active = false;
                }
            @endphp
            @if(count($menu['sub_menu']) > 0)
            <li class="menu-item {{ ($parent_active !== false) ? 'active open' : '' }}">
                <a href="{{ $menu['route'] }}" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons {{ $menu['icon'] }}"></i>
                    <div>{{ $menu['menu'] }}</div>
                </a>
                <ul class="menu-sub">
                    @foreach($menu['sub_menu'] as $submenu)
                    @php
                        if($child_menu_active == $submenu['menu']){
                            $child_active = true;
                        }else{
                            $child_active = false;
                        }
                    @endphp
                    <li class="menu-item {{ ($child_active !== false) ? 'active' : '' }}">
                        <a href="{{ route($submenu['route']) }}" class="menu-link">
                            <div>{{ $submenu['menu'] }}</div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endif
        @endif
        @endforeach
    </ul>
</aside>