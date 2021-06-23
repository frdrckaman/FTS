<?php require_once('php/core/init.php');
$user = new User();
$override = new OverideData();
if($_GET['content'] == 'visit'){
    if($_GET['studyID']){
        $visit_code=$override->get('clients','id',$_GET['studyID']);?>
        <input type="hidden" name="visit_code" class="form-control" value="<?=$visit_code[0]['visit_code']+1?>" />
        <input type="number" name="visit_code" class="form-control" value="<?=$visit_code[0]['visit_code']+1?>" disabled/>
   <?php }
}elseif($_GET['content'] == 'site'){
    if($_GET['site']){
        $sites=$override->getNews('site','c_id',$_GET['site'],'status',1);?>
        <option value="">Select Site</option>
        <?php foreach($sites as $site){?>
            <option value="<?=$site['id']?>"><?=$site['name']?></option>
        <?php }
    }
}