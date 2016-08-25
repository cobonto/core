@extends('admin.layout.main')
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
                                <tr>
                                    <td class="bold" id="{{ $author }}">{{ $author }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach($data as $module)
                                    <tr class="{{ $author }}">
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