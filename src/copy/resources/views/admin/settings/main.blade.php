@extends('admin.layout.main')
@section('content')
    <div id="ettings" class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{{transTpl('settings','settings')}}</h3>
                </div>
                <!-- /.panel-header -->
                <div class="panel-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                       @include('admin.settings.navbar')
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="profile_{{$active_settings}}">
                          {!! $html !!}
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
@endsection