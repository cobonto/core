<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>{{ transTpl('message_from','email') }}:{{ isset($site_name)?$site_name:''  }}</title>
    <style>
        .tbl_child {
            -webkit-padding-start: inherit !important;
        }
        .tbl_wrap {
            border-radius: 14px;
        }
        .tbl_head {
            border-radius: 14px 14px 0 0;
        }
        .tbl_foot {
            border-radius: 0 0 14px 14px;
        }
        @media (max-width: 800px) {
            .tbl_child {
                width: 540px !important;
            }
        }
        @media (max-width: 600px) {
            .tbl_child {
                width: 440px !important;
            }
        }
        @media (max-width: 480px) {
            .tbl_child {
                width: 300px !important;
            }
            .tbl_head .shop_name {
                display: block;
                width: 100%;
                text-align: center;
                padding-bottom: 10px;
            }
            .tbl_footer, .tbl_footer tbody, .tbl_footer tr, .tbl_footer td {
                display: block;
            }
            .tbl_footer .font-address {
                line-height: 22px;
            }
            .tbl_footer .font-contact {
                display: block !important;
                margin-bottom: 10px;
            }
            .tbl_footer .font-seperator {
                display: none !important;
            }
        }
    </style>
</head>
<body style="font-family:tahoma;font-size:12px;margin:0;">
<table class="tbl_main" style="width:100%;background-color:#f2f1ed;padding:60px 0;" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td>
            <table class="tbl_child tbl_header" style="width:600px;direction:rtl;margin:auto;" cellspacing="0" cellpadding="10px">
                <tbody>
                <tr>
                    <td style="text-align:center;"> <img class="logo" src="{{ asset($logo) }}" width="200px" height="80px"/></td>
                </tr>
                </tbody>
            </table>
            <table class="tbl_child tbl_wrap" style="width:600px;direction:rtl;margin:auto;color:#3b5e74;box-shadow:0 0 10px 2px #e8e8e8;border-radius:14px;" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <table class="tbl_head" style="width:100%;min-height:72px;background-color:#f6f7f9;color:#3b5e74;" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td class="shop_name" style="text-align:center;">
                                    <font style="font-family:tahoma;font-size:14px;">{{ isset($site_name)?$site_name:''  }}</font><br><br>
                                    <font style="font-family:tahoma;font-size:13px;">{{ $site_description }}</font>
                                </td>
                                <!--<td class="shop_link" style="text-align:left;"><font style="font-family:tahoma;font-size:13px;">{link_shop}</font></td>-->
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="tbl_body" style="width:100%;background-color:#ffffff;min-height:80px;direction:rtl;" cellspacing="0" cellpadding="10px">
                            <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:14px;">سلام {{ $firstname }} {{ $lastname }}</font></td>

                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:12px;">رمز عبو شما تغییر یافت</font></td>

                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:14px;">ایمیل:</font>{{ $email }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:14px;">پسورد:</font>{{ $password }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>

                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:14px;">نکات مهم:</font></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:12px;">1. اطلاعات خود را به صورت امن نگهداری کنید</font></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:12px;">2. در صورت بروز مشکل در اطلاعات شما تیم پشتیبانی را مطلع نمایید</font></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td><td></td><td></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="tbl_foot" style="width:100%;min-height:100px;background-color:#f6f7f9;color:#6b8288;border-radius:0 0 14px 14px;" cellspacing="0" cellpadding="10px">
                            <tbody>
                            <tr><td></td></tr>
                            <tr>
                                <td style="text-align:center;">
                                    @if($site_link)<a href="{{ $site_link }}" style="display:inline-block;padding: 3px;"><img src="https://fitclub.ir/portal/img/mail/website.png" /></a>@endif
                                    @if($instagram_link)<a href="{{ $instagram_link }}" style="display:inline-block;padding: 3px;"><img src="https://fitclub.ir/portal/img/mail/instagram.png" /></a>@endif
                                    @if($telegram_link)<a href="{{ $telegram_link }}" style="display:inline-block;padding: 3px;"><img src="https://fitclub.ir/portal/img/mail/telegram.png" /></a>@endif
                                </td>
                            </tr>
                            <tr><td></td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="tbl_child tbl_footer" style="width:600px;direction:rtl;margin:auto;color:#9a9a9a;" cellspacing="0" cellpadding="10px">
                <tbody>
                <tr>
                    <td style="text-align:center;">
                        <font class="font-address" style="font-family:tahoma;font-size:12px;color:#9a9a9a;display:block;margin-bottom:10px;">
                            {{ $address }}
                        </font>
                        <font class="font-contact" style="font-family:tahoma;font-size:12px;display:inline-block;">تلفن: <span style="display:inline-block;direction:ltr;">{{ $phone }}</span></font>
                        <font class="font-seperator" style="font-family:monospace;font-size:14px;display:inline-block;">&nbsp;&nbsp;::&nbsp;&nbsp;</font>
                        <font class="font-contact" style="font-family:tahoma;font-size:12px;display:inline-block;">سایت: <span style="display:inline-block;direction:ltr;">{{ $site_address }}</span></font>
                        <font class="font-seperator" style="font-family:monospace;font-size:14px;display:inline-block;">&nbsp;&nbsp;::&nbsp;&nbsp;</font>
                        <font class="font-contact" style="font-family:tahoma;font-size:12px;display:inline-block;">ایمیل: <span style="display:inline-block;direction:ltr;">{{ $email_address }}</span></font>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>