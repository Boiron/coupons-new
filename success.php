<?
$email = $_GET['email'];
//FOR DEBUG USE ONLY
$debug = $_GET['debug'];
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Success!</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>

<body>
<div class="container">
	<div class="row" style="margin-top:30px;">
    	<div class="jumbotron">
            <h1>Success!</h1>
            <p>Your coupon has been emailed to <? echo $email; ?></p>
            <p><h4>Debug Info:</h4>
            	<? echo $debug; ?>
          	</p>
            <p><button class="btn btn-primary btn-lg" href="#" role="button" onClick="window.history.back()">Go Back</button></p>
        </div>
	</div>
</div>
</body>
</html>
