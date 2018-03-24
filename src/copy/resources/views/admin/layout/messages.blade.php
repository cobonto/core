@if(count($errors) or session('success') or session('warning') or session('info'))
    <div class="col-lg-12">
        <div class="alert alert-@if(count($errors))red @elseif(session('success'))green @elseif(session('warning'))yellow @elseif(session('info'))blue @endif">
            <h4>@if(count($errors)){{ transTpl('have_errors','helpers') }} @elseif(session('success'))
                   {{ transTpl('success','helpers') }} @elseif(session('warning')){{transTpl('note','helpers')}} @elseif(session('info')){{ transTpl('info','helpers') }}@endif</h4>
            @if(count($errors))
                @foreach($errors->all() as $error)
                    <p>{!!  $error !!}</p>
                @endforeach
            @elseif(session('warning'))
                @foreach(session('warning') as $warning)
                    <p>{!!  $warning !!}</p>
                @endforeach
            @elseif(session('success'))
                <p>{!!  session('success') !!}</p>
            @elseif(session('info'))
                @foreach(session('info') as $info)
                    <p>{{  $info }}</p>
                @endforeach

            @endif
        </div>
    </div>
@endif