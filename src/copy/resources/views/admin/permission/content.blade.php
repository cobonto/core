<div class="panel">
    <h3>{{transTpl('controllers','positions')}}</h3>
    <table class="table" id="table_2">
        <thead>
        <tr>
            <th></th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="viewall ajaxPower"
                       data-rel="-1||2||view||112||107">
                {{ transTpl('view','positions') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="addall ajaxPower"
                       data-rel="-1||2||add||112||107">
                {{ transTpl('add','positions') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="editall ajaxPower"
                       data-rel="-1||2||edit||112||107">
                {{ transTpl('edit','positions') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="deleteall ajaxPower"
                       data-rel="-1||2||delete||112||107">
                {{ transTpl('destroy','positions') }}
            </th>
            <th>
                <input disabled="disabled" type="checkbox" name="1" class="allall ajaxPower"
                       data-rel="-1||2||all||112||107">
                {{ transTpl('all','positions') }}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($controllers as $controller)
            <tr class="parent">
                <td class="bold"><strong>{{ $controller['name'] }}</strong></td>
                <td>
                    <input type="checkbox" {{ isset($permissions["{$controller['route']}.index"])?'checked="checked"':'' }} data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.index" data-role="{{ $role->id}}" class="ajaxPower index">
                </td>
                <td>
                    <input type="checkbox" {{ isset($permissions["{$controller['route']}.create"])?'checked="checked"':'' }}  data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.create" data-role="{{  $role->id }}" class="ajaxPower create">
                </td>
                <td>
                    <input type="checkbox" {{ isset($permissions["{$controller['route']}.edit"])?'checked="checked"':'' }}  data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.edit" data-role="{{  $role->id }}" class="ajaxPower edit">
                </td>
                <td>
                    <input type="checkbox" {{ isset($permissions["{$controller['route']}.destroy"])?'checked="checked"':'' }}  data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}.destroy" data-role="{{  $role->id }}" class="ajaxPower destroy">
                </td>
                <td>
                    <input disabled="disabled" type="checkbox" data-controller="{{ $controller['name'] }}" data-route="{{$controller['route']}}'.index" data-role="{{ \Auth::user()->role_id }}" class="all">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>