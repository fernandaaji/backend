<?php
function register_user($name, $password, $email){
  global $link;
     
  $nama = escape($name);
  $pass = escape($password);
 
  $hash = hashSSHA($pass);
 
  $salt = $hash["salt"]; 
 
  $encrypted_password = $hash["encrypted"]; 
   
     
  $query = "INSERT INTO users(user_username, user_password, unique_id, user_email) VALUES('$nama', '$encrypted_password', '$salt', '$email') ON DUPLICATE KEY UPDATE unique_id = '$salt'";
  
  $user_new = mysqli_query($link, $query);
  if( $user_new ) {
        $usr = "SELECT * FROM users WHERE user_username = '$nama'";
        $result = mysqli_query($link, $usr);
        $user = mysqli_fetch_assoc($result);
        return $user;
  }else{
        return NULL;
  }
}

function escape($data){
    global $link;
    return mysqli_real_escape_string($link, $data);
}

function cek_nama($name){
    global $link;
    $query = "SELECT * FROM users WHERE user_username = '$name'";
    if( $result = mysqli_query($link, $query) ) return mysqli_num_rows($result);
}

function hashSSHA($password) {
    $salt = sha1(rand());
    $salt = substr($salt, 0, 10);
    $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
    $hash = array("salt" => $salt, "encrypted" => $encrypted);
    return $hash;
}

public function checkhashSSHA($salt, $password) {
    $hash = base64_encode(sha1($password . $salt, true) . $salt);
    return $hash;
}

function cek_data_user($name,$pass){
    global $link;
    $nama = escape($name);
    $password = escape($pass);
    
    $query  = "SELECT * FROM users WHERE user_username = '$nama'";
    $result = mysqli_query($link, $query);
    $data = mysqli_fetch_assoc($result);
    
    $unique_id = $data['unique_id'];
    $encrypted_password = $data['user_password'];
    $hash = checkhashSSHA($unique_id, $password);
    if($encrypted_password == $hash){
        return $data;
    }else{
        return false;
    }
}
?>