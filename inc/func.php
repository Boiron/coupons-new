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
    //header('Location: success.php?email=' . $email . '&debug=' . $debug);
	header('Location: success.php?email=' . $email);
}

function send_email($email, $coupon_url, $img){
	// Email From
	$email_from = "coupons@boironusa.com";
	
	// Email To
	$email_to = $email;
	
	// Email Subject
	$email_subject = "The Boiron Coupon You Requested";
	
	// Email Message
	// This is an HTML formatted message	
	$small_img = "http://www.boironusa.com/coupon/img/small/" . $img;
	$small_img = substr_replace($small_img, '-sm.jpg', -4);
	$email_html_msg = file_get_contents('email_templates/BoironCoupons.html', true);
	$email_html_msg = str_replace("*|COUPON-PDF|*", $coupon_url, $email_html_msg);
	$email_html_msg = str_replace("*|COUPON-IMG|*", $small_img, $email_html_msg);
	// This is a plain text formatted message
	$email_plain_msg = "Click the link below to download your coupon:\r\n";
	$email_plain_msg .= $coupon_url;
	
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
	"Content-Type:text/html; charset=UTF-8\n" .
	"Content-Transfer-Encoding: 7bit\n\n" .
	$email_html_msg . "\n\n";
	
	// multipart boundary for the plain text message
	$email_message .= "--{$mime_boundary}\n" .
	"Content-Type:text/plain; charset=\"iso-8859-1\"\n" .
	"Content-Transfer-Encoding: 7bit\n\n" .
	$email_plain_msg . "\n\n";
	
	
	// end the multipart message
	$email_message .= "--{$mime_boundary}--\n";
	
	// try to send the email and verify the results
	$sendit = @mail($email_to, $email_subject, $email_message, $headers);
	if(!$sendit) {
	  die_with_error("The Email could not be sent.");
	}
}

function send_notification_email($msg, $subject){
	$email_from = "coupons@boironusa.com";
	$email_to = "web@boironusa.com";
	$email_subject = $subject;
	
	// Email Message
	// This is an HTML formatted message
	$email_html_msg = $msg;
	
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
	"Content-Type:text/html; charset=UTF-8\n" .
	"Content-Transfer-Encoding: 7bit\n\n" .
	$email_html_msg . "\n\n";
	
	// end the multipart message
	$email_message .= "--{$mime_boundary}--\n";
	
	// try to send the email and verify the results
	$sendit = @mail($email_to, $email_subject, $email_message, $headers);
	if(!$sendit) {
	  die_with_error("The notification email could not be sent.");
	}
}
function get_retailers($db, $product){
	$stmt = $db->prepare("SELECT * FROM `dev_coupons_retailers` WHERE `product`=:product");
	$stmt->bindParam(':product', $product);
	$stmt->execute();
	if($row = $stmt->fetch()){
		$retailer_img = $row['img'];
	}
	$stmt->closeCursor();
	if ($retailer_img!=''){
		return $retailer_img;
	}
	else{
		return false;
	}
}
?>