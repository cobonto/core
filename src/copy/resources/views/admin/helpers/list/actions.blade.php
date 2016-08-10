<div class="btn-group">
    <button class="btn btn-info" type="button">Actions</button>
    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle" type="button" aria-expanded="false">
        <span class="caret"></span>
    </button>
    <ul role="menu" class="dropdown-menu">
        @foreach($actions as $action=>$params)
            <li>
                @if(isset($params['route_name']))
                    <a href="{!! route($params['route_name'],['id'=>$data->id]) !!}">{{$params['name']}}</a>
                @else
                    <a class="{{ $action }}" href="{!! route($route_name.$action,['id'=>$data->id]) !!}">{{ $params['name']}}</a>
                @endif
            </li>
        @endforeach
    </ul>
</div>