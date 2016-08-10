<div class="form-group {{ isset($input['has'])?$input['has']:''}}">
    <div class="col-sm-offset-2 col-sm-10">

        @foreach($input['options'] as $option)
            <div class="radio">
                <label>
                    <input id="@if(isset($input['id'])){{ $input['id'] }} @else {{ $input['name'] }} @endif" name="{{ $input['name'] }}" class="@if(isset($input['class'])){{ $input['class'] }} @endif"
                           value="{{ $option['value'] }}" type="radio" @if($option['value']==$values[$input['name']]) checked="checked" @endif
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