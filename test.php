<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$pageError = null;$successMessage = null;$errorM = false;$errorMessage = null;
$t_crf=0;$p_crf=0;$w_crf=0;$s_name=null;$c_name=null;$site=null;$country=null;
$study_crf=null;$data_limit=10000;

//modification remove all pilot crf have been removed/deleted from study crf
if($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_visit')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'study_id' => array(
                    'required' => true,
                ),
                'last_visit' => array(
                    'required' => true,
                ),
                'nxt_visit' => array(
                    'required' => true,
                ),
                'visit_code' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $date=date('Y-m-d',strtotime(Input::get('last_visit')));
                    $user->createRecord('visit', array(
                        'visit_code' => Input::get('visit_code'),
                        'visit_date' => $date,
                        'client_id' => Input::get('study_id'),
                        'staff_id'=>$user->data()->id
                    ));
                    $date=null;
                    $checkClient=$override->get('schedule','client_id',Input::get('study_id'));
                    $date=date('Y-m-d',strtotime(Input::get('nxt_visit')));
                    if($checkClient){
                        $user->updateRecord('schedule',array('visit_date'=>$date,'client_id'=>Input::get('study_id')),$checkClient[0]['id']);
                    }else{
                        $user->createRecord('schedule', array(
                            'visit_date' => $date,
                            'client_id' => Input::get('study_id'),
                        ));
                    }
                    $date=null;
                    $getVisit=$override->get('clients','id',Input::get('study_id'));
                    $visitCode = $getVisit[0]['visit_code'] + 1;
                    if($visitCode){
                        $user->updateRecord('clients',array('visit_code'=>Input::get('visit_code')),Input::get('study_id'));
                    }
                    $successMessage = 'Visit Added Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
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

            <div class="block">
                <div class="header">
                    <h2>ADD VISITS</h2>
                </div>
                <div class="content">
                    <div class="col-md-offset-2 col-md-8">
                        <form method="post">
                            <div class="modal-body clearfix">
                                <div class="controls">
                                    <div class="form-row">
                                        <div class="col-md-2">STUDY ID:</div>
                                        <div class="col-md-10">
                                            <select name="study_id" id="study" class="select2" style="width: 100%;" tabindex="-1">
                                                <option value="">Select study ID</option>
                                                <?php foreach ($override->getData('clients') as $client){?>
                                                    <option value="<?=$client['id']?>"><?=$client['study_id']?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="waitS1" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                                    <div class="form-row" id="s1">
                                        <div class="col-md-2">VISIT CODE:</div>
                                        <div class="col-md-10" id="v_code">
                                            <input type="hidden" name="visit_code" class="form-control" value="0" required=""/>
                                            <input type="number" name="visit_code" class="form-control" value="0" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-2">VISIT DATE:</div>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                <input type="text" name="last_visit" class="datepicker form-control" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-2">NEXT VISIT:</div>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                <input type="text" name="nxt_visit" class="datepicker form-control" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="pull-right col-md-3">
                                    <input type="submit" name="add_visit" value="ADD" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<script>
    $(document).ready(function(){
        $('#study').change(function(){
            var studyID = $(this).val();
            $('#s1').hide();
            $('#waitS1').show();
            $.ajax({
                url:"process.php?content=visit",
                method:"GET",
                data:{studyID:studyID},
                dataType:"text",
                success:function(data){
                    $('#v_code').html(data);
                    $('#s1').show();
                    $('#waitS1').hide();
                }
            });
        });

        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    });
</script>
</html>