<div class="btn-group">
    <button class="btn btn-default"
            type="button">{{ transTpl('unregister') }}</button>
    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle"
            type="button" aria-expanded="false">
        <span class="caret"></span>
    </button>
    <ul role="menu" class="dropdown-menu">
        <!-- install or unistall link -->
        <li>
            <a class="unregister" href="#">{{ transTpl('unregister') }}</a>
        </li>

    </ul>
</div>