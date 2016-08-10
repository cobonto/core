<div class="form-group {{ isset($input['has'])?$input['has']:''}}">
    <div class="col-sm-offset-2 col-sm-10">

        @foreach($input['options'] as $option)
            <div class="checkbox">
                <label>
                    <input name="{{ $input['name'] }}[]" class="{{isset($input['class'])? $input['class']:'' }}"
                           value="{{ $option['value'] }}" type="checkbox" @if(in_array($option['value'],explode(',',$values[$input['name']]))) checked="checked" @endif
                    @if(isset($input['attributes']) && count($input['attributes']))
                        @foreach($input['attributes'] as $key=>$value)
                            {{ $key }}="{{ $value }}"
                        @endforeach
                    @endif
                    />
                    {{ $option['label'] }}
                </label>
            </div>

        @endforeach
            @if(isset($input['desc']))<span class="help-block">{{ $input['desc'] }}</span>@endif
    </div>
</div>