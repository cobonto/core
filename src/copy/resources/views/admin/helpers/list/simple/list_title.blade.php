@foreach($titles as $key=>$title)
    @if($key==0)
        <li>{{ $title['name'] }}<</li>
    @else
        @if($id !=$title['id'])
            <li><i class="fa fa-angle-right"></i><a href="{{ $title['link'] }}">{{ $title['name'] }}</a></li>
        @else
        <li><i class="fa fa-angle-right"></i> {{ $title['name'] }}</li>
        @endif
    @endif

@endforeach
