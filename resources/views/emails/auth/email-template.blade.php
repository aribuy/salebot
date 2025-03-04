<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title></title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600" rel="stylesheet" type="text/css">
    <!-- Web Font / @font-face : BEGIN -->
    @php $color = setting('primary_color'); @endphp
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 24px;
            color:#8094ae;
            font-weight: 400;
        }
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif !important;
        }
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        table table table {
            table-layout: auto;
        }
        a {
            text-decoration: none;
            color: {{$color}} !important;
            word-break: break-all;
        }
        img {
            -ms-interpolation-mode:bicubic;
        }
        .email-body {
            width: 96%;
            margin: 0 auto;
            background: #ffffff;
            padding: 10px !important;
        }
        .email-heading {
            font-size: 18px;
            color: {{$color}};
            font-weight: 600;
            margin: 0;
            line-height: 1.4;
        }
        .email-btn {
            background: {{$color}};
            border-radius: 4px;
            color: #ffffff !important;
            display: inline-block;
            font-size: 13px;
            font-weight: 600;
            line-height: 44px;
            text-align: center;
            text-decoration: none;
            text-transform: uppercase;
            padding: 0 30px;
        }
        .email-heading-s2 {
            font-size: 16px;
            color: {{$color}};
            font-weight: 600;
            margin: 0;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .link-block {
            display: block;
        }
        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }
        .email-note {
            margin: 0;
            font-size: 13px;
            line-height: 22px;
            color: {{$color}};
        }
    </style>
</head>
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f5f6fa;">
<center style="width: 100%; background-color: #f5f6fa;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f6fa">
        <tr>
            <td style="padding: 40px 0;">
                <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
                    <tbody>
                    <tr>
                        <td style="width:95px;padding: 30px 30px 15px 30px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                <tr>
                                    <td>
                                        <img style="max-height: 32px" src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80',[]) }}" alt="Corporate Logo">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 30px 20px">
                            <p style="margin-bottom: 25px; color: ;">{!! $email_templates->body !!}</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size:0px;padding:10px 25px;padding-top:30px;padding-bottom:40px;word-break:break-word;">

                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                                <tr>
                                    <td align="center" bgcolor="{{$color}}" role="presentation" style="border:none;border-radius:3px;color:#ffffff;cursor:auto;padding:15px 25px;" valign="middle">
                                        <p style="background:{{$color}};color:#ffffff;font-family:'Helvetica Neue',Arial,sans-serif;font-size:15px;font-weight:normal;line-height:120%;Margin:0;text-decoration:none;text-transform:none;">
                                            <a style="margin-bottom: 25px;color:#ffffff;" href="{{ $link }}">{{ __('verified_now') }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                    @if(!blank(setting('mail_signature') || setting('mail_signature') != ''))
                        <tr>
                            <td style="text-align:left; color:{{ $color }} ; padding: 20px 30px 40px" >
                                {!! setting('mail_signature') !!}
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
</center>
</body>
</html>
