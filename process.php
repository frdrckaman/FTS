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
    if ($_GET['getUid'] == 'VAC080') {
        $project_id = 1;
    } elseif ($_GET['getUid'] == 'VAC082') {
        $project_id = 2;
    } elseif ($_GET['getUid'] == 'VAC083') {
        $project_id = 3;
    } elseif ($_GET['getUid'] == 'MAL - HERBAL') {
        $project_id = 4;
    }
    $sts = $override->get('clients', 'project_id', $project_id) ?>
    <?php foreach ($sts as $st) { ?>
        <option value="<?= $st['study_id'] ?>"><?= $st['study_id'] ?></option>
<?php }
} ?>



<?php
if ($_GET['id'] == '2') {

    foreach ($override->get('visit', 'visit_date', date('Y-m-d')) as $data) {

        // $data[] = $row;

        $json_data = array(
            // "draw"            => 1,   
            // "recordsTotal"    => intval( $totalRecords ),  
            // "recordsFiltered" => intval($totalRecords),
            "data"            => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format

    }
}




//include connection file 
// include_once("connection.php");

// // initilize all variable
// $params = $columns = $totalRecords = $data = array();

// $params = $_REQUEST;

// //define index of column
// $columns = array( 
//     0 =>'id',
//     1 =>'employee_name', 
//     2 => 'employee_salary',
//     3 => 'employee_age'
// );

// $where = $sqlTot = $sqlRec = "";

// // getting total number records without any search
// $sql = "SELECT * FROM `employee` ";
// $sqlTot .= $sql;
// $sqlRec .= $sql;


//  $sqlRec .=  " ORDER BY employee_name";

// $queryTot = mysqli_query($conn, $sqlTot) or die("database error:". mysqli_error($conn));


// $totalRecords = mysqli_num_rows($queryTot);

// $queryRecords = mysqli_query($conn, $sqlRec) or die("error to fetch employees data");

//iterate on results row and create new index array of data
// while( $row = mysqli_fetch_row($queryRecords) ) { 
//     $data[] = $row;
// }	

// $json_data = array(
//         "draw"            => 1,   
//         "recordsTotal"    => intval( $totalRecords ),  
//         "recordsFiltered" => intval($totalRecords),
//         "data"            => $data   // total data array
//         );

// echo json_encode($json_data);  // send data as json format

//     }
?>