<div class="form-group {{ isset($input['has'])?$input['has']:''}}">
    <label for="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
           class="col-lg-2 control-label">{{ $input['title'] }}</label>

    <div class="col-lg-{{isset($input['col'])? $input['col']:6 }}">
        @if(isset($input['prefix']))<span class="input-group-addon">{!!  $input['prefix']  !!}</span>@endif
        <input type="checkbox" class="form-control {{ isset($input['class'])?$input['class']:'' }}" id="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
        @if(isset($input['attributes']) && count($input['attributes']))
            @foreach($input['attributes'] as $key=>$value)
                {{ $key }}="{{ $value }}"
            @endforeach
        @endif @if($values[$input['name']]) checked="checked" @endif />
        @if(isset($input['desc']))<span class="help-block">{{ $input['desc'] }}</span>@endif
    </div>

</div>
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        {!! $input['javascript'] !!}

    });
</script>
@endpush
