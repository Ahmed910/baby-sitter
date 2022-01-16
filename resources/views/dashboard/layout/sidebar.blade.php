<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{!! route('dashboard.home') !!}">
                    <span class="brand-logo">
                        <img src="{{ asset('dashboardAssets') }}/images/icons/logo_sm.png" alt="">
                    </span>
                    <h2 class="brand-text">{{ setting('project_name') }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ request()->route()->getName() == 'dashboard.home' ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{!! route('dashboard.home') !!}">
                    <i data-feather='home'></i>
                    <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.general.home') !!}">
                        {!! trans('dashboard.general.home') !!}
                    </span>
                </a>
            </li>

            @if (auth()->user()->hasPermissions('setting','store'))
               <li class="{{ request()->route()->getName() == 'dashboard.setting.index' ? 'active' : '' }} nav-item">
                   <a class="d-flex align-items-center" href="{{ route('dashboard.setting.index') }}">
                       <i data-feather='settings'></i>
                       <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.setting.setting') !!}">
                           {!! trans('dashboard.setting.setting') !!}
                       </span>
                   </a>
               </li>
             @endif

             @if (auth()->user()->hasPermissions('client'))
             <li class=" nav-item">
                 <a class="d-flex align-items-center" href="#">
                     <i data-feather='users'></i>
                     <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.client.clients') !!}">
                         {!! trans('dashboard.client.clients') !!}
                     </span>
                 </a>
                 <ul class="menu-content">
                     <li class="{{ request()->route()->getName() == 'dashboard.client.index' || request()->route()->getName() == 'dashboard.client.show' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.client.index') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.client.clients') !!}">
                                 {!! trans('dashboard.general.show_all') !!}
                             </span>
                         </a>
                     </li>
                     @if (auth()->user()->hasPermissions('client','store'))
                     <li class="{{ request()->route()->getName() == 'dashboard.client.create' || request()->route()->getName() == 'dashboard.client.edit' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.client.create') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.client.add_client') !!}">
                                 {!! trans('dashboard.general.add_new') !!}
                             </span>
                         </a>
                     </li>
                     @endif
                 </ul>
             </li>
             @endif

             @if (auth()->user()->hasPermissions('sitter'))
             <li class=" nav-item">
                 <a class="d-flex align-items-center" href="#">
                     <i data-feather='users'></i>
                     <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.sitter.sitters') !!}">
                         {!! trans('dashboard.sitter.sitters') !!}
                     </span>
                 </a>
                 <ul class="menu-content">
                     <li class="{{ request()->route()->getName() == 'dashboard.sitter.index' || request()->route()->getName() == 'dashboard.sitter.show' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.sitter.index') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.sitter.sitters') !!}">
                                 {!! trans('dashboard.general.show_all') !!}
                             </span>
                         </a>
                     </li>

                     @if (auth()->user()->hasPermissions('sitter','store'))
                     <li class="{{ request()->route()->getName() == 'dashboard.sitter.create' || request()->route()->getName() == 'dashboard.sitter.edit' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.sitter.create') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.sitter.add_sitter') !!}">
                                 {!! trans('dashboard.general.add_new') !!}
                             </span>
                         </a>
                     </li>
                     @endif
                 </ul>
             </li>
             @endif

             @if (auth()->user()->hasPermissions('feature'))
             <li class=" nav-item">
                 <a class="d-flex align-items-center" href="#">
                     <i data-feather='users'></i>
                     <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.feature.features') !!}">
                         {!! trans('dashboard.feature.features') !!}
                     </span>
                 </a>
                 <ul class="menu-content">
                     <li class="{{ request()->route()->getName() == 'dashboard.feature.index' || request()->route()->getName() == 'dashboard.feature.show' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.feature.index') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.feature.features') !!}">
                                 {!! trans('dashboard.general.show_all') !!}
                             </span>
                         </a>
                     </li>

                     @if (auth()->user()->hasPermissions('feature','store'))
                     <li class="{{ request()->route()->getName() == 'dashboard.feature.create' || request()->route()->getName() == 'dashboard.feature.edit' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.feature.create') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.feature.add_feature') !!}">
                                 {!! trans('dashboard.general.add_new') !!}
                             </span>
                         </a>
                     </li>
                     @endif
                 </ul>
             </li>
             @endif


             @if (auth()->user()->hasPermissions('center'))
             <li class=" nav-item">
                 <a class="d-flex align-items-center" href="#">
                     <i data-feather='users'></i>
                     <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.center.centers') !!}">
                         {!! trans('dashboard.center.centers') !!}
                     </span>
                 </a>
                 <ul class="menu-content">
                     <li class="{{ request()->route()->getName() == 'dashboard.center.index' || request()->route()->getName() == 'dashboard.center.show' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.center.index') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.center.centers') !!}">
                                 {!! trans('dashboard.general.show_all') !!}
                             </span>
                         </a>
                     </li>

                     @if (auth()->user()->hasPermissions('center','store'))
                     <li class="{{ request()->route()->getName() == 'dashboard.center.create' || request()->route()->getName() == 'dashboard.center.edit' ? 'active' : '' }}">
                         <a class="d-flex align-items-center" href="{!! route('dashboard.center.create') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.center.add_center') !!}">
                                 {!! trans('dashboard.general.add_new') !!}
                             </span>
                         </a>
                     </li>
                     @endif
                 </ul>
             </li>
             @endif


             @if (auth()->user()->hasPermissions('sitter_worker'))
             <li class=" nav-item">
                 <a class="d-flex align-items-sitter_worker" href="#">
                     <i data-feather='users'></i>
                     <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.sitter_worker.sitter_workers') !!}">
                         {!! trans('dashboard.sitter_worker.sitter_workers') !!}
                     </span>
                 </a>
                 <ul class="menu-content">
                     <li class="{{ request()->route()->getName() == 'dashboard.sitter_worker.index' || request()->route()->getName() == 'dashboard.sitter_worker.show' ? 'active' : '' }}">
                         <a class="d-flex align-items-sitter_worker" href="{!! route('dashboard.sitter_worker.index') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.sitter_worker.sitter_workers') !!}">
                                 {!! trans('dashboard.general.show_all') !!}
                             </span>
                         </a>
                     </li>

                     @if (auth()->user()->hasPermissions('sitter_worker','store'))
                     <li class="{{ request()->route()->getName() == 'dashboard.sitter_worker.create' || request()->route()->getName() == 'dashboard.sitter_worker.edit' ? 'active' : '' }}">
                         <a class="d-flex align-items-sitter_worker" href="{!! route('dashboard.sitter_worker.create') !!}">
                             <i data-feather="circle"></i>
                             <span class="menu-item" data-i18n="{!! trans('dashboard.sitter_worker.add_sitter_worker') !!}">
                                 {!! trans('dashboard.general.add_new') !!}
                             </span>
                         </a>
                     </li>
                     @endif
                 </ul>
             </li>
             @endif



            {{-- Admins --}}
            @if (auth()->user()->hasPermissions('manager'))
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='users'></i>
                    <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.manager.managers') !!}">
                        {!! trans('dashboard.manager.managers') !!}
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->route()->getName() == 'dashboard.manager.index' || request()->route()->getName() == 'dashboard.manager.show' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.manager.index') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.manager.managers') !!}">
                                {!! trans('dashboard.general.show_all') !!}
                            </span>
                        </a>
                    </li>
                    @if (auth()->user()->hasPermissions('manager','store'))
                    <li class="{{ request()->route()->getName() == 'dashboard.manager.create' || request()->route()->getName() == 'dashboard.manager.edit' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.manager.create') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.manager.add_manager') !!}">
                                {!! trans('dashboard.general.add_new') !!}
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- Roles --}}
            @if (auth()->user()->hasPermissions('role'))
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='package'></i>
                    <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.role.roles') !!}">
                        {!! trans('dashboard.role.roles') !!}
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->route()->getName() == 'dashboard.role.index' || request()->route()->getName() == 'dashboard.role.show' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.role.index') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.role.roles') !!}">
                                {!! trans('dashboard.general.show_all') !!}
                            </span>
                        </a>
                    </li>
                    @if (auth()->user()->hasPermissions('role','store'))
                    <li class="{{ request()->route()->getName() == 'dashboard.role.create' || request()->route()->getName() == 'dashboard.role.edit' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.role.create') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.role.add_role') !!}">
                                {!! trans('dashboard.general.add_new') !!}
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif



            {{-- Country --}}
            @if (auth()->user()->hasPermissions('country'))
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i class="fas fa-flag"></i>
                    <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.country.countries') !!}">
                        {!! trans('dashboard.country.countries') !!}
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->route()->getName() == 'dashboard.country.index' || request()->route()->getName() == 'dashboard.country.show' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.country.index') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.country.countries') !!}">
                                {!! trans('dashboard.general.show_all') !!}
                            </span>
                        </a>
                    </li>
                    @if (auth()->user()->hasPermissions('country','store'))
                    <li class="{{ request()->route()->getName() == 'dashboard.country.create' || request()->route()->getName() == 'dashboard.country.edit' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.country.create') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.country.add_country') !!}">
                                {!! trans('dashboard.general.add_new') !!}
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            {{-- City --}}
            @if (auth()->user()->hasPermissions('city'))
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i class="fas fa-city"></i>
                    <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.city.cities') !!}">
                        {!! trans('dashboard.city.cities') !!}
                    </span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->route()->getName() == 'dashboard.city.index' || request()->route()->getName() == 'dashboard.city.show' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.city.index') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.city.cities') !!}">
                                {!! trans('dashboard.general.show_all') !!}
                            </span>
                        </a>
                    </li>
                    @if (auth()->user()->hasPermissions('city','store'))
                    <li class="{{ request()->route()->getName() == 'dashboard.city.create' || request()->route()->getName() == 'dashboard.city.edit' ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{!! route('dashboard.city.create') !!}">
                            <i data-feather="circle"></i>
                            <span class="menu-item" data-i18n="{!! trans('dashboard.city.add_city') !!}">
                                {!! trans('dashboard.general.add_new') !!}
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif





                    {{-- Sliders --}}
              @if (auth()->user()->hasPermissions('slider'))
              <li class=" nav-item">
                  <a class="d-flex align-items-center" href="#">
                    <i class="fas fa-sliders-h"></i>
                      <span class="menu-title text-truncate" data-i18n="{!! trans('dashboard.slider.sliders') !!}">
                          {!! trans('dashboard.slider.sliders') !!}
                      </span>
                  </a>
                  <ul class="menu-content">
                      <li class="{{ request()->route()->getName() == 'dashboard.slider.index' || request()->route()->getName() == 'dashboard.slider.show' ? 'active' : '' }}">
                          <a class="d-flex align-items-center" href="{!! route('dashboard.slider.index') !!}">
                              <i data-feather="circle"></i>
                              <span class="menu-item" data-i18n="{!! trans('dashboard.slider.sliders') !!}">
                                  {!! trans('dashboard.general.show_all') !!}
                              </span>
                          </a>
                      </li>
                      @if (auth()->user()->hasPermissions('slider','store'))
                      <li class="{{ request()->route()->getName() == 'dashboard.slider.create' || request()->route()->getName() == 'dashboard.slider.edit' ? 'active' : '' }}">
                          <a class="d-flex align-items-center" href="{!! route('dashboard.slider.create') !!}">
                              <i data-feather="circle"></i>
                              <span class="menu-item" data-i18n="{!! trans('dashboard.slider.add_type') !!}">
                                  {!! trans('dashboard.general.add_new') !!}
                              </span>
                          </a>
                      </li>
                      @endif
                  </ul>
              </li>
              @endif




        </ul>
    </div>
</div>
<!-- END: Main Menu-->
