<?
require('inc/func.php');

// Get URL paramater
$coupon_id = $_GET['id'];

//Get Coupon Info From DB
$stmt = $db->prepare("SELECT * FROM `dev_coupons` WHERE `id`=:id");
$stmt->bindParam(':id', $coupon_id);
$stmt->execute();
//If coupon found
if($row = $stmt->fetch()){
	$currentDate = date('Y-m-d');
	$coupon_exp = $row['date_exp'];
	//Check to see if coupon is expired.
	if($currentDate > $coupon_exp){
		die_with_error("This coupon has expired.");
	}
	$coupon_type = $row['type'];
	$coupon_product = $row['product'];
	$coupon_desc = $row['desc'];
}
//If coupon not found
else {
	die_with_error("Coupon not found.");
}

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Coupon Download Form - <? echo $coupon_desc ?></title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css"/>
<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<div class="container">
    	<div class="row">
        	<div class="col-lg-8">
                <h1>Coupon Download Form</h1>
				<h3><? echo $coupon_desc ?></h3>
                <br>
                <em>Fill out the form below to have the coupon emailed to you.</em>
                <br><br>
        	</div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <form role="form" action="form_submit.php" method="post" class="couponForm">
                	<div class="row">
                        <div class="form-group col-sm-4">
                            <label for="inputFirstName">First Name</label>
                            <input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="First Name">
                        </div><!-- form-group -->
                        <div class="form-group col-sm-5">
                            <label for="inputLastName">Last Name</label>
                            <input type="text" class="form-control" id="inputLastName" name="last_name" placeholder="Last Name">
                        </div><!-- form-group -->
                    </div><!-- row -->
                    <div class="row">
                    	<div class="form-group col-sm-8">
                            <label for="inputEmail">Email address</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter email">
                        </div><!-- form-group -->
                   	</div><!-- row -->
                    <div class="row">
                    	<div class="form-group col-sm-4 col-xs-6">
                            <label for="inputZip">Zip Code</label>
                            <input type="text" class="form-control" id="inputZip" name="zip" placeholder="Zip Code">
                    	</div><!-- form-group -->
                    </div><!-- row -->
                    <div class="row">
                    	<div class="col-lg-12">
                        	<label>Which Boiron products have you tried?</label>
                        </div>
                        <div class="form-group col-lg-12">
                        	<div class="col-sm-5">
                                <input type="checkbox" value="Arnicare" name="products[]"> Arnicare<br>
                                <input type="checkbox" value="Calendula" name="products[]"> Calendula<br>
                                <input type="checkbox" value="Camilia" name="products[]"> Camilia<br>
                                <input type="checkbox" value="Chestal" name="products[]"> Chestal<br>
                                <input type="checkbox" value="Children's Chestal" name="products[]"> Children's Chestal<br>
                            </div>
                            <div class="col-sm-5">
                                <input type="checkbox" value="Coldcalm" name="products[]"> Coldcalm<br>
                                <input type="checkbox" value="Oscillo" name="products[]"> Oscillo<br>
                                <input type="checkbox" value="Blue Tubes" name="products[]"> Blue Tubes<br>
                                <input type="checkbox" value="Other" name="products[]"> Other<br>
                            </div>
                        </div><!-- form-group -->
                    </div><!-- row -->
                    <div class="form-group">
                        <label for="inputNewsletter">Boiron special offers and coupons</label><br>
                        <input type="checkbox" id="inputNewsletter" name="newsletter" value="Y"> Please send me valuable coupons, specials & updates by email.
                    </div><!-- form-group -->
                    <input type="hidden" name="coupon_id" value="<? echo $coupon_id; ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div><!-- col-md-6 -->
		</div><!-- row -->
        <div class="row" style="color:white;">
        	<br><br><br><br>
            <strong>Type: </strong> <? echo $coupon_type; ?><br>
            <strong>Product: </strong> <? echo $coupon_product; ?><br>
            <strong>Description: </strong> <? echo $coupon_desc; ?><br>
        </div><!-- row -->
    </div><!-- container -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script>
    <script type="text/javascript">
	$(document).ready(function() {
		$('.couponForm').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				first_name: {
					validators: {
						notEmpty: {
							message: 'First name is required.'
						}
					}
				},
				last_name: {
					validators: {
						notEmpty: {
							message: 'Last name is required.'
						}
					}
				},
				email: {
					validators: {
						notEmpty: {
							message: 'Email address is required.'
						},
						emailAddress: {
							message: 'The input is not a valid email address.'
						}
					}
				},
				zip: {
					validators: {
						notEmpty: {
							message: 'Zipcode is required.'
						},
						zipCode: {
							country: 'US',
							message: 'Not a valid US zipcode.'
						}
					}
				}
			}
		});
	});
	</script>
</body>
</html>
