<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="POST" action="{{ $form_url }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $id or '' }}"/>
            @foreach($forms as $form)
                <div class="panel panel-flat">
                    <div class="panel-heading with-border">
                        <h3 class="panel-title">@if(isset($form['title'])) {{ $form['title'] }}@else {{ transTpl('form','helpers')}} @endif</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <div class="panel-body">
                        @foreach($form['input'] as $input)
                            @include('admin.helpers.form.input.'.$input['type'])
                        @endforeach
                    </div>
                    <!-- /.box-body -->
                    <div class="panel-footer">
                        @if($route_list)
                            <a class="btn btn-default" href="{{ $route_list }}"><i class="fa fa-remove"></i>{{ transTpl('cancel','helpers') }}</a>
                        @endif
                        @if(isset($form['links']))
                            @foreach($form['links'] as $link)
                                <a @if(isset($link['id'])) id="{{ $link['id'] }}" @endif href="{{ $link['link'] }}"
                                   class="btn btn-default {{ isset($link['class'])?$link['class']:'' }}">
                                    @if(isset($link['icon']))<i class="fa fa-{{ $link['icon'] }}"></i>@endif
                                    {{ $link['name'] }}
                                </a>
                            @endforeach
                        @endif
                        @if(isset($form['buttons']))
                            @foreach($form['buttons'] as $button)
                                <button type="{{$button['type']}}"
                                        class="btn btn-default{{ isset($button['class'])?$button['class']:'' }}">
                                    @if(isset($button['icon']))<i class="fa fa-{{ $button['icon'] }}"></i>@endif
                                    {{ $button['name'] }}
                                </button>
                            @endforeach
                        @endif
                        @if(isset($form['submit']))
                            @if(isset($controller))
                                @if( (!isset($id) && ($controller->hasPermission('store'))) || ($controller->hasPermission('store') && $controller->hasPermission('store')))
                                    @foreach($form['submit'] as $button)
                                        <button value="1" type="submit" name="{{ $button['name'] }}"
                                                class="btn {{  isset($button['class'])?$button['class']:'btn-primary'}} pull-right">{{ $button['title'] }}</button>
                                    @endforeach
                                @endif
                            @else
                                @foreach($form['submit'] as $button)
                                    <button value="1" type="submit" name="{{ $button['name'] }}"
                                            class="btn {{  isset($button['class'])?$button['class']:'btn-primary'}} pull-right">{{ $button['title'] }}</button>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>
</div>