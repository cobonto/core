<!-- Left side column. contains the logo and sidebar -->
<div class="sidebar">
        {!! hook('displayAdminSideBarTop') !!}
                <!-- search form -->
        {{--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>--}}
                <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul>
            @if(hasAccess('dashboard'))
                <li class="menu" class="{{ activeMenu('dashboard') }}">
                    <a href="{{ adminRoute('dashboard.index') }}"><i class="fa fa-dashboard"></i> <span>{{ transTpl('dashboard','sidebar') }}</span>
                        <!--<i class="fa fa-angle-left pull-right"></i>-->
                    </a>
                </li>
            @endif

            <li class="title">{{ transTpl('system_menu','sidebar') }}</li>
            @if(hasAccess(['users','groups']) && config('app.core_user_controller'))
                <li>
                    <a href="{{ adminRoute('users.index') }}">
                        <i class="fa fa-user"></i> <span>{{ transTpl('users','sidebar') }}</span><i class="fa fa-caret-right pull-right"></i>
                    </a>
                    <ul class="sub-menu">
                       @if(hasAccess('users')) <li class="{{ activeMenu('users') }}"><a href="{{ adminRoute('users.index') }}"><i class="fa fa-circle"></i>{{ transTpl('users','sidebar') }}</a></li>@endif
                        @if(hasAccess('groups'))<li class="{{ activeMenu('groups') }}"><a href="{{ adminRoute('groups.index') }}"><i class="fa fa-circle"></i> {{ transTpl('groups','sidebar') }}</a></li>@endif
                    </ul>
                </li>
            @endif

            @if(hasAccess(['modules','positions']))
                <li class="menu {{ activeMenu('modules') }}">
                    <a>
                        <i class="fa fa-th"></i> <span>{{ transTpl('modules','sidebar') }}</span><i class="fa fa-caret-right pull-right"></i>
                    </a>
                    <ul class="sub-menu">
                        @if(hasAccess('modules')) <li class=""><a href="{{ adminRoute('modules.index') }}"><i class="fa fa-circle"></i>{{ transTpl('list_modules','sidebar') }}</a></li>@endif
                        @if(hasAccess('positions'))<li class=""><a href="{{ adminRoute('positions.index') }}"><i class="fa fa-circle"></i> {{ transTpl('position_modules','sidebar') }}</a>
                        </li>@endif
                    </ul>
                </li>
            @endif

            @if(hasAccess(['admins','roles','permissions']) && config('app.core_admin_controller'))
            <li class="menu {{ activeMenu('admins') }} {{ activeMenu('roles') }} {{ activeMenu('permissions') }}">
                <a><i class="fa fa-cog"></i> <span>{{ transTpl('management','sidebar') }}</span><i class="fa fa-caret-right pull-right"></i></a>
                <ul class="sub-menu">
                    @if(hasAccess('admins')) <li class="{{ activeMenu('admins') }}"><a href="{{ adminRoute('admins.index') }}"><i class="fa fa-circle"></i>{{ transTpl('employees','sidebar') }}</a></li>@endif
                    @if(hasAccess('roles'))<li class="{{ activeMenu('roles') }}"><a href="{{ adminRoute('roles.index') }}"><i class="fa fa-circle"></i> {{ transTpl('roles','sidebar') }}</a></li>@endif
                    @if(hasAccess('permissions'))<li class="{{ activeMenu('permissions') }}"><a href="{{ adminRoute('permissions.index') }}"><i class="fa fa-circle"></i> {{ transTpl('permissions','sidebar') }}</a></li>@endif
                </ul>
            </li>
                @endif
                @if(hasAccess(['settings','translates']))
                    <li class="menu {{ activeMenu('translates') }} {{ activeMenu('settings') }} {{ activeMenu('performances') }}">
                        <a><i class="fa fa-wrench"></i> <span>{{ transTpl('settings','sidebar') }}</span><i class="fa fa-caret-right pull-right"></i></a>
                        <ul class="sub-menu">
                            @if(hasAccess('settings')) <li class="{{ activeMenu('settings') }}"><a href="{{ adminRoute('settings.settings',['settings'=>'general']) }}"><i class="fa fa-circle"></i>{{ transTpl('settings','sidebar') }}</a></li>@endif
                            @if(hasAccess('translates')) <li class="{{ activeMenu('translates') }}"><a href="{{ adminRoute('translates.index') }}"><i class="fa fa-circle"></i>{{ transTpl('translates','sidebar') }}</a></li>@endif
                        </ul>
                    </li>
                @endif
                {!! hook('displayAdminSideBar') !!}
        </ul>
    <!-- /.sidebar -->
</div>