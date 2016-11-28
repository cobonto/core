<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
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
        <ul class="sidebar-menu">
            @if(hasAccess('dashboard'))
                <li class="{{ activeMenu('dashboard') }}">
                    <a href="{{ adminRoute('dashboard.index') }}"><i class="fa fa-dashboard"></i> <span>{{ transTpl('dashboard','sidebar') }}</span>
                        <!--<i class="fa fa-angle-left pull-right"></i>-->
                    </a>
                </li>
            @endif
            {!! hook('displayAdminSideBar') !!}
            @if(hasAccess(['users','groups']))
                <li class="treeview {{ activeMenu('users') }} {{ activeMenu('admin/groups') }}">
                    <a href="{{ adminRoute('users.index') }}">
                        <i class="fa fa-user"></i> <span>{{ transTpl('users','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                       @if(hasAccess('users')) <li class="{{ activeMenu('users') }}"><a href="{{ adminRoute('users.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('users','sidebar') }}</a></li>@endif
                        @if(hasAccess('groups'))<li class="{{ activeMenu('groups') }}"><a href="{{ adminRoute('groups.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('groups','sidebar') }}</a></li>@endif
                    </ul>
                </li>
            @endif
            @if(hasAccess(['modules','positions']))
                <li class="treeview {{ activeMenu('modules') }}">
                    <a href="{{ adminRoute('modules.index') }}">
                        <i class="fa fa-th"></i> <span>{{ transTpl('modules','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if(hasAccess('modules')) <li class=""><a href="{{ adminRoute('modules.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('list_modules','sidebar') }}</a></li>@endif
                        @if(hasAccess('positions'))<li class=""><a href="{{ adminRoute('positions.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('position_modules','sidebar') }}</a>
                        </li>@endif
                    </ul>
                </li>
            @endif
                @if(hasAccess(['employees','roles','permissions']))
            <li class="treeview {{ activeMenu('employees') }} {{ activeMenu('roles') }} {{ activeMenu('permissions') }}">
                <a href=""><i class="fa fa-cog"></i> <span>{{ transTpl('management','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @if(hasAccess('employees')) <li class="{{ activeMenu('employees') }}"><a href="{{ adminRoute('employees.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('employees','sidebar') }}</a></li>@endif
                    @if(hasAccess('roles'))<li class="{{ activeMenu('roles') }}"><a href="{{ adminRoute('roles.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('roles','sidebar') }}</a></li>@endif
                    @if(hasAccess('permissions'))<li class="{{ activeMenu('permissions') }}"><a href="{{ adminRoute('permissions.index') }}"><i class="fa fa-circle-o"></i> {{ transTpl('permissions','sidebar') }}</a></li>@endif
                </ul>
            </li>
                @endif
                @if(hasAccess(['settings','translates','performances']))
                    <li class="treeview {{ activeMenu('translates') }} {{ activeMenu('settings') }} {{ activeMenu('performances') }}">
                        <a href=""><i class="fa fa-wrench"></i> <span>{{ transTpl('settings','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            @if(hasAccess('translates')) <li class="{{ activeMenu('translates') }}"><a href="{{ adminRoute('translates.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('translates','sidebar') }}</a></li>@endif
                        </ul>
                    </li>
                @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>