<?php
class User {
    private $_db,
        $_data,
        $_getdata,
        $_sessionName,
        $_sessionTableName,
        $_sessionTable,
        $_cookieName,
        $_override;
    public $isLoggedIn;

    public function __construct($user = null){
        $this->_db = DB::getInstance();
        $this->_override = new OverideData();
        $this->_sessionName = config::get('session/session_name');
        $this->_sessionTable = config::get('session/session_table');
        $this->_cookieName = config::get('remember/cookie_name');

        if(!$user){
            if(Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);
                $this->_sessionTableName = Session::getTable($this->_sessionTable);
                if($this->findUser($user,$this->_sessionTableName)){
                    $this->isLoggedIn = true;
                } else {

                }
            }
        } else {
            $this->find($user);
        }
    }
    public function getSessionTable(){
        return $this->_sessionTableName;
    }
    public function validateBundle($message,$noUser){
        $noWords = $this->countWords($message,$noUser);
        if($noWords <= $this->checkBundle()[0]['sms']){
            return true;
        }
    }
    public function countWords($message,$noUser){
        return ceil((mb_strlen($message))/160) * $noUser;
    }
    public function checkBundle(){
        $sms = $this->_db->getValue('bundle_usage');
        return $sms;
    }
    public function wordCount($message){
        return ceil((mb_strlen($message)));
    }
    public function sendSMS($to,$textMessage)
    {
        if ($to <> '') {
            $from = '';
            $messageId = null;
            $text = $textMessage;
            $notifyUrl = null;
            $notifyContentType = null;
            $callbackData = null;
            $username = '';
            $password = '';

            $postUrl = "https://api.infobip.com/sms/1/text/advanced";

            // creating an object for sending SMS
            $destination = array("messageId" => $messageId,
                "to" => $to);

            $message = array("from" => $from,
                "destinations" => array($destination),
                "text" => $text,
                "notifyUrl" => $notifyUrl,
                "notifyContentType" => $notifyContentType,
                "callbackData" => $callbackData);

            $postData = array("messages" => array($message));
            $postDataJson = json_encode($postData);

            $ch = curl_init();
            $header = array("Content-Type:application/json", "Accept:application/json");

            curl_setopt($ch, CURLOPT_URL, $postUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // response of the POST request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $responseBody = json_decode($response);
            curl_close($ch);
            if ($httpCode >= 200 && $httpCode < 300) {

            }
        }
    }

    public function getOS() {

        global $user_agent;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os_platform  = "Unknown OS Platform";

        $os_array     = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;

        return $os_platform;
    }

    function getBrowser() {

        global $user_agent;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser        = "Unknown Browser";

        $browser_array = array(
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $browser = $value;

        return $browser;
    }

    function getIp() {
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    public function renameFile($file,$name){
        rename($file,$name);
        return $name;
    }
    public function download($path){
        $file = $path;
        $filename = 'PRST Constitution.pdf';
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        header('Accept-Ranges: bytes');
        @readfile($file);
    }
    public function readPdf($path){
        $file = $path;
        $filename = 'Document.pdf';
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        header('Accept-Ranges: bytes');
        @readfile($file);
    }
    function customStringLength($x, $length){
        if(strlen($x)<=$length) {return $x;}
        else {
            $y=substr($x,0,$length) . '...';
            return $y;
        }
    }
    function removeSpecialChar($string){
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    function excelRow($x,$y){
        $arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        if($x > 26){
            if($x%26 == 0){$v = abs($x/26 - $x/26);}else{$v = abs(floor($x/26) - 1);}
            return $arr[$v].''.$arr[$y];
        }else{
            return $arr[$y];
        }
    }
    function splitPDF($file,$page,$name){
        exec('pdftk '.$file.' cat '.$page.' output '.$name.'.pdf');
    }
    function pdfPages($file){
        exec('pdftk '.$file.' dump_data', $output, $return);
        $no = implode(' ',$output);
        return $no[1];
    }
    function countPDF($file){$pageNo=null;
        exec('pdftk '.$file.' dump_data', $output, $return);
        $array = explode(' ', $output[0]);
        if($array && $array[1] == 'NumberOfPages:'){
            $pageNo = $array[1];
        }else{
            foreach($output as $out){
                $ar = explode(' ', $out);
                if($ar[0] == 'NumberOfPages:'){
                    $pageNo = $ar[1];
                    break;
                }
            }
        }
        return $pageNo ;
    }

    function exportData($data,$file) {
        $timestamp = time();
        $filename = $file.'_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $isPrintHeader = false;
        foreach ($data as $row) {
            if (! $isPrintHeader) {
                echo implode("\t", array_keys($row)) . "\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
        exit();
    }
    function exportSchedule($data1,$data2,$file) {
        $timestamp = time();
        $filename = $file.'_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        foreach ($data1 as $row) {
            echo implode("\t", array_values($row)) . "\n";
        }
        foreach ($data2 as $row2) {
            echo implode("\t", array_values($row2)) . "\n";

        }
        exit();
    }
    function schedule(){
        $clients = $this->_override->getData('clients');
        foreach ($clients as $client){
            $schedule = $this->_override->get('schedule','client_id',$client['id'])[0];
            $visit = $this->_override->getNews('visit','client_id',$client['id'],'visit_date',date('Y-m-d'))[0];
            if($visit){

            }else{

            }
        }
    }

    function scheduleUpdate(){
        $data=$this->_override->getData('schedule_update')[0];
        if($data){
            if($data['update_date'] != date('Y-m-d')){
                $this->updateRecord('schedule_update',array('update_date'=>date('Y-m-d')),$data['id']);
            }
        }else{
            $this->createRecord('schedule_update',array('update_date'=>date('Y-m-d')));
        }
    }

    function generateSchedule($pid, $date, $v_point, $status){
        $x= $v_point;$arr = array();$y=0;$vg='V1';
        $nxt_visit = $date;$vty='Clinic';$vc='V1';$lw=0;$hw=0;
        if($x == 1){
            $nxt_visit = date('Y-m-d', strtotime($nxt_visit));
            $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
        }
        while($x < 728){
            if($x <= 7){
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 1 days'));
                $y++;$vc='V1 + '.$y;if($y==2 || $y==7){$vty='Clinic';$lw=1;$hw=1;}else{$vty='Home';$lw=0;$hw=0;}
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
                $x++;
            }elseif ($x == 8){
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 7 days'));
                $x += 6;$y=14;$vc='V1 + '.$y;$vty='Clinic';$lw=1;$hw=3;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
                $y=14;$vg='V2';
            }elseif ($x == 14){$y=14;$vg='V2';
                if($status == 'c'){
                    $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 14 days'));
                }elseif ($status == 'u'){$status='c';
                    $nxt_visit = date('Y-m-d', strtotime($date));
                }
                $x += 14;$vty='Clinic';if($y==14){$vc='V2';$lw=1;$hw=3;}else{$vc='V2 + '.$y;$lw=0;$hw=0;}
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
                $y=1;
            }elseif ($x >= 28 && $x < 35){
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 1 days'));
                $x++;if($x==28){$vc='V2';$y=0;$vty='Clinic';$lw=7;$hw=14;}else{$vc='V2 + '.$y;$vty='Home';$lw=0;$hw=0;}if($y==2 || $y==7){$vty='Clinic';}
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
                $y++;
            }elseif ($x == 35){$y=14;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 7 days'));
                $x += 7;$vc='V2 + '.$y;$vty='Clinic';$lw=0;$hw=0;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
            }elseif ($x == 42){$y=1;$vg='V3';
                if($status == 'c'){
                    $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 14 days'));
                }elseif ($status == 'u'){
                    $nxt_visit = date('Y-m-d', strtotime($date));
                }
                $x += 14;$vc='V3';$vty='Clinic';$lw=7;$hw=14;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
            }elseif ($x >= 56 && $x < 63){
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 1 days'));
                $x++;if($y==56){$vc='V3';$y=0;}else{$vc='V3 + '.$y;}if($y==2 || $y==7){$vty='Clinic';}else{$vty='Home';}$lw=0;$hw=0;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
                $y++;
            }elseif ($x == 63){$y=14;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 7 days'));
                $x += 7;$vc='V3 + '.$y;$vty='Clinic';$lw=1;$hw=3;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
                $y++;
            }elseif ($x == 70){$y=28;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 14 days'));
                $x += 14;$vc='V3 + '.$y;$vty='Clinic';$lw=7;$hw=2;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
            }elseif ($x == 84){$y=84;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 56 days'));
                $x += 56;$vc='V3 + '.$y;$vty='Clinic';$lw=7;$hw=7;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
            }elseif ($x == 140){$y=184;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 100 days'));
                $x += 100;$vc='V3 + '.$y;$vty='Clinic';$lw=7;$hw=7;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
            }elseif ($x == 240){$y=336;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 152 days'));
                $x += 152;$vc='V3 + '.$y;$vty='Clinic';$lw=28;$hw=28;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
            }elseif ($x == 392){
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit. ' + 336 days'));
                $x += 336;$vc='V3 + '.$y;$vty='Clinic';$lw=28;$hw=28;
                $this->createRecord('visit',array('visit_code'=>$vc,'visit_group'=>$vg,'visit_date'=>$nxt_visit,'visit_type'=>$vty,'l_window'=>$lw,'h_window'=>$hw,'client_id'=>$pid,'status'=>0,'staff_id'=>$this->data()->id));
            }elseif ($x == 728){
                break;
            }
        }
    }

    function dateDiff($date1,$date2){
        $date = strtotime($date1) - strtotime($date2);
        return $date/86400;
    }

    function updateSchedule($pid, $date, $day){
        if($day==1){$visit= 'V'.$day;
            $this->deleteRecord('visit','client_id',$pid);
            $this->generateSchedule($pid,$date,$day,'c');
        }elseif ($day==2){$visit= 'V2';$v_p=14;
            foreach ($this->_override->getNews('visit','client_id',$pid, 'visit_group',$visit) as $vst){
                $this->deleteRecord('visit','id',$vst['id']);
            }$visit= 'V3';
            foreach ($this->_override->getNews('visit','client_id',$pid, 'visit_group',$visit) as $vst){
                $this->deleteRecord('visit','id',$vst['id']);
            }
            $this->generateSchedule($pid,$date,$v_p,'u');
        }elseif ($day==3){$visit= 'V3';$v_p=42;
            foreach ($this->_override->getNews('visit','client_id',$pid, 'visit_group',$visit) as $vst){
                $this->deleteRecord('visit','id',$vst['id']);
            }
            $this->generateSchedule($pid,$date,$v_p,'u');
        }

    }


    function removePDF($file){
        exec('rm '.$file, $output, $return);
        return true;
    }
    public function update($fields = array(),$id = null){
        if(!$id && $this->isLoggedIn()){
            $id = $this->data()->id;
        }
        if(!$this->_db->update('staff',$id,$fields)){
            throw new Exception('There is problem updating');
        }
    }
    public function updateRecord($table,$fields=array(),$id = null){
        if(!$id && $this->isLoggedIn()){
            $id = $this->data()->id;
        }
        if(!$this->_db->update($table,$id,$fields)){
            throw new Exception('There is problem updating');
        }
    }
    public function updateFunction($table,$fields=array(),$id = null){
        if(!$this->_db->update($table,$id,$fields)){
            throw new Exception('There is problem updating');
        }
    }
    public function payment($test,$medicine,$quantity){
        $pay = $this->_db->getValue('test_list','id',$test);
        if($medicine && $quantity){$med = $this->_db->getValue('medicine','id',$medicine);
            $medCost = $med[0]['price'] * $quantity;
            $cost = $pay[0]['cost'] + $medCost;
        }else{
            $cost = $pay[0]['cost'];
        }
        return $cost;
    }
    public function deleteRecord($table,$field,$value){
        if(!$this->_db->delete($table, array($field, '=', $value))){
            throw new Exception('There is problem deleting');
        }
    }

    public function updateSubject($table,$fields=array(),$id = null){
        if(!$id && $this->isLoggedIn()){
            $id = $this->data()->id;
        }
        if(!$this->_db->updateSubject($table,$id,$fields)){
            throw new Exception('There is problem updating');
        }
    }

    public function create($fields = array()){
        if(!$this->_db->insert('staff',$fields)){
            throw new Exception('There is a problem creating Account');
        }
    }
    public function createRecord($table,$fields = array()){
        if(!$this->_db->insert($table,$fields)){
            throw new Exception('There is a problem creating Account');
        }return true;
    }

    public function find($user = null){
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'email';
            $data = $this->_db->get('staff',array($field,'=',$user));

            if($data->count()){
                $this->_data=$data->first();
                return true;
            }
        }
    }
    public function findUser($user = null,$table){
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get($table,array($field,'=',$user));

            if($data->count()){
                $this->_data=$data->first();
                return true;
            }
        }
    }

    public function loginUser($username=null,$password=null,$table){
        if(!$username && !$password && $this->exists()){
            Session::put($this->_sessionName,$this->data()->id);
        } else {
            $user = $this->findUser($username,$table);
            if($user){
                if($this->data()->password === Hash::make($password,$this->data()->salt)){
                    Session::put($this->_sessionName,$this->data()->id);
                    Session::putSession($this->_sessionTable,$table);
                    return true;
                }
            }
        }
        return false;
    }

    public function login($username=null,$password=null,$remember = false){
        if(!$username && !$password && $this->exists()){
            Session::put($this->_sessionName,$this->data()->id);
        } else {
            $user = $this->find($username);
            if($user){
                if($this->data()->password === Hash::make($password,$this->data()->salt)){
                    Session::put($this->_sessionName,$this->data()->id);
                    if($remember){
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('user_session',array('user_id','=',$this->data()->id));
                        if(!$hashCheck->count()){
                            $this->_db->insert('user_session' ,array(
                                'user_id' => $this->data()->id,
                                'hash' =>$hash
                            ));
                        }else {
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookieName,$hash,config::get('remember/cookie_expiry'));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function exists(){
        return (!empty($this->_data)) ? true : false;
    }
    public function logout(){
        $this->_db->delete('user_session', array('user_id', '=', $this->data()->id));
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }
    public function data(){
        return $this->_data;
    }
    public function isLoggedIn(){
        return $this->isLoggedIn;
    }
    public function selectAll($table){
        if($result = $this->_db->getAll($table)){
            $this->_getdata = $result;
        } else throw new Exception('There is a problem getting the values');
    }
    public function getData(){
        return $this->_getdata;
    }
    public function getInfo($table,$field,$value){
        return $this->_override->get($table,$field,$value);
    }
}