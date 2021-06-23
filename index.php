<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();$usr=null;
$email = new Email();$st=null;
$random = new Random();
$pageError = null;$successMessage = null;$errorM = false;$errorMessage = null;
if(!$user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Token::check(Input::get('token'))) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'username' => array('required' => true),
                'password' => array('required' => true)
            ));
            if ($validate->passed()) {
                $st=$override->get('staff','username',Input::get('username'));
                if($st){
                    if($st[0]['count'] >3){
                        $errorMessage = 'You Account have been deactivated,Someone was trying to access it with wrong credentials. Please contact your system administrator';
                    }
                    else{
                        $login = $user->loginUser(Input::get('username'), Input::get('password'), 'staff');
                        if ($login) {
                            $lastLogin = $override->get('staff','id',$user->data()->id);
                            if($lastLogin[0]['last_login'] == date('Y-m-d')){}else{
                                try {
                                    $user->updateRecord('staff', array(
                                        'last_login' => date('Y-m-d'),
                                        'count' => 0,
                                    ), $user->data()->id);
                                } catch (Exception $e) {}
                            }
                            try {
                                $user->updateRecord('staff', array(
                                    'count' => 0,
                                ), $user->data()->id);
                            } catch (Exception $e) {}

                            Redirect::to('dashboard.php');
                        }
                        else {
                            $usr=$override->get('staff','username',Input::get('username'));
                            if($usr && $usr[0]['count'] < 3){
                                try {
                                    $user->updateRecord('staff', array(
                                        'count' => $usr[0]['count'] + 1,
                                    ), $usr[0]['id']);
                                } catch (Exception $e) {
                                }
                                $errorMessage = 'Wrong username or password';
                            }
                            else{
                                try {
                                    $user->updateRecord('staff', array(
                                        'count' => $usr[0]['count'] + 1,
                                    ), $usr[0]['id']);
                                } catch (Exception $e) {
                                }
                                $email->deactivation($usr[0]['email_address'],$usr[0]['lastname'],'Account Deactivated');
                                $errorMessage = 'You Account have been deactivated,Someone was trying to access it with wrong credentials. Please contact your system administrator';
                            }
                        }
                    }
                }else{
                    $errorMessage = 'Invalid username, Please check your credentials and try again';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
}else{
    Redirect::to('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> VTS </title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link href="css/stylesheets.css" rel="stylesheet" type="text/css">

    <script type='text/javascript' src='js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='js/plugins/uniform/jquery.uniform.min.js'></script>

    <script type='text/javascript' src='js/plugins.js'></script>
    <script type='text/javascript' src='js/actions.js'></script>
    <script type='text/javascript' src='js/settings.js'></script>
</head>
<body class="bg-img-num1">

<div class="container">

    <div class="login-block">
        <div class="block block-transparent">
            <div class="head">
                <div class="user">
                    <div class="info user-change">
                        <img src="img/nimr.jpg" class="img-circle img-thumbnail" width="225"/>
                    </div>
                </div>
            </div>
            <?php if($errorMessage){?>
                <div class="block">
                    <div class="alert alert-danger">
                        <b>Error!</b> <?=$errorMessage?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            <?php }elseif($pageError){?>
                <div class="block col-md-12">
                    <div class="alert alert-danger">
                        <b>Error!</b> <?php foreach($pageError as $error){echo $error.' , ';}?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            <?php }?>
            <form method="post">
                <div class="form-row">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="icon-user"></span>
                                </div>
                                <input type="text" name="username" class="form-control" placeholder="Login" required/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="icon-key"></span>
                                </div>
                                <input type="password" name="password" class="form-control" placeholder="Password" required/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <input type="hidden" name="token" value="<?=Token::generate();?>">
                            <input type="submit" value="Log In" class="btn btn-default btn-block btn-clean">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

</body>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</html>