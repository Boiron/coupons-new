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
	$coupon_code = $row['code'];
	$side_img = "/coupon/img/side/" . $row['side_img'];
	//If the coupon type is Newsletter
	if($coupon_type == 'Newsletter'){
		$email = $_GET['email'];
		$firstname = $_GET['firstname'];
		$lastname = $_GET['lastname'];
		$zip = $_GET['zip'];
		//Check to see if email is empty
		if($email == ''){
			die_with_error("Email address not found.");
		}
		$hideName = true;
		$hideZip = true;
		$hideProducts = true;
		$hideNewsletter = true;
	}
	//If the coupon type is BMF
	if($coupon_type == 'BMF'){
		$hideProducts = true;
	}
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
<meta name="viewport" content="width=device-width">
<title>Coupon Download Form - <? echo $coupon_desc ?></title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css"/>
<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<div class="container">
    	<div class="row">
        	<div class="col-lg-8">
            	<!--
                <h1>Coupon Download Form</h1>
                -->
				<h3><? echo $coupon_desc ?></h3>
                <br>
                <em>Fill out the form below to have the coupon emailed to you.</em>
                <br><br>
        	</div>
        </div>
        
        <div class="row">
            <div class="col-sm-8">
                <form role="form" action="form_submit.php<? if($newsletter == "y"){ echo '?newsletter=y'; } ?>" method="post" class="couponForm" id="formID">
                	<div class="row <? if($hideName){ echo 'hidden'; } ?>">
                        <div class="form-group col-sm-5 col-md-4">
                            <label for="inputFirstName">First Name</label>
                            <input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="First Name" value="<? echo $firstname ?>">
                        </div><!-- form-group -->
                        <div class="form-group col-sm-6 col-md-5">
                            <label for="inputLastName">Last Name</label>
                            <input type="text" class="form-control" id="inputLastName" name="last_name" placeholder="Last Name" value="<? echo $lastname ?>">
                        </div><!-- form-group -->
                    </div><!-- row -->
                    <div class="row">
                    	<div class="form-group col-sm-8">
                            <label for="inputEmail">Email address</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter email" value="<? echo $email ?>">
                        </div><!-- form-group -->
                   	</div><!-- row -->
                    <div class="row" <? if($hideZip){ echo 'hidden'; } ?>>
                    	<div class="form-group col-sm-4 col-xs-6">
                            <label for="inputZip">Zip Code</label>
                            <input type="text" class="form-control" id="inputZip" name="zip" placeholder="Zip Code" value="<? echo $zip ?>">
                    	</div><!-- form-group -->
                    </div><!-- row -->
                    <div class="row hidden-xs <? if($hideProducts){ echo 'hidden'; } ?>">
                    	<div class="col-lg-12">
                        	<label>Which Boiron products have you tried?</label>
                        </div>
                        <div class="form-group col-sm-12">
                        	<div class="col-sm-5" class="products">
                                <input type="checkbox" value="Arnicare" name="products[]"> Arnicare<br>
                                <input type="checkbox" value="Calendula" name="products[]"> Calendula<br>
                                <input type="checkbox" value="Camilia" name="products[]"> Camilia<br>
                                <input type="checkbox" value="Chestal" name="products[]"> Chestal<br>
                                <input type="checkbox" value="Children's Chestal" name="products[]"> Children's Chestal<br>
                            </div>
                            <div class="col-sm-5"  class="products">
                                <input type="checkbox" value="Coldcalm" name="products[]"> Coldcalm<br>
                                <input type="checkbox" value="Oscillo" name="products[]"> Oscillo<br>
                                <input type="checkbox" value="Blue Tubes" name="products[]"> Blue Tubes<br>
                                <input type="checkbox" value="Other" name="products[]"> Other<br>
                            </div>
                        </div><!-- form-group -->
                    </div><!-- row -->
                    <div class="form-group <? if($hideNewsletter){ echo 'hidden'; } ?>">
                        <label for="inputNewsletter">Boiron special offers and coupons</label><br>
                        <input type="checkbox" id="inputNewsletter" name="newsletter" value="Y"> Please send me valuable coupons, specials & updates by email.
                    </div><!-- form-group -->
                    <input type="hidden" name="coupon_id" value="<? echo $coupon_id; ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <hr>
                <p style="font-size:70%">
                	<strong>Privacy Policy</strong><br>
                    We respect your privacy. We will not sell or rent personally identifiable information provided on this survey to any third party for any purpose. All of the information we collect through the survey is used solely for our internal purposes. View our <a href="http://www.arnicare.com/privacy" target="_top">Privacy Policy</a> for more details.<br>
                    <strong><? echo $coupon_code; ?></strong>
       			</p>
            </div><!-- col-lg-8 -->
            <div class="col-sm-4 hidden-xs">
            	<img class="img-responsive" src="<? echo $side_img ?>" alt="">
            </div>
		</div><!-- row -->
    </div><!-- container -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script>
    <script type="text/javascript">
	$(document).ready(function() {
		//If email is pre-populated, submit the form
		if($('#inputEmail').val()!=''){
			console.log("Email Is Pre-populated");
			$('.container').addClass('hidden');
			$('#formID').submit();
		}
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
							message: 'This is not a valid email address.'
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
