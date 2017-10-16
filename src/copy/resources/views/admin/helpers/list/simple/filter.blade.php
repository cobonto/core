@foreach($fields as $name=>$options)
    <form method="post" action="{{ adminRoute('list.filters') }}">
        {{ csrf_field() }}
        <input type="hidden" name="class_name" value="{{ get_class($controller) }}"/>
        <td class="filter" style="width:{{ isset($options['width'])?$options['width']:'auto' }};text-align: {{ isset($options['align'])?$options['align']:'center' }}">
            @if(isset($options['filter']) && $options['filter']==false)
                --
            @else
            <!-- choose type -->
                @if(!isset($options['filter_type']))
                    <input type="text" name="filter_{{ $name }}"
                           value="{{ isset($filter_values[$name])?trim($filter_values[$name]['value'],'%'):''}}"/>
                @else
                <!-- bool-->
                    @if($options['filter_type']=='bool')
                        <select name="filter_{{ $name }}" class="select2">
                            <option value="" @if(!isset($filter_values[$name]))selected @endif>--</option>
                            <option value="1" @if(isset($filter_values[$name]) && $filter_values[$name]['value']==1)selected @endif>{{ transTpl('yes') }}</option>
                            <option value="0" @if(isset($filter_values[$name]) && $filter_values[$name]['value']==0)selected @endif>{{ transTpl('no') }}</option>
                        </select>
                    @elseif($options['filter_type']=='select')
                    <!-- select filter -->
                        <select class="select2"  name="filter_{{ $name }}">
                            <option value="" @if(!isset($filter_values[$name]))selected @endif>--</option>
                            @if(count($options['filter_data']['data']))
                                @foreach($options['filter_data']['data'] as $row)
                                    <option value="{{ $row->{$options['filter_data']['key']} }}" @if(isset($filter_values[$name]) && $filter_values[$name]['value']==$row->{$options['filter_data']['key']})selected @endif>{{ $row->{$options['filter_data']['label']} }}</option>
                                @endforeach
                            @endif
                        </select>
                    @elseif($options['filter_type']=='date')
                        @push('scripts')
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('.datepicker').datepicker({
                                        language: 'fa'
                                    });
                                });
                            </script>
                        @endpush
                        <input class="datepicker" type="text" name="filter_{{ $name }}_from"
                               value="{{ isset($filter_values["{$name}_from"])?$filter_values["{$name}_from"]['value']:'' }}"/>
                        <input class="datepicker" type="text" name="filter_{{ $name }}_to"
                               value="{{ isset($filter_values["{$name}_to"])?$filter_values["{$name}_to"]['value']:'' }}"/>
                    @endif
                @endif
            @endif
        </td>
        @endforeach

        <th class="filters_btn" style="width:auto">
            <button type="submit" name="submitFilter" value="1" class="btn btn-primary">{{ transTpl('filter') }}</button>
            @if($filter_values)
                <button type="submit" name="resetFilter" value="1" class="btn btn-default">{{ transTpl('reset') }}</button>
            @endif
        </th>
    </form>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function(){
                $('.select2').select2({ width: '100%' });
            })
        </script>
    @endpush