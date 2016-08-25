@extends('admin.layout.main')
@section('header')
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <h5>
                    {{ $title }}
                </h5>
            </div>
            <div class="col-lg-6">
                @if($create)
                    <a class="create btn btn-default btn-circle btn-info" href="{!! route($route_name.'create') !!}">
                        <i class="fa fa-plus"></i>
                        New
                    </a>
                @endif
            </div>
        </div>
        <ol class="breadcrumb">
            @include('admin.layout.breadcrumb')
        </ol>

    </section>
@endsection
@section('content')
    <div class="row">
        <div class="box">
            <div class="box-body">
                {!! $HtmlList->table() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{!! $HtmlList->scripts() !!}
@endpush