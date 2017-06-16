@extends('admin.layout.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ transTpl('translate_file','translate') }}</h3>
                </div>
                <form class="form-horizontal" role="form" method="post" action="{{ $controller->getRoute('load',true) }}">
                    {!! csrf_field() !!}
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="language">{{ transTpl('language','translate')}}</label>
                            <div class="col-md-10">
                                <select id="language" class="form-control" name="language">
                                    @foreach($languages as $language)
                                        <option value="{{ $language }}">{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="environment">{{ transTpl('environment','translate')}}</label>
                            <div class="col-md-10">
                                <select class="form-control" id="environment" name="environment">
                                    <option value="0" selected="selected">--</option>
                                    @foreach($environment as $key=>$value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group hidden">
                            <label class="col-md-2 control-label" for="file">{{ transTpl('file','translate')}}</label>
                            <div class="col-md-10">
                                <select class="form-control" id="file" name="file"></select>
                            </div>
                        </div>
                    </div>
                    <div id="load" class="box-footer hidden ">
                        <button type="submit" class="btn btn-primary pull-right">{{ transTpl('load','translate') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection