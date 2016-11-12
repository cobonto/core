@extends('admin.layout.main')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('roles')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">

                    <div class="col-xs-3"> <!-- required for floating -->
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabs-left">
                            @foreach($roles as $role)
                            <li class="{{ $role->id==$roles[0]->id?'active':''}}"><a data-role="{{ $role->id }}" href="#profile_{{$role->id}}" data-toggle="tab">{{ $role->name }}</a></li>
                                @endforeach
                        </ul>
                    </div>
                    <div class="col-xs-9">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            @foreach($roles as $role)
                            <div class="tab-pane {{ $role->id==$roles[0]->id?'active':''}}" id="profile_{{ $role->id }}">
                                @if($role->id==1)
                                    <div class="alert alert-warning">{{ transTpl('no_edit_administrator','permission') }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection