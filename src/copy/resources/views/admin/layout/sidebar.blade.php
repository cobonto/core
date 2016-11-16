<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        {!! $HOOK_SIDEBAR_TOP !!}
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
        <ul class="sidebar-menu">
            @if(hasAccess('dashboard'))
                <li class="{{ activeMenu('admin/dashboard') }}">
                    <a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> <span>{{ transTpl('dashboard','sidebar') }}</span>
                        <!--<i class="fa fa-angle-left pull-right"></i>-->
                    </a>
                </li>
            @endif
            @if(hasAccess(['users','groups']))
                <li class="treeview {{ activeMenu('admin/users') }} {{ activeMenu('admin/groups') }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa fa-user"></i> <span>{{ transTpl('users','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                       @if(hasAccess('users')) <li class="{{ activeMenu('admin/users') }}"><a href="{{ route('admin.users.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('users','sidebar') }}</a></li>@endif
                        @if(hasAccess('groups'))<li class="{{ activeMenu('admin/groups') }}"><a href="{{ route('admin.groups.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('groups','sidebar') }}</a></li>@endif
                    </ul>
                </li>
            @endif
            @if(hasAccess(['modules','positions']))
                <li class="treeview {{ activeMenu('admin/modules') }}">
                    <a href="{{ route('admin.modules.index') }}">
                        <i class="fa fa-th"></i> <span>{{ transTpl('modules','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if(hasAccess('modules')) <li class=""><a href="{{ route('admin.modules.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('list_modules','sidebar') }}</a></li>@endif
                        @if(hasAccess('positions'))<li class=""><a href="{{ route('admin.positions.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('position_modules','sidebar') }}</a>
                        </li>@endif
                    </ul>
                </li>
            @endif
                @if(hasAccess(['employees','roles','permissions']))
            <li class="treeview {{ activeMenu('admin/employees') }} {{ activeMenu('admin/roles') }} {{ activeMenu('admin/permissions') }}">
                <a href=""><i class="fa fa-cog"></i> <span>{{ transTpl('management','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @if(hasAccess('employees')) <li class="{{ activeMenu('admin/employees') }}"><a href="{{ route('admin.employees.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('employees','sidebar') }}</a></li>@endif
                    @if(hasAccess('roles'))<li class="{{ activeMenu('admin/roles') }}"><a href="{{ route('admin.roles.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('roles','sidebar') }}</a></li>@endif
                    @if(hasAccess('permissions'))<li class="{{ activeMenu('admin/permissions') }}"><a href="{{ route('admin.permissions.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('permissions','sidebar') }}</a></li>@endif
                </ul>
            </li>
                @endif
                @if(hasAccess(['settings','translates','performances']))
                    <li class="treeview {{ activeMenu('admin/translates') }} {{ activeMenu('admin/settings') }} {{ activeMenu('admin/performances') }}">
                        <a href=""><i class="fa fa-wrench"></i> <span>{{ transTpl('settings','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            @if(hasAccess('translates')) <li class="{{ activeMenu('admin/translates') }}"><a href="{{ route('admin.translates.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('translates','sidebar') }}</a></li>@endif
                        </ul>
                    </li>
                @endif
            {!! $HOOK_SIDEBAR !!}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>