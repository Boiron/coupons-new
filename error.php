<?
$error_msg = $_GET['msg'];
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>Error - <? echo $error_msg ?></title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>

<body>
<div class="container">
	<div class="row" style="margin-top:30px;">
    	<div class="jumbotron">
            <h1>Oops!</h1>
            <p><? echo $error_msg; ?></p>
            <p><button class="btn btn-primary btn-lg" href="#" role="button" onClick="window.history.back()">Go Back</button></p>
        </div>
	</div>
</div>
</body>
</html>
