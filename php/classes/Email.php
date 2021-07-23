<?php
use PHPMailer\PHPMailer\PHPMailer;
class Email {
    private $_smtpEmail,
            $_smtpPassword,
            $_smtpUsername,
            $_smtpHost,
            $_smtpaddCC,
            $_smtpaddBcc,
            $_website,
            $_info,
            $_project;


    function __construct(){
        $this->_smtpEmail = 'bagamoyoihi@gmail.com';
        $this->_smtpHost = 'smtp.gmail.com';
        $this->_smtpUsername = 'bagamoyoihi@gmail.com';
        $this->_smtpPassword = 'Data@2020';
        $this->_smtpaddCC = 'frdrckaman@gmail.com';
        $this->_website = 'www.ihi.or.tz';
        $this->_info = 'info@ihi.or.tz';
        $this->_project = 'VAC082';

    }
    function emailConf($email,$subject,$body){
        $mail = new PHPMailer();
        $mail->Host = $this->_smtpHost;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = $this->_smtpUsername;
        $mail->Password = $this->_smtpPassword;
        $mail->SMTPSecure = "tls"; //TLS
        $mail->Port = 587; //587
        $mail->addAddress($email);
        $mail->setFrom($this->_smtpUsername,$this->_project.' DATA MANAGEMENT SYSTEM');
        $mail->addReplyTo($this->_smtpUsername);
//        $mail->addCC($this->_smtpaddCC);
        $mail->addBCC($this->_smtpaddCC);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $body;

        if ($mail->send()){
            return true;
        }
        else{
            return false;
        }
    }
    function sendEmail($email,$lastname,$username,$password,$subject){
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" >
            <head>
                <!-- If you delete this meta tag, Half Life 3 will never be released. -->
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title></title>
                <style type="text/css">
                        * { 
                            margin:0;
                            padding:0;
                        }
                        * { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }
                        
                        img { 
                            max-width: 100%; 
                        }
                        .collapse {
                            margin:0;
                            padding:0;
                        }
                        body {
                            -webkit-font-smoothing:antialiased; 
                            -webkit-text-size-adjust:none; 
                            width: 100%!important; 
                            height: 100%;
                        }
     
                        a { color: #2BA6CB;}
                        
                        .btn {
                            text-decoration:none;
                            color: #FFF;
                            background-color: #666;
                            padding:10px 16px;
                            font-weight:bold;
                            margin-right:10px;
                            text-align:center;
                            cursor:pointer;
                            display: inline-block;
                        }
                        
                        p.callout {
                            padding:15px;
                            background-color:#ECF8FF;
                            margin-bottom: 15px;
                        }
                        .callout a {
                            font-weight:bold;
                            color: #2BA6CB;
                        }
                        
                        table.social {
                        /* 	padding:15px; */
                            background-color: #ebebeb;
                            
                        }
                        .social .soc-btn {
                            padding: 3px 7px;
                            font-size:12px;
                            margin-bottom:10px;
                            text-decoration:none;
                            color: #FFF;font-weight:bold;
                            display:block;
                            text-align:center;
                        }
                        a.fb { background-color: #3B5998!important; }
                        a.tw { background-color: #1daced!important; }
                        a.gp { background-color: #DB4A39!important; }
                        a.ms { background-color: #000!important; }
                        
                        .sidebar .soc-btn { 
                            display:block;
                            width:100%;
                        }

                        table.head-wrap { width: 80%;}
                        
                        .header.container table td.logo { padding: 15px; }
                        .header.container table td.label { padding: 15px; padding-left:0px;}

                        table.body-wrap { width: 100%;}

                        table.footer-wrap { width: 100%;	clear:both!important;
                        }
                        .footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
                        .footer-wrap .container td.content p {
                            font-size:10px;
                            font-weight: bold;
                            
                        }

                        h1,h2,h3,h4,h5,h6 {
                        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
                        }
                        h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
                        
                        h1 { font-weight:200; font-size: 44px;}
                        h2 { font-weight:200; font-size: 37px;}
                        h3 { font-weight:500; font-size: 27px;}
                        h4 { font-weight:500; font-size: 23px;}
                        h5 { font-weight:900; font-size: 17px;}
                        h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;}
                        
                        .collapse { margin:0!important;}
                        
                        p, ul { 
                            margin-bottom: 10px; 
                            font-weight: normal; 
                            font-size:14px; 
                            line-height:1.6;
                        }
                        p.lead { font-size:17px; }
                        p.last { margin-bottom:0px;}
                        
                        ul li {
                            margin-left:5px;
                            list-style-position: inside;
                        }

                        ul.sidebar {
                            background:#ebebeb;
                            display:block;
                            list-style-type: none;
                        }
                        ul.sidebar li { display: block; margin:0;}
                        ul.sidebar li a {
                            text-decoration:none;
                            color: #666;
                            padding:10px 16px;
                        /* 	font-weight:bold; */
                            margin-right:10px;
                        /* 	text-align:center; */
                            cursor:pointer;
                            border-bottom: 1px solid #777777;
                            border-top: 1px solid #FFFFFF;
                            display:block;
                            margin:0;
                        }
                        ul.sidebar li a.last { border-bottom-width:0px;}
                        ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
      
                        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                        .container {
                            display:block!important;
                            max-width:600px!important;
                            margin:0 auto!important; /* makes it centered */
                            clear:both!important;
                        }
                        
                        /* This should also be a block element, so that it will fill 100% of the .container */
                        .content {
                            padding:15px;
                            max-width:600px;
                            margin:0 auto;
                            display:block; 
                        }
                        
                        /* Let\'s make sure tables in the content area are 100% wide */
                        .content table { width: 100%; }
                        
                        
                        /* Odds and ends */
                        .column {
                            width: 300px;
                            float:left;
                        }
                        .column tr td { padding: 15px; }
                        .column-wrap { 
                            padding:0!important; 
                            margin:0 auto; 
                            max-width:600px!important;
                        }
                        .column table { width:100%;}
                        .social .column {
                            width: 280px;
                            min-width: 279px;
                            float:left;
                        }
                        
                        /* Be sure to place a .clear element after each set of columns, just to be safe */
                        .clear { display: block; clear: both; }

                        @media only screen and (max-width: 600px) {
                            
                            a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
                        
                            div[class="column"] { width: auto!important; float:none!important;}
                            
                            table.social div[class="column"] {
                                width:auto!important;
                            }
                        
                        }
                </style>
            </head>
            <body bgcolor="#FFFFFF" style="background-color: #cccccc">
            <!-- BODY -->
                <table class="body-wrap">
                    <tr>
                        <td></td>
                        <td class="container" bgcolor="#FFFFFF">
                            <table class="social" width="100%">
                                <tr>
                                    <td>
                                        <!-- column 1 -->
                                        <table align="" style="padding: 20px">
                                            <tr>
                                                <td align="right"><h3 class="collapse" style="font-weight: bolder">'.$this->_project.' DATA MANAGEMENT SYSTEM</h3></td>
                                            </tr>
                                        </table>
                                        <!-- /column 1 -->
   
                                        <span class="clear"></span>
                
                                    </td>
                                </tr>
                            </table>
                            <div class="content">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>Dear '.$lastname.'</h3>
                                            <p class="lead">Your new Account on '.$this->_project.' Data Management System ('.$this->_project.' DMS) have been created by your supervisor.</p>
                                            <p class="lead">Username: '.$username.'</p>
                                            <p class="lead">Password: '.$password.'</p>
                                            <!-- Callout Panel -->
                                            <p class="callout">
                                                Your advice to login and change your password as soon as possible </a>
                                            </p><!-- /Callout Panel -->
                
                                            <!-- contact Info -->
                                            <table class="social" width="100%">
                                                <tr>
                                                    <td>
                                                        <!-- column 1 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Visit our website at : <a href="'.$this->_website.'">'.$this->_website.'</a></p>
                                                                    <p> </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <!-- /column 1 -->
                
                                                        <!-- column 2 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Send us an Email : <strong><a href="'.$this->_info.'">'.$this->_info.'</a></strong></p>
                                                                </td>
                                                            </tr>
                                                        </table><!-- /column 2 -->
                                                    </td>
                                                </tr>
                                            </table><!-- /contact Info -->
                
                                            <!---- footer--->
                                            <table class="footer-wrap" >
                                                <tr>
                                                    <td></td>
                                                    <td class="container">
                
                                                        <!-- content -->
                                                        <div class="content">
                                                            <table>
                                                                <tr>
                                                                    <td align="center">
                                                                        <p>
                                                                            <a href="#">Terms</a> |
                                                                            <a href="#">Privacy</a> |
                                                                            <a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div><!-- /content -->
                
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <!-- end of footer -->
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- /content -->
                        </td>
                
                    </tr>
                </table><!-- /BODY -->

            </body>
        </html>';
        $this->emailConf($email,$subject,$body);
    }
    function  resetPassword($email,$lastname,$subject,$link=null){
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" >
            <head>
                <!-- If you delete this meta tag, Half Life 3 will never be released. -->
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title></title>
                <style type="text/css">
                        * { 
                            margin:0;
                            padding:0;
                        }
                        * { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }
                        
                        img { 
                            max-width: 100%; 
                        }
                        .collapse {
                            margin:0;
                            padding:0;
                        }
                        body {
                            -webkit-font-smoothing:antialiased; 
                            -webkit-text-size-adjust:none; 
                            width: 100%!important; 
                            height: 100%;
                        }
     
                        a { color: #2BA6CB;}
                        
                        .btn {
                            text-decoration:none;
                            color: #FFF;
                            background-color: #666;
                            padding:10px 16px;
                            font-weight:bold;
                            margin-right:10px;
                            text-align:center;
                            cursor:pointer;
                            display: inline-block;
                        }
                        
                        p.callout {
                            padding:15px;
                            background-color:#ECF8FF;
                            margin-bottom: 15px;
                        }
                        .callout a {
                            font-weight:bold;
                            color: #2BA6CB;
                        }
                        
                        table.social {
                        /* 	padding:15px; */
                            background-color: #ebebeb;
                            
                        }
                        .social .soc-btn {
                            padding: 3px 7px;
                            font-size:12px;
                            margin-bottom:10px;
                            text-decoration:none;
                            color: #FFF;font-weight:bold;
                            display:block;
                            text-align:center;
                        }
                        a.fb { background-color: #3B5998!important; }
                        a.tw { background-color: #1daced!important; }
                        a.gp { background-color: #DB4A39!important; }
                        a.ms { background-color: #000!important; }
                        
                        .sidebar .soc-btn { 
                            display:block;
                            width:100%;
                        }

                        table.head-wrap { width: 80%;}
                        
                        .header.container table td.logo { padding: 15px; }
                        .header.container table td.label { padding: 15px; padding-left:0px;}

                        table.body-wrap { width: 100%;}

                        table.footer-wrap { width: 100%;	clear:both!important;
                        }
                        .footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
                        .footer-wrap .container td.content p {
                            font-size:10px;
                            font-weight: bold;
                            
                        }

                        h1,h2,h3,h4,h5,h6 {
                        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
                        }
                        h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
                        
                        h1 { font-weight:200; font-size: 44px;}
                        h2 { font-weight:200; font-size: 37px;}
                        h3 { font-weight:500; font-size: 27px;}
                        h4 { font-weight:500; font-size: 23px;}
                        h5 { font-weight:900; font-size: 17px;}
                        h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;}
                        
                        .collapse { margin:0!important;}
                        
                        p, ul { 
                            margin-bottom: 10px; 
                            font-weight: normal; 
                            font-size:14px; 
                            line-height:1.6;
                        }
                        p.lead { font-size:17px; }
                        p.last { margin-bottom:0px;}
                        
                        ul li {
                            margin-left:5px;
                            list-style-position: inside;
                        }

                        ul.sidebar {
                            background:#ebebeb;
                            display:block;
                            list-style-type: none;
                        }
                        ul.sidebar li { display: block; margin:0;}
                        ul.sidebar li a {
                            text-decoration:none;
                            color: #666;
                            padding:10px 16px;
                        /* 	font-weight:bold; */
                            margin-right:10px;
                        /* 	text-align:center; */
                            cursor:pointer;
                            border-bottom: 1px solid #777777;
                            border-top: 1px solid #FFFFFF;
                            display:block;
                            margin:0;
                        }
                        ul.sidebar li a.last { border-bottom-width:0px;}
                        ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
      
                        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                        .container {
                            display:block!important;
                            max-width:600px!important;
                            margin:0 auto!important; /* makes it centered */
                            clear:both!important;
                        }
                        
                        /* This should also be a block element, so that it will fill 100% of the .container */
                        .content {
                            padding:15px;
                            max-width:600px;
                            margin:0 auto;
                            display:block; 
                        }
                        
                        /* Let\'s make sure tables in the content area are 100% wide */
                        .content table { width: 100%; }
                        
                        
                        /* Odds and ends */
                        .column {
                            width: 300px;
                            float:left;
                        }
                        .column tr td { padding: 15px; }
                        .column-wrap { 
                            padding:0!important; 
                            margin:0 auto; 
                            max-width:600px!important;
                        }
                        .column table { width:100%;}
                        .social .column {
                            width: 280px;
                            min-width: 279px;
                            float:left;
                        }
                        
                        /* Be sure to place a .clear element after each set of columns, just to be safe */
                        .clear { display: block; clear: both; }

                        @media only screen and (max-width: 600px) {
                            
                            a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
                        
                            div[class="column"] { width: auto!important; float:none!important;}
                            
                            table.social div[class="column"] {
                                width:auto!important;
                            }
                        
                        }
                </style>
            </head>
            <body bgcolor="#FFFFFF" style="background-color: #cccccc">
            <!-- BODY -->
                <table class="body-wrap">
                    <tr>
                        <td></td>
                        <td class="container" bgcolor="#FFFFFF">
                            <table class="social" width="100%">
                                <tr>
                                    <td>
                                        <!-- column 1 -->
                                        <table align="" style="padding: 20px">
                                            <tr>
                                                <td align="right"><h3 class="collapse" style="font-weight: bolder">'.$this->_project.' DATA MANAGEMENT SYSTEM</h3></td>
                                            </tr>
                                        </table>
                                        <!-- /column 1 -->
   
                                        <span class="clear"></span>
                
                                    </td>
                                </tr>
                            </table>
                            <div class="content">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>Dear '.$lastname.'</h3>
                                            <p class="lead">Your password have been reset by the system admin, due to the fact that you either forget it or you have request it </p>
                                            
                                          
                                            <p class="lead">Password: 123456</p>
                                            
                                            <!-- Callout Panel -->
                                            <p class="callout" style="font-weight: bold;color: coral">
                                                Note: Your advice to change this password to a more secured password after login to your account.
                                            </p><!-- /Callout Panel -->
                
                                            <!-- contact Info -->
                                            <table class="social" width="100%">
                                                <tr>
                                                    <td>
                                                        <!-- column 1 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Visit our website at : <a href="'.$this->_website.'">'.$this->_website.'</a></p>
                                                                    <p> </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <!-- /column 1 -->
                
                                                        <!-- column 2 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Send us an Email : <strong><a href="'.$this->_info.'">'.$this->_info.'</a></strong></p>
                                                                </td>
                                                            </tr>
                                                        </table><!-- /column 2 -->
                                                    </td>
                                                </tr>
                                            </table><!-- /contact Info -->
                
                                            <!---- footer--->
                                            <table class="footer-wrap" >
                                                <tr>
                                                    <td></td>
                                                    <td class="container">
                
                                                        <!-- content -->
                                                        <div class="content">
                                                            <table>
                                                                <tr>
                                                                    <td align="center">
                                                                        <p>
                                                                            <a href="#">Terms</a> |
                                                                            <a href="#">Privacy</a> |
                                                                            <a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div><!-- /content -->
                
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <!-- end of footer -->
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- /content -->
                        </td>
                
                    </tr>
                </table><!-- /BODY -->

            </body>
        </html>';
        $this->emailConf($email,$subject,$body);
    }
    function deactivation($email,$lastname,$subject){
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" >
            <head>
                <!-- If you delete this meta tag, Half Life 3 will never be released. -->
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title></title>
                <style type="text/css">
                        * { 
                            margin:0;
                            padding:0;
                        }
                        * { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }
                        
                        img { 
                            max-width: 100%; 
                        }
                        .collapse {
                            margin:0;
                            padding:0;
                        }
                        body {
                            -webkit-font-smoothing:antialiased; 
                            -webkit-text-size-adjust:none; 
                            width: 100%!important; 
                            height: 100%;
                        }
     
                        a { color: #2BA6CB;}
                        
                        .btn {
                            text-decoration:none;
                            color: #FFF;
                            background-color: #666;
                            padding:10px 16px;
                            font-weight:bold;
                            margin-right:10px;
                            text-align:center;
                            cursor:pointer;
                            display: inline-block;
                        }
                        
                        p.callout {
                            padding:15px;
                            background-color:#ECF8FF;
                            margin-bottom: 15px;
                        }
                        .callout a {
                            font-weight:bold;
                            color: #2BA6CB;
                        }
                        
                        table.social {
                        /* 	padding:15px; */
                            background-color: #ebebeb;
                            
                        }
                        .social .soc-btn {
                            padding: 3px 7px;
                            font-size:12px;
                            margin-bottom:10px;
                            text-decoration:none;
                            color: #FFF;font-weight:bold;
                            display:block;
                            text-align:center;
                        }
                        a.fb { background-color: #3B5998!important; }
                        a.tw { background-color: #1daced!important; }
                        a.gp { background-color: #DB4A39!important; }
                        a.ms { background-color: #000!important; }
                        
                        .sidebar .soc-btn { 
                            display:block;
                            width:100%;
                        }

                        table.head-wrap { width: 80%;}
                        
                        .header.container table td.logo { padding: 15px; }
                        .header.container table td.label { padding: 15px; padding-left:0px;}

                        table.body-wrap { width: 100%;}

                        table.footer-wrap { width: 100%;	clear:both!important;
                        }
                        .footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
                        .footer-wrap .container td.content p {
                            font-size:10px;
                            font-weight: bold;
                            
                        }

                        h1,h2,h3,h4,h5,h6 {
                        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
                        }
                        h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
                        
                        h1 { font-weight:200; font-size: 44px;}
                        h2 { font-weight:200; font-size: 37px;}
                        h3 { font-weight:500; font-size: 27px;}
                        h4 { font-weight:500; font-size: 23px;}
                        h5 { font-weight:900; font-size: 17px;}
                        h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;}
                        
                        .collapse { margin:0!important;}
                        
                        p, ul { 
                            margin-bottom: 10px; 
                            font-weight: normal; 
                            font-size:14px; 
                            line-height:1.6;
                        }
                        p.lead { font-size:17px; }
                        p.last { margin-bottom:0px;}
                        
                        ul li {
                            margin-left:5px;
                            list-style-position: inside;
                        }

                        ul.sidebar {
                            background:#ebebeb;
                            display:block;
                            list-style-type: none;
                        }
                        ul.sidebar li { display: block; margin:0;}
                        ul.sidebar li a {
                            text-decoration:none;
                            color: #666;
                            padding:10px 16px;
                        /* 	font-weight:bold; */
                            margin-right:10px;
                        /* 	text-align:center; */
                            cursor:pointer;
                            border-bottom: 1px solid #777777;
                            border-top: 1px solid #FFFFFF;
                            display:block;
                            margin:0;
                        }
                        ul.sidebar li a.last { border-bottom-width:0px;}
                        ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
      
                        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                        .container {
                            display:block!important;
                            max-width:600px!important;
                            margin:0 auto!important; /* makes it centered */
                            clear:both!important;
                        }
                        
                        /* This should also be a block element, so that it will fill 100% of the .container */
                        .content {
                            padding:15px;
                            max-width:600px;
                            margin:0 auto;
                            display:block; 
                        }
                        
                        /* Let\'s make sure tables in the content area are 100% wide */
                        .content table { width: 100%; }
                        
                        
                        /* Odds and ends */
                        .column {
                            width: 300px;
                            float:left;
                        }
                        .column tr td { padding: 15px; }
                        .column-wrap { 
                            padding:0!important; 
                            margin:0 auto; 
                            max-width:600px!important;
                        }
                        .column table { width:100%;}
                        .social .column {
                            width: 280px;
                            min-width: 279px;
                            float:left;
                        }
                        
                        /* Be sure to place a .clear element after each set of columns, just to be safe */
                        .clear { display: block; clear: both; }

                        @media only screen and (max-width: 600px) {
                            
                            a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
                        
                            div[class="column"] { width: auto!important; float:none!important;}
                            
                            table.social div[class="column"] {
                                width:auto!important;
                            }
                        
                        }
                </style>
            </head>
            <body bgcolor="#FFFFFF" style="background-color: #cccccc">
            <!-- BODY -->
                <table class="body-wrap">
                    <tr>
                        <td></td>
                        <td class="container" bgcolor="#FFFFFF">
                            <table class="social" width="100%">
                                <tr>
                                    <td>
                                        <!-- column 1 -->
                                        <table align="" style="padding: 20px">
                                            <tr>
                                                <td align="right"><h3 class="collapse" style="font-weight: bolder">'.$this->_project.' DATA MANAGEMENT SYSTEM</h3></td>
                                            </tr>
                                        </table>
                                        <!-- /column 1 -->
   
                                        <span class="clear"></span>
                
                                    </td>
                                </tr>
                            </table>
                            <div class="content">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>Dear '.$lastname.'</h3>
                                            <p class="lead">For security purpose your account have been deactivated due to the fact that there have been several attempt to access it with invalid Credentials</p>
                                            
                                            <!-- Callout Panel -->
                                            <p class="callout">
                                                Please contact system administrator for account reactivation
                                            </p><!-- /Callout Panel -->
                
                                            <!-- contact Info -->
                                            <table class="social" width="100%">
                                                <tr>
                                                    <td>
                                                        <!-- column 1 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Visit our website at : <a href="'.$this->_website.'">'.$this->_website.'</a></p>
                                                                    <p> </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <!-- /column 1 -->
                
                                                        <!-- column 2 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Send us an Email : <strong><a href="'.$this->_info.'">'.$this->_info.'</a></strong></p>
                                                                </td>
                                                            </tr>
                                                        </table><!-- /column 2 -->
                                                    </td>
                                                </tr>
                                            </table><!-- /contact Info -->
                
                                            <!---- footer--->
                                            <table class="footer-wrap" >
                                                <tr>
                                                    <td></td>
                                                    <td class="container">
                
                                                        <!-- content -->
                                                        <div class="content">
                                                            <table>
                                                                <tr>
                                                                    <td align="center">
                                                                        <p>
                                                                            <a href="#">Terms</a> |
                                                                            <a href="#">Privacy</a> |
                                                                            <a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div><!-- /content -->
                
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <!-- end of footer -->
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- /content -->
                        </td>
                
                    </tr>
                </table><!-- /BODY -->

            </body>
        </html>';
        $this->emailConf($email,$subject,$body);
    }
    function emailSend($email,$subject,$body){
        $this->emailConf($email,$subject,$body);
    }
    function systemUpdate($email,$subject){
        $all='All';
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" >
            <head>
    <!-- If you delete this meta tag, Half Life 3 will never be released. -->
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <style type="text/css">
        * {
            margin:0;
            padding:0;
        }
        * { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }

        img {
            max-width: 100%;
        }
        .collapse {
            margin:0;
            padding:0;
        }
        body {
            -webkit-font-smoothing:antialiased;
            -webkit-text-size-adjust:none;
            width: 100%!important;
            height: 100%;
        }

        a { color: #2BA6CB;}

        .btn {
            text-decoration:none;
            color: #FFF;
            background-color: #666;
            padding:10px 16px;
            font-weight:bold;
            margin-right:10px;
            text-align:center;
            cursor:pointer;
            display: inline-block;
        }

        p.callout {
            padding:15px;
            background-color:#ECF8FF;
            margin-bottom: 15px;
        }
        .callout a {
            font-weight:bold;
            color: #2BA6CB;
        }

        table.social {
            /* 	padding:15px; */
            background-color: #ebebeb;

        }
        .social .soc-btn {
            padding: 3px 7px;
            font-size:12px;
            margin-bottom:10px;
            text-decoration:none;
            color: #FFF;font-weight:bold;
            display:block;
            text-align:center;
        }
        a.fb { background-color: #3B5998!important; }
        a.tw { background-color: #1daced!important; }
        a.gp { background-color: #DB4A39!important; }
        a.ms { background-color: #000!important; }

        .sidebar .soc-btn {
            display:block;
            width:100%;
        }

        table.head-wrap { width: 80%;}

        .header.container table td.logo { padding: 15px; }
        .header.container table td.label { padding: 15px; padding-left:0px;}

        table.body-wrap { width: 100%;}

        table.footer-wrap { width: 100%;	clear:both!important;
        }
        .footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
        .footer-wrap .container td.content p {
            font-size:10px;
            font-weight: bold;

        }

        h1,h2,h3,h4,h5,h6 {
            font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
        }
        h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }

        h1 { font-weight:200; font-size: 44px;}
        h2 { font-weight:200; font-size: 37px;}
        h3 { font-weight:500; font-size: 27px;}
        h4 { font-weight:500; font-size: 23px;}
        h5 { font-weight:900; font-size: 17px;}
        h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;}

        .collapse { margin:0!important;}

        p, ul {
            margin-bottom: 10px;
            font-weight: normal;
            font-size:14px;
            line-height:1.6;
        }
        p.lead { font-size:17px; }
        p.last { margin-bottom:0px;}

        ul li {
            margin-left:5px;
            list-style-position: inside;
        }

        ul.sidebar {
            background:#ebebeb;
            display:block;
            list-style-type: none;
        }
        ul.sidebar li { display: block; margin:0;}
        ul.sidebar li a {
            text-decoration:none;
            color: #666;
            padding:10px 16px;
            /* 	font-weight:bold; */
            margin-right:10px;
            /* 	text-align:center; */
            cursor:pointer;
            border-bottom: 1px solid #777777;
            border-top: 1px solid #FFFFFF;
            display:block;
            margin:0;
        }
        ul.sidebar li a.last { border-bottom-width:0px;}
        ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display:block!important;
            max-width:600px!important;
            margin:0 auto!important; /* makes it centered */
            clear:both!important;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            padding:15px;
            max-width:600px;
            margin:0 auto;
            display:block;
        }

        /* Let\'s make sure tables in the content area are 100% wide */
        .content table { width: 100%; }


        /* Odds and ends */
        .column {
            width: 300px;
            float:left;
        }
        .column tr td { padding: 15px; }
        .column-wrap {
            padding:0!important;
            margin:0 auto;
            max-width:600px!important;
        }
        .column table { width:100%;}
        .social .column {
            width: 280px;
            min-width: 279px;
            float:left;
        }

        /* Be sure to place a .clear element after each set of columns, just to be safe */
        .clear { display: block; clear: both; }

        @media only screen and (max-width: 600px) {

            a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}

            div[class="column"] { width: auto!important; float:none!important;}

            table.social div[class="column"] {
                width:auto!important;
            }

        }
    </style>
</head>
            <body bgcolor="#FFFFFF" style="background-color: #cccccc">
                <!-- BODY -->
                <table class="body-wrap">
                    <tr>
                        <td></td>
                        <td class="container" bgcolor="#FFFFFF">
                            <table class="social" width="100%">
                                <tr>
                                    <td>
                                        <!-- column 1 -->
                                        <table align="" style="padding: 20px">
                                            <tr>
                                                <td align="right"><h3 class="collapse" style="font-weight: bolder">'.$this->_project.' DATA MANAGEMENT SYSTEM</h3></td>
                                            </tr>
                                        </table>
                                        <!-- /column 1 -->
                
                                        <span class="clear"></span>
                
                                    </td>
                                </tr>
                            </table>
                            <div class="content">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>Dear '.$all.'</h3>
                                            <p class="lead">In our best efforts to provide an excellent service to all users of '.$this->_project.' DMS, we are scheduling performing system update from '.date("Y-m-d").' at 1600hrs and expected to be completed on '.date("Y-m-d", strtotime(date("Y-m-d"). ' + 7 days')).' at 2300hrs. During the update, some of functionality may not be available or not working properly.</p>
                                           
                                            <!-- Callout Panel -->
                                            <p class="callout" style="color: orange;font-weight: bold">
                                                Please report any issues to the central data management team.
                                            </p><!-- /Callout Panel -->
                                            <p class="lead"> </p>
                                            <p class="lead">&nbsp;</p>
                                            <p class="lead">Kind regards,</p>
                                            <p class="">Winstone Makwesheni,</p>
                                            <p class="">System Developer</p>
                                            <!-- contact Info -->
                                            <table class="social" width="100%">
                                                <tr>
                                                    <td>
                                                        <!-- column 1 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Visit our website at : <a href="'.$this->_website.'">'.$this->_website.'</a></p>
                                                                    <p> </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <!-- /column 1 -->
                
                                                        <!-- column 2 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Send us an Email : <strong><a href="'.$this->_info.'">'.$this->_info.'</a></strong></p>
                                                                </td>
                                                            </tr>
                                                        </table><!-- /column 2 -->
                                                    </td>
                                                </tr>
                                            </table><!-- /contact Info -->
                
                                            <!---- footer--->
                                            <table class="footer-wrap" >
                                                <tr>
                                                    <td></td>
                                                    <td class="container">
                
                                                        <!-- content -->
                                                        <div class="content">
                                                            <table>
                                                                <tr>
                                                                    <td align="center">
                                                                        <p>
                                                                            <a href="#">Terms</a> |
                                                                            <a href="#">Privacy</a> |
                                                                            <a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div><!-- /content -->
                
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <!-- end of footer -->
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- /content -->
                        </td>
                
                    </tr>
                </table><!-- /BODY -->

            </body>
        </html>';
        $this->emailConf($email,$subject,$body);
    }
    function updateReport($email,$subject,$token){
        $all='All';
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" >
            <head>
                <!-- If you delete this meta tag, Half Life 3 will never be released. -->
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title></title>
                <style type="text/css">
                    * {
                        margin:0;
                        padding:0;
                    }
                    * { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }
            
                    img {
                        max-width: 100%;
                    }
                    .collapse {
                        margin:0;
                        padding:0;
                    }
                    body {
                        -webkit-font-smoothing:antialiased;
                        -webkit-text-size-adjust:none;
                        width: 100%!important;
                        height: 100%;
                    }
            
                    a { color: #2BA6CB;}
            
                    .btn {
                        text-decoration:none;
                        color: #FFF;
                        background-color: #666;
                        padding:10px 16px;
                        font-weight:bold;
                        margin-right:10px;
                        text-align:center;
                        cursor:pointer;
                        display: inline-block;
                    }
            
                    p.callout {
                        padding:15px;
                        background-color:#ECF8FF;
                        margin-bottom: 15px;
                    }
                    .callout a {
                        font-weight:bold;
                        color: #2BA6CB;
                    }
            
                    table.social {
                        /* 	padding:15px; */
                        background-color: #ebebeb;
            
                    }
                    .social .soc-btn {
                        padding: 3px 7px;
                        font-size:12px;
                        margin-bottom:10px;
                        text-decoration:none;
                        color: #FFF;font-weight:bold;
                        display:block;
                        text-align:center;
                    }
                    a.fb { background-color: #3B5998!important; }
                    a.tw { background-color: #1daced!important; }
                    a.gp { background-color: #DB4A39!important; }
                    a.ms { background-color: #000!important; }
            
                    .sidebar .soc-btn {
                        display:block;
                        width:100%;
                    }
            
                    table.head-wrap { width: 80%;}
            
                    .header.container table td.logo { padding: 15px; }
                    .header.container table td.label { padding: 15px; padding-left:0px;}
            
                    table.body-wrap { width: 100%;}
            
                    table.footer-wrap { width: 100%;	clear:both!important;
                    }
                    .footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
                    .footer-wrap .container td.content p {
                        font-size:10px;
                        font-weight: bold;
            
                    }
            
                    h1,h2,h3,h4,h5,h6 {
                        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
                    }
                    h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
            
                    h1 { font-weight:200; font-size: 44px;}
                    h2 { font-weight:200; font-size: 37px;}
                    h3 { font-weight:500; font-size: 27px;}
                    h4 { font-weight:500; font-size: 23px;}
                    h5 { font-weight:900; font-size: 17px;}
                    h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;}
            
                    .collapse { margin:0!important;}
            
                    p, ul {
                        margin-bottom: 10px;
                        font-weight: normal;
                        font-size:14px;
                        line-height:1.6;
                    }
                    p.lead { font-size:17px; }
                    p.last { margin-bottom:0px;}
            
                    ul li {
                        margin-left:5px;
                        list-style-position: inside;
                    }
            
                    ul.sidebar {
                        background:#ebebeb;
                        display:block;
                        list-style-type: none;
                    }
                    ul.sidebar li { display: block; margin:0;}
                    ul.sidebar li a {
                        text-decoration:none;
                        color: #666;
                        padding:10px 16px;
                        /* 	font-weight:bold; */
                        margin-right:10px;
                        /* 	text-align:center; */
                        cursor:pointer;
                        border-bottom: 1px solid #777777;
                        border-top: 1px solid #FFFFFF;
                        display:block;
                        margin:0;
                    }
                    ul.sidebar li a.last { border-bottom-width:0px;}
                    ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
            
                    /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                    .container {
                        display:block!important;
                        max-width:600px!important;
                        margin:0 auto!important; /* makes it centered */
                        clear:both!important;
                    }
            
                    /* This should also be a block element, so that it will fill 100% of the .container */
                    .content {
                        padding:15px;
                        max-width:600px;
                        margin:0 auto;
                        display:block;
                    }
            
                    /* Let\'s make sure tables in the content area are 100% wide */
                    .content table { width: 100%; }
            
            
                    /* Odds and ends */
                    .column {
                        width: 300px;
                        float:left;
                    }
                    .column tr td { padding: 15px; }
                    .column-wrap {
                        padding:0!important;
                        margin:0 auto;
                        max-width:600px!important;
                    }
                    .column table { width:100%;}
                    .social .column {
                        width: 280px;
                        min-width: 279px;
                        float:left;
                    }
            
                    /* Be sure to place a .clear element after each set of columns, just to be safe */
                    .clear { display: block; clear: both; }
            
                    @media only screen and (max-width: 600px) {
            
                        a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
            
                        div[class="column"] { width: auto!important; float:none!important;}
            
                        table.social div[class="column"] {
                            width:auto!important;
                        }
            
                    }
                </style>
            </head>
            <body bgcolor="#FFFFFF" style="background-color: #cccccc">
                <!-- BODY -->
                <table class="body-wrap">
                    <tr>
                        <td></td>
                        <td class="container" bgcolor="#FFFFFF">
                            <table class="social" width="100%">
                                <tr>
                                    <td>
                                        <!-- column 1 -->
                                        <table align="" style="padding: 20px">
                                            <tr>
                                                <td align="right"><h3 class="collapse" style="font-weight: bolder">'.$this->_project.' DATA MANAGEMENT SYSTEM</h3></td>
                                            </tr>
                                        </table>
                                        <!-- /column 1 -->
                
                                        <span class="clear"></span>
                
                                    </td>
                                </tr>
                            </table>
                            <div class="content">
                                <table>
                                    <tr>
                                        <td>
                                            <h3>Dear '.$all.'</h3>
                                            <p class="lead">In our best efforts to provide an excellent service to all users of '.$this->_project.' DMS, we have currently finished upgrade the system and its is now back online.</p>
                                           
                                            <!-- Callout Panel -->
                                            <p class="" style="font-weight: bolder"> Updates performed:</p><!-- /Callout Panel -->
                                                <ul>
                                                    <li>Fix performance issues</li>
                                                    <li>Updates system security</li>
                                                    <li>Access control</li>
                                                    <li>System reports</li>                              
                                                </ul>
                                            <p class="" style="color: orangered;font-weight: bold">
                                            
                                            <p class="callout">
                                            <span style="color: darkred;font-weight: bold">Note: Secure password must contain at least 8 characters which include Upper letters,lower case letters,numbers and special characters</span><br><br>
                                            For any suggestions,comments, challenges or system failures please contact the central data management team for assistance. </p>
                                            
                                            <p class="lead">Kind regards,</p>
                                            <p class="lead">&nbsp;</p>
                                            <p class="">Winstone Makwesheni,</p>
                                            <p class="">System Developer</p>
                                            <!-- contact Info -->
                                            <table class="social" width="100%">
                                                <tr>
                                                    <td>
                                                        <!-- column 1 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Visit our website at : <a href="'.$this->_website.'">'.$this->_website.'</a></p>
                                                                    <p> </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <!-- /column 1 -->
                
                                                        <!-- column 2 -->
                                                        <table align="left" class="column">
                                                            <tr>
                                                                <td>
                                                                    <p style="font-weight: bolder">Send us an Email : <strong><a href="'.$this->_info.'">'.$this->_info.'</a></strong></p>
                                                                </td>
                                                            </tr>
                                                        </table><!-- /column 2 -->
                                                    </td>
                                                </tr>
                                            </table><!-- /contact Info -->
                
                                            <!---- footer--->
                                            <table class="footer-wrap" >
                                                <tr>
                                                    <td></td>
                                                    <td class="container">
                
                                                        <!-- content -->
                                                        <div class="content">
                                                            <table>
                                                                <tr>
                                                                    <td align="center">
                                                                        <p>
                                                                            <a href="#">Terms</a> |
                                                                            <a href="#">Privacy</a> |
                                                                            <a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div><!-- /content -->
                
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <!-- end of footer -->
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- /content -->
                        </td>
                
                    </tr>
                </table><!-- /BODY -->

            </body>
        </html>';
        $this->emailConf($email,$subject,$body);
    }
}