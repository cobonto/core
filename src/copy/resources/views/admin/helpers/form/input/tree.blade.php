<div class="form-group {{ isset($input['has'])?$input['has']:''}}">
    <label for="{{ isset($input['id'])? $input['id'] : $input['name'] }}"
           class="col-lg-2 control-label">{{ $input['title'] }}</label>

    <div class="col-lg-{{isset($input['col'])? $input['col']:9 }}">
        @if($input['multiSelect'])
            @foreach($values[$input["name"]] as $id)
                <input type="hidden" name="{{ $input['name'] }}[]" id="selected_{{ $id }}" value="{{ $id }}"/>
            @endforeach
        @else
            <input name="{{ $input['name'] }}" type="hidden" value="{{ $values[$input["name"]] }}"/>
        @endif

        <div id="{{ isset($input['id'])? $input['id'] : $input['name'] }}"></div>
    </div>
</div>
@if(isset($return) && $return)
    <script type="text/javascript">
        $(document).ready(function () {
            {!! $input['javascript'] !!}
            // for selected
            $('#' + tree_id).on('nodeSelected', function (event, data) {
                if (multiSelect == false) {
                    $('input[name=' + tree_name + ']').val(data.id);
                }
                else {
                    $('#' + tree_id).before('<input type=hidden id="selected_' + data.id + '" name="' + tree_name + '[]" value="' + data.id + '" />');
                }
            });
            $('#' + tree_id).on('nodeUnselected', function (event, data) {
                if (multiSelect == false) {
                    $("input[name=" + tree_name + "]").val('');
                }
                else {
                    $('#' + tree_id).parent('div').find('#selected_' + data.id).remove();
                }
            });
        });

    </script>
@else
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                {!! $input['javascript'] !!}
                // for selected
                $('#' + tree_id).on('nodeSelected', function (event, data) {
                    if (multiSelect == false) {
                        $('input[name=' + tree_name + ']').val(data.id);
                    }
                    else {
                        $('#' + tree_id).before('<input type=hidden id="selected_' + data.id + '" name="' + tree_name + '[]" value="' + data.id + '" />');
                    }
                });
                $('#' + tree_id).on('nodeUnselected', function (event, data) {
                    if (multiSelect == false) {
                        $("input[name=" + tree_name + "]").val('');
                    }
                    else {
                        $('#' + tree_id).parent('div').find('#selected_' + data.id).remove();
                    }
                });
            });

        </script>
    @endpush
@endif