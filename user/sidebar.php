<?php
if (is_user_logged_in()){

	if ( !include_once( plugin_dir_path( __FILE__ ) . '../common/includes.php')) echo "timebank include error";

// PREPARATION
	$config = getConfiguration();
    $userId = get_current_user_id();
    $current_user = wp_get_current_user();
    $userName = $current_user->user_login;
    $pathToTimebank = pathToTimebank();

	if ($userData = getUserData ($userId)){
		echo "<div class=userstats>";
		
		// PRINT NEW REQUEST BUTTON	
		echo '<a href="#TB_inline?width=600&height=400&inlineId=showExchangeWindow" class="thickbox">' . __('NEW EXCHANGE', 'timebank') . '</a>';

		// INCLUDE NEW REQUEST html + js code	
		include_once( plugin_dir_path( __FILE__ ) . 'new_exchange.php');
			
		// PRINT BUTTONS
		echo '<br /><a href="' . $pathToTimebank . '">' . __('Go to your Timebank', 'timebank') . '</a>';
		echo '<br /><div>' . __('Balance', 'timebank') . ": $userData->balance $config->currency</div>";
		echo "<div>" . __('Total Sales', 'timebank') . ": $userData->total_sell_transfers</div>";
		echo "<div>" . __('Total Buys', 'timebank') . ": $userData->total_buy_transfers</div>";
		//echo "<div>" . __('Status', 'timebank') . ": $userData->type</div>"; 
		echo '</div><div style="clear:both";></div><br />';
	}

}else{
	//echo '<center><strong>You need to log to your Wordpress Account before using Timebank!</strong><br /></center>';
}
?>