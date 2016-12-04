@extends('admin.layout.main')
@section('header')
    <section class="content-header" xmlns="http://www.w3.org/1999/html">
        <div class="row">
            <div class="col-lg-6">
                <h5>
                    {{ $title }}
                </h5>
            </div>
            <div class="col-lg-6">
                <!-- new module -->
                <button type="button" class="new btn btn-default btn-primary" data-toggle="modal"
                        data-target="#myModal">
                    New module
                </button>
                <a href="{{ $clear_cache_url }}" class="rebuild btn btn-default btn-info">
                   Refresh list
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
                                    <h4 class="modal-title" id="myModalLabel">Upload module</h4>
                                </div>
                                <div class="modal-body">
                                    <label class="btn btn-default btn-file">
                                       Select module <input type="file" name="module"/>
                                    </label>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <ol class="breadcrumb">
            @include('admin.layout.breadcrumb')
        </ol>

    </section>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('List')}}</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Version</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($modules))
                            @foreach($modules as $author=>$data)
                                <tr class="author" id="{{ strtolower($author) }}">
                                    <td class="bold" >{{ $author }}&nbsp;&nbsp;<i class="fa fa-angle-left"></i></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($data as $module)
                                    <tr class="{{ strtolower($author) }} hidden">
                                        <td>{{$module['name']}}</td>
                                        <td>{{ $module['version'] }}</td>
                                        <td>@include('admin.module.actions')</td>
                                    <tr>
                                        @endforeach
                                    </tr>
                                @endforeach
                                @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection