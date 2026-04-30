<?php
require_once 'config.php';
header('Content-Type: application/json');
 $method = $_SERVER['REQUEST_METHOD'];
 $action = $_GET['action'] ?? '';
function respond($d){echo json_encode($d);exit;}

if($method=='GET'){
    if($action=='settings') respond($mysqli->query("SELECT * FROM settings WHERE id=1")->fetch_assoc());
    if($action=='schedules'){
        $r=$mysqli->query("SELECT s.*, sd.name as sound_name FROM schedules s LEFT JOIN sounds sd ON s.sound_id=sd.id ORDER BY s.time");
        $d=[]; while($row=$r->fetch_assoc()){ $row['days']=array_map('intval',array_filter(explode(',',$row['days']))); $d[]=$row; }
        respond($d);
    }
    if($action=='sounds') respond($mysqli->query("SELECT * FROM sounds")->fetch_all(MYSQLI_ASSOC));
}

if($method=='POST'){
    $in = json_decode(file_get_contents('php://input'), true);
    
    // Upload Handler
    if(isset($_FILES['file'])){
        $type=$_POST['type']??'sound';
        $dir='uploads/'.($type=='logo'?'':'sounds/');
        if(!is_dir($dir)) mkdir($dir,0777,true);
        $fn=time().'_'.basename($_FILES['file']['name']);
        $path=$dir.$fn;
        if(move_uploaded_file($_FILES['file']['tmp_name'],$path)){
            if($type=='logo') $mysqli->query("UPDATE settings SET logo_path='$path' WHERE id=1");
            else {
                $n=$_POST['name']??'Sound';
                $stmt=$mysqli->prepare("INSERT INTO sounds (name,file_path) VALUES (?,?)");
                $stmt->bind_param("ss",$n,$path); $stmt->execute();
            }
            respond(['success'=>true]);
        }
        respond(['success'=>false]);
    }

    // CRUD
    if($action=='save_settings'){
        $stmt=$mysqli->prepare("UPDATE settings SET school_name=?, school_address=?, system_active=? WHERE id=1");
        $a=isset($in['system_active'])?1:0;
        $stmt->bind_param("ssi",$in['school_name'],$in['school_address'],$a);
        $stmt->execute(); respond(['success'=>true]);
    }
    if($action=='save_schedule'){
        $days=implode(',',$in['days']);
        if($in['id']){
            $stmt=$mysqli->prepare("UPDATE schedules SET time=?,type=?,name=?,days=?,sound_id=? WHERE id=?");
            $stmt->bind_param("ssssii",$in['time'],$in['type'],$in['name'],$days,$in['sound_id'],$in['id']);
        }else{
            $stmt=$mysqli->prepare("INSERT INTO schedules (time,type,name,days,sound_id) VALUES (?,?,?,?,?)");
            $stmt->bind_param("ssssi",$in['time'],$in['type'],$in['name'],$days,$in['sound_id']);
        }
        $stmt->execute(); respond(['success'=>true]);
    }
    if($action=='delete_schedule'){
        $mysqli->query("DELETE FROM schedules WHERE id=".(int)$in['id']);
        respond(['success'=>true]);
    }
}