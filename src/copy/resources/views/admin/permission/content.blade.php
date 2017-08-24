<div class="panel">
    @foreach($access as $app)
    <table class="table" id="table_2">
        <thead>
        <tr>
            <th></th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="viewall ajaxPower"
                       data-rel="-1||2||view||112||107">
                {{ transTpl('view','permission') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="addall ajaxPower"
                       data-rel="-1||2||add||112||107">
                {{ transTpl('add','permission') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="editall ajaxPower"
                       data-rel="-1||2||edit||112||107">
                {{ transTpl('edit','permission') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="deleteall ajaxPower"
                       data-rel="-1||2||delete||112||107">
                {{ transTpl('destroy','permission') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="allall ajaxPower"
                       data-rel="-1||2||all||112||107">
                {{ transTpl('store','permission') }}
            </th>
        </tr>
        </thead>
        <tbody>
        <caption>{{ $app['title'] }}</caption>
        @foreach($app['controllers'] as $controller)
            <tr class="parent">
                <td class="bold"><strong>{{ $controller['name'] }}</strong></td>
                <td>
                    <input @if(isset($controller['exceptions']) && in_array('index',$controller['exceptions']))disabled @endif type="checkbox" {{ isset($permissions["{$controller['route']}.index"])?'checked="checked"':'' }} data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.index" data-role="{{ $role->id}}" class="ajaxPower index">
                </td>
                <td>
                    <input @if(isset($controller['exceptions']) && in_array('create',$controller['exceptions']))disabled @endif type="checkbox" {{ isset($permissions["{$controller['route']}.create"])?'checked="checked"':'' }}  data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.create" data-role="{{  $role->id }}" class="ajaxPower create">
                </td>
                <td>
                    <input @if(isset($controller['exceptions']) && in_array('edit',$controller['exceptions']))disabled @endif type="checkbox" {{ isset($permissions["{$controller['route']}.edit"])?'checked="checked"':'' }}  data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.edit" data-role="{{  $role->id }}" class="ajaxPower edit">
                </td>
                <td>
                    <input @if(isset($controller['exceptions']) && in_array('destroy',$controller['exceptions']))disabled @endif type="checkbox" {{ isset($permissions["{$controller['route']}.destroy"])?'checked="checked"':'' }}  data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.destroy" data-role="{{  $role->id }}" class="ajaxPower destroy">
                </td>
                <td>
                    <input @if(isset($controller['exceptions']) && in_array('store',$controller['exceptions']))disabled @endif type="checkbox" {{ isset($permissions["{$controller['route']}.store"])?'checked="checked"':'' }} data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.store" data-role="{{ $role->id }}" class="ajaxPower store">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
        @endforeach
        {!! hook('displayPermissions',['permissions'=>$permissions,'role'=>$role]) !!}
</div>