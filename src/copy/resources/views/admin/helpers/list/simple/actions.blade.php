<td style="text-align: {{ isset($options['align'])?$options['align']:'center' }}">
    @if(count($actions)>count($skip_actions))
        <div class="btn-group">
            <button class="btn btn-info" type="button">{{ transTpl('actions','helpers') }}</button>
            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle" type="button" aria-expanded="false">
                <span class="caret"></span>
            </button>
            <ul role="menu" class="dropdown-menu">
                @foreach($actions as $action=>$params)
                    @if(!in_array($action,array_keys($skip_actions)) || (isset($skip_actions[$action]) && !in_array($row->id,$skip_actions[$action])))
                        <li>
                            @if(isset($params['route_name']))
                                <a href="{!! route($params['route_name'],['id'=>$row->id]) !!}">{{$params['name']}}</a>
                            @else
                                <a class="{{ $action }}"
                                   href="{!! route($route_name.$action,['id'=>$row->id]) !!}">{{ $params['name']}}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
</td>