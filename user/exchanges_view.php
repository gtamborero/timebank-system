<div class="container">
<div class="timebank">
<?php
// USER VIEW
error_reporting( E_ERROR | E_WARNING | E_PARSE );
global $bp; //BuddyPress global

include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php');

$config = getConfiguration();

	// If user is set = Show the user. If is not set = Show current user
	if ($_GET['user']!=""){
		$user = $_GET['user'];
	}else{
            $current_user = wp_get_current_user();
            $user = $current_user->user_login;
            //IF buddy
            if(isset($bp)){
                if (bp_get_displayed_user_username()) $user = bp_get_displayed_user_username();
            }
	}
	//get user ID
	$userId = get_user_by( 'login', $user);
	$userId = (string) $userId->ID;
	$pathToTimebank = pathToTimebank();
	$username = "";

	$userData = getUserData ($userId);

	//SHOW USER STATS
	if ($user && $userData){
		//echo '<font style="font-size:24px;"><strong>' . $user . '</strong> ' . __('TimeBank stats', 'timebank') . '</font><br />';
		echo "<div class=userstats>";
			echo '<div>' . __('Balance', 'timebank') . ':<br />' . $userData->balance . ' ' . $config->currency . '</div>';
				echo '<div>' . __('User', 'timebank') . ' ' . $config->currency_limits . ':<br /> ' . __('Max', 'timebank') . ': ' . $userData->max_limit . ' - ' . __('Min', 'timebank') . ': ' . $userData->min_limit . '</div>';
				echo '<div>' . __('Status', 'timebank') . ': <br />' . $userData->type . '</div>';
				echo '<div>' . __('Totals Sells', 'timebank') . ':<br /> ' . $userData->total_sell_transfers . '</div>';
				echo '<div>' . __('Total Buys', 'timebank') . ':<br /> ' . $userData->total_buy_transfers . '</div>';
			echo '<div style="clear:both; padding:0px; margin:0px; border:0px;"></div>';
		echo '</div><div style="clear:both";></div><br />';
	}else{
		echo '<br /><br />' . __('You need to log to your Wordpress Account before using Timebank!', 'timebank');
	}

	//print button NEW REQUEST + show tables if user is logged in
	if (isWpUser()){

		// PRINT NEW REQUEST BUTTON
		echo '
		<a href="#TB_inline?width=600&height=400&inlineId=showExchangeWindow" class="thickbox">' . __('NEW EXCHANGE', 'timebank') . '</a>';

		// INCLUDE NEW REQUEST html + js code
		include_once( 'new_exchange.php');

		echo '<div id="tbGiven" class="blink">Loading Given ' . $config->currency . ' <img src="' . TB_PLUGIN_URL . '/img/loading.gif" style="width:70px;"></div>';
		echo '<div id="tbReceived" class="blink">Loading Received ' . $config->currency . ' <img src="' . TB_PLUGIN_URL . '/img/loading.gif" style="width:70px;"></div>';
	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			<?php /* AJAX SECURE NONCE */ $nonce = wp_create_nonce( 'list_exchanges' ); ?>
				jQuery.ajax({
						type: "post",
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
						async: true,
						data: { action: 'list_given_exchanges', _ajax_nonce: '<?php echo $nonce; ?>', userId: <?php echo $userId; ?>, currency: '<?php echo $config->currency; ?>' },
						success: function(html){
								jQuery("#tbGiven").html(html + "<br />");
						}
				});
				jQuery.ajax({
						type: "post",
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
						async: true,
						data: { action: 'list_received_exchanges', _ajax_nonce: '<?php echo $nonce; ?>', userId: <?php echo $userId; ?>, currency: '<?php echo $config->currency; ?>' },
						success: function(html){
								jQuery("#tbReceived").html(html + "<br />");
						}
				});
		});
	</script>
	<?php } ?>
</div>

</div>
