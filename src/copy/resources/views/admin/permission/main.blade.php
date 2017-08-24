@extends('admin.layout.main')
@section('content')
    <div class="tab-block page-content row">
        <div class="col-lg-2">

            <!-- Nav tabs -->
            <ul class="nav roles tabs-left border-left">
                @foreach($roles as $role)
                    <li class="{{ $role->id==$roles[0]->id?'active':''}}"><a data-role="{{ $role->id }}" href="#profile_{{$role->id}}" data-toggle="tab">{{ $role->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-10">
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
@endsection