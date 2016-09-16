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
            <li class="{{ activeMenu('admin/dashboard') }}">
                <a href="{{ route('admin.dashboard.index') }}">
                    <i class="fa fa-dashboard"></i> <span>{{ transTpl('dashboard','sidebar') }}</span> <!--<i class="fa fa-angle-left pull-right"></i>-->
                </a>
            </li>
            <li class="{{ activeMenu('admin/users') }}">
                <a href="{{ route('admin.users.index') }}">
                    <i class="fa fa-user"></i> <span>{{ transTpl('users','sidebar') }}</span><!--<i class="fa fa-angle-left pull-right"></i>-->
                </a>
            </li>
            <li class="treeview {{ activeMenu('admin/modules') }}">
                <a href="{{ route('admin.modules.index') }}">
                    <i class="fa fa-th"></i> <span>{{ transTpl('modules','sidebar') }}</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="{{ route('admin.modules.index') }}"><i class="fa fa-circle-o"></i>{{ transTpl('list_modules','sidebar') }}</a></li>
                    <li class=""><a href="{{ route('admin.positions') }}"><i class="fa fa-circle-o"></i> {{ transTpl('position_modules','sidebar') }}</a></li>
                </ul>
            </li>
            {!! $HOOK_SIDEBAR !!}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>