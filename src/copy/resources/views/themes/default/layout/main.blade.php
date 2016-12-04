<!DOCTYPE html>
<html lang="{{env('locale')}}" xmlns="http://www.w3.org/1999/html">
<!--header-->
@include(config('app.theme').'.layout.header')
@yield('content')
<!-- footer -->
@include(config('app.theme').'.layout.footer')
<!-- srcript -->

@if(count($javascript_files))
    @foreach($javascript_files as $file)
        <script type="text/javascript" src="{{ asset($file) }}"></script>
    @endforeach

@endif
@include('admin.layout.javascript')
</body>
</html>
