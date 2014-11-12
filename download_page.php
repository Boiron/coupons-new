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
	$coupon_exp = strtotime(date("Y-m-d", strtotime($coupon_date)) . " +5 days");
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
	$coupon_img = "http://www.boironusa.com/coupon/img/small/" . $row['img'];
	$small_img = substr_replace($coupon_img, '-sm.jpg', -4);
	$pixel1 = $row['pixel1'];
	$pixel2 = $row['pixel2'];
	$coupon_upc = substr($row['upc'], -5);
	$coupon_dollars = substr($row['desc'], 0, 2);
	$coupon_site = $row['site'];
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
        	<div class="well text-center">
            	<p>Click on the coupon below to download and print. To find a retailer near you, use our <a href="http://www.boironusa.com/wheretobuy/retailers/" target="_parent">store locator.</a></p>
            </div>
            <div class="col-sm-12 text-center">
            	<h3><? echo $coupon_desc; ?></h3>
                <a href="<? echo $coupon_url; ?>" target="_blank" class="couponLink">
                    <img align="center" alt="<? echo $coupon_desc; ?>" src="<? echo $small_img; ?>" width="" style="max-width: 300px;">
                </a>
            </div>
        </div><!-- row -->
        <div class="row">
            <div class="col-sm-12 text-center" style="margin-top:75px;">
            	<a href="http://www.boironusa.com/app" target="_blank">
                <img src="http://www.boironusa.com/app/banners/600x300-3Taps.jpg" alt="">
                </a>
            </div>
        </div>
    </div><!-- container -->
    <!-------- BEGIN CONVERSION PIXELS --------->
    <?
	$pixels = $db->query("SELECT `pixel` FROM `dev_coupons_pixels` WHERE `coupon_id`=$coupon_id");
	foreach ($pixels as $row) {
		echo $row['pixel'];
	}
	?>
    <!-------- END CONVERSION PIXELS --------->
    <!-- Google Analytics -->
    <?
	$trackingcode = $db->query("SELECT `tracking` FROM `dev_coupons_ga` WHERE `site`='$coupon_site'");
	foreach ($trackingcode as $row) {
		$gacode = $row['tracking'];
	}
	?>
	<script async src='//www.google-analytics.com/analytics.js'></script>
    <script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', '<? echo $gacode; ?>', 'auto');
    </script>
    <!-- End Google Analytics -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
		$('.couponLink').click(function() {
			ga('send', {
			  'hitType': 'event',          // Required.
			  'eventCategory': '<? echo $coupon_type ?> Coupon Download',   // Required.
			  'eventAction': 'Print <? echo $coupon_desc; ?>',      // Required.
			  'eventLabel': '<? echo $coupon_dollars . ' Coupon: ' . $coupon_upc; ?>'
			});
		});
	</script>
</body>
</html>


