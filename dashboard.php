<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$user->scheduleUpdate();
$user->schedule();
$pageError = null;$successMessage = null;$errorM = false;$errorMessage = null;
$t_crf=0;$p_crf=0;$w_crf=0;$s_name=null;$c_name=null;$site=null;$country=null;
$study_crf=null;$data_limit=10000;
//modification remove all pilot crf have been removed/deleted from study crf
if($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('appointment')) {
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
        elseif (Input::get('search')){
            $link='info.php?id=7&cid='.Input::get('study_id');
            Redirect::to($link);
        }
    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title> FTS </title>

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
        <div class="col-md-2">
            <?php require 'sideBar.php'?>
        </div>
        <div class="col-md-10">
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
                <div class="head bg-dot20">
                    <form method="post">
                        <div class="modal-body clearfix">
                            <div class="controls">
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <select name="study_id" id="study_id" class="select2" style="width: 100%;" tabindex="-1">
                                            <option value="">Enter Study ID</option>
                                            <?php foreach ($override->getData('clients') as $client){?>
                                                <option value="<?=$client['id']?>"><?=$client['study_id']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" name="search" value="Search" class="btn btn-success btn-clean">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="block">
                <div class="header">
                    <h2>TODAY SCHEDULE VISITS </h2>
                </div>
                <div class="content">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                        <thead>
                        <tr>

                            <th width="20%">STUDY ID</th>
                            <th width="10%">VISIT CODE</th>
                            <th width="25%">STATUS</th>
                            <th width="20%">PHONE NUMBER</th>
                            <th width="20%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $x=1;foreach ($override->get('visit','visit_date',date('Y-m-d')) as $data){
                            $client=$override->get('clients','id',$data['client_id'])[0];
                            $lastVisit= $override->getlastRow('visit','client_id',$data['client_id'],'visit_date');
                            if($client['status'] == 1){?>
                            <tr>
                                <td><?=$client['study_id'].' ( '.$override->get('patient_group','id',$client['pt_group'])[0]['name'].' / '.$override->get('study','id',$client['project_id'])[0]['study_code'].' ) '?></td>
                                <td><?=$data['visit_code'].' ( '.$data['visit_type'].' ) '?></td>
                                <td>
                                    <div class="btn-group btn-group-xs">
                                        <?php if($data['sn_cl_status']==0){?>&nbsp;
                                            <button class="btn btn-warning">SN|CL:Pending</button>
                                        <?php }elseif($data['sn_cl_status']==1){?>
                                            <button class="btn btn-success">SN|CL:Completed</button>
                                        <?php }elseif($data['sn_cl_status']==2){?>
                                            <button class="btn btn-danger">SN|CL:Missed</button>
                                        <?php }?>
                                    </div>
                                    <div class="btn-group btn-group-xs">
                                        <?php if($data['dc_status']==0){?>&nbsp;
                                            <button class="btn btn-warning">DC:Pending</button>
                                        <?php }elseif($data['dc_status']==1){?>
                                            <button class="btn btn-success">DC:Completed</button>
                                        <?php }elseif($data['dc_status']==2){?>
                                            <button class="btn btn-danger">DC:Missed</button>
                                        <?php }?>
                                    </div>
                                    <div class="btn-group btn-group-xs">
                                        <?php if($data['dm_status']==0){?>&nbsp;
                                            <button class="btn btn-warning">DM:Pending</button>
                                        <?php }elseif($data['dm_status']==1){?>
                                            <button class="btn btn-success">DM:Completed</button>
                                        <?php }elseif($data['dm_status']==2){?>
                                            <button class="btn btn-danger">DM:Missed</button>
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
                                                                <input type="text" name="visit_code" class="form-control" value="<?=$data['visit_code'].' ( '.$data['visit_type'].' ) '?>" disabled/>
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