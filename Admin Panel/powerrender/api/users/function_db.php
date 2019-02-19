<?php
    class function_db{
    	private $conn;

	    function __construct(){
	        require_once 'db.php';

	        $db = new db();
	        $this->conn = $db->connect();
	    }

	    function __destruct(){

	    }

	    public function storeUser($name, $email, $password){

	    	$uuid = uniqid('', true);
	    	$hash = $this->hashSSHA($password);
	    	$encrypted_password = $hash["encrypted"];
	    	$salt = $hash["salt"];

	    	$stmt = $this->conn->prepare("INSERT INTO tbl_app_users(unique_id, name, email, encrypted_password, salt, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
	    	$stmt->bind_param("sssss", $uuid, $name, $email, $encrypted_password, $salt);
	    	$result = $stmt->execute();
	    	$stmt->close();

	    	if ($result) {
	    		$stmt = $this->conn->prepare("SELECT * FROM tbl_app_users WHERE email = ?");
	    		$stmt->bind_param("s", $email);
	    		$stmt->execute();
	    		$user = $stmt->get_result()->fetch_assoc();
	    		$stmt->close();

	    		return $user;

	    	}else{
	    		return false;
	    	}

	    }

	    public function getUserByEmailPassword($email, $password){
	    	$stmt = $this->conn->prepare("SELECT * FROM tbl_app_users WHERE email = ?");
	    	$stmt->bind_param("s", $email);

	    	if ($stmt->execute()) {
	    		$user = $stmt->get_result()->fetch_assoc();
	    		$stmt->close();

	    		$salt = $user['salt'];
	    		$encrypted_password = $user['encrypted_password'];
	    		$hash = $this->chechkhashSSHA($salt, $password);

	    		if ($encrypted_password == $hash) {
	    			
	    			return $user;
	    		}
	    	}else{
	    		return NULL;
	    	}
	    }

	    public function isUserExist($email){
	    	$stmt = $this->conn->prepare("SELECT email from tbl_app_users WHERE email = ?");
	    	$stmt->bind_param("s", $email);
	    	$stmt->execute();
	    	$stmt->store_result();

	    	if ($stmt->num_rows > 0) {
	    		$stmt->close();
	    		return true;
	    	}else{
	    		$stmt->close();
	    		return false;
	    	}
	    }

	    public function hashSSHA($password){
	    	$salt = sha1(rand());
	    	$salt = substr($salt, 0, 10);
	    	$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
	    	$hash = array("salt" => $salt, "encrypted" => $encrypted);
	    	return $hash;
	    }

	    public function chechkhashSSHA($salt, $password){
	    	$hash = base64_encode(sha1($password . $salt, true) . $salt);

	    	return $hash;
	    }
    }
?>