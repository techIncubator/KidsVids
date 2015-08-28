<!DOCTYPE html>
<html>
<head>
<link rel='stylesheet' href='signup.css' type='text/css' />
</head>
<body>
<div id="container">

<form action="add-user.php" method="post">

<p><b> Sorry !! you have not Signed Up. You need to Sign Up to continue. </b></p>
<div class="labels">
<label>Your Full Name: &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $_GET['dp']; ?></label>
</div>

<input type="hidden" id="inputFullname" name="fname" class="form-control" value="<?php echo $_GET['dp']; ?>">


<div class="labels">		
<label>Your Email Address: &nbsp &nbsp  <?php echo $_GET['email']; ?></label>
</div>

<input type="hidden" id="inputEmail" name="email" class="form-control" value="<?php echo $_GET['email']; ?>">

<div class="labels" style="display:inline-block;">
<label>Please enter your Parent's Twitter ID:&nbsp</label>
</div>
<input type="text" id="inputTwitter" name="inputTwitter" class="form-control">
<br>

<input name="submit" type="submit" value="Sign up"/>
 
</form>
</div>
</body>
</html>
