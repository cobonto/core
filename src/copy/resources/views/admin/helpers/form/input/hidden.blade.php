<input name="{{ $input['name'] }}" type="{{ $input['type'] }}" class="form-control {{ isset($input['class'])?$input['class']:'' }}" id="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
@if(isset($input['attributes']) && count($input['attributes']))
@foreach($input['attributes'] as $key=>$value)
{{ $key }}="{{ $value }}"
@endforeach
@endif value="{{ $values[$input["name"]] }}"/>