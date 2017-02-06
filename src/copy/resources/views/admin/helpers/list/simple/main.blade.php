@extends('admin.layout.main')
{{--@section('header')
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <h5>
                    {{ $title }}
                </h5>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
    </section>
@endsection--}}
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    {{ $title }}
                    @if($search)
                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right"
                                       placeholder="Search">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($create)
                        <a class="create btn btn-default btn-circle btn-info" href="{!! route($route_name.'create') !!}">
                            <i class="fa fa-plus"></i>
                            {{ transTpl('new') }}
                        </a>
                    @endif
                </div>
                <div class="box-body no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            @foreach($fields as $name=>$options)
                                <th style="width:{{ isset($options['width'])?$options['width']:'auto' }};text-align: center">{{ $options['title'] }}</th>
                            @endforeach
                            @if(count($actions))
                                <th style="width:auto;text-align: center">{{ transTpl('actions') }}</th>
                            @endif
                        </tr>
                        @if($filters)
                            <tr class="filter">
                                @include('admin.helpers.list.simple.filter')
                            </tr>
                        @endif
                        </thead>
                        <tbody>
                        @if(count($rows))
                            @foreach($rows as $row)
                                <tr @if($position_identifier) id="positions_{{ $row->{$position_identifier} }}|{{ $row->position }}"@endif>
                                    @foreach($fields as $name=>$options)
                                        @if(isset($options['type']) && $options['type']=='position')
                                            <td style="width:{{ isset($options['width'])?$options['width']:'auto' }};text-align: {{ isset($options['align'])?$options['align']:'center' }}"><div class="icon_position"><i class="fa fa-arrows"></div></td>
                                        @else
                                        <td id="{{ isset($options['id'])?$options['id']:$name}}"
                                            class="{{ isset($options['class'])?$options['class']:''}}"
                                            style="width:{{ isset($options['width'])?$options['width']:'auto' }};text-align: {{ isset($options['align'])?$options['align']:'center' }}">{!! isset($options['function'])?$controller->{$options['function']}($row):$row->{$name} !!}
                                        </td>
                                        @endif
                                    @endforeach
                                    @if(count($actions))
                                        @include('admin.helpers.list.simple.actions')
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <td colspan="99"><div class="no_data bg-warning">{{ transTpl('no_data') }}</div></td>
                        @endif
                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    @if($rows->total()>10)
                        <div class="col-lg-3 ">
                            <div class="pagination pull-left">
                                <form id="per_page_form" method="post" action="{{ adminRoute('list.filters') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="class_name" value="{{ get_class($controller) }}"/>
                                    <select name="perPage" onchange="$('#per_page_form').submit()" class="select2">
                                        @if($rows->total()>10)
                                            <option value="10" @if($per_page==10)selected="selected" @endif>10</option>@endif
                                        @if($rows->total()>20)
                                            <option value="20" @if($per_page==20)selected="selected" @endif>20</option>@endif
                                        @if($rows->total()>50)
                                            <option value="50" @if($per_page==50)selected="selected" @endif>50</option>@endif
                                        @if($rows->total()>100)
                                            <option value="100" @if($per_page==100)selected="selected" @endif>100</option>@endif
                                        @if($rows->total()>300)
                                            <option value="300" @if($per_page==300)selected="selected" @endif>300</option>@endif
                                    </select>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-9 ">
                        <div class="no-margin pull-right">
                            {{ $rows->render() }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection