<?php
// define variables and set to empty values
include 'db_cred.php';
include 'encryption/secured_encrypt.php';
include 'encryption/config_keys.php';
$first_key = base64_decode(FIRSTKEY);
$second_key = base64_decode(SECONDKEY);   

$name = "";
$emailErr = "";
$email = "";
$category= "";
$telno = "";
$testcode = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name=test_input($_POST["name1"]);
	$email = test_input($_POST["email"]);
	$category = test_input($_POST["category"]);
	$telno = test_input($_POST["telno"]);
	$testcode = test_input($_POST["testcode"]);
	}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

$ci_name = secured_encrypt($name, $first_key, $second_key);
$ci_email = secured_encrypt($email, $first_key, $second_key);
$ci_cat = secured_encrypt($category, $first_key, $second_key);
$ci_telno= secured_encrypt($telno, $first_key, $second_key);
$ci_testcode = secured_encrypt($testcode, $first_key, $second_key);

$sql = "INSERT INTO yftchain (name,email, category,telno,testcode)
VALUES ('$ci_name','$ci_email', '$ci_cat','$ci_telno','$ci_testcode')";

$conn->query($sql);
$conn->close();  

//sleep for 3 seconds



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Host = "smtp.office365.com";
$mail->Port = 587; 
$mail->IsHTML(true);
$mail->Username = 'info@dyt-group.com';
$mail->Password = 'dytyftchainE895';
$mail->SetFrom('info@dyt-group.com');

$mail->Subject = 'Welcome to DYT';
$mail->AddAddress($email);
$mail->AddEmbeddedImage('vids/logo2.png', 'logo');

$mail->Body    = '
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
			<img src="logo" height="60" alt="logo">
			<p><h2><b> Hi there, new friend! </b><h2>
			<h4 style="text-align:justified"> We thank you for joining our journey to decentralise the sports industry and now we&aposre excited to show what&aposs next.
 			<br><br>As a subscriber, you&aposll be among the first to know when we launch our private beta. In the meantime, hold tight 
			and we&aposll be in contact from time-to-time with updates.   </h4>
			<h4>A big thank you, from all of us at the DYT&aposs team in India! </h4> </p> <br>
			<p>	
			<h4 style="text-align:left">See you online! </h4>
			<h4 style="text-align:left">Decoding Youth Talent (DYT) Team </h4>
			</p>

</body>
</html>';
//$mail->SMTPDebug = 3;
$mail->send();
include 'thanks.html';
?>
