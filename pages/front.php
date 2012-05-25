<?php
if(checkLogin()){
	echo "hey";
}else{
	echo '<h1>Password please :)</h1>';
	echo '<div class="row">
	<div class="span4">
	<form action="index.php?do=tryLogin" method="post" class="well form-inline">
		<input type="password" name="password" placeholder="Password" />
		<input type="submit" value="Let\'s try" class="btn" />
	</form>
	</div>
	</div>';
}
?>