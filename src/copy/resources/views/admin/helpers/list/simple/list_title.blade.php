@foreach($titles as $key=>$title)
    @if($key==0)
        <div class="bold box-title">{{ $title['name'] }}</div>
    @else
        &nbsp;<i class="fa fa-angle-right"></i>&nbsp;
        @if($id !=$title['id'])
            <a href="{{ $title['link'] }}">{{ $title['name'] }}</a>
        @else
            {{ $title['name'] }}
        @endif
    @endif

@endforeach
