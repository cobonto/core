@if($errors->has() or session('success') or session('warning') or session('info'))
    <div class="callout callout-@if($errors->has())danger @elseif(session('success'))success @elseif(session('warning'))warning @elseif(session('info'))info @endif">
        <h4>@if($errors->has())You have Error(s): @elseif(session('success'))Success! @elseif(session('warning'))Note!@elseif(session('info'))Information!@endif</h4>
        @if($errors->has())
            @foreach($errors->all() as $error)
                <p>{!!  $error !!}</p>
            @endforeach
        @elseif(session('warning'))
            @foreach(session('warning') as $warning)
                <p>{!!  $warning !!}</p>
            @endforeach
        @elseif(session('success'))
            <p>{!!  session('success') !!}</p>
        @elseif(session('info'))
            @foreach(session('info') as $info)
                <p>{!!  $info !!}</p>
            @endforeach

        @endif

    </div>
@endif