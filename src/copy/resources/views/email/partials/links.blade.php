@if($link1!=null)
<font style="font-family:tahoma;font-size:13px;">
    <a href="{{ $link1 }}">{{$name1}}</a>
</font>
<font class="dot" style="color:#ff6600;">
    &nbsp;●&nbsp;
</font>
@endif
@if($link2!=null)
    <font style="font-family:tahoma;font-size:13px;">
        <a href="{{ $link2 }}">{{$name2}}</a>
    </font>
    <font class="dot" style="color:#ff6600;">
        &nbsp;●&nbsp;
    </font>
@endif
@if($link3!=null)
    <font style="font-family:tahoma;font-size:13px;">
        <a href="{{ $link3 }}">{{$name3}}</a>
    </font>
    <font class="dot" style="color:#ff6600;">
        &nbsp;●&nbsp;
    </font>
@endif
@if($link4!=null)
    <font style="font-family:tahoma;font-size:13px;">
        <a href="{{ $link4 }}">{{$name4}}</a>
    </font>
    <font class="dot" style="color:#ff6600;">
        &nbsp;●&nbsp;
    </font>
@endif