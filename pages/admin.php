<?php
if(isAdmin()){
	echo "<h1>Admin</h1>";
	echo '<div class="btn-group" id="adminnav">';
		echo '<a class="btn btn-primary'; if($do == "folders"){ echo " active"; } echo'" href="index.php?p=admin&amp;do=folders">Folders</a>';
		echo '<a class="btn btn-primary'; if($do == "passwords"){ echo " active"; } echo'" href="index.php?p=admin&amp;do=passwords">Passwords</a>';
		echo '<a class="btn btn-primary'; if($do == "configuration"){ echo " active"; } echo'" href="index.php?p=admin&amp;do=configuration">Configuration</a>';
	echo '</div>';
	if($do == ""){
		echo "<p>Choose the thing what you want to do.</p>";
	}
	if($do == "folders"){
		echo "<h2>Folders</h2>";
		echo '<p><a href="#" class="btn btn-small"><i class="icon-plus-sign"></i> New folder</a></p>';
		echo '<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Slug</th>
					<th>Users</th>
				</tr>
			</thead>
			<tbody>';
		echo '</tbody>
		</table>';
	}
}else{
	echo "<h1>Nothing to see here!</h1>";
}
?>