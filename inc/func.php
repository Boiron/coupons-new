<?
// Use PDO to connect to the DB
$dsn = 'mysql:dbname=busaweb_coupons;host=127.0.0.1';
$user = 'busaweb_coupons';
$password = 's7]&8uq=u=I2';

try {
	$db = new PDO($dsn, $user, $password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
	die_with_error('PDO Connection failed: ' . $e->getMessage());
}
function die_with_error($error) {
	$db = null;
    header('Location: error.php?msg=' . $error);
}

function die_with_success($email, $debug) {
	$db = null;
    header('Location: success.php?email=' . $email . '&debug=' . $debug);
}

function send_email($email, $coupon_url, $img){
	// Email From
	$email_from = "coupons@boiron.com";
	
	// Email To
	$email_to = $email;
	
	// Email Subject
	$email_subject = "The Boiron Coupon You Requested";
	
	// Email Message
	// This is an HTML formatted message
	//$email_html_msg = "<h3>Click the link below to download your coupon:</h3>";
	//$email_html_msg .= '<a href="'. $coupon_url . '">' . $coupon_url . '</a>';
	$small_img = "http://www.boironusa.com/coupon/png/" . $img;
	$email_html_msg = file_get_contents('email_templates/calendula.html', true);
	$email_html_msg = str_replace("*|COUPON-PDF|*", $coupon_url, $email_html_msg);
	$email_html_msg = str_replace("*|COUPON-PNG|*", $small_img, $email_html_msg);
	
	// start setting up the email header
	$headers = "From: ".$email_from;
	
	// create boundary string
	// boundary string must be unique using MD5 to generate a pseudo random hash
	$random_hash = md5(date('r', time())); 
	$mime_boundary = "==Multipart_Boundary_x{$random_hash}x";
	
	// set email header as a multipart/mixed message
	// this allows the sending of an attachment combined with the HTML message
	$headers .= "\nMIME-Version: 1.0\n" .
	"Content-Type: multipart/mixed;\n" .
	" boundary=\"{$mime_boundary}\"";
	
	// multipart boundary for the HTML message
	$email_message = "This is a multi-part message in MIME format.\n\n" .
	"--{$mime_boundary}\n" .
	"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
	"Content-Transfer-Encoding: 7bit\n\n" .
	$email_html_msg . "\n\n";
	
	
	// end the multipart message
	$email_message .= "--{$mime_boundary}--\n";
	
	// try to send the email and verify the results
	$sendit = @mail($email_to, $email_subject, $email_message, $headers);
	if(!$sendit) {
	  die_with_error("The Email could not be sent.");
	}
}
/*
function send_email($email, $coupon_url){
	// Email From
	$email_from = "coupons@boiron.com";
	
	// Email To
	$email_to = $email;
	
	// Email Subject
	$email_subject = "The Boiron Coupon You Requested";
	
	// Email Message
	$email_message = "<h3>Click the link below to download your coupon:</h3>";
	$email_message .= '<a href="'. $coupon_url . '">' . $coupon_url . '</a>';
	
	// start setting up the email header
	$headers = "From: " . $email_from . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	// try to send the email and verify the results
	$sendit = @mail($email_to, $email_subject, $email_message, $headers);
	if(!$sendit) {
	  die("ERROR: The Email could not be sent.");
	}
}
*/
?>