<?php 
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/login.css">
    
   
    <title>Sign in</title>
	
</head>
<body>

<!-- <h2>Weekly Coding Challenge #1: Sign in/up Form</h2> -->
<div class="container" id="container">
	<div class="form-container sign-up-container">
				<form action="config/login_process.php" method="POST" autocomplete="off">
			<h2>Create Account</h2>

			<input type="text" placeholder="UserName" name="username_sup" />
			<input type="text" placeholder="ชื่อ-นามสกุล" name="fullname_sup" />
			<input type="text" placeholder="ตำแหน่ง" name="position_sup" />
			<input type="password" placeholder="Password" name="password_sup" />
			
			<button>Sign Up</button>
			</form>

	</div>

	<div class="form-container sign-in-container">
		<form action="config/login_process.php" method="POST">
			<h1>Sign in</h1>
			<div class="social-container">

			</div>
			<span>use your account</span>
			<input type="text" placeholder="Email" name="email_sin" />
			<input type="password" placeholder="Password" name="password_sin" />
			<!-- <a href="#">Forgot your password?</a> -->
			<button>Sign In</button>
		</form>
	</div>
	
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
			<h1>Welcome </h1> 	
			<p1>ยินดีต้อนรับสู่คณะวิทยาศาสตร์การแพทย์</p1> <p1> <a target="_blank" href="Thaimap_new.php"> Click </a> เพื่อเข้าสู่เว็บไซต์</p1>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Welcome </h1> 	
				<p1>ยินดีต้อนรับสู่คณะวิทยาศาสตร์การแพทย์</p1> <p1> <a target="_blank" href="Thaimap_new.php"> Click </a> เพื่อเข้าสู่เว็บไซต์</p1>
				<?php 
                        
                        if(isset($_SESSION['DoYouKnowImBigBrother']) ){
                            
                    
                    ?>
				<button class="ghost" id="signUp">Sign Up</button>
				<?php }?>
			</div>
		</div>
	</div>
</div>

<footer>
	<p>
		<!-- Created with <i class="fa fa-heart"></i> by
		<a target="_blank" href="https://florin-pop.com">Florin Pop</a>
		- Read how I created this and how you can join the challenge
		<a target="_blank" href="https://www.florin-pop.com/blog/2019/03/double-slider-sign-in-up-form/">here</a>. -->
	</p>
</footer>
<script src="js/script.js"></script>

</body>

</html>