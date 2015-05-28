<?php
 
define("PASSWORD","password");
 
session_start();
 
if(isset($_GET['step']) && $_GET['step'] == 'nonce') {
	getNonce();
} else if (isset($_POST['response']) && isset($_POST['temp1']) && isset($_POST['temp2'])) {
	checkAuthenticationResponce();
	processEntry();
}
 
function checkAuthenticationResponce() {
	if(!isset($_SESSION['tempNonce']) || hash('sha256', $_SESSION['tempNonce'] . PASSWORD . $_POST['temp1'] . $_POST['temp2']) != $_POST['response']) {
		header("HTTP/1.0 401 Authorization Required");
		exit;
	} else {
		unset($_SESSION['tempNonce']);
	}
}
 
function getNonce() {
	$_SESSION['tempNonce'] = hash('sha256', 'some secret nonsense to make sure nobody can predict this nonce' . time());
	echo $_SESSION['tempNonce'];
}
 
function processEntry() {
	$mysqli = new mysqli("database_connection_information");
 
	/* check connection */
	if ($mysqli->connect_errno) {
		header("HTTP/1.0 500 Internal Server Error");
		exit();
	}
 
	/* Create table doesn't return a resultset */
	$stmt = $mysqli->prepare("INSERT INTO temps (temp1, temp2) VALUES(?,?)");
	$stmt->bind_param('dd', floatval($_POST['temp1']), floatval($_POST['temp2']));
 
	if ($stmt->execute() === true) {
		echo "added";
	} else {
		header("HTTP/1.0 500 Internal Server Error");
	}
 
	$mysqli->close();
}
 
?>
