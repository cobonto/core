<li class="@if($active_settings=='general') active @endif"><a data-profile="general" href="#profile_general" data-toggle="tab"><i class="fa fa-wrench"></i>{{ transTpl('general','settings') }}</a></li>
<li class="@if($active_settings=='email') active @endif"><a data-profile="email" href="#profile_email" data-toggle="tab"><i class="fa fa-envelope"></i>{{ transTpl('email','settings') }}</a></li>
<li class="@if($active_settings=='adminUrl') active @endif"><a data-profile="adminUrl" href="#profile_admin_url" data-toggle="tab"><i class="fa fa-link"></i>{{ transTpl('admin_url','settings') }}</a></li>
{{--<li class="@if($active_settings=='deployment') active @endif"><a data-profile="deployment" href="#profile_deployment" data-toggle="tab"><i class="fa fa-wrench"></i>{{ transTpl('deployment','settings') }}</a></li>--}}
{!! hook('displayAdminsettingsNavBar') !!}
