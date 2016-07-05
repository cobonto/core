@extends('admin.layout.main')
@section('content')
        <!-- display hook top of dashboard -->
    <div class="row">

        {!! $HOOK_DASHBOARD_TOP !!}
    </div>
    <!--hook right and left -->
    <div class="row">
        <div class="col-lg-7">{!! $HOOK_DASHBOARD_LEFT !!}</div>
        <div class="col-lg-5">{!! $HOOK_DASHBOARD_RIGHT !!}</div>
    </div>
    <!--hook right and left -->
    <div class="row">
        {!! $HOOK_DASHBOARD_FOOTER !!}
    </div>
@endsection