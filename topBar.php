<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$countries=null;$checkError=false;$date=null;
if($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_client')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'study_id' => array(
                    'required' => true,
                    'unique' => 'clients',
                    'min' => 6,
                ),
                'initials' => array(
                    'required' => true,
                    'max' => 3,
                ),
                'phone_number' => array(
                    'unique' => 'clients'
                ),
                'screening_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $s_date=date('Y-m-d',strtotime(Input::get('screening_date')));
                try {
                    $user->createRecord('clients', array(
                        'study_id' => Input::get('study_id'),
                        'visit_code' => Input::get('visit_code'),
                        'status' =>1,
                        'initials' => Input::get('initials'),
                        'phone_number' => Input::get('phone_number'),
                        'phone_number2' => Input::get('phone_number2'),
                        'screening_date' => $s_date,
                        'pt_group' => Input::get('group'),
                        'reason' => '',
                        'details'=> '',
                        'visit_cat'=> 0,
                        'staff_id'=>$user->data()->id
                    ));
                    $client = $override->get('clients','study_id',Input::get('study_id'))[0];
                    $checkClient=$override->get('schedule','client_id',$client['id']);
                    $nxt_visit=date('Y-m-d',strtotime($s_date.' + 1 days'));

                    if($checkClient){
                        $user->updateRecord('schedule',array('visit_date'=>$nxt_visit),$client['id']);
                    } else{
                        $user->createRecord('schedule', array(
                            'visit_date' => $nxt_visit,
                            'client_id' => $client['id'],
                        ));
                    }

                   $successMessage = 'Client Added Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        /*elseif (Input::get('add_visit')){
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
                        $user->updateRecord('schedule',array('visit_date'=>$date),Input::get('study_id'));
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
                        $user->updateRecord('clients',array('visit_code'=>$visitCode),Input::get('study_id'));
                    }
                    $successMessage = 'Visit Added Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }*/
        elseif (Input::get('add_staff')) {
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
                    'unique' => 'staff'
                ),
                'phone_number' => array(
                    'required' => true,
                    'unique' => 'staff'
                ),
                'email_address' => array(
                    'required' => true,
                    'unique' => 'staff'
                ),
            ));
            if ($validate->passed()) {
                $salt = $random->get_rand_alphanumeric(32);
                $password = '123456';
                switch (Input::get('position')) {
                    case 'Principle Investigator':
                        $accessLevel = 1;
                        break;
                    case 'Coordinator':
                        $accessLevel = 2;
                        break;
                    case 'Data Manager':
                        $accessLevel = 3;
                        break;
                    case 'Country Coordinator':
                        $accessLevel = 4;
                        break;
                    case 'Country PI':
                        $accessLevel = 4;
                        break;
                    case 'Country Data Manager':
                        $accessLevel = 5;
                        break;
                    case 'Data Clark':
                        $accessLevel = 6;
                        break;
                }
                try {
                    $user->createRecord('staff', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'username' => Input::get('username'),
                        'password' => Hash::make($password,$salt),
                        'salt' => $salt,
                        'reg_date' => date('Y-m-d'),
                        'access_level' => $accessLevel,
                        'phone_number' => Input::get('phone_number'),
                        'email_address' => Input::get('email_address'),
                        'c_id' => Input::get('country_id'),
                        's_id' => Input::get('site_id'),
                        'status' => 1,
                        'pswd' => 0,
                        'last_login'=>'',
                        'picture'=>'',
                        'token' =>'',
                        'power'=>0,
                        'count'=>0,
                        'staff_id'=>$user->data()->id
                    ));
                    $email->sendEmail(Input::get('email_address'),Input::get('firstname'),Input::get('username'),$password, 'Account Creation');
                    $successMessage = 'Staff Registered Successful' ;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_country')) {
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
                    $user->createRecord('country', array(
                        'name' => Input::get('country_name'),
                        'short_code' => Input::get('short_code'),
                        'status' => 1
                    ));
                    $successMessage = 'Country Registered Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_site')) {
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
                ),

            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('site', array(
                        'name' => Input::get('site_name'),
                        'short_code' => Input::get('short_code'),
                        'c_id' => Input::get('country_id'),
                        'status' => 1
                    ));
                    $successMessage = 'Site Registered Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('search_schedule')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'from_date' => array(
                    'required' => true,
                ),
                'to_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $link='info.php?id=12&from='.$date=date('Y-m-d',strtotime(Input::get('from_date'))).'&to='.$date=date('Y-m-d',strtotime(Input::get('to_date')));
                    Redirect::to($link);

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
<nav class="navbar brb" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-reorder"></span>
        </button>
        <a class="navbar-brand" href="index.php"><img src="img/nimrLogo.png" class="img-thumbnail img-circle"/></a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="index.php">
                    <span class="icon-home"></span> dashboard
                </a>
            </li>
            <li class="">
                <a href="#add_client" data-toggle="modal" data-backdrop="static" data-keyboard="false" ><span class="icon-plus-sign"></span> Add New Client</a>
            </li>
            <li class="">
                <!--<a href="#add_visit" data-toggle="modal" data-backdrop="static" data-keyboard="false" ><span class="icon-bookmark"></span> Add Visit</a>-->
                <a href="add.php" ><span class="icon-bookmark"></span> Add Visit</a>
            </li>
            <li class="">
                <a href="#searchSchedule" data-toggle="modal"><span class="icon-search"></span> Search Schedule</a>
            </li>
            <li class="">
                <a href="profile.php">
                    <span class="icon-user"></span> Profile
                </a>
            </li>
            <?php if($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3){?>
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-group"></span> STAFF</a>
                    <ul class="dropdown-menu">
                        <li><a href="#add_staff" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD STAFF</a></li>
                        <li><a href="info.php?id=8">MANAGE STAFF</a></li>
                    </ul>
                </li>
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-gear"></span> MANAGEMENT</a>
                    <ul class="dropdown-menu">
                        <li><a href="#add_country" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD COUNTRY</a></li>
                        <li><a href="#add_site" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD SITE</a></li>
                        <li><a href="info.php?id=9">MANAGE SITE / COUNTRIES</a></li>
                    </ul>
                </li>
            <?php }elseif($user->data()->access_level == 4){?>
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-group"></span> STAFF</a>
                    <ul class="dropdown-menu">
                        <li><a href="#add_staff" data-toggle="modal" data-backdrop="static" data-keyboard="false">ADD STAFF</a></li>
                        <li><a href="info.php?id=1">MANAGE STAFF</a></li>
                    </ul>
                </li>
            <?php }?>
        </ul>
        <form class="navbar-form navbar-right" role="search">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="search..."/>
            </div>
        </form>
    </div>
</nav>
<div class="modal" id="add_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD NEW CLIENT</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">STUDY ID:</div>
                            <div class="col-md-10">
                                <input type="text" name="study_id" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">VISIT CODE:</div>
                            <div class="col-md-10">
                                <input type="number" name="visit_code" class="form-control" value="0" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">INITIALS:</div>
                            <div class="col-md-10">
                                <input type="text" name="initials" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Phone:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number" class="form-control" value=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Phone2:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number2" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Screening Date:</div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                    <input type="text" name="screening_date" class="datepicker form-control" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="form-row" id="st">
                            <div class="col-md-2">Group:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="site" name="site_id" required="">
                                    <option value="">Select Group</option>
                                    <option value="1">Group 1</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_client" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="add_visit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD NEW VISIT</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">STUDY ID:</div>
                            <div class="col-md-10">
                                <select name="study_id" id="study_id" class="select2" style="width: 100%;" tabindex="-1">
                                    <option value="">Select study ID</option>
                                    <?php foreach ($override->getData('clients') as $client){?>
                                        <option value="<?=$client['id']?>"><?=$client['study_id']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div id="waitS" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                        <div class="form-row" id="s">
                            <div class="col-md-2">VISIT CODE:</div>
                            <div class="col-md-10" id="visit_code">
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
                        <input type="submit" name="add_visit" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="add_staff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">NEW STAFF</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">First Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="firstname" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Last Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="lastname" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Country:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="country" name="country_id" required="">
                                    <option value="">Select Country</option>
                                    <?php if($user->data()->access_level == 1 || $user->data()->access_level == 2 || $user->data()->access_level == 3){
                                        $countries=$override->get('country','status',1);
                                    }elseif($user->data()->access_level == 4){
                                        $countries=$override->getNews('country','id',$user->data()->c_id,'status',1);}
                                    foreach($countries as $country){?>
                                        <option value="<?=$country['id']?>"><?=$country['name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div id="waitSty" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/spinner-mini.gif' width="12" height="12" /><br>Loading..</div>
                        <div class="form-row" id="st">
                            <div class="col-md-2">Site:</div>
                            <div class="col-md-10">
                                <select class="form-control" id="site" name="site_id" required="">
                                    <option value="">Select Site</option>
                                    <?php foreach ($override->getData('site') as $site){?>
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
                                    <option value="">Select Position</option>
                                    <?php foreach($override->getData('position') as $position){if($user->data()->access_level == 1 && $user->data()->power == 1){?>
                                        <option value="<?=$position['name']?>"><?=$position['name']?></option>
                                    <?php }elseif($user->data()->access_level == 1 && $position['name'] != 'Principle Investigator'){?>
                                        <option value="<?=$position['name']?>"><?=$position['name']?></option>
                                    <?php }elseif($user->data()->access_level == 2 || $user->data()->access_level == 3 && $position['name'] != 'Coordinator' && $position['name'] != 'Principle Investigator'){?>
                                        <option value="<?=$position['name']?>"><?=$position['name']?></option>
                                    <?php }elseif ($user->data()->access_level == 4 && $position['name'] != 'Coordinator' && $position['name'] != 'Principle Investigator' && $position['name'] !='Data Manager' /*&& $position['name'] !='Country Coordinator'*/ ){?>
                                        <option value="<?=$position['name']?>"><?=$position['name']?></option>
                                    <?php }}?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Username:</div>
                            <div class="col-md-10">
                                <input type="text" name="username" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Phone:</div>
                            <div class="col-md-10">
                                <input type="text" name="phone_number" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Email:</div>
                            <div class="col-md-10">
                                <input type="text" name="email_address" class="form-control" value="" required=""/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_staff" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="add_country" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD COUNTRY</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="country_name" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_country" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="add_site" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">NEW SITE</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">Name:</div>
                            <div class="col-md-10">
                                <input type="text" name="site_name" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Short Code:</div>
                            <div class="col-md-10">
                                <input type="text" name="short_code" class="form-control" value="" required=""/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Country:</div>
                            <div class="col-md-10">
                                <select class="form-control" name="country_id" required="">
                                    <option value="">Select Country</option>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="add_site" value="ADD" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="searchSchedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">SEARCH SCHEDULE</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-md-2">From:</div>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                <input type="text" name="from_date" class="datepicker form-control" value="" required/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">To:</div>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                <input type="text" name="to_date" class="datepicker form-control" value="" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right col-md-3">
                        <input type="submit" name="search_schedule" value="Search" class="btn btn-success btn-clean">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#study_id').change(function(){
            var studyID = $(this).val();
            $('#s').hide();
            $('#waitS').show();
            $.ajax({
                url:"process.php?content=visit",
                method:"GET",
                data:{studyID:studyID},
                dataType:"text",
                success:function(data){
                    $('#visit_code').html(data);
                    $('#s').show();
                    $('#waitS').hide();
                }
            });
        });
        $('#country').change(function(){
            var site = $(this).val();
            $('#st').hide();
            $('#waitSty').show();
            $.ajax({
                url:"process.php?content=site",
                method:"GET",
                data:{site:site},
                dataType:"text",
                success:function(data){
                    $('#site').html(data);
                    $('#st').show();
                    $('#waitSty').hide();
                }
            });
        });
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    });
</script>