<div class="btn-group">
    <button class="btn {{isset($module['installed'])?'btn-success':'btn-info'}}"
            type="button">{{isset($module['installed'])?'نصب شده':'نصب نشده'}}</button>
    @if(isset($module['core']) && $module['core'])
        @if(isset($module['configurable']) && $module['configurable'])
            <button data-toggle="dropdown"
                    class="btn {{isset($module['installed'])?'btn-success':'btn-info'}} dropdown-toggle"
                    type="button" aria-expanded="false">
                <span class="caret"></span>
            </button>
        @endif
    @else
        <button data-toggle="dropdown"
                class="btn {{isset($module['installed'])?'btn-success':'btn-info'}} dropdown-toggle"
                type="button" aria-expanded="false">
            <span class="caret"></span>
        </button>
    @endif
    <ul role="menu" class="dropdown-menu">
        <!-- install or unistall link -->
        @if(!isset($module['core']) || $module['core']==false)
            <li>
                <a href="{{isset($module['installed'])?adminRoute('modules.uninstall',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ]):
                    adminRoute('modules.install',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ])}}">{{isset($module['installed'])?'حذف نصب':'نصب'}}</a>
            </li>
            @endif
                    <!-- disable and enable -->
            @if(isset($module['installed']))
                @if(!isset($module['core']) || $module['core']==false)
                    <li>
                        <a href="{{$module['active'] ?adminRoute('modules.disable',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ]):
                    adminRoute('modules.enable',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ])}}">{{$module['active']?'غیرفعال':'فعال'}}</a>
                    </li>
                    @endif
                            <!-- configuration module -->
                    @if(isset($module['configurable']) && $module['configurable'])
                        <li>
                            <a href="{{adminRoute('modules.configure',['author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ])}}">پیکربندی </a>
                        </li>
                        @endif
                        @endif
                                <!-- remove module -->
                        @if(!isset($module['core']) || $module['core']==false)
                            <li>
                                <a class="delete" href="">حذف</a>
                                <form style="display:none"
                                      id="delete_{{ $module['author'] }}_{{ $module['name']}}"
                                      action="{{ adminRoute('modules.destroy',['id'=>'null']) }}"
                                      method="POST">
                                    <input type="hidden" name="author"
                                           value="{{ strtolower(camel_case($module['author'])) }}"/>
                                    <input type="hidden" name="name"
                                           value="{{ strtolower(camel_case($module['name'])) }}"/>
                                    <input type="hidden" name="_method" value="DELETE">
                                    {!! csrf_field() !!}
                                </form>
                            </li>
                        @endif
    </ul>
</div>