<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$site=$override->get('site','id',$user->data()->s_id);
$country=$override->get('country','id',$user->data()->c_id);
$clntNo=$override->getNo('clients');
$ap=$override->countNoRepeatAll('visit','client_id');
$end=$override->getCount('clients','status',0);
$tv=$override->getCount('visit','visit_date',date('Y-m-d'));
if($user->data()->position == 1){$a_status='dm_status';}
elseif ($user->data()->position == 6){$a_status='sn_cl_status';}
elseif ($user->data()->position == 12){$a_status='dc_status';}
elseif ($user->data()->position == 5){$a_status='sn_cl_status';}
$pnd=$override->countDataNot('visit','status', 0, $a_status,0);
//$msAp=0;$apnt=0;
$msNo=0;foreach ($override->getData('schedule') as $data){
    if($data['visit_date'] < date('Y-m-d')){
        $msNo++;
    }
}
$msa=0;$clnt=null;
foreach ($override->get('visit','status',2) as $sch){
    $clnt=$override->get('clients','id',$sch['client_id']);
    if($clnt[0]['status'] == 1){
        if($sch['visit_date'] < date('Y-m-d')){
            $msa++;
        }
    }
}
$tdy=0;$clnt=null;
foreach ($override->getDataOrderByAs('schedule','visit_date') as $sch){
    $clnt=$override->get('clients','id',$sch['client_id']);
    if($clnt[0]['status'] == 1){
        if($sch['visit_date'] == date('Y-m-d')){
            $tdy++;
        }
    }
}
if($clntNo){
    $msAp=($msNo/$clntNo)*100;
    $apnt=($ap/$clntNo)*100;
}
?>
<div class="block block-drop-shadow">
    <div class="user bg-default bg-light-rtl">
        <div class="info">
            <a href="#" class="informer informer-three">
                <span><?=$country[0]['short_code']?> / <?=$site[0]['short_code']?></span>
                <?=$override->get('position','id',$user->data()->position)[0]['name']?>
            </a>
            <a href="#" class="informer informer-four">
                <span><?=$user->data()->firstname?></span>
                <?=$user->data()->lastname?>
            </a>
            <?php if($user->data()->picture){?>
                <img src="<?=$user->data()->picture?>" class="img-thumbnail img-circle" width="90" height="90"/>
            <?php }else{?>
                <img src="assets/users/blank.png" class="img-thumbnail img-circle"/>
            <?php }?>
        </div>
    </div>
    <div class="content list-group list-group-icons">
        <a href="info.php?id=1" class="list-group-item"><span class="icon-text-height"></span>Today Schedule Visits<i class="icon-angle-right pull-right"></i><span class="label label-success pull-right"><?=$tv?></span></a>
        <a href="info.php?id=3" class="list-group-item"><span class="icon-book"></span>All Schedule Visits<i class="icon-angle-right pull-right"></i><span class="label label-success pull-right"><?=$override->getNo('visit')?></span></a>
        <a href="info.php?id=2" class="list-group-item"><span class="icon-calendar-empty"></span>Missed Visit<i class="icon-angle-right pull-right"></i><span class="label label-warning pull-right"><?=$msa?></span></a>
        <a href="info.php?id=10" class="list-group-item"><span class="icon-warning-sign"></span>End of Study<i class="icon-angle-right pull-right"></i><span class="label label-danger pull-right"><?=$end?></span></a>
        <a href="info.php?id=4" class="list-group-item"><span class="icon-calendar"></span>All Patient Visits<i class="icon-angle-right pull-right"></i></a>
        <a href="info.php?id=13" class="list-group-item"><span class="icon-windows"></span>Pending Verification<i class="icon-angle-right pull-right"></i><span class="label label-warning pull-right"><?=$pnd?></span></a>
        <a href="info.php?id=5" class="list-group-item"><span class="icon-user"></span>Patients<i class="icon-angle-right pull-right"></i><span class="label label-info pull-right"><?=$clntNo?></span></a>
        <a href="logout.php" class="list-group-item"><span class="icon-off"></span>Logout<i class="icon-angle-right pull-right"></i></a>
    </div>
</div>
<div class="block block-drop-shadow">
    <div class="head bg-dot20">
        <h2>Total Visits</h2>
        <div class="side pull-right">
            <ul class="buttons">
                <li><a href="#"><span class="icon-cogs"></span></a></li>
            </ul>
        </div>
        <div class="head-subtitle">Total number of all follow up</div>
        <div class="head-panel tac" style="line-height: 0px;">
            <div class="knob">
                <input type="text" data-fgColor="#3F97FE" data-min="0" data-max="2000" data-width="100" data-height="100" value="<?=$override->getNo('visit')?>" data-readOnly="true"/>
            </div>
        </div>
    </div>

</div>
<div class="block block-drop-shadow">
    <div class="head bg-dot20">
        <h2>STATUS</h2>
        <div class="side pull-right">
            <ul class="buttons">
                <li><a href="#"><span class="icon-cogs"></span></a></li>
            </ul>
        </div>
        <div class="head-subtitle">Missed vs Follow up</div>
        <div class="head-panel">
            <div class="hp-info hp-simple pull-left hp-inline">
                <span class="hp-main">Missed Appointment <span class="icon-angle-right"></span> <?=number_format((float)$msAp, 2, '.', '')?>%</span>
                <div class="hp-sm">
                    <div class="progress progress-small">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="width: <?=number_format((float)$msAp, 2, '.', '')?>%"></div>
                    </div>
                </div>
            </div>
            <div class="hp-info hp-simple pull-left hp-inline">
                <span class="hp-main">Clinical Follow ups <span class="icon-angle-right"></span> <?=number_format((float)$apnt, 2, '.', '')?>%</span>
                <div class="hp-sm">
                    <div class="progress progress-small">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100" style="width: <?=number_format((float)$apnt, 2, '.', '')?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
