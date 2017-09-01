php@extends('admin.layout.main')
@section('content')<div class="row"><div class="col-lg-12">
        <div class="toolbar clearfix">
            <div class="toolbar-title">{{ $title }}</div>
            <div class="toolbar-buttons">
                <button type="button" class="new btn btn-default btn-primary" data-toggle="modal" data-target="#myModal">
                    ماژول جدید
                </button>
                <a href="{{ $clear_cache_url }}" class="rebuild btn btn-default btn-info">
                    دوباره سازی لیست
                </a>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <form method="post" enctype="multipart/form-data" action="{{ $upload_url }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">آپلود کردن ماژول</h4>
                                </div>
                                <div class="modal-body">
                                    <label class="btn btn-default btn-file">
                                        Select module <input type="file" name="module"/>
                                    </label>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn btn-primary">آپلود</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{--<div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>--}}
            </div>
        </div>
        @if(count($modules))
            @foreach($modules as $author=>$data)
                <div class="panel panel-flat panel-module">
                    <div class="panel-heading">{{ $author }}</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-module">
                                <thead>
                                <tr>
                                    <th class="col-md-8 col-lg-8 name">نام</th>
                                    <th class="col-md-2 col-lg-2 version">نسخه</th>
                                    <th class="col-md-2 col-lg-2 actions">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $module)
                                    <tr class="{{ strtolower($author) }}">
                                        <td class="name">{{$module['name']}}</td>
                                        <td class="version">{{ $module['version'] }}</td>
                                        <td class="actions">@include('admin.module.actions')</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif</div></div>
@endsection