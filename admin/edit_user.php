<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );

include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');

global $wpdb;
// UPDATE USER
if ($_POST['option']=="edit"){

	
	// INSERT PREPARATION
	$userId    = $_POST['userid'];
	$maxLimit = $_POST['max_limit'];
	$minLimit = $_POST['min_limit'];
	$status    = $_POST['status'];
	
	if (!updateUser ($userId, $maxLimit, $minLimit, $status)){ 
            echo '<center>' .__('Notice: User not modified', 'timebank') . '</center><br />';
        }
};

	$config = getConfiguration();

// USERS TABLE VIEW

	$userId = $_GET['userid']; // Get from navigator
	$userdata = getUserData ($userId)
?>

<!-- TIMEBANK STYLE INIT -->	
<div class="timebank">

	<p style="font-size:20px;"><strong><?php _e('TIME-BANK EDIT USERS', 'timebank'); ?></strong></p>
	<hr>
	
	<form action="" method="post">
		<input name="page" type="hidden" value="timebank_adduser" />
		<input name="option" type="hidden" value="edit" />	
		<input name="userid" type="hidden" value="<?php echo $userdata->fk_wpuser; ?>" />
		<table border=1 width=50% >
		<td><?php _e('User name', 'timebank'); ?></td><td><?php echo userIdToUserName ($userdata->fk_wpuser); ?></td><tr>
		<td><?php _e('Max. Limit', 'timebank'); ?></td><td><input name="max_limit" type="text" size="4" maxlength="4" value="<?php echo $userdata->max_limit; ?>" /></td><tr>
		<td><?php _e('Min. Limit', 'timebank'); ?></td><td><input name="min_limit" type="text" size="4" maxlength="4" value="<?php echo $userdata->min_limit; ?>" /></td><tr>
		<td>Status</td><td>
			<select name="status">
				<?php $statusType = getUserStatusType();
			    foreach ($statusType as $type){
					echo "<option value=$type->id "; 
					if ( $userdata->status == $type->id ) echo "selected";
					echo "> $type->type </option>";
				} 
				?>
			</select>
		</td><tr>
                <td colspan="2" style="background-color:#fff;">	<input type="submit" value="<?php _e('SAVE DATA', 'timebank'); ?>" style="float:right;" class="button" /></td>
		</table>
	</form>

<!-- TIMEBANK STYLE CLOSE -->	
</div>