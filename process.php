<?php error_reporting(E_ALL ^ E_NOTICE); ?>
<?php require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
if ($_GET['content'] == 'visit') {
    if ($_GET['studyID']) {
        $visit_code = $override->get('clients', 'id', $_GET['studyID']); ?>
        <input type="hidden" name="visit_code" class="form-control" value="<?= $visit_code[0]['visit_code'] + 1 ?>" />
        <input type="number" name="visit_code" class="form-control" value="<?= $visit_code[0]['visit_code'] + 1 ?>" disabled />
    <?php }
} elseif ($_GET['content'] == 'site') {
    if ($_GET['site']) {
        $sites = $override->getNews('site', 'c_id', $_GET['site'], 'status', 1); ?>
        <option value="">Select Site</option>
        <?php foreach ($sites as $site) { ?>
            <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
    <?php }
    }
} elseif ($_GET['cnt'] == 'study') {
    if($_GET['getUid'] == 'VAC080'){
        $project_id = 1;
    }elseif($_GET['getUid'] == 'VAC082'){
        $project_id = 2;
    }elseif($_GET['getUid'] == 'VAC083'){
        $project_id = 3;
    }elseif($_GET['getUid'] == 'MAL - HERBAL'){
        $project_id = 4;
    }
    $sts = $override->get('clients', 'project_id', $project_id ) ?>
    <?php foreach ($sts as $st) { ?>
        <option value="<?= $st['study_id'] ?>"><?= $st['study_id'] ?></option>
<?php }
} ?>