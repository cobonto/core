@extends('admin.layout.main')
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