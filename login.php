<?php 
require_once "core/init.php";
if (isset($_POST['name']) && isset($_POST['password'])) { 
    $nama  = $_POST['name'];
    $pass = $_POST['password'];
     
    $user = cek_data_user($nama,$pass);
    if($user != false){
        $response["error"] = FALSE;
        $response["user"]["name"] = $user["user_username"];
        $response["user"]["user_key"] = $user["unique_id"];
        echo json_encode($response);
    }else{
        $response["error"] = TRUE;
        $response["error_msg"] = "Login gagal. Password/Nik salah";
        echo json_encode($response);
    }
}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "Nik atau Password tidak boleh kosong !";
    echo json_encode($response);
}
?>