<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$pageError = null;$successMessage = null;$errorM = false;$errorMessage = null;
$t_crf=0;$p_crf=0;$w_crf=0;$s_name=null;$c_name=null;$site=null;$country=null;
$study_crf=null;$data_limit=10000;
$favicon=$override->get('images','cat',1)[0];
$logo=$override->get('images','cat',2)[0];

//modification remove all pilot crf have been removed/deleted from study crf
/*$d1=strtotime('7/1/2019');
$d2=strtotime('7/5/2019');
$r = $d2-$d1;
print_r($r/86400);*/
if($_GET['id'] == 11){$col1=0;$col2=12;}else{$col1=2;$col2=10;}
if($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('edit_client')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'study_id' => array(
                    'required' => true,
                    'min' => 6,
                ),
                'initials' => array(
                    'required' => true,
                    'max' => 3,
                ),
                'visit_code' => array(

                ),
                'phone_number' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('clients', array(
                        'study_id' => Input::get('study_id'),
                        'visit_code' => Input::get('visit_code'),
                        'initials' => Input::get('initials'),
                        'phone_number' => Input::get('phone_number'),
                        'phone_number2' => Input::get('phone_number2'),
                    ),Input::get('id'));
                    $successMessage = 'Information Saved successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('delete_client')){
            try {
                $user->updateRecord('clients', array(
                    'status' => 0,
                    'reason'=> Input::get('reason'),
                ),Input::get('id'));
                $successMessage = 'Patient End Study Successful';

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        elseif (Input::get('delete_client_schedule')){
            try {
                $user->deleteRecord('visit','client_id',Input::get('id'));
                $successMessage = 'Patient Deleted Successful';

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        elseif (Input::get('appointment')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_status' => array(
                    'required' => true,
                ),
            ));
            $getVisit=$override->get('clients','id',Input::get('client_id'));
            $getV = $override->getNews('visit','id',Input::get('id'),'visit_date',date('Y-m-d'));
            if($user->data()->position == 1){$a_status='dm_status';}
            elseif ($user->data()->position == 6 || $user->data()->position == 5){$a_status='sn_cl_status';}
            elseif ($user->data()->position == 12){$a_status='dc_status';}
            if ($validate->passed()) {
                try {
                    if($user->data()->position == 5 || $user->data()->position == 6){
                        $user->updateRecord('visit', array(
                            $a_status => Input::get('visit_status'),
                            'status' => Input::get('visit_status'),
                            'staff_id'=>$user->data()->id
                        ),Input::get('v_id'));
                        $date=null;
                        $visitCode = $getVisit[0]['visit_code'] + 1;
                        if($visitCode){
                            $user->updateRecord('clients',array('visit_code'=>$visitCode),Input::get('client_id'));
                        }
                        $successMessage = 'Visit Added Successful' ;
                    }else{
                        if(Input::get('sn') == 1 || Input::get('sn') == 2){
                            $user->updateRecord('visit', array(
                                $a_status => Input::get('visit_status'),
                                'staff_id'=>$user->data()->id
                            ),Input::get('v_id'));
                        }else{
                            $errorMessage='Patient must be attended by study nurse or clinician first';
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('delete_staff')) {
            try {
                $user->updateRecord('staff', array(
                    'status' => 0,
                ),Input::get('id'));
                $successMessage = 'Staff Deleted Successful';

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        elseif(Input::get('edit_staff')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                    'min' => 3,
                ),
                'lastname' => array(
                    'required' => true,
                    'min' => 3,
                ),
                'country_id' => array(
                    'required' => true,
                ),
                'site_id' => array(
                    'required' => true,
                ),
                'position' => array(
                    'required' => true,
                ),
                'username' => array(
                    'required' => true,
                ),
                'phone_number' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                switch (Input::get('position')) {
                    case 1:
                        $accessLevel = 1;
                        break;
                    case 2:
                        $accessLevel = 1;
                        break;
                    case 3:
                        $accessLevel = 1;
                        break;
                    default:
                        $accessLevel = 4;
                }
                try {
                    $user->updateRecord('staff', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'username' => Input::get('username'),
                        'access_level' => $accessLevel,
                        'phone_number' => Input::get('phone_number'),
                        'email_address' => Input::get('email_address'),
                        'c_id' => Input::get('country_id'),
                        's_id' => Input::get('site_id'),
                        'status' => 1
                    ), Input::get('id'));
                    $successMessage = 'Staff Info Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            }else {
                $pageError = $validate->errors();
            }
        }
        elseif(Input::get('delete_site')){
            try {
                $user->updateRecord('site', array(
                    'status' => 0,
                ),Input::get('id'));
                $successMessage = 'Site Deleted Successful';

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        elseif(Input::get('delete_country')){
            try {
                $user->updateRecord('country', array(
                    'status' => 0,
                ),Input::get('id'));
                $successMessage = 'Country Deleted Successful';

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        elseif(Input::get('delete_end_study')){
            try {
                $user->deleteRecord('end_study_reason','id',Input::get('id'));
                $successMessage = 'Reason Deleted Successful';

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        elseif(Input::get('edit_site')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'site_name' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 2,
                ),
                'country_id' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('site', array(
                        'name' => Input::get('site_name'),
                        'short_code' => Input::get('short_code'),
                        'c_id' => Input::get('country_id')
                    ),Input::get('id'));
                    $successMessage = 'Site Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif(Input::get('edit_country')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'country_name' => array(
                    'required' => true,
                ),
                'short_code' => array(
                    'required' => true,
                    'min' => 2,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('country', array(
                        'name' => Input::get('country_name'),
                        'short_code' => Input::get('short_code'),
                    ),Input::get('id'));
                    $successMessage = 'Country Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif(Input::get('edit_end_study')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'reason' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('end_study_reason', array(
                        'reason' => Input::get('reason'),
                    ),Input::get('id'));
                    $successMessage = 'Reason Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif(Input::get('reset_password')){
            $salt = $random->get_rand_alphanumeric(32);
            $password = '123456';
            try{
                $user->updateRecord('staff',array(
                    'password' => Hash::make($password, $salt),
                    'salt' => $salt,
                ),Input::get('id'));
                $email->resetPassword(Input::get('email'),Input::get('firstname'),'Password Reset');
                $successMessage = 'Password Reset to Default Successful';
            }
            catch (Exception $e) {
                $e->getMessage();
            }
        }
        elseif (Input::get('add_end_study')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'reason' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('clients',array(
                            'status'=>0,
                            'reason'=>Input::get('reason'),
                            'details'=>Input::get('details')
                    ),Input::get('id'));
                    foreach ($override->get('visit','client_id',Input::get('id')) as $end){
                        if($end['status'] == 0){
                            $user->updateRecord('visit',array('status'=>4),$end['id']);
                        }
                    }

                    $successMessage = 'End of Study Added Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_visit')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit_code' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $date=date('Y-m-d',strtotime(Input::get('visit_date')));
                    $user->updateRecord('visit',array(
                        'visit_date'=>$date,
                        'visit_code' => Input::get('visit_code')
                    ),Input::get('id'));

                    $successMessage = 'Visit Edited Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_schedule')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateSchedule(Input::get('id'),Input::get('visit_date'),Input::get('visit'));
                    $successMessage = 'Visit Edited Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif(Input::get('delete_visit')){
            try {
                $vsc=$override->get('clients','id',Input::get('cl_id'));
                $nw_vc = $vsc[0]['visit_code'] - 1;
                $user->updateRecord('clients',array('visit_code' => $nw_vc),Input::get('cl_id'));
                $user->deleteRecord('visit', 'id', Input::get('id'));
                $successMessage = 'Visit Deleted Successful';

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        elseif (Input::get('nxt_visit')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'next_visit' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $date=date('Y-m-d',strtotime(Input::get('next_visit')));
                    $user->updateRecord('schedule',array(
                        'visit_date'=>$date
                    ),Input::get('id'));

                    $successMessage = 'Next Visit Edited Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('download')){
            $override->dateRange('visit','visit_date',$_GET['from'],$_GET['to']);$y=0;$list= array();$data1=null;$mqz=null;$am=null;$r=0;
            while($y<=$user->dateDiff($_GET['to'],$_GET['from'])){
                if($y==0){$list[$y]='Study ID';$y++;}else{
                    $list[$y]=date('Y-m-d', strtotime($_GET['from']. ' + '.$y.' days'));$y++;
                }

            }
            $data1[0]=$list;

            foreach ($override->dateRangeD('visit','client_id','visit_date',$_GET['from'],$_GET['to']) as $dt){$f=0;
                if($dt['status'] != 4){
                    $client=$override->get('clients', 'id', $dt['client_id'])[0];
                    $clientGroup=$override->get('patient_group','id',$client['pt_group'])[0]['name'];
                    foreach ($list as $data){
                        $d=$override->getNews('visit','client_id',$dt['client_id'],'visit_date',$data)[0];
                        if($f==0){
                            $mqz[$f]= $client['study_id'].'('.$clientGroup.') ';$f++;
                        }else{
                            if($d){
                                if($d['status']==1){
                                    $mqz[$f]= 'Done '.$d['visit_code'].' '.$d['visit_type'];$f++;
                                }elseif ($d['status']==2){
                                    $mqz[$f]= 'Missed '.$d['visit_code'].' '.$d['visit_type'];$f++;
                                }elseif ($d['status']==0){
                                    $mqz[$f]= 'Scheduled '.$d['visit_code'].' '.$d['visit_type'];$f++;
                                }
                            }
                            else{
                                $mqz[$f]= '-';$f++;
                            }
                        }
                    }
                    $am[$r] = $mqz;
                    $r++;
                }
            }
            $user->exportSchedule($data1,$am,'schedule');
        }
        elseif(Input::get('edit_pt_group')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'group_name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('patient_group', array(
                        'name' => Input::get('group_name'),
                    ),Input::get('id'));
                    $successMessage = 'Group Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_study')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'study_code' => array(
                    'required' => true,
                    'min' => 2,
                )
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('study', array(
                        'name' => Input::get('name'),
                        'study_code' => Input::get('study_code'),
                        'sample_size' => Input::get('sample_size'),
                        'duration' => Input::get('duration'),
                        'start_date' => Input::get('start_date'),
                        'end_date' => Input::get('end_date'),
                        'details' => Input::get('details'),
                    ),Input::get('id'));
                    $successMessage = 'Study Edited Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_image')){
            // $attachment_file = Input::get('pic');
            if (!empty($_FILES['image']["tmp_name"])) {
                $attach_file = $_FILES['image']['type'];
                if ($attach_file == "image/jpeg" || $attach_file == "image/jpg" || $attach_file == "image/png" || $attach_file == "image/gif" || $attach_file == "image/ico") {
                    $folderName = 'img/project/';
                    $attachment_file = $folderName . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']["tmp_name"], $attachment_file)) {
                        $file = true;
                    } else {
                        {
                            $errorM1 = true;
                            $errorMessage = 'Your Image Not Uploaded ,';
                        }
                    }
                } else {
                    $errorM1 = true;
                    $errorMessage = 'None supported file format';
                }//not supported format
                if($errorM1 == false){
                    try {
                        $user->updateRecord('images', array(
                            'location' => $attachment_file,
                            'project_id'=>Input::get('project'),
                            'cat' => Input::get('image_cat'),
                            'status'=>1
                        ),Input::get('id'));
                        $successMessage = 'Your Image Uploaded successfully';
                    } catch (Exception $e) {
                        $e->getMessage();
                    }
                }
            }else{
                $errorMessage = 'You have not select any image to upload';

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

    <script type='text/javascript' src='js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='js/plugins/uniform/jquery.uniform.min.js'></script>

    <script type='text/javascript' src='js/plugins/knob/jquery.knob.js'></script>
    <script type='text/javascript' src='js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='js/plugins/datatables/jquery.dataTables.min.js'></script>
    <script type='text/javascript' src='js/plugins/select2/select2.min.js'></script>
    <script type='text/javascript' src='js/plugins/tagsinput/jquery.tagsinput.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui-timepicker-addon.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap-file-input.js'></script>

    <script type='text/javascript' src='js/plugins.js'></script>
    <script type='text/javascript' src='js/actions.js'></script>
    <script type='text/javascript' src='js/settings.js'></script>


</head>
<body class="bg-img-num1" data-settings="open">

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php require 'topBar.php'?>
        </div>
    </div>
    <div class="row">
        <?php if($_GET['id'] !=11){?>
            <div class="col-md-<?=$col1?>">
                <?php require 'sideBar.php'?>
            </div>
        <?php }?>
        <div class="col-md-offset-0 col-md-<?=$col2?>">
            <div>
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
            </div>
            <?php if($_GET['id'] == 1){?>
                <div class="block">
                    <div class="header">
                        <h2>TODAY SCHEDULE VISITS FOR ALL STUDIES</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>

                                <th width="8%">STUDY ID</th>
                                <th width="15">GROUP NAME / STUDY NAME</th>
                                <th width="10%">VISIT CODE</th>
                                <th width="10%">VISIT STATUS</th>
                                <th width="25%">DATA STATUS</th>
                                <th width="10%">PHONE NUMBER</th>
                                <th width="20%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x=1;foreach ($override->get('visit','visit_date',date('Y-m-d')) as $data){
                                $client=$override->get('clients','id',$data['client_id'])[0];
                                $lastVisit= $override->getlastRow('visit','client_id',$data['client_id'],'visit_date');
                                if($client['status'] == 1){?>
                                    <tr>
                                        <td><?=$client['study_id']; ?></td>
                                        <td><?=$override->get('patient_group','id',$client['pt_group'])[0]['name'].' / '.$override->get('study','id',$client['project_id'])[0]['study_code']; ?></td>
                                        <td><?=$data['visit_code'].' ( '.$data['visit_type'].' ) '?></td>
                                        <td>
                                        <div class="btn-group btn-group-xs">
                                                <?php if($data['status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Visit Pending</button>
                                                <?php }elseif($data['status']==1){?>
                                                    <button class="btn btn-success">Visit Completed</button>
                                                <?php }elseif($data['status']==2){?>
                                                    <button class="btn btn-danger">Visit Missed</button>
                                                <?php }?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['sn_cl_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Clinician Pending</button>
                                                <?php }elseif($data['sn_cl_status']==1){?>
                                                    <button class="btn btn-success">Clinician Completed</button>
                                                <?php }elseif($data['sn_cl_status']==2){?>
                                                    <button class="btn btn-danger">Clinician Missed</button>
                                                <?php }?>
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['dc_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Data Pending</button>
                                                <?php }elseif($data['dc_status']==1){?>
                                                    <button class="btn btn-success">Data Completed</button>
                                                <?php }elseif($data['dc_status']==2){?>
                                                    <button class="btn btn-danger">Data Missed</button>
                                                <?php }?>
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['dm_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Review Pending</button>
                                                <?php }elseif($data['dm_status']==1){?>
                                                    <button class="btn btn-success">Review Completed</button>
                                                <?php }elseif($data['dm_status']==2){?>
                                                    <button class="btn btn-danger">Review Missed</button>
                                                <?php }?>
                                            </div>
                                        </td>
                                        <td><?=$client['phone_number']?></td>
                                        <td>
                                            <a href="#appnt<?=$x?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a>
                                        </td>
                                        <div class="modal" id="appnt<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">APPOINTMENT</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">VISIT CODE:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="hidden" name="visit_code" value="<?=$client['visit_code']+1?>">
                                                                        <input type="text" name="visit_code" class="form-control" value="<?=$data['visit_code'].'('.$data['visit_type'].')'?>" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row" id="st">
                                                                    <div class="col-md-2">Status</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="site" name="visit_status" required>
                                                                            <option value="">Select Status</option>
                                                                            <option value="1">Complete</option>
                                                                            <option value="2">Missing</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$lastVisit[0]['id']?>">
                                                                <input type="hidden" name="v_id" value="<?=$data['id']?>">
                                                                <input type="hidden" name="client_id" value="<?=$client['id']?>">
                                                                <input type="hidden" name="sn" value="<?=$data['sn_cl_status']?>">
                                                                <input type="submit" name="appointment" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php }$x++;}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 2){?>
                <div class="block">
                    <div class="header">
                        <h2>MISSED VISITS</h2>
                    </div>
                    <div class="content">

                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>
                                <th width="20%">STUDY ID</th>
                                <th width="20%">GROUP</th>
                                <th width="5%">DAYS</th>
                                <th width="50%">DETAILS</th>
                                <th width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $x=1;foreach ($override->getDataOrderBy1('visit','status',2,'visit_date') as $data){
                                    $cl=$override->get('clients','id',$data['client_id']);
                                    if($cl[0]['status'] == 1){
                                        if($data['visit_date'] < date('Y-m-d')){
                                            $lastVisit=$override->getlastRow('visit','client_id',$data['client_id'],'id');
                                            $client=$override->get('clients','id',$data['client_id']);
                                            $group=$override->get('patient_group','id',$client[0]['pt_group'])[0]['name'];
                                            $mcDays=(strtotime(date('Y-m-d'))-strtotime($data['visit_date']))?>
                                            <tr>
                                                <td><?=$client[0]['study_id'].' ( '?><?=$client[0]['phone_number'].' ) '?></td>
                                                <td><?=$group.' / '.$override->get('study','id',$client[0]['project_id'])[0]['study_code']?></td>
                                                <td><?=($mcDays/86400)?></td>
                                                <td>
                                                    <div class="btn-group btn-group-xs"><?php if($client[0]['status']==2){?>&nbsp;<button class="btn btn-danger">End Study</button> <?php echo$client[0]['reason'].' { '.$client[0]['details'].' } ';}else{?><button class="btn btn-success">Active</button><?php }echo' '?></div>
                                                </td>
                                                <td>
                                                    <a href="#reason<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Information"><span class="glyphicon-log-out"></span></a>
                                                </td>
                                            </tr>
                                            <div class="modal" id="reason<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">END OF STUDY</h4>
                                                            </div>
                                                            <div class="modal-body clearfix">
                                                                <div class="controls">
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Reason:</div>
                                                                        <div class="col-md-10">
                                                                            <select class="form-control" id="c" name="reason" required="">
                                                                                <option value="">Select reason for study termination</option>
                                                                                <?php foreach ($override->getData('end_study_reason') as $end_study){?>
                                                                                    <option value="<?=$end_study['reason']?>"><?=$end_study['reason']?></option>
                                                                                <?php }?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="col-md-2">Details:</div>
                                                                        <div class="col-md-10">
                                                                            <textarea name="details" class="form-control" rows="4"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="pull-right col-md-3">
                                                                    <input type="hidden" name="id" value="<?=$data['client_id']?>">
                                                                    <input type="submit" name="add_reason" value="Submit" class="btn btn-success btn-clean">
                                                                </div>
                                                                <div class="pull-right col-md-2">
                                                                    <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $x++;}}}?>
                            </tbody>
                        </table>

                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 3){?>
                <div class="block">
                    <div class="header">
                        <h2>All SCHEDULES</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>
                                <th width="20%">STUDY ID</th>
                                <th width="10%">VISIT CODE</th>
                                <th width="25%">LAST VISIT</th>
                                <th width="20%">NEXT VISIT</th>
                                <th width="20%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x=1;foreach ($override->getDataOrderByAsc('schedule','visit_date') as $data){
                                $client=$override->get('clients','id',$data['client_id']);
                                $lastVisit=$override->getlastRow('visit','client_id',$data['client_id'],'id')?>
                                <tr>
                                    <td><?=$client[0]['study_id'].' ( '.$client[0]['initials'].' ) '.$client[0]['phone_number']?></td>
                                    <td><?=$client[0]['visit_code']?></td>
                                    <td><?=$lastVisit[0]['visit_date']?></td>
                                    <td><?=$data['visit_date']?></td>
                                    <td>
                                        <a href="#next_visit<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Staff Information"><span class="icon-pencil"></span></a>
                                    </td>
                                </tr>
                                <div class="modal" id="next_visit<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">EDIT NEXT VISIT</h4>
                                                </div>
                                                <div class="modal-body clearfix">
                                                    <div class="controls">
                                                        <div class="form-row">
                                                            <div class="col-md-2">STUDY ID:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="study_id" class="form-control" value="<?=$client[0]['study_id']?>" disabled/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">VISIT CODE:</div>
                                                            <div class="col-md-10">
                                                                <input type="number" name="visit_code" class="form-control" value="<?=$client[0]['visit_code']?>" disabled/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">INITIALS:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="initials" class="form-control" value="<?=$client[0]['initials']?>" disabled/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Last Visit:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="visit_date" class="datepicker form-control" value="<?=$lastVisit[0]['visit_date']?>" disabled/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Next Visit:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="next_visit" class="datepicker form-control" value="<?=$data['visit_date']?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="pull-right col-md-3">
                                                        <input type="hidden" name="id" class="form-control" value="<?=$data['id']?>" required=""/>
                                                        <input type="submit" name="nxt_visit" value="SUBMIT" class="btn btn-success btn-clean">
                                                    </div>
                                                    <div class="pull-right col-md-2">
                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php $x++;}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 4){?>
                <div class="block">
                    <div class="header">
                        <h2>ALL VISITS</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">STUDY ID</th>
                                <th width="20%">CURRENT VISIT CODE</th>
                                <th width="20%">PHONE NUMBER</th>
                                <th width="15%">VIEW</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x=1;$no=0;$data=$override->getRepeatAll('visit','client_id','id');

                            foreach($data as $value){$client=$override->get('clients','id',$value['client_id']); ?>
                                <tr>
                                    <td><?=$x?></td>
                                    <td><?=$client[0]['study_id']?>
                                        <?php if($client[0]['status'] == 0){?>
                                            <div class="btn-group btn-group-xs">
                                                <button class="btn btn-danger"><span class="icon-ok-sign"></span> End Study </button>
                                            </div>
                                        <?php }else {?>
                                            <div class="btn-group btn-group-xs">
                                                <button class="btn btn-success"><span class="icon-ok-sign"></span> Active </button>
                                            </div>
                                        <?php }?>
                                    </td>
                                    <td><?=$client[0]['visit_code']?></td>
                                    <td><?=$client[0]['phone_number']?></td>
                                    <td><div class="btn-group btn-group-xs"><a href="info.php?id=6&cid=<?=$value['client_id']?>" class="btn btn-info btn-clean"><span class="icon-eye-open"></span> View All Visits</a></div></td>
                                </tr>
                                <?php $x++;}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 5){?>
                <div class="block">
                    <div class="header">
                        <h2>LIST OF ALL PATIENTS</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>
                                <th width="15%">STUDY ID</th>
                                <th width="10%">GROUP/STUDY</th>
                                <th width="25%">LAST VISIT</th>
                                <th width="20%">PHONE NUMBER</th>
                                <th width="20%">Manage</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $y=1;foreach ($override->getDataOrderByAsc('clients','study_id') as $client){
                                    $lastVisit=$override->getlastRow('visit','client_id',$client['id'],'id')?>
                                    <tr>
                                        <td><?=$client['study_id'].'  ( '.$client['initials'].' )  '?><?php if($client['status'] == 1){?><div class="btn-group btn-group-xs"><button class="btn btn-success">Active</button></div><?php }else{?><div class="btn-group btn-group-xs"><button class="btn btn-danger">End Study</button></div><?php }?></td>
                                        <td><?=$override->get('patient_group','id',$client['pt_group'])[0]['name'].' / '.$override->get('study','id',$client['project_id'])[0]['study_code']?></td>
                                        <td><?php if($lastVisit){echo $lastVisit[0]['visit_date'];}else{echo '';}?></td>
                                        <td><?=$client['phone_number'].' '.$client['phone_number2']?></td>
                                        <td>
                                            <a href="#edit_client<?=$y?>" data-toggle="modal" class="widget-icon" title="Edit Staff Information"><span class="icon-pencil"></span></a>
                                            <a href="#reasons<?=$y?>" data-toggle="modal" class="widget-icon" title="End Study"><span class="icon-warning-sign"></span></a>
                                            <a href="#edit_schedule<?=$y?>" data-toggle="modal" class="widget-icon" title="Edit Schedule"><span class="icon-refresh"></span></a>
                                            <a href="#delete_client<?=$y?>" data-toggle="modal" class="widget-icon" title="Delete Staff"><span class="icon-trash"></span></a>
                                            <a href="info.php?id=11&pid=<?=$client['id']?>" class="widget-icon" title="list schedule"><span class="icon-list"></span></a>
                                            <a href="#delete_client_schedule<?=$y?>" data-toggle="modal" class="widget-icon" title="Delete Patient Schedules"><span class="icon-remove"></span></a>
                                        </td>
                                    </tr>
                                    <div class="modal" id="edit_client<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">EDIT CLIENT</h4>
                                                    </div>
                                                    <div class="modal-body clearfix">
                                                        <div class="controls">
                                                            <div class="form-row">
                                                                <div class="col-md-2">STUDY ID:</div>
                                                                <div class="col-md-10">
                                                                    <input type="text" name="study_id" class="form-control" value="<?=$client['study_id']?>" required=""/>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-2">VISIT CODE:</div>
                                                                <div class="col-md-10">
                                                                    <input type="number" name="visit_code" class="form-control" value="<?=$client['visit_code']?>" required=""/>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-2">INITIALS:</div>
                                                                <div class="col-md-10">
                                                                    <input type="text" name="initials" class="form-control" value="<?=$client['initials']?>" required=""/>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-2">Phone:</div>
                                                                <div class="col-md-10">
                                                                    <input type="text" name="phone_number" class="form-control" value="<?=$client['phone_number']?>" required=""/>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-2">Phone2:</div>
                                                                <div class="col-md-10">
                                                                    <input type="text" name="phone_number2" class="form-control" value="<?=$client['phone_number2']?>" />
                                                                </div>
                                                            </div>
                                                            <div class="form-row" id="st">
                                                                <div class="col-md-2">Project:</div>
                                                                <div class="col-md-10">
                                                                    <select class="form-control" id="project_id" name="project_id" required>
                                                                        <option value="<?=$group['project_id']?>"><?=$override->get('study','id',$group['project_id'])['id']['name']?></option>
                                                                        <?php foreach ($override->getData('study') as $group){?>
                                                                            <option value="<?=$group['id']?>"><?=$group['name']?></option>
                                                                        <?php }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="pull-right col-md-3">
                                                            <input type="hidden" name="id" class="form-control" value="<?=$client['id']?>" required=""/>
                                                            <input type="submit" name="edit_client" value="SUBMIT" class="btn btn-success btn-clean">
                                                        </div>
                                                        <div class="pull-right col-md-2">
                                                            <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal" id="reasons<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">END OF STUDY</h4>
                                                    </div>
                                                    <div class="modal-body clearfix">
                                                        <div class="controls">
                                                            <div class="form-row">
                                                                <div class="col-md-2">Reason:</div>
                                                                <div class="col-md-10">
                                                                    <select class="form-control" id="c" name="reason" required="">
                                                                        <option value="">Select reason for study termination</option>
                                                                        <?php foreach ($override->getData('end_study_reason') as $end_study){?>
                                                                            <option value="<?=$end_study['reason']?>"><?=$end_study['reason']?></option>
                                                                        <?php }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-2">Details:</div>
                                                                <div class="col-md-10">
                                                                    <textarea name="details" class="form-control" rows="4"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="pull-right col-md-3">
                                                            <input type="hidden" name="id" value="<?=$client['id']?>">
                                                            <input type="submit" name="add_end_study" value="Submit" class="btn btn-success btn-clean">
                                                        </div>
                                                        <div class="pull-right col-md-2">
                                                            <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal" id="edit_schedule<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">EDIT SCHEDULE</h4>
                                                    </div>
                                                    <div class="modal-body clearfix">
                                                        <div class="controls">
                                                            <div class="form-row">
                                                                <div class="col-md-2">Visit:</div>
                                                                <div class="col-md-10">
                                                                    <select class="form-control" id="c" name="visit" required>
                                                                        <option value="">Select Visit</option>
                                                                        <option value="1">V1</option>
                                                                        <option value="2">V2</option>
                                                                        <option value="3">V3</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-2">VISIT DATE:</div>
                                                                <div class="col-md-10">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                                        <input type="text" name="visit_date" class="datepicker form-control" value="" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="pull-right col-md-3">
                                                            <input type="hidden" name="id" value="<?=$client['id']?>">
                                                            <input type="submit" name="edit_schedule" value="Submit" class="btn btn-success btn-clean">
                                                        </div>
                                                        <div class="pull-right col-md-2">
                                                            <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal modal-danger" id="delete_client<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS PATIENT ?</h4>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-2">Reason:</div>
                                                        <div class="col-md-10">
                                                            <textarea rows="4" name="reason" class="form-control" value="<?=$client['study_id']?>" required=""></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <div class="col-md-2 pull-right">
                                                            <input type="hidden" name="id" value="<?=$client['id']?>">
                                                            <input type="submit" name="delete_client" value="END" class="btn btn-default btn-clean">
                                                        </div>
                                                        <div class="col-md-2 pull-right">
                                                            <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal modal-danger" id="delete_client_schedule<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS PATIENT SCHEDULES?</h4>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="col-md-2 pull-right">
                                                            <input type="hidden" name="id" value="<?=$client['id']?>">
                                                            <input type="submit" name="delete_client_schedule" value="DELETE" class="btn btn-default btn-clean">
                                                        </div>
                                                        <div class="col-md-2 pull-right">
                                                            <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php $y++;}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 6){?>
                <div class="block">
                    <div class="header">
                        <h2>VISITS</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>
                                <th width="20%">STUDY ID</th>
                                <th width="10%">INITIALS</th>
                                <th width="10%">VISIT CODE</th>
                                <th width="25%">VISIT DTE</th>
                                <th width="30%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $y=1;if(isset($_GET['cid'])){$x=1;foreach ($override->getDataOrderByA('visit','client_id',$_GET['cid'],'visit_date') as $data){
                                $client=$override->get('clients','id',$data['client_id']);
                                $nextVisit=$override->get('schedule','client_id',$data['client_id'])?>
                                <tr>
                                    <td><?=$client[0]['study_id']?></td>
                                    <td><?=$client[0]['initials']?></td>
                                    <td><?=$data['visit_code']?></td>
                                    <td><?=$data['visit_date']?></td>
                                    <td>
                                        <a href="#edit_visit<?=$y?>" data-toggle="modal" class="widget-icon" title="Edit Staff Information"><span class="icon-pencil"></span></a>
                                        <a href="#delete_visit<?=$y?>" data-toggle="modal" class="widget-icon" title="Delete Staff"><span class="icon-trash"></span></a>
                                    </td>
                                </tr>
                                <div class="modal" id="edit_visit<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">EDIT VISIT</h4>
                                                </div>
                                                <div class="modal-body clearfix">
                                                    <div class="controls">
                                                        <div class="form-row">
                                                            <div class="col-md-2">STUDY ID:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="study_id" class="form-control" value="<?=$client[0]['study_id']?>" disabled/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">VISIT CODE:</div>
                                                            <div class="col-md-10">
                                                                <input type="number" name="visit_code" class="form-control" value="<?=$data['visit_code']?>" />
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">INITIALS:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="initials" class="form-control" value="<?=$client[0]['initials']?>" disabled/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Date:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="visit_date" class="datepicker form-control" value="<?=$data['visit_date']?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="pull-right col-md-3">
                                                        <input type="hidden" name="id" class="form-control" value="<?=$data['id']?>" required=""/>
                                                        <input type="submit" name="edit_visit" value="SUBMIT" class="btn btn-success btn-clean">
                                                    </div>
                                                    <div class="pull-right col-md-2">
                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modal-danger" id="delete_visit<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS VISIT?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-md-2 pull-right">
                                                        <input type="hidden" name="id" value="<?=$data['id']?>">
                                                        <input type="hidden" name="cl_id" value="<?=$data['client_id']?>">
                                                        <input type="submit" name="delete_visit" value="DELETE" class="btn btn-default btn-clean">
                                                    </div>
                                                    <div class="col-md-2 pull-right">
                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php $y++;}}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 7){?>
                <div class="block">
                    <div class="header">
                        <h2>PATIENT VISIT</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>

                                <th width="20%">STUDY ID</th>
                                <th width="10%">VISIT CODE</th>
                                <th width="25%">LAST VISIT</th>
                                <th width="20%">PHONE NUMBER</th>
                                <th width="20%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x=1;if(isset($_GET['cid'])){foreach ($override->get('schedule','client_id',$_GET['cid']) as $data){
                                $client=$override->get('clients','id',$data['client_id'])?>
                                <tr>
                                    <td><?=$client[0]['study_id']?></td>
                                    <td><?=$client[0]['visit_code']?></td>
                                    <td><?=$data['visit_date']?></td>
                                    <td><?=$client[0]['phone_number']?></td>
                                    <td>
                                        <a href="#appnt<?=$x?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a>
                                    </td>
                                    <div class="modal" id="appnt<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">APPOINTMENT</h4>
                                                    </div>
                                                    <div class="modal-body clearfix">
                                                        <div class="controls">
                                                            <div class="form-row">
                                                                <div class="col-md-2">VISIT CODE:</div>
                                                                <div class="col-md-10">
                                                                    <input type="hidden" name="visit_code" value="<?=$client[0]['visit_code']+1?>">
                                                                    <input type="number" name="visit_code" class="form-control" value="<?=$client[0]['visit_code']+1?>" disabled/>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-2">NEXT VISIT:</div>
                                                                <div class="col-md-10">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                                        <input type="text" name="visit_date" class="datepicker form-control" value=""/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="pull-right col-md-3">
                                                            <input type="hidden" name="id" value="<?=$data['id']?>">
                                                            <input type="submit" name="appointment" value="Submit" class="btn btn-success btn-clean">
                                                        </div>
                                                        <div class="pull-right col-md-2">
                                                            <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                <?php $x++;}}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 8){?>
                <div class="block">
                    <div class="header">
                        <h2>STAFF</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th width="15%">NAME</th>
                                <th width="10%">USERNAME</th>
                                <th width="10%">POSITION</th>
                                <th width="10%">COUNTRY</th>
                                <th width="10%">SITE</th>
                                <th width="10%">PHONE</th>
                                <th width="10%">EMAIL</th>
                                <th width="10%">STATUS</th>
                                <th width="25%">MANAGE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $y=0;$x=1;if($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3){$staffs=$override->get('staff','status',1);}
                            elseif($user->data()->access_level == 4){$staffs=$override->getNews('staff','status',1,'c_id',$user->data()->c_id);}
                            foreach($staffs as $staff){if($user->data()->access_level != 1 || $user->data()->id != $staff['id']){
                                if($user->data()->access_level == 1){$power=1;}else{$power=0;}
                                $site=$override->get('site','id',$staff['s_id']);
                                $country=$override->get('country','id',$staff['c_id']);
                                $position=$override->get('position','id', $staff['position'])[0]?>
                                <tr>
                                    <td><?=$x?></td>
                                    <td><?=$staff['firstname'].' '.$staff['lastname']?></td>
                                    <td><?=$staff['username']?></td>
                                    <td><?=$position['name']?></td>
                                    <td><?=$country[0]['name']?></td>
                                    <td><?=$site[0]['name']?></td>
                                    <td><?=$staff['phone_number']?></td>
                                    <td><?=$staff['email_address']?></td>
                                    <td><div class="btn-group btn-group-xs"> <?php if($staff['token'] || $staff['count']>= 4){?><button class="btn btn-warning">INACTIVE</button> <?php }else{?><button class="btn btn-success">ACTIVE</button><?php }?></div></td></td>
                                    <td>
                                        <?php if($staff['access_level'] != 2 || $power == 1){?>
                                            <a href="#edit_staff<?=$y?>" data-toggle="modal" class="widget-icon" title="Edit Staff Information"><span class="icon-pencil"></span></a>
                                            <a href="#reset_password<?=$y?>" data-toggle="modal" class="widget-icon" title="Reset Password to Default"><span class="icon-refresh"></span></a>
                                            <a href="#delete_staff<?=$y?>" data-toggle="modal" class="widget-icon" title="Delete Staff"><span class="icon-trash"></span></a>
                                        <?php }?>
                                    </td>
                                </tr>
                                <div class="modal" id="edit_staff<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">EDIT STAFF</h4>
                                                </div>
                                                <div class="modal-body clearfix">
                                                    <div class="controls">
                                                        <div class="form-row">
                                                            <div class="col-md-2">First Name:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="firstname" class="form-control" value="<?=$staff['firstname']?>" required=""/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Last Name:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="lastname" class="form-control" value="<?=$staff['lastname']?>" required=""/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Country:</div>
                                                            <div class="col-md-10">
                                                                <select class="form-control" id="c" name="country_id" required="">
                                                                    <option value="<?=$country[0]['id']?>"><?=$country[0]['name']?></option>
                                                                    <?php if($user->data()->access_level == 1 || $user->data()->access_level == 2){
                                                                        $countries=$override->get('country','status',1);
                                                                    }elseif($user->data()->access_level == 4){
                                                                        $countries=$override->getNews('country','id',$user->data()->c_id,'status',1);}
                                                                    foreach($countries as $country){?>
                                                                        <option value="<?=$country['id']?>"><?=$country['name']?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="w" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                                                        <div class="form-row" id="s_i">
                                                            <div class="col-md-2">Site:</div>
                                                            <div class="col-md-10">
                                                                <select class="form-control" id="site_i" name="site_id" required="">
                                                                    <option value="<?=$site[0]['id']?>"><?=$site[0]['name']?></option>
                                                                    <?php foreach($override->get('site','status',1) as $site){?>
                                                                        <option value="<?=$site['id']?>"><?=$site['name']?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Position:</div>
                                                            <div class="col-md-10">
                                                                <select class="form-control" name="position" required="">
                                                                    <!-- you need to properly manage positions -->
                                                                    <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                    <?php foreach($override->getData('position') as $position){if($user->data()->access_level == 1 && $user->data()->power == 1){?>
                                                                        <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                    <?php }elseif($user->data()->access_level == 1 && $position['name'] != 'Principle Investigator'){?>
                                                                        <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                    <?php }elseif($user->data()->access_level == 2 && $position['name'] != 'Coordinator' && $position['name'] != 'Principle Investigator'){?>
                                                                        <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                    <?php }}?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Username:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="username" class="form-control" value="<?=$staff['username']?>" required=""/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Phone:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="phone_number" class="form-control" value="<?=$staff['phone_number']?>" required=""/>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col-md-2">Email:</div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="email_address" class="form-control" value="<?=$staff['email_address']?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="pull-right col-md-3">
                                                        <input type="hidden" name="id" value="<?=$staff['id']?>">
                                                        <input type="submit" name="edit_staff" value="Submit" class="btn btn-success btn-clean">
                                                    </div>
                                                    <div class="pull-right col-md-2">
                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modal-info" id="reset_password<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">YOU SURE YOU WANT TO RESET PASSWORD FOR THIS STAFF ?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-md-2 pull-right">
                                                        <input type="hidden" name="firstname" value="<?=$staff['firstname']?>">
                                                        <input type="hidden" name="email" value="<?=$staff['email_address']?>">
                                                        <input type="hidden" name="id" value="<?=$staff['id']?>">
                                                        <input type="submit" name="reset_password" value="RESET" class="btn btn-default btn-clean">
                                                    </div>
                                                    <div class="col-md-2 pull-right">
                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modal-danger" id="delete_staff<?=$y?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS STAFF ?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-md-2 pull-right">
                                                        <input type="hidden" name="id" value="<?=$staff['id']?>">
                                                        <input type="submit" name="delete_staff" value="DELETE" class="btn btn-default btn-clean">
                                                    </div>
                                                    <div class="col-md-2 pull-right">
                                                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php $x++;}$y++;}?>
                            </tbody>
                        </table>

                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 9){?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="block">
                            <div class="header">
                                <h2>COUNTRIES</h2>
                            </div>
                            <div class="content">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>SHORT CODE</th>
                                        <th>MANAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;foreach($override->get('country','status',1) as $country){?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td><?=$country['name']?></td>
                                            <td><?=$country['short_code']?></td>
                                            <td>
                                                <a href="#edit_country<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                <a href="#delete_country<?=$x?>" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>
                                            </td>
                                        </tr>
                                        <div class="modal" id="edit_country<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT COUNTRY</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Name:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="country_name" class="form-control" value="<?=$country['name']?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Short Code:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="short_code" class="form-control" value="<?=$country['short_code']?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$country['id']?>">
                                                                <input type="submit" name="edit_country" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_country<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS COUNTRY</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?=$country['id']?>">
                                                                <input type="submit" name="delete_country" value="DELETE" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $x++;}?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block">
                            <div class="header">
                                <h2>SITES</h2>
                            </div>
                            <div class="content">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>SHORT CODE</th>
                                        <th>COUNTY</th>
                                        <th>MANAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;foreach($override->get('site','status',1) as $site){$country=$override->get('country','id',$site['c_id'])?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td><?=$site['name']?></td>
                                            <td><?=$site['short_code']?></td>
                                            <td><?=$country[0]['name']?></td>
                                            <td>
                                                <a href="#edit_site<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                <a href="#delete_site<?=$x?>" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>
                                            </td>
                                        </tr>
                                        <div class="modal" id="edit_site<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT SITE</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Name:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="site_name" class="form-control" value="<?=$site['name']?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Short Code:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="short_code" class="form-control" value="<?=$site['short_code']?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Country:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" name="country_id">
                                                                            <option value="<?=$country[0]['id']?>"><?=$country[0]['name']?></option>
                                                                            <?php foreach($override->getData('country') as $country){?>
                                                                                <option value="<?=$country['id']?>"><?=$country['name']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$site['id']?>">
                                                                <input type="submit" name="edit_site" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_site<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS SITE</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?=$site['id']?>">
                                                                <input type="submit" name="delete_site" value="DELETE" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $x++;}?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block">
                            <div class="header">
                                <h2>END OF STUDY REASON</h2>
                            </div>
                            <div class="content">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>REASON</th>
                                        <th>MANAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;foreach($override->getData('end_study_reason') as $site){?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td><?=$site['reason']?></td>
                                            <td>
                                                <a href="#edit_end_reason<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                <a href="#delete_end_reason<?=$x?>" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>
                                            </td>
                                        </tr>
                                        <div class="modal" id="edit_end_reason<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT SITE</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Reason:</div>
                                                                    <div class="col-md-10">
                                                                        <textarea name="reason" rows="4" class="form-control"><?=$site['reason']?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$site['id']?>">
                                                                <input type="submit" name="edit_end_study" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_end_reason<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS REASON</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?=$site['id']?>">
                                                                <input type="submit" name="delete_end_study" value="DELETE" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $x++;}?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block">
                            <div class="header">
                                <h2>PATIENT GROUP</h2>
                            </div>
                            <div class="content">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>MANAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;foreach($override->getData('patient_group') as $group){?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td><?=$group['name']?></td>
                                            <td>
                                                <a href="#edit_patient_group<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
<!--                                                <a href="#delete_patient_group--><?//=$x?><!--" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>-->
                                            </td>
                                        </tr>
                                        <div class="modal" id="edit_patient_group<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT PATIENT GROUP</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Name:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" name="group_name" class="form-control" value="<?=$group['name']?>" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$group['id']?>">
                                                                <input type="submit" name="edit_pt_group" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_patient_group<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS GROUP</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?=$site['id']?>">
                                                                <input type="submit" name="delete_pt_group" value="DELETE" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $x++;}?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-offset-2 col-md-8">
                        <div class="block">
                            <div class="header">
                                <h2>STUDY</h2>
                            </div>
                            <div class="content">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>CODE</th>
                                        <th>DURATION</th>
                                        <th>SAMPLE SIZE</th>
                                        <th>START</th>
                                        <th>END</th>
                                        <th>DETAILS</th>
                                        <th>MANAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;foreach($override->getData('study') as $group){?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td><?=$group['name']?></td>
                                            <td><?=$group['study_code']?></td>
                                            <td><?=$group['duration']?></td>
                                            <td><?=$group['sample_size']?></td>
                                            <td><?=$group['start_date']?></td>
                                            <td><?=$group['end_date']?></td>
                                            <td><?=$group['details']?></td>
                                            <td>
                                                <a href="#edit_study<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                <!--                                                <a href="#delete_patient_group--><?//=$x?><!--" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>-->
                                            </td>
                                        </tr>
                                        <div class="modal" id="edit_study<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">EDIT STUDY</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-3">STUDY NAME:</div>
                                                                    <div class="col-md-8">
                                                                        <input type="text" name="name" class="form-control" value="<?=$group['name']?>" required=""/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-3">STUDY CODE:</div>
                                                                    <div class="col-md-8">
                                                                        <input type="text" name="study_code" class="form-control" value="<?=$group['study_code']?>" required=""/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-3">STUDY DURATION:</div>
                                                                    <div class="col-md-8">
                                                                        <input type="number" name="duration" class="form-control" value="<?=$group['duration']?>" required=""/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-3">SAMPLE SIZE:</div>
                                                                    <div class="col-md-8">
                                                                        <input type="number" name="sample_size" class="form-control" value="<?=$group['sample_size']?>" required=""/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-3">START DATE:</div>
                                                                    <div class="col-md-8">
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                                            <input type="text" name="start_date" class="datepicker form-control" value="<?=$group['start_date']?>"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-3">END DATE:</div>
                                                                    <div class="col-md-8">
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                                            <input type="text" name="end_date" class="datepicker form-control" value="<?=$group['end_date']?>"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-3">Details:</div>
                                                                    <div class="col-md-8">
                                                                        <textarea name="details" class="form-control" rows="4"><?=$group['details']?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" class="form-control" value="<?=$group['id']?>"/>
                                                                <input type="submit" name="edit_study" value="ADD" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_delete<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS GROUP</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?=$site['id']?>">
                                                                <input type="submit" name="delete_pt_group" value="DELETE" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $x++;}?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block">
                            <div class="header">
                                <h2>IMAGES</h2>
                            </div>
                            <div class="content">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CATEGORY</th>
                                        <th>LOCATION</th>
                                        <th>MANAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;foreach($override->getData('images') as $group){?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td><?php if($group['cat']==1){echo'Favicon';}elseif ($group['cat']==2){echo 'Logo';}elseif ($group['cat']==3){echo 'Image';}?></td>
                                            <td><?=$group['location']?></td>
                                            <td>
                                                <a href="#edit_image<?=$x?>" data-toggle="modal" class="widget-icon" title="Edit Site Information"><span class="icon-pencil"></span></a>
                                                <!--                                                <a href="#delete_patient_group--><?//=$x?><!--" data-toggle="modal" class="widget-icon" title="Delete Site"><span class="icon-trash"></span></a>-->
                                            </td>
                                        </tr>
                                        <div class="modal" id="edit_image<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form enctype="multipart/form-data" method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">ADD IMAGES</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Image:</div>
                                                                    <div class="col-md-10">
                                                                        <div class="input-group file">
                                                                            <input type="text" class="form-control" value=""/>
                                                                            <input type="file" name="image" required/>
                                                                            <span class="input-group-btn">
                                                                                <button class="btn" type="button">Browse</button>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Project:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" name="project" required="">
                                                                            <option value="">Select Project</option>
                                                                            <?php foreach($override->getData('study') as $study){?>
                                                                                <option value="<?=$study['id']?>"><?=$study['name']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col-md-2">Image Purpose:</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" name="image_cat" required="">
                                                                            <option value="">Select Purpose</option>
                                                                            <option value="1">Favicon</option>
                                                                            <option value="2">Logo</option>
                                                                            <option value="3">Image</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$group['id']?>">
                                                                <input type="submit" name="edit_image" value="ADD" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal modal-danger" id="delete_image<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">YOU SURE YOU WANT TO DELETE THIS GROUP</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="id" value="<?=$site['id']?>">
                                                                <input type="submit" name="delete_pt_group" value="DELETE" class="btn btn-default btn-clean">
                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $x++;}?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 10){?>
                <div class="block">
                    <div class="header">
                        <h2>END OF STUDY CLIENTS</h2>
                    </div>
                    <div class="content">

                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">STUDY ID</th>
                                <th width="20%">GROUP</th>
                                <th width="5%">STATUS</th>
                                <th width="20%">Reason</th>
                                <th width="50%">DETAILS</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x=1;foreach ($override->getDataOrderByA('clients','status',0,'study_id') as $data){
                                $lastVisit=$override->getlastRow('visit','client_id',$data['id'],'id');
                                if($lastVisit){$lVisit = $lastVisit[0]['visit_date'];}else{$lVisit='';}?>
                                <tr>
                                    <td><?=$x?></td>
                                    <td><?=$data['study_id'].' ( '?><?=$data['phone_number'].' ) '?></td>
                                    <td><?=$override->get('patient_group','id',$data['pt_group'])[0]['name'].' / '.$override->get('study','id',$data['project_id'])[0]['study_code']?></td>
                                    <td><div class="btn-group btn-group-xs"><button class="btn btn-danger">End Study</button></div></td>
                                    <td>( <?=$data['reason']?> )</td>
                                    <td><?=$data['details']?></td>
                                </tr>

                                <?php $x++;}?>
                            </tbody>
                        </table>

                    </div>
                </div>
            <?php }
            elseif ($_GET['id'] == 11){?>
                <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th width="3%">Study ID</th>
                        <?php $x=1;foreach ($override->getDataOrderByA('visit','client_id',$_GET['pid'],'visit_date') as $data){?>
                            <th width="3%"><?=$data['visit_date']?></th>
                            <?php $x++;}?>
                    </tr>

                    </thead>
                    <tbody>
                    <tr>
                        <td style="font-weight: bold"><?=$override->get('clients','id',$_GET['pid'])[0]['study_id']?></td>
                        <?php $x=1;foreach ($override->getDataOrderByA('visit','client_id',$_GET['pid'],'visit_date') as $data){?>
                            <td>
                                <div class="btn-group btn-group-xs"><?php if($data['status']==1){?>&nbsp;
                                        <button class="btn btn-success"><span class="icon-ok-sign"></span> Done</button>
                                    <?php }elseif($data['status']==2){?>
                                        <button class="btn btn-danger"><span class="icon-remove-sign"></span> Missed</span></button>
                                    <?php }elseif ($data['status']==0){?>
                                        <button class="btn btn-info"><span class="icon-dashboard"></span> Scheduled</button>
                                    <?php }?>
                            </td>
                            <?php $x++;}?>
                    </tr>
                    </tbody>
                </table>
            <?php }
            elseif ($_GET['id'] == 12){
                $override->dateRange('visit','visit_date',$_GET['from'],$_GET['to']);$y=0;$list= array();
                while($y<=$user->dateDiff($_GET['to'],$_GET['from'])){$list[$y]=date('Y-m-d', strtotime($_GET['from']. ' + '.$y.' days'));$y++;}?>
                <form method="post">
                    <input type="submit" name="download" value="Download Data" class="btn btn-info">
                </form>
                <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th width="3%">Study ID (Group Name)</th>
                        <?php $x=1;foreach ($list as $data){?>
                            <th width="3%"><?=$data?></th>
                            <?php $x++;}?>
                    </tr>

                    </thead>
                    <tbody>
                    <?php foreach ($override->dateRangeD('visit','client_id','visit_date',$_GET['from'],$_GET['to']) as $dt){
                        if($dt['status'] != 4){
                        $client=$override->get('clients', 'id', $dt['client_id'])[0];?>
                        <tr>
                            <td><?=$client['study_id'].' ( '.$override->get('patient_group','id',$client['pt_group'])[0]['name'].' ) '?>
                                <?php if($client['status'] == 0){?>
                                    <div class="btn-group btn-group-xs">
                                        <button class="btn btn-danger"><span class="icon-ok-sign"></span> End Study </button>
                                    </div>
                                <?php }}?>
                            </td>
                            <?php $x=1;foreach ($list as $data){
                                $d=$override->getNews('visit','client_id',$dt['client_id'],'visit_date',$data)[0];
//                                echo ' => ';print_r($dt['client_id']);print_r($d['status']);echo ' : ';print_r($d['visit_date']);echo ' , '
//                                print_r($data['client_id']);echo ' : ';print_r($data['visit_date']);echo ' , '?>
                                <td>
                                    <div class="btn-group btn-group-xs"><?php if($d){if($d['status']==1){?>&nbsp;
                                            <button class="btn btn-success"><span class="icon-ok-sign"></span> Done <?=$d['visit_code'].' '.$d['visit_type']?></button>
                                        <?php }elseif($d['status']==2){?>
                                            <button class="btn btn-danger"><span class="icon-remove-sign"></span> Missed <?=$d['visit_code'].' '.$d['visit_type']?></span></button>
                                        <?php }elseif ($d['status']==0){?>
                                            <button class="btn btn-info"><span class="icon-dashboard"></span> Scheduled <?=$d['visit_code'].' '.$d['visit_type']?></button>
                                        <?php }}else{?>
                                            -
                                        <?php }?>
                                    </div>
                                </td>
                                <?php $x++;}?>
                        </tr>

                    <?php }?>
                    </tbody>
                </table>
            <?php }elseif ($_GET['id'] == 13){?>
                <div class="block">
                    <div class="header">
                        <h2>TODAY PENDING VISITS</h2>
                    </div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                            <tr>

                                <th width="8%">STUDY ID</th>
                                <th width="15%">GROUP NAME / STUDY NAME</th>
                                <th width="10%">VISIT CODE</th>
                                <th width="10%">VISIT STATUS</th>
                                <th width="25%">DATA STATUS</th>
                                <th width="10%">PHONE NUMBER</th>
                                <th width="20%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($user->data()->position == 1){$a_status='dm_status';}
                            elseif ($user->data()->position == 6){$a_status='sn_cl_status';}
                            elseif ($user->data()->position == 12){$a_status='dc_status';}
                            elseif ($user->data()->position == 5){$a_status='sn_cl_status';}
                            $x=1;foreach ($override->getDataNot('visit','status', 0,$a_status,0) as $data){
                                $client=$override->get('clients','id',$data['client_id'])[0];
                                $lastVisit= $override->getlastRow('visit','client_id',$data['client_id'],'visit_date');
                                if($client['status'] == 1){?>
                                    <tr>
                                    <td><?=$client['study_id']; ?></td>
                                        <td><?=$override->get('patient_group','id',$client['pt_group'])[0]['name'].' / '.$override->get('study','id',$client['project_id'])[0]['study_code']; ?></td>
                                        <td><?=$data['visit_code'].' ( '.$data['visit_type'].' ) '?></td>
                                        <td>
                                        <div class="btn-group btn-group-xs">
                                                <?php if($data['status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Visit Pending</button>
                                                <?php }elseif($data['status']==1){?>
                                                    <button class="btn btn-success">Visit Completed</button>
                                                <?php }elseif($data['status']==2){?>
                                                    <button class="btn btn-danger">Visit Missed</button>
                                                <?php }?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['sn_cl_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Clinician Pending</button>
                                                <?php }elseif($data['sn_cl_status']==1){?>
                                                    <button class="btn btn-success">Clinician Completed</button>
                                                <?php }elseif($data['sn_cl_status']==2){?>
                                                    <button class="btn btn-danger">Clinician Missed</button>
                                                <?php }?>
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['dc_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Data Pending</button>
                                                <?php }elseif($data['dc_status']==1){?>
                                                    <button class="btn btn-success">Data Completed</button>
                                                <?php }elseif($data['dc_status']==2){?>
                                                    <button class="btn btn-danger">Data Missed</button>
                                                <?php }?>
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <?php if($data['dm_status']==0){?>&nbsp;
                                                    <button class="btn btn-warning">Review  Pending</button>
                                                <?php }elseif($data['dm_status']==1){?>
                                                    <button class="btn btn-success">Review Completed</button>
                                                <?php }elseif($data['dm_status']==2){?>
                                                    <button class="btn btn-danger">Review Missed</button>
                                                <?php }?>
                                            </div>
                                        </td>
                                        <td><?=$client['phone_number']?></td>
                                        <td>
                                            <a href="#appnt<?=$x?>" data-toggle="modal" class="widget-icon" title="Add Visit"><span class="icon-share"></span></a>
                                        </td>
                                        <div class="modal" id="appnt<?=$x?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">APPOINTMENT</h4>
                                                        </div>
                                                        <div class="modal-body clearfix">
                                                            <div class="controls">
                                                                <div class="form-row">
                                                                    <div class="col-md-2">VISIT CODE:</div>
                                                                    <div class="col-md-10">
                                                                        <input type="hidden" name="visit_code" value="<?=$client['visit_code']+1?>">
                                                                        <input type="text" name="visit_code" class="form-control" value="<?=$data['visit_code'].'('.$data['visit_type'].')'?>" disabled/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row" id="st">
                                                                    <div class="col-md-2">Status</div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control" id="site" name="visit_status" required>
                                                                            <option value="">Select Status</option>
                                                                            <option value="1">Complete</option>
                                                                            <option value="2">Missing</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="pull-right col-md-3">
                                                                <input type="hidden" name="id" value="<?=$lastVisit[0]['id']?>">
                                                                <input type="hidden" name="v_id" value="<?=$data['id']?>">
                                                                <input type="hidden" name="client_id" value="<?=$client['id']?>">
                                                                <input type="hidden" name="sn" value="<?=$data['sn_cl_status']?>">
                                                                <input type="submit" name="appointment" value="Submit" class="btn btn-success btn-clean">
                                                            </div>
                                                            <div class="pull-right col-md-2">
                                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php }$x++;}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

</body>

</html>