<?
require('inc/classes/coupon.class.php');
require('inc/func.php');
require('inc/generate_coupon.php');


//MailChimp
require_once 'inc/MCAPI.class.php';

//Pull all values from POST
$coupon_id = $_POST['coupon_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$zip = $_POST['zip'];
$products_array = $_POST['products'];
if($products_array){
	$products_tried = implode(", ", $_POST['products']);
}
else{
	$products_tried = NULL;
}
if ($_POST['newsletter']){
	$newsletter = "1";
}else{
	$newsletter = NULL;
}

//Find out coupon expiration date (5 days from now)
$currentDate = date("F j, Y"); // current date
$exp_date = strtotime(date("Y-m-d", strtotime($currentDate)) . " +5 days");
//Create unique hash using coupon id and exp date
$hash = md5($coupon_id . $exp_date);
$hash = substr($hash, -6);
$ip_address = $_SERVER['REMOTE_ADDR'];


//Check to make sure coupon exists
$stmt = $db->prepare("SELECT * FROM `dev_coupons` WHERE `id`=:id");
$stmt->bindParam(':id', $coupon_id);
$stmt->execute();
//If coupon found
if($row = $stmt->fetch()){
	$coupon = new Coupon($coupon_id, 
		$row['type'], 
		$row['product'], 
		$row['desc'], 
		$row['date_created'], 
		$row['date_exp'], 
		$row['img'], 
		$row['upc'],
		$row['medium']);
	$coupon->first_name = $first_name;
	$coupon->last_name = $last_name;
	$coupon->email = $email;
	$coupon->zip = $zip;
	$coupon->products = $products_tried;
	if($newsletter == 1)
		$coupon->newsletter = 'Yes';
	else
		$coupon->newsletter = 'No';
	
	$coupon_type = $row['type'];
	$coupon_product = $row['product'];
	$coupon_desc = $row['desc'];
	$coupon_img = $row['img'];
	if(!$coupon_img){
		die_with_error("Coupon Image File Not Found.");
	}
	$coupon_upc = substr($row['upc'], -5);
	$side_img = $row['side_img'];
	$coupon_site = $row['site'];
}
//If coupon not found
else {
	die_with_error("Coupon not found.");
}

//Check to make sure that person hasn't already downloaded coupon
$stmt = $db->prepare("SELECT * FROM `dev_coupons_downloaded` WHERE `email`=:email AND `coupon_id`=:id");
$stmt->bindParam(':email', $email);
$stmt->bindParam(':id', $coupon_id);
$stmt->execute();
//If person has downloaded coupon
if($row = $stmt->fetch()){
	die_with_error("You have already downloaded this coupon.");
}

//Else, store in `coupons_downloaded`, send email, and show verification message
else{
	//MailChimp API Key
	$apikey = 'd71aef15a5934aa73398f6a7332e1c93-us8';
	$api = new MCAPI($apikey);
	//Generate coupon PDF file
	$retailers = get_retailers($db, $coupon_product);
	$coupon_url = generate_coupon($coupon_img, $coupon_product, $coupon_upc, $hash, $retailers);
	
	//Email PDF
	$download_url = "http://www." . $coupon_site . ".com/download-coupon/?id=" . $hash;
	//send_email($coupon, $download_url);
	
	//Subscribe to Mailchimp list if they opted in
	if($newsletter == '1'){
		$listId = 'c2e06c10bc'; 
		$my_email = $email;
		$double_optin = false; 
		$send_welcome = false;  
		//$api = new MCAPI($apikey);  
		$merge_vars = Array( 
			'FNAME' => $first_name, 
			'LNAME' => $last_name,
			'ZIP' => $zip,
			'FROM' => $coupon_type,
			'COUPON' => $coupon_desc,
			'PRODUCTS' => $products_tried
		);
		foreach($products_array as $product){
			
			if($product == "Children\'s Chestal"){
				$product_name = "CCHESTAL";
			}
			else if($product == "Blue Tubes"){
				$product_name = "BLUETUBES";
			}
			else {
				$product_name = strtoupper($product);
			}
			$merge_vars[$product_name] = 'Yes';
		}
		$retval = $api->listSubscribe( $listId, $my_email, $merge_vars, $double_optin, $send_welcome);
		
	}
		
	//Store record in the DB
	$stmt = $db->prepare("INSERT INTO `dev_coupons_downloaded` (`coupon_id`, `first_name`, `last_name`, `email`, `zip`, `products_tried`, `newsletter`, `ip_address`, `hash`, `coupon_url`) VALUES (:id, :first_name, :last_name, :email, :zip, :products_tried, :newsletter, :ip_address, :hash, :coupon_url)");
	$stmt->bindParam(':id', $coupon_id);
	$stmt->bindParam(':first_name', $first_name);
	$stmt->bindParam(':last_name', $last_name);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':zip', $zip);
	$stmt->bindParam(':products_tried', $products_tried);
	$stmt->bindParam(':newsletter', $newsletter);
	$stmt->bindParam(':ip_address', $ip_address);
	$stmt->bindParam(':hash', $hash);
	$stmt->bindParam(':coupon_url', $coupon_url);
	//$stmt->execute();
	
	//var_dump($coupon);
	
	//GET TOTAL COUPON DOWNLOAD COUNT
	$sql = "SELECT count(*) FROM `dev_coupons_downloaded` WHERE coupon_id = $coupon_id"; 
	$result = $db->prepare($sql); 
	$result->execute(); 
	$count = $result->fetchColumn();
	$notif_subject = $coupon->product . ' Coupon - ' . $coupon_type . ' [#' . $count . ']';
	//send_notification_email($coupon, $notif_subject);
	
	//If newsletter coupon then send directly to download page
	if($coupon_type == 'Newsletter'){
		$listId = 'c0815969fa';
		$retval = $api->listMemberInfo( $listId, array($email) );
		if($retval['success'] > 0){
			//Store Record in DB
			$stmt->execute();
			send_notification_email($coupon, $notif_subject);
			header("Location: http://www.boironusa.com/coupon/download_page.php?id=" . $hash);
		}
		else {
			die_with_error("Invalid email address.");
		}
	}
	//Otherwise email them the coupon
	else{
		//Store Record in DB
		$stmt->execute();
		send_notification_email($coupon, $notif_subject);
		send_email($coupon, $download_url);	
		die_with_success($email, $debug);
	}
}
?>
