<div class="form-group {{ isset($input['has'])?$input['has']:''}}">
    <label for="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
           class="col-lg-2 control-label">{{ $input['title'] }}</label>

    <div class="{{isset($input['suffix']) || isset($input['prefix'])?
            'input-group':''}} col-lg-{{isset($input['col'])? $input['col']:6 }}">
        @if(isset($input['prefix']))<span class="input-group-addon">{!!  $input['prefix']  !!}</span>@endif
        <input name="{{ $input['name'] }}" @if(isset($input['multiple'])) multiple="multiple"
               @endif type="{{ $input['type'] }}" class="form-control {{ isset($input['class'])?$input['class']:'' }}"
               id="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
        @if(isset($input['attributes']) && count($input['attributes']))
            @foreach($input['attributes'] as $key=>$value)
                {{ $key }}="{{ $value }}"
            @endforeach
        @endif value="{{ $values[$input["name"]] }}"/>
        @if(isset($input['suffix']))<span class="input-group-addon">{!!  $input['suffix']  !!}</span>@endif
        @if(isset($input['desc']))<span class="help-block">{{ $input['desc'] }}</span>@endif
    </div>
</div>
@if(isset($input['images']) && $input['images'])
    <div class="form-group">
        <div class="col-lg-2"></div>
        @foreach($input['images'] as $image)
            <div class="col-lg-1">
                <img src="{{ asset($image['url']) }}"/>
                @if(isset($input['multiple']) && $input['multiple'])
                    <input type="radio" @if($image->cover)checked="checked" @endif class="cover" name="cover" value="{{ $image->id }}" />
                    <a class="delete_image" href="{{ $image->delete_url }}"><i class="fa fa-trash"></i></a>
                @endif
            </div>
        @endforeach
    </div>
    <script id="delete_image_template" type="text/x-handlebars-template">
        <form id="delete_image_form" method="post" action="@{{ url_image }}">
            {!! csrf_field() !!}
            <input type="hidden" name="_method" value="DELETE">
        </form>
    </script>
@endif
