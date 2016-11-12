@extends('admin.layout.main')
@section('content')
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-red">403</h2>
            <div class="error-content">
                <h3><i class="fa fa-warning text-red"></i>{{ transTpl('limited_access','403') }}</h3>
                <p>
                    {{ transTpl('limited_access_description','403') }}
                </p>
            </div>
        </div>
    </section>
@endsection
