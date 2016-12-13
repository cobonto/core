<!DOCTYPE html>
<html lang="{{env('locale')}}">
<!--header-->
@include('admin.layout.header')
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="breadcrumb">
        <ol>
            @include('admin.layout.breadcrumb')
        </ol>
    </div>
    <!-- Content Header maybe yeild  -->
    @hasSection ('header')
    @yield('header')
    @else
        {{--<section class="content-header">
            <h1>
                {{ $title }}
            </h1>
            
        </section>--}}
    @endif
    <section class="content">
        @include('admin.layout.messages')
        @yield('content')
    </section>
</div>

<!-- footer -->
@include('admin.layout.footer')
</div>
<!-- srcript -->

@if(count($javascript_files))
    @foreach($javascript_files as $file)
        <script type="text/javascript" src="{{ asset($file) }}"></script>
    @endforeach

@endif
@include('admin.layout.javascript')
</html>
