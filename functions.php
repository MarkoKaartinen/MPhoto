<?php
//Collection of functions used in MPhoto

//This function will hash pass to sha512
function pass($pass){
	$pass = hash("sha512",$pass);
	return $pass;
}

//will check pass and return pass id or admin
function checkPass($pass){
	global $adminPass, $conn;
	if($pass == $adminPass){
		return "admin";
	}else{
		try {
			$sql = $conn->prepare("SELECT * FROM password WHERE pa_pass = ?");
			$sql->bindValue(1, $pass);
			$sql->execute();
		} catch (PDOException $e) {
			die("ERROR: " . $e->getMessage());
		}
		if($sql->rowCount() == 1){
			$data = $sql->fetchObject();
			return $data->pa_id;
		}else{
			return false;
		}
	}
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

//will generate slug for the photo folder (Folder name -> folder-name) (source: http://cubiq.org/the-perfect-php-clean-url-generator)
function generateSlug($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

//will check if there is a folder with that slug already
function isThereFolderAlready($slug){
	global $conn;
	try {
		$sql = $conn->prepare("SELECT * FROM photos WHERE p_slug = ?");
		$sql->bindValue(1, $slug);
		$sql->execute();
	} catch (PDOException $e) {
		die("ERROR: " . $e->getMessage());
	}
	if($sql->rowCount() == 1){
		return true;
	}else{
		return false;
	}
}

function getFolderPasses($p_id){
	global $conn;
	try {
		$sql = $conn->prepare("SELECT * FROM passwords INNER JOIN p_pa on pa_id = pa_nro WHERE p_nro = ?");
		$sql->bindValue(1, $p_id);
		$sql->execute();
	} catch (PDOException $e) {
		die("ERROR: " . $e->getMessage());
	}
	return $sql->fetchAll();
}

function getFolders(){
	global $conn;
	try {
		$sql = $conn->prepare("SELECT * FROM photos");
		$sql->execute();
	} catch (PDOException $e) {
		die("ERROR: " . $e->getMessage());
	}
	return $sql->fetchAll();
}

//get all passwords
function getPasses(){
	global $conn;
	try {
		$sql = $conn->prepare("SELECT * FROM passwords");
		$sql->execute();
	} catch (PDOException $e) {
		die("ERROR: " . $e->getMessage());
	}
	return $sql->fetchAll();
}

//will create folder 
function createFolder($name, $pass, $desc, $passid = false){
	global $conn;
	$origslug = generateSlug($name);
	if(isThereFolderAlready($origslug) == true){
		$i = 1;
		$foldcheck = true;
		while($foldcheck == true){
			$slug = $origslug."-".$i;
			$i++;
			$foldcheck = isThereFolderAlready($slug);
		}
	}else{
		$slug = $origslug;
	}

	try {
		$sql = $conn->prepare("INSERT INTO photos (p_name, p_slug) VALUES(?, ?)");
		$sql->bindValue(1, $name);
		$sql->bindValue(2, $slug);
		$sql->execute();
		$photo_id = $conn->lastInsertId();
	} catch (PDOException $e) {
		die("ERROR: " . $e->getMessage());
	}
	
	if($passid == false){	
		$pass = pass($pass);
		try {
			$sql = $conn->prepare("INSERT INTO passwords (pa_pass, pa_desc) VALUES(?, ?)");
			$sql->bindValue(1, $pass);
			$sql->bindValue(2, $desc);
			$sql->execute();
			$pass_id = $conn->lastInsertId();
		} catch (PDOException $e) {
			die("ERROR: " . $e->getMessage());
		}
	}else{
		$pass_id = $passid;
	}
	
	try {
		$sql = $conn->prepare("INSERT INTO p_pa (p_nro, pa_nro) VALUES(?, ?)");
		$sql->bindValue(1, $photo_id);
		$sql->bindValue(2, $pass_id);
		$sql->execute();
	} catch (PDOException $e) {
		die("ERROR: " . $e->getMessage());
	}
	
	if(mkdir("./photos/$slug")){
		return true;
	}else{
		return false;
	}
}

//functiont that sows error messages (and other messages)
function showErrorMsg($msg){
	global $messages;
	if($msg != ""){
		if($messages[$msg]['bad'] == 1){
			$errorStyle = "alert alert-error";
		}else{
			$errorStyle = "alert alert-success";
		}
		echo '<div class="'.$errorStyle.'"><a class="close" data-dismiss="alert" href="#">×</a>'.$messages[$msg]['message'].'</div>';
	}
}
?>