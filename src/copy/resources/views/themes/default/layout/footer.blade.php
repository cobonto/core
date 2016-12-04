@hasSection('footer')
    @yield('footer')
@else
<footer>
    {!! hook('displayFooter') !!}
</footer>
@endif