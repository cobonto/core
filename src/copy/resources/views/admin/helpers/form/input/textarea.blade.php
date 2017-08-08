<div class="form-group {{ isset($input['has'])?$input['has']:''}}">
    <label for="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
           class="col-lg-2 control-label">{{ $input['title'] }}</label>

    <div class="@if(isset($input['suffix']) || isset($input['prefix']))
            input-group
    @endif col-lg-@if(isset($input['col'])){{ $input['col'] }}@else 6 @endif @if(isset($input['suffix']) || isset($input['prefix'])) input-group @endif">
        @if(isset($input['prefix']))<span class="input-group-addon">{!!  $input['prefix']  !!}</span>@endif
        <textarea name="{{ $input['name'] }}" rows="{{ isset($input['rows'])?$input['rows']:5 }}"
                  cols="{{ isset($input['cols'])?$input['cols']:10 }}"
                  class="form-control @if(isset($input['class'])){{ $input['class'] }} @endif"
                  id="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
        @if(isset($input['attributes']) && count($input['attributes']))
            @foreach($input['attributes'] as $key=>$value)
                {{ $key }}="{{ $value }}"
            @endforeach
        @endif
        >{{ $values[$input['name']] }}</textarea>
        @if(isset($input['suffix']))<span class="input-group-addon">{!!  $input['suffix']  !!}</span>@endif
        @if(isset($input['desc']))<span class="help-block">{{ $input['desc'] }}</span>@endif
    </div>

</div>
@if(isset($return) && $return)
    @if(isset($input['javascript']))
        <script type="text/javascript">
            $(document).ready(function () {
                {!! $input['javascript'] !!}

            });
        </script>
    @endif
@else
    @if(isset($input['javascript']))
        @push('scripts')
            <script type="text/javascript">
                $(document).ready(function () {
                    {!! $input['javascript'] !!}

                });
            </script>
        @endpush
    @endif
@endif