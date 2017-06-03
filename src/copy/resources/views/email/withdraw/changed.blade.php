<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>{{ transTpl('message_from','email') }}:{{ $site_name  }}</title>
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
            .tbl_foot .dot {
                font-size: 8px;
            }
            .tbl_footer, .tbl_footer tbody, .tbl_footer tr, .tbl_footer td {
                display: block;
            }
            .tbl_footer .phone {
                diaplay: block;
                width: 100% !important;
                text-align: center;
            }
            .tbl_footer .social {
                display: inline-block;
                text-align: center;
                margin: 0;
            }
        }
    </style>
</head>
<body style="font-family:tahoma;font-size:14px;margin:0;">
<table class="tbl_main" style="width:100%;background-color:#f2f1ed;padding:60px 0;" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td>
            <table class="tbl_child tbl_header" style="width:600px;direction:rtl;margin:auto;" cellspacing="0" cellpadding="10px">
                <tbody>
                <tr>
                    <td style="text-align:center;">{logo}</td>
                </tr>
                </tbody>
            </table>
            <table class="tbl_child tbl_wrap" style="width:600px;direction:rtl;margin:auto;color:#3b5e74;box-shadow:0 0 10px 2px #e8e8e8;border-radius:14px;" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <table class="tbl_head" style="width:100%;min-height:72px;background-color:#f6f7f9;color:#3b5e74;" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td class="shop_name"><font style="font-family:tahoma;font-size:13px;">{{ $site_name }}</font></td>
                                <!--  <td class="shop_link" style="text-align:left;"><font style="font-family:tahoma;font-size:13px;">{link_shop}</font></td>  -->
                                <td>&nbsp;</td>
                            </tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            </tbody>
                        </table>
                        <table class="tbl_body" style="width:100%;background-color:#ffffff;min-height:80px;direction:rtl;" cellspacing="0" cellpadding="10px">
                            <tbody>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:14px;">سلام {{ $firstname }} {{ $lastname }}</font></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:12px;">وضعیت درخواست تراکنش شما تغییر یافت</font></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:14px;">مبلغ درخواستی:{{ $price }}</font></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:12px;">توضیحات:{{ $description }}</font></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><font style="font-family:tahoma;font-size:12px;">تاریخ:{{ dateFormat($date,'Y-m-d H:i:s') }}</font></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>

                            </tbody>
                        </table>
                        <table class="tbl_foot" style="width:100%;min-height:100px;background-color:#f6f7f9;color:#6b8288;border-radius:0 0 14px 14px;" cellspacing="0" cellpadding="10px">
                            <tbody>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td class="links" style="text-align:center;">@include('email.partials.links')</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="tbl_child tbl_footer" style="width:600px;direction:rtl;margin:auto;color:#3d5e73;" cellspacing="0" cellpadding="10px">
                <tbody>
                <tr>
                    <td class="phone" style="width:36px;">
                        <img class="phone_img" src={{ asset('img/mail/phone-icon.png') }}/>
                    </td>
                    <td class="phone">
                        <font class="phone_nbr" style="font-family:tahoma;font-size:13px;color:#3d5e73;direction:ltr">{{ $mobile }}</font>
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