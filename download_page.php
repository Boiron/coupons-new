<?
require('inc/func.php');

// Get URL paramater
$coupon_hash = $_GET['id'];
$currentDate = date('Y-m-d');

//Get Coupon Download Info
//Get Coupon Info From DB
$stmt = $db->prepare("SELECT * FROM `dev_coupons_downloaded` WHERE `hash`=:hash");
$stmt->bindParam(':hash', $coupon_hash);
$stmt->execute();
//If coupon found
if($row = $stmt->fetch()){
	$coupon_id = $row['coupon_id'];
	$coupon_date = $row['date'];
	$coupon_exp = strtotime(date("Y-m-d", strtotime($coupon_date)) . " +15 days");
	//Check to see if coupon is expired.
	if($currentDate > $coupon_exp){
		die_with_error("This coupon has expired.");
	}
	$coupon_url = $row['coupon_url'];	
}
else {
	die_with_error("Coupon not found.");
}
$stmt->closeCursor();

//Get Coupon Info From DB
$stmt = $db->prepare("SELECT * FROM `dev_coupons` WHERE `id`=:id");
$stmt->bindParam(':id', $coupon_id);
$stmt->execute();
//If coupon found
if($row = $stmt->fetch()){
	$coupon_id = $row['id'];
	$coupon_type = $row['type'];
	$coupon_product = $row['product'];
	$coupon_desc = $row['desc'];
	$coupon_img = "http://www.boironusa.com/coupon/png/" . $row['png'];
	//$side_img = $row['side_img'];
	$pixel1 = $row['pixel1'];
	$pixel2 = $row['pixel2'];
}
//If coupon not found
else {
	die_with_error("Coupon not found.");
}
$stmt->closeCursor();

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Download & Print Coupon - <? echo $coupon_desc ?></title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<div class="container">
    	<div class="row">
        	<div class="well">
            	<p>Click on the coupon below to download and print. To find a retailer near you, use our store locator.</p>
            </div>
            <div class="col-sm-8 center-block">
            	<h3><? echo $coupon_desc; ?></h3>
                <a href="<? echo $coupon_url; ?>" target="_blank">
                    <img align="center" alt="<? echo $coupon_desc; ?>" src="<? echo $coupon_img; ?>" width="" style="max-width: 300px;">
                </a>
            </div>
        </div><!-- row -->
    </div><!-- container -->
    <? 
	if($pixel1){
		echo $pixel1;
	}
	if($pixel2){
		echo $pixel2;
	}
	?>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>


