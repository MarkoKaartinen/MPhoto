<?php
if(isAdmin()){
	$task = $_GET['task'];
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
		if($task == "addFolderForm"){
			echo '<form action="index.php?p=admin&amp;do=folders&amp;task=addFolder" method="POST" class="form-horizontal checkthisform">
				<fieldset>
					<legend>Add new folder</legend>
					<div class="control-group" id="fname_gr">
						<label class="control-label" for="fname">Folder name</label>
						<div class="controls">
							<input type="text" name="fname" id="fname" class="checkthis">
							<span class="help-inline" id="fname_err"></span>
						</div>
					</div>
					
					<div class="control-group" id="pchoose_gr">
						<label class="control-label" for="pchoose">Choose password</label>
						<div class="controls">
							<select name="pchoose" id="pchoose">
								<option value="new">New password</option>
								<option value="old">Old password</option>
							</select>
							<span class="help-inline" id="pchoose_err"></span>
						</div>
					</div>
					
					<div id="oldPassword">
						<div class="control-group" id="passid_gr">
							<label class="control-label" for="passid">Password</label>
							<div class="controls">
								<select id="passid" name="passid">
									<option value="">Select password</option>';
									$passwords = getPasses();
									for($i = 0; $i < count($passwords); $i++){
										echo "<option value=\"".$passwords[$i]['pa_id']."\">".$passwords[$i]['pa_desc']."</option>";
									}
								echo '</select>
								<span class="help-inline" id="passid_err"></span>
							</div>
						</div>
					</div>
					
					<div id="newPassword">
						<div class="control-group" id="fpass_gr">
							<label class="control-label" for="fpass">Password</label>
							<div class="controls">
								<input type="password" name="fpass" id="fpass">
								<span class="help-inline" id="fpass_err"></span>
							</div>
						</div>
						<div class="control-group" id="fdesc_gr">
							<label class="control-label" for="fdesc">Description for password</label>
							<div class="controls">
								<input type="text" name="fdesc" id="fdesc">
								<span class="help-inline" id="fdesc_err"></span>
								<p class="help-block">Use this field to describe password. For example: Pass for Mike</p>
							</div>
						</div>
					</div>
					
					<div class="form-actions">
						<input type="submit" class="btn btn-primary" value="Create folder" />
						<a href="index.php?p=admin&amp;do=folders" class="btn">Cancel</a>
					</div>
				</fieldset>
			</form>';
		}
		
		if($task == "addFolder" || $task == ""){	
			if($task == "addFolder"){
				$passid = $_POST['passid'];
				if($passid == ""){
					$passid = false;
				}
				$add = createFolder($_POST['fname'], $_POST['fpass'], $_POST['fdesc'], $passid);
				if($add == true){
					$msg = "folderAddSuccess";
				}else{
					$msg = "folderAddFail";
				}
			}
		
			//show error message if there is one
			showErrorMsg($msg);	
			
			echo '<p><a href="index.php?p=admin&amp;do=folders&amp;task=addFolderForm" class="btn btn-small"><i class="icon-plus-sign"></i> New folder</a></p>';
			echo '<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Slug</th>
						<th>Users</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>';
				$folders = getFolders();
				for($i = 0; $i < count($folders); $i++){
					echo "<tr>";
					echo "<td>".$folders[$i]['p_name']."</td>";
					echo "<td>".$folders[$i]['p_slug']."</td>";
					echo "<td>";
						$fpasses = getFolderPasses($folders[$i]['p_id']);
						for($j = 0; $j < count($fpasses); $j++){
							echo $fpasses[$j]['pa_desc'];
							if(($j+1) != count($fpasses)){
								echo "<br />";
							}
						}
					echo "</td>";
					echo "<td></td>";
					echo "</tr>";
				}
			echo '</tbody>
			</table>';
		}
	}
}else{
	echo "<h1>Nothing to see here!</h1>";
}
?>