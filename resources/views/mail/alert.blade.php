<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Zen Flat Ping Email</title>
    <style type="text/css" media="screen">
        .ExternalClass {
            display: block !important;
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        body,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        body,
        p,
        td {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            color: #333333;
            line-height: 1.5em;
        }

        h1 {
            font-size: 24px;
            font-weight: normal;
            line-height: 24px;
        }

        body,
        p {
            margin-bottom: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }

        img {
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        .background {
            background-color: #333333;
        }

        table.background {
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        .block-img {
            display: block;
            line-height: 0;
        }

        a {
            color: white;
            text-decoration: none;
        }

        a,
        a:link {
            color: #2A5DB0;
            text-decoration: underline;
        }

        table td {
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            text-align: left;
        }

        .wrap {
            width: 600px;
        }

        .wrap-cell {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .header-cell,
        .body-cell,
        .footer-cell {
            padding-left: 20px;
            padding-right: 20px;
        }

        .header-cell {
            background-color: #eeeeee;
            font-size: 24px;
            color: #ffffff;
        }

        .body-cell {
            background-color: #ffffff;
            padding-top: 30px;
            padding-bottom: 34px;
        }

        .footer-cell {
            background-color: #eeeeee;
            text-align: center;
            font-size: 13px;
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .card {
            width: 400px;
            margin: 0 auto;
        }

        .data-heading {
            text-align: right;
            padding: 10px;
            background-color: #ffffff;
            font-weight: bold;
        }

        .data-value {
            text-align: left;
            padding: 10px;
            background-color: #ffffff;
        }

        .force-full-width {
            width: 100% !important;
        }
    </style>
    <style type="text/css" media="only screen and (max-width: 600px)">
        @media only screen and (max-width: 600px) {

            body[class*="background"],
            table[class*="background"],
            td[class*="background"] {
                background: #eeeeee !important;
            }

            table[class="card"] {
                width: auto !important;
            }

            td[class="data-heading"],
            td[class="data-value"] {
                display: block !important;
            }

            td[class="data-heading"] {
                text-align: left !important;
                padding: 10px 10px 0;
            }

            table[class="wrap"] {
                width: 100% !important;
            }

            td[class="wrap-cell"] {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
        }
    </style>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="" class="background">
    <table class="background" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
        <tbody>
            <tr>
                <td class="background" align="center" valign="top" width="100%">
                    <center>
                        <table class="wrap" width="600" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="wrap-cell" style="padding-top: 30px; padding-bottom: 30px;"
                                        valign="top">
                                        <table class="force-full-width" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="header-cell" valign="top" height="60"><br />
                                                        <h2>
                                                            <font color="#ff9a44">PT. NIRAMAS UTAMA</font>
                                                        </h2>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="body-cell" valign="top">
                                                        <table width="100%" cellspacing="0" cellpadding="0"
                                                            bgcolor="#ffffff">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 15px; background-color: #ffffff;"
                                                                        valign="top">
                                                                        <h1>Silahkan Approval Permintaan Work Order</h1>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    {{-- @foreach ($data as $key) --}}
                                                                    <td style="padding-bottom: 20px; background-color: #ffffff;"
                                                                        valign="top">
                                                                        Permintaan<strong> Work Order </strong> oleh
                                                                        <strong> {{ $sendmail['namefrom'] }} </strong> Sedang / Sudah dikerjakan GA. Silahkan klik tombol Approval di bawah ini untuk approve.
                                                                    </td>
                                                                    {{-- @endforeach --}}
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table style="width: 100%;" width="100%"  cellspacing="0" cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td style="width: 35.7143%;">
                                                                                        <div>
                                                                                            <a style="color: #ffffff; background: #ff9a44; display: inline-block; font-family: sans-serif; font-size: 18px; line-height: 40px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;"
                                                                                                title="Approval"
                                                                                                href="{{ route('approval') }}">Approval
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-top: 20px; background-color: #ffffff;">Salam hangat,<br />Work Order System</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="footer-cell" valign="top">PT. NIRAMAS UTAMA<br />
                                                        <a title="HR System" href="#">Work Order System</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
