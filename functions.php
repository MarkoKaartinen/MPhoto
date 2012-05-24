<?php
//Collection of functions used in MPhoto

//This function will hash pass to sha512
function pass($pass){
	$pass = hash("sha512",$pass);
	return $pass;
}

//This will check if you are logged in or not
function checkLogin(){
	if($_SESSION['mphoto_uid'] != ""){
		return true;
	}else{
		return false;
	}
}

//logout function
function logout(){
	unset($_SESSION['mphoto_uid']);
}

//login function
function login($id){
	$_SESSION['mphoto_uid'] = $id; 
}

//will check if user is admin
function isAdmin(){
	if($_SESSION['mphoto_uid'] == "admin"){
		return true;
	}else{
		return false;
	}
}

//will return all photo folders which user have rights
function fetchPhotoFolders(){
	global $conn;
	$user = $_SESSION['mphoto_uid'];
	if($user != ""){
		try {
			if($user == "admin"){ //admin will see everything
				$sql = $conn->prepare("SELECT * FROM photos");
			}elseif($user != ""){
				$sql = $conn->prepare("SELECT * FROM photos WHERE pa_nro = ? INNER JOIN p_pa ON p_nro = p_id");
				$sql->bindValue(1, $user);		
			}
		}catch (PDOException $e) {
			die("ERROR: " . $e->getMessage());
		}
		return $sql->fetchAll();
	}else{
		return false;
	}
}

//MySQL PDO connect
function connect(){
	global $host, $dbname, $dbuser, $dbpass;
	try {
	    $conn = new PDO("mysql:host=$host;dbname=$dbname", "$dbuser", "$dbpass");
	} catch (PDOException $e) {
	    die("VIRHE: " . $e->getMessage());
	}
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $conn;
}

//will generate slug for the photo folder (Folder name -> folder-name)
function generateSlug($phrase, $maxLength = 255){
	$result = strtolower($phrase);

	$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
	$result = trim(preg_replace("/[\s-]+/", " ", $result));
	$result = trim(substr($result, 0, $maxLength));
	$result = preg_replace("/\s/", "-", $result);

	return $result;
}

?>