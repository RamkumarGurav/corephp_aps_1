<?php	
require_once('class.phpmailer.php');  
require_once('class.smtp.php');  
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting) {
        return true;
    }
}
//@set_magic_quotes_runtime(false);
//ini_set('magic_quotes_runtime', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');
$timestamp = date("Y-m-d H:i:s");
define('MAINSITE','https://www.appolopublicschool.com/');
/*echo '<pre>';
print_r($_POST);
echo '</pre>';
//exit; 
*/

if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
	$link = $_SERVER['HTTP_REFERER'];
	$link_array = explode('/',$link);
	$page_action = end($link_array);
	$parts = parse_url($link);
	$page_host = $parts['host'];
	if (strpos($parts["host"], 'www.') !== false) { //echo 'yes<br>';
	 $parts1 = explode('www.',$parts["host"]);
	 $page_host = $parts1[1];
	}
	
	if($page_host != 'appolopublicschool.com') {
		echo '<script language="javascript">';
		echo 'alert("Host validation failed! Please try again.")';
		echo '</script>';
		echo "<script>location.href='../error.html'</script>";		
		die();
	}
}else{
    echo '<script language="javascript">';
	echo 'alert("Error: HTTP_REFERER failed! Please try again.")';
	echo '</script>';
	echo "<script>location.href='../error.html'</script>";		
	die();
}

$request='';


if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
	$param['secret'] = '6LcxrdYmAAAAAJac5hg6CbV9In90835sxpKksvLy';
	$param['response'] = $_POST['g-recaptcha-response'];
	$param['remoteip'] = $_SERVER['REMOTE_ADDR'];
	foreach($param as $key=>$val) {
		$request.= $key."=".$val;
		$request.= "&";
	}
	$request = substr($request, 0, strlen($request)-1);
	$url = "https://www.google.com/recaptcha/api/siteverify?".$request;	
	//echo $url; exit;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result_data = curl_exec($ch);	
	if(curl_exec($ch) === false){
		$error_info= curl_error($ch);
		/*echo '<pre>';
		print_r($error_info);
		echo '</pre>';*/
	}
	curl_close($ch);
	
	$response = json_decode($result_data);
	//print_r($response);
	//exit;
	if($response->success != 1){
		echo '<script language="javascript">';
		echo 'alert("google recaptcha validation failed! Please try again.")';
		echo '</script>';
		echo "<script>location.href='../error.html'</script>";	
		die();
	}
}else{
	echo '<script language="javascript">';
	echo 'alert("Error: google recaptcha post validation failed! Please try again.")';
	echo '</script>';
	echo "<script>location.href='../error.html'</script>";		
	die();
}
//exit;
if(!empty($_POST['enq_type']) && !empty($_POST['name_contact_us']) && !empty($_POST['email_contact_us']) && !empty($_POST['contact_contact_us']) && !empty($_POST['message_contact_us']) && $_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['message_contact_us']) && !empty($_POST['message_contact_us']))
	{
		if(preg_match('/http|www/i',$_POST['message_contact_us']))
		{
			echo '<script language="javascript">';
			echo 'alert("Something Went Wrong! Please try again.")';
			echo '</script>';
			echo "<script>location.href='../error.html'</script>";	
			die();
		}
	}
	
	$full_name = '';
	if(isset($_POST['name_contact_us']) && !empty($_POST['name_contact_us']))
	{
		$full_name = trim($_POST['name_contact_us']);
	}
	if(isset($_POST['lastname_contact_us']) && !empty($_POST['lastname_contact_us']))
	{
		$full_name = trim($_POST['name_contact_us']).' '.trim($_POST['lastname_contact_us']);
	}
	
	$pagelink = '';
	if(isset($_POST['pagelink']) && !empty($_POST['pagelink']))
	{
		$pagelink = trim($_POST['pagelink']);
	}
	
	$contact = trim($_POST['contact_contact_us']);
	$email = '';
	if(isset($_POST['email_contact_us']) && !empty($_POST['email_contact_us']))
	{
		$email = trim($_POST['email_contact_us']);
	}
	
	$message = '';
	if(isset($_POST['message_contact_us']) && !empty($_POST['message_contact_us']))
	{
		$message = trim($_POST['message_contact_us']);
	}
	
	$mailMessage ="<html><body>". "\r\n";
	$mailMessage .= "<font size=2 face=Verdana color=#000080>". "\r\n";
	$date=date('D dS M, Y h:i a');
	$mailMessage .= "<p align=left><strong>Date :</strong>" .$date. "</p>". "\r\n";

	if(!empty($full_name))
	{
		$mailMessage .= "<p align=left><b>Name : </b>&nbsp;".$full_name."</p>\r\n";
	}
	if(!empty($email))
	{
		$mailMessage .= "<p align=left><b>Email ID : </b>&nbsp;".$email."</p>\r\n";
	}
	$mailMessage .= "<p align=left><b>Phone No: </b>&nbsp;".$contact."</p>\r\n";
	
	if(!empty($message))
	{
		$mailMessage .= "<p align=left><b>Message : </b>&nbsp;".$message."</p>\r\n";		
	}
	
	if(!empty($pagelink))
	{
		$mailMessage .= "<p align=left><strong>Page Name : </strong><a href='https://www.appolopublicschool.com/".$pagelink."'>".$pagelink."</a></p>". "\r\n";
	}
	
	$mailMessage .="</font></body></html>\r\n";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";	
	//$from_email='ccs@appolopublicschool.com';
	$from_name="APPOLO PUBLIC SCHOOL";
	$subject = "New Enquiry From www.appolopublicschool.com";
	if($_POST['enq_type'] == 'contact-us')
	{
		$subject = "New Contact Enquiry - ".$from_name;
	}
	//echo "Mail Message : $mailMessage </br>";
	//exit;
	$address=$from_email="noreply@appolopublicschool.com";
	//$to="abhishek.khandelwal82@gmail.com";
	$to="appolopublicsch@gmail.com ";
	
	$mail   = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host       = "appolopublicschool.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	$mail->IsHTML(true);
	$mail->Username   = "noreply@appolopublicschool.com";  // GMAIL username
	$mail->Password   = "76q8Bq?j1";            // GMAIL password
	$mail->Subject    = $subject;
	$mail->MsgHTML($mailMessage);
	$mail->From         = $from_email;
	$mail->FromName     = $full_name;
	$mail->AddAddress($to, $from_name);  // Add a recipient
	$mail->AddReplyTo($email, $full_name);  // Add a recipient
	//$mail->AddBCC("noreply@appolopublicschool.com");// 
	if($mail->Send())
	{
		$mailMessageStatus = "sent"; //echo "Message Successfully Sent!";
	}
	else
	{
		$mailMessageStatus = "OOPS !! Mail Delivery Failed. Please Try Again."; //echo "Message delivery failed...";
		$error_info=$mail->ErrorInfo;
		//echo "error_info : $error_info <br>";
	}
	//exit;
	echo "<script>location.href='../thank-you.html'</script>";	
	die();
}
else if(!empty($_POST['enq_type']) && !empty($_POST['name_sidecontact_us']) && !empty($_POST['email_sidecontact_us']) && !empty($_POST['contact_sidecontact_us']) && !empty($_POST['message_sidecontact_us']) && $_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['message_sidecontact_us']) && !empty($_POST['message_sidecontact_us']))
	{
		if(preg_match('/http|www/i',$_POST['message_sidecontact_us']))
		{
			echo '<script language="javascript">';
			echo 'alert("Something Went Wrong! Please try again.")';
			echo '</script>';
			echo "<script>location.href='../error.html'</script>";	
			die();
		}
	}
	
	$full_name = '';
	if(isset($_POST['name_sidecontact_us']) && !empty($_POST['name_sidecontact_us']))
	{
		$full_name = trim($_POST['name_sidecontact_us']);
	}
	if(isset($_POST['lastname_sidecontact_us']) && !empty($_POST['lastname_sidecontact_us']))
	{
		$full_name = trim($_POST['name_sidecontact_us']).' '.trim($_POST['lastname_sidecontact_us']);
	}
	
	$pagelink = '';
	if(isset($_POST['pagelink']) && !empty($_POST['pagelink']))
	{
		$pagelink = trim($_POST['pagelink']);
	}
	
	$contact = trim($_POST['contact_sidecontact_us']);
	$email = '';
	if(isset($_POST['email_sidecontact_us']) && !empty($_POST['email_sidecontact_us']))
	{
		$email = trim($_POST['email_sidecontact_us']);
	}
	
	$message = '';
	if(isset($_POST['message_sidecontact_us']) && !empty($_POST['message_sidecontact_us']))
	{
		$message = trim($_POST['message_sidecontact_us']);
	}
	
	$mailMessage ="<html><body>". "\r\n";
	$mailMessage .= "<font size=2 face=Verdana color=#000080>". "\r\n";
	$date=date('D dS M, Y h:i a');
	$mailMessage .= "<p align=left><strong>Date :</strong>" .$date. "</p>". "\r\n";

	if(!empty($full_name))
	{
		$mailMessage .= "<p align=left><b>Name : </b>&nbsp;".$full_name."</p>\r\n";
	}
	if(!empty($email))
	{
		$mailMessage .= "<p align=left><b>Email ID : </b>&nbsp;".$email."</p>\r\n";
	}
	$mailMessage .= "<p align=left><b>Phone No: </b>&nbsp;".$contact."</p>\r\n";
	
	if(!empty($message))
	{
		$mailMessage .= "<p align=left><b>Message : </b>&nbsp;".$message."</p>\r\n";		
	}
	
	if(!empty($pagelink))
	{
		$mailMessage .= "<p align=left><strong>Page Name : </strong><a href='https://www.appolopublicschool.com/".$pagelink."'>".$pagelink."</a></p>". "\r\n";
	}
	
	$mailMessage .="</font></body></html>\r\n";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";	
	//$from_email='ccs@appolopublicschool.com';
	$from_name="APPOLO PUBLIC SCHOOL";
	$subject = "New Enquiry From www.appolopublicschool.com";
	if($_POST['enq_type'] == 'SideContact-us')
	{
		$subject = "New Side Contact Enquiry - ".$from_name;
	}
	//echo "Mail Message : $mailMessage </br>";
	//exit;
	$address=$from_email="noreply@appolopublicschool.com";
	//$to="abhishek.khandelwal82@gmail.com";
	$to="appolopublicsch@gmail.com ";
	
	$mail   = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host       = "appolopublicschool.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	$mail->IsHTML(true);
	$mail->Username   = "noreply@appolopublicschool.com";  // GMAIL username
	$mail->Password   = "76q8Bq?j1";            // GMAIL password
	$mail->Subject    = $subject;
	$mail->MsgHTML($mailMessage);
	$mail->From         = $from_email;
	$mail->FromName     = $full_name;
	$mail->AddAddress($to, $from_name);  // Add a recipient
	$mail->AddReplyTo($email, $full_name);  // Add a recipient
	//$mail->AddBCC("noreply@appolopublicschool.com");// 
	if($mail->Send())
	{
		$mailMessageStatus = "sent"; //echo "Message Successfully Sent!";
	}
	else
	{
		$mailMessageStatus = "OOPS !! Mail Delivery Failed. Please Try Again."; //echo "Message delivery failed...";
		$error_info=$mail->ErrorInfo;
		//echo "error_info : $error_info <br>";
	}
	//exit;
	echo "<script>location.href='../thank-you.html'</script>";	
	die();
}
else
{
	echo "<script>location.href='../error.html'</script>";	
	die();
}

function n_digit_random($digits)
{
  return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
}

?>
