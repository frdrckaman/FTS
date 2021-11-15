<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$site=null;$country=null;$errorM1=null;
$pageError = null;$successMessage = null;$errorM = false;$errorMessage = null;
$favicon=$override->get('images','cat',1)[0];
$logo=$override->get('images','cat',2)[0];
if($user->isLoggedIn()) {
    $site_name=$override->get('site','id',$user->data()->s_id);
    $country_name=$override->get('country','id',$user->data()->c_id);
    if (Input::exists('post')) {
        if (Input::get('change_pswd')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'old_password' => array(
                    'required' => true,
                ),
                'new_password' => array(
                    'required' => true,
                    'min' => 6,
                ),
                're_password' => array(
                    'required' => true,
                    'matches' => 'new_password'
                )
            ));
            if ($validate->passed()) {
                if (Hash::make(Input::get('old_password'), $user->data()->salt) !== $user->data()->password) {
                    $errorMessage = 'Your current password is wrong';
                } else {
                    $salt = Hash::salt(32);
                    try {
                        $user->update(array(
                            'password' => Hash::make(Input::get('new_password'), $salt),
                            'salt' => $salt
                        ));
                    } catch (Exception $e) {
                    }
                    $successMessage = 'Password changed successfully';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif(Input::get('info_btn')){
            try {
                $user->updateRecord('staff', array(
                    'phone_number' => Input::get('phone_number'),
                    'email_address' => Input::get('email_address'),
                ), $user->data()->id);
            } catch (Exception $e) {
            }
            $successMessage = 'Your profile information changed successfully';
        }
        elseif(Input::get('photo')){
           // $attachment_file = Input::get('pic');
            if (!empty($_FILES['image']["tmp_name"])) {
                $attach_file = $_FILES['image']['type'];
                if ($attach_file == "image/jpeg" || $attach_file == "image/jpg" || $attach_file == "image/png" || $attach_file == "image/gif") {
                    $folderName = 'assets/users/';
                    $attachment_file = $folderName . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']["tmp_name"], $attachment_file)) {
                        $file = true;
                    } else {
                        {
                            $errorM1 = true;
                            $errorMessage = 'Your profile Picture Not Uploaded ,';
                        }
                    }
                } else {
                    $errorM1 = true;
                    $errorMessage = 'None supported file format';
                }//not supported format
                if($errorM1 == false){
                    try {
                        $user->updateRecord('staff', array(
                            'picture' => $attachment_file,
                        ), $user->data()->id);
                    } catch (Exception $e) {
                    }
                    $successMessage = 'Your profile Picture Uploaded successfully';
                }
            }else{
                $errorMessage = 'You have not select any picture to upload';

            }
        }
    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> VTS </title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" href="<?php if($favicon){echo $favicon['location'];}else{echo 'favicon.ico';}?>">
    <link href="css/stylesheets.css" rel="stylesheet" type="text/css">
    <link href="css/morris.css" rel="stylesheet" type="text/css">

    <script type='text/javascript' src='js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='js/plugins/tagsinput/jquery.tagsinput.min.js'></script>

    <script type='text/javascript' src='js/plugins/ibutton/jquery.ibutton.html'></script>

    <script type='text/javascript' src='js/morris.min.js'></script>
    <script type='text/javascript' src='js/raphael.min.js'></script>

    <script type='text/javascript' src='js/plugins.js'></script>
    <script type='text/javascript' src='js/actions.js'></script>
    <script type='text/javascript' src='js/settings.js'></script>
</head>
<body class="bg-img-num1">

<div class="container">
<div class="row">
    <div class="col-md-12">
        <?php include'topBar.php'?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="profile.php">profile</a></li>
            <li class="active"><?=$user->data()->firstname.' ',$user->data()->lastname;?></li>
        </ol>
    </div>
</div>

<div class="row">

<div class="col-md-2">
    <div class="block block-drop-shadow">
        <form enctype="multipart/form-data" method="post">
            <div class="head bg-dot30 npb">
                <h2>Picture</h2>
                <div class="pull-right">
                    <input type="submit" value="Save" name="photo" class="btn btn-default btn-clean">
                </div>
            </div>
            <div class="head bg-dot30 np tac">
                <?php if($user->data()->picture){?>
                    <img src="<?=$user->data()->picture?>" class="img-thumbnail img-circle" width="90" height="90"/>
                <?php }else{?>
                    <img src="assets/users/blank.png" class="img-thumbnail img-circle"/>
                <?php }?>
            </div>
            <div class="content controls">
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="input-group file">
                            <input type="text" class="form-control" value=""/>
                            <input type="file" name="image"/>
                        <span class="input-group-btn">
                            <button class="btn" type="button">Browse</button>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12">
                        <input type="text" class="form-control" value="<?=$user->data()->username?>" disabled/>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <form enctype="multipart/form-data" method="post">
        <div class="block block-drop-shadow">
            <div class="header">
                <h2>Change password</h2>
            </div>
            <div class="content controls">
                <div class="form-row">
                    <div class="col-md-12">
                        <input type="password" name="old_password" class="form-control" placeholder="Old password"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12">
                        <input type="password" name="new_password" class="form-control" placeholder="New password"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12">
                        <input type="password" name="re_password" class="form-control" placeholder="Re-password"/>
                    </div>
                </div>
            </div>
            <div class="footer tar">
                <input type="submit" value="Confirm" name="change_pswd" class="btn btn-default btn-clean">
            </div>
        </div>
    </form>
</div>

<div class="col-md-7">
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
    <?php }elseif($successMessage){?>
        <div class="block">
            <div class="alert alert-success">
                <b>Success!</b> <?=$successMessage?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        </div>
    <?php }?>
    <div class="block block-drop-shadow">
        <div class="header">
            <h2>Edit profile</h2>
        </div>
        <form enctype="multipart/form-data" method="post">
            <div class="content controls">
                <div class="form-row">
                    <div class="col-md-3">First Name:</div>
                    <div class="col-md-9">
                        <input type="text" name="firstname" class="form-control" value="<?=$user->data()->firstname?>" disabled/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3">Last Name:</div>
                    <div class="col-md-9">
                        <input type="text" name="lastname" class="form-control" value="<?=$user->data()->lastname?>" disabled/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3">E-mail:</div>
                    <div class="col-md-9">
                        <input type="email" name="email_address" class="form-control" value="<?=$user->data()->email_address?>"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3">Phone:</div>
                    <div class="col-md-9">
                        <input type="text" name="phone_number" class="form-control" value="<?=$user->data()->phone_number?>"/>
                    </div>
                </div>
            </div>
            <div class="footer tar">
                <div class="pull-right col-md-2">
                    <input type="submit" value="Confirm" name="info_btn" class="btn btn-default btn-clean">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="col-md-3">
    <div class="block block-drop-shadow">
        <div class="header">
            <h2>Staff Information</h2>
        </div>
        <div class="content controls">
            <div class="form-row">
                <div class="col-md-6">Country:</div>
                <div class="col-md-6">
                    <input type="text" class="form-control" value="<?=$country_name[0]['name'].' ( '.$country_name[0]['short_code'].' ) '?>" disabled/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">Site:</div>
                <div class="col-md-6">
                    <input type="text" class="form-control" value="<?=$site_name[0]['name'].' ( '.$site_name[0]['short_code'].' ) '?>" disabled/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">Position:</div>
                <div class="col-md-6">
                    <input type="text" class="form-control" value="<?=$override->get('position','id',$user->data()->position)[0]['name']?>" disabled/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">Created on:</div>
                <div class="col-md-6">
                    <input type="text" class="form-control" value="<?=$user->data()->reg_date?>" disabled/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">Last Seen:</div>
                <div class="col-md-6">
                    <input type="text" class="form-control" value="<?=$user->data()->last_login?>" disabled/>
                </div>
            </div>

        </div>
    </div>

</div>

</div>

</div>
<script>
    <?php if($user->data()->pswd == 0){?>
    $(window).on('load',function(){
        $("#change_password").modal({
            backdrop: 'static',
            keyboard: false
        },'show');
    });
    <?php }?>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

</body>

</html>