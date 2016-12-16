@extends('admin.layout.main')
@section('content')
    <div id="ettings" class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{transTpl('settings','settings')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <div class="col-xs-3"> <!-- required for floating -->
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabs-left">
                           @include('admin.settings.navbar')
                        </ul>
                    </div>
                    <div class="col-xs-9">
                        <!-- Tab panes -->
                        <div class="tab-content">
                                <div class="tab-pane active" id="profile_{{$active_settings}}">
                                  {!! $html !!}
                                </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection