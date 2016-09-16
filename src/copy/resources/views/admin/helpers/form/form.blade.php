<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="POST" action="{{ $form_url }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $id or '' }}"/>
            @foreach($forms as $form)
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@if(isset($form['title'])) {{ $form['title'] }}@else {{ transTpl('form','helpers')}} @endif</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <div class="box-body">
                        @foreach($form['input'] as $input)
                            @include('admin.helpers.form.input.'.$input['type'])
                        @endforeach
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a class="btn btn-default" href="{{ $route_list }}"><i class="fa fa-remove"></i>{{ transTpl('cancel','helpers') }}</a>
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
                            @foreach($form['submit'] as $button)
                                <button type="submit" name="{{ $button['name'] }}"
                                        class="btn {{  isset($button['class'])?$button['class']:'btn-primary'}} pull-right">{{ $button['title'] }}</button>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>
</div>