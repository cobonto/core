<div class="form-group {{ isset($input['has'])?$input['has']:''}}">
    <label for="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
           class="col-lg-2 control-label">{{ $input['title'] }}</label>

    <div class="col-lg-{{isset($input['col'])? $input['col'] : 6 }}">
        <select class="form-control {{isset($input['class'])? $input['class']:''}}"
                name="{{ $input['name'] }}"
                id="{{ isset($input['id'])?$input['id']:$input['name'] }}"
        @if(isset($input['attributes']) && count($input['attributes']))
            @foreach($input['attributes'] as $key=>$value)
                {{ $key }}="{{ $value }}"
            @endforeach
        @endif
        >
        @foreach($input['options']['query'] as $option)
            @if(!isset($input['attributes']['multiple']))
                <option value="{{ $option[$input['options']['key']] }}"
                        @if($option[$input['options']['key']]== $values[$input["name"]])selected="selected" @endif>{{ $option[$input['options']['name']] }}</option>
            @else
                <option value="{{ $option[$input['options']['key']] }}"
                        @if($id && in_array($option[$input['options']['key']], $values[$input["name"]]))selected="selected" @endif>{{ $option[$input['options']['name']] }}</option>
                @endif
                @endforeach
                </select>
                @if(isset($input['desc']))<span class="help-block">{{ $input['desc'] }}</span>@endif
    </div>
</div>
@if(isset($return) && $return)
    <script type="text/javascript">
        $(document).ready(function () {
            {!! $input['javascript'] !!}
        });
    </script>
@else
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                {!! $input['javascript'] !!}
            });
        </script>
    @endpush
@endif