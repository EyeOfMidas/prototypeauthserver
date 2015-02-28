<?php
if(isset($_REQUEST['help'])) { ?>
<p>To use:</p>
<ul>
	<li><strong>GET</strong> - <a href="/?method=get">/?method=GET</a></li>
	<li><strong>GET</strong> - <a href="/?method=get&key=key">/?method=GET&key=[some key]</a></li>
	<li><strong>POST</strong> - <a href="/?method=post&key=key&value=value">/?method=POST&key=[some key]&value=[some value]</a></li>
	<li><strong>DELETE</strong> - <a href="/?method=delete&key=key">/?method=DELETE&key=[some key]</a></li>
<?php	die();
}
if(!file_exists("data/auth.json")) {
	file_put_contents("data/auth.json", json_encode(array(), JSON_FORCE_OBJECT));
}
session_start();

if(isset($_REQUEST['logout'])) {
	session_destroy();
	echo json_encode(array("success" => true));
	die();
}
if(!isset($_SESSION['isAuthorized'])) {
	if(authorized()) {
		echo json_encode(array("success" => true));
	} else {
		echo json_encode(array("success" => false, "error" => "Username or password invalid."));
	}
	die();
}

$datafile = getDataFile();
if(!file_exists($datafile)) {
	file_put_contents($datafile, json_encode(array(), JSON_FORCE_OBJECT));
}

$data = file_get_contents($datafile);
$parsedData = json_decode($data, true);

//$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
$method = valueWithDefault('method', "get");

switch (strtolower($method)) {
	case "post":
		$key = valueWithDefault('key', null);
		$value = valueWithDefault('value', null);
		post($key, $value, $parsedData);
	break;
	case "delete":
		$key = valueWithDefault('key', null);
		delete($key, $parsedData);
	break;
	case "get":
	default:
		get(valueWithDefault('key', null), $parsedData);
	break;
}

function valueWithDefault($index, $default) {
	return (isset($_REQUEST[$index]) ? $_REQUEST[$index] : $default);
}

function authorized() {
	$auth = file_get_contents("data/auth.json");
	$parsedAuth = json_decode($auth, true);

	$loginName = valueWithDefault('username', null);
	$loginPass = valueWithDefault('password', null);
	if(isset($parsedAuth[$loginName]) && $parsedAuth[$loginName]['password'] == $loginPass) {
		$userKey = sha1($loginName . $loginPass);

		$_SESSION['isAuthorized'] = true;
		$_SESSION['access'] = $parsedAuth[$loginName]['access'];

		return true;
	}
	return false;
}

function getDataFile() {
	return "data/" . $_SESSION['access'];
}

function post($key, $value, $parsedData) {
	if($key && $value) {
		$parsedData[$key] = $value;
		file_put_contents(getDataFile(), json_encode($parsedData));
		echo json_encode(array("success" => true));
	} else {
		echo json_encode(array("success" => false, "error" => "Key or value not specified."));
	}
}

function delete($key, $parsedData) {
	if($key) {
		if(isset($parsedData[$key])) {
			unset($parsedData[$key]);
			file_put_contents(getDataFile(), json_encode($parsedData));
			echo json_encode(array("success" => true));
		} else {
			echo json_encode(array("success" => false, "error" => "Key not found."));
		}
	} else {
		echo json_encode(array("success" => false, "error" => "Key not specified."));
	}
}

function get($key, $parsedData) {
	if($key){
		if(isset($parsedData[$key])) {
			echo json_encode($parsedData[$key]);
		} else {
			echo json_encode(array("error" => "Key '" . $key . "' not found."));
		}
	} else {
		echo json_encode($parsedData);
	}
}
