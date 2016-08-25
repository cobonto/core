<div class="btn-group">
    <button class="btn {{isset($module['installed'])?'btn-success':'btn-info'}}"
            type="button">{{isset($module['installed'])?'Installed':'Uninstalled'}}</button>
    <button data-toggle="dropdown" class="btn {{isset($module['installed'])?'btn-success':'btn-info'}} dropdown-toggle"
            type="button" aria-expanded="false">
        <span class="caret"></span>
    </button>
    <ul role="menu" class="dropdown-menu">
        <!-- install or unistall link -->
        <li>


            <a href="{{isset($module['installed'])?route('admin.modules.uninstall',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ]):
                    route('admin.modules.install',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ])}}">{{isset($module['installed'])?'Uninstall':'Install'}}</a>
        </li>
        <!-- disable and enable -->
        @if(isset($module['installed']))
            <li>
                <a href="{{$module['active'] ?route('admin.modules.disable',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ]):
                    route('admin.modules.enable',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ])}}">{{$module['active']?'Disable':'Enable'}}</a>
            </li>
            <!-- configuration module -->
            @if(isset($module['configurable']))
                <li>
                    <a href="{{
                    route('admin.modules.configure',[
                    'author'=>strtolower(camel_case($module['author'])),
                    'name'=>strtolower(camel_case($module['name'])),
                    ])}}">Configuration</a>
                </li>
            @endif
        @endif
    </ul>
</div>