<?php

// TIMEBANK AJAX FUNCTIONS

// Esta función se debe declarar también en timebank.php
function ajax_new_transfer() {
    check_ajax_referer( "new_transfer" );
	//If receiving buyer or seller get the other one
	if (isset($_POST['buyerUserId'])){
    $buyerUserId = $_POST['buyerUserId'];
  }else{
    $buyerUserId = userNameToUserId($_POST['buyerUserName']);
  }

	if (isset($_POST['sellerUserId'])){
    $sellerUserId = $_POST['sellerUserId'];
  }else{
    $sellerUserId = userNameToUserId($_POST['sellerUserName']);
  }
    newExchange ($sellerUserId, $buyerUserId, $_POST["amount"], $_POST["description"], $_POST["createdBy"]);
    die(); //hack for ajax not echo 0
}

// Esta función se debe declarar también en timebank.php
function ajax_validate_transfer() {
    check_ajax_referer( "validate_transfer" );
    updateExchangeStatus($_POST["exchangeId"], ACCEPTED );
    die(); //hack for ajax not echo 0
}

// Esta función se debe declarar también en timebank.php
function ajax_reject_transfer() {
    check_ajax_referer( "reject_transfer" );
    updateExchangeStatus($_POST["exchangeId"], REJECTED );
    die(); //hack for ajax not echo 0
}

// Esta función se debe declarar también en timebank.php
function ajax_comment_transfer() {
    check_ajax_referer( "comment_transfer" );
    rateExchange($_POST["exchangeId"], $_POST["rate"], $_POST["comment"], $_POST["concept"]);
    die(); //hack for ajax not echo 0
}

// Esta función se debe declarar también en timebank.php
function ajax_list_given_exchanges(){

	$config = getConfiguration();

	//print button ACCEPT / REJECT + ajax function
	include_once( 'validate_transfer.php');
	//print button COMMENT + ajax function
	include_once( 'comment_transfer.php');

	check_ajax_referer( "list_exchanges" );
	$userId = $_POST["userId"];

	//SHOW PURCHASE
	$result = purchaseView ($userId);

	echo '<div class="buys">';
	echo '<br /><div style="font-size:20px; margin-bottom:15px; text-align:center;"><strong>' . __('Time Given', 'timebank') . '</strong></div>';
	echo '<table style="background-color:#fff; width:99%;">';
	echo "<th>" . __('Date', 'timebank') . "</th>";
	echo "<th>" . __('Recipient', 'timebank') . "</th>";
	echo "<th>" . __('Concept', 'timebank') . "</th>";
	echo "<th>" . $_POST["currency"] . "</th>";
	echo "<th>" . __('Rating', 'timebank') . "</th>";
	echo "<th>" . __('Comment', 'timebank') . "</th>";
	echo "<th>" . __('Status', 'timebank') . "</th>";
	echo "<tr>";


	foreach ($result as $res) {
		echo "<td>" . $res->datetime_created . "</td>";
		echo "<td id=user_value" . $res->id . ">" . $res->user_login . "</td>";
		echo "<td id=concept_value" . $res->id . ">" . $res->concept . "</td>";
		echo "<td id=amount_value" . $res->id . ">" . $res->amount . "</td>";
		echo "<td id=rating_value" . $res->id . "><div class=rateit data-rateit-value=" . $res->rating_value . " data-rateit-ispreset=true data-rateit-readonly=true></div></td>";
		echo "<td id=rating_comment" . $res->id . ">" . $res->rating_comment . "</td>";

		//View $options if user is user viewer
		if ($res->id_buyer == get_current_user_id()){

			//Pending
			if ($res->status == "1") { echo "<td id=status" . $res->id . " class=\"alert\">" . __('Pending', 'timebank') . "<br />"; if ($res->id_buyer != $res->created_by) echo '<a id='. $res->id .' href=#TB_inline?width=460&height=250&inlineId=inline2 class="thickbox validate">' . __('Accept / Reject', 'timebank') . '</a>'; }
			//Accepted
			if ($res->status == "2") { echo "<td id=status" . $res->id . " class=accepted>" . __('Accepted', 'timebank') . "<br /><a id=". $res->id ." href=#TB_inline?width=460&height=320&inlineId=inline3 class=\"thickbox comment\">" . __('Comment', 'timebank') . '</a>'; }
			//Completed
			if ($res->status == "3") { echo "<td id=status" . $res->id . " class=completed>" . __('Completed', 'timebank') . "<a>"; }
			//Rejected
			if ($res->status == "4") { echo "<td id=status" . $res->id . " class=rejected>" . __('Rejected', 'timebank') . "<a>"; }
			//Cancelled
			if ($res->status == "5") { echo "<td id=status" . $res->id . " class=rejected>" . __('Cancelled', 'timebank') . "<a>"; }

			echo "</td>";

		}else{

			//Pending
			if ($res->status == "1") { $options = "<td id=status" . $res->id . ">" . __('Pending', 'timebank'); }
			//Accepted
			if ($res->status == "2") { $options = "<td id=status" . $res->id . ">" . __('Accepted', 'timebank'); }
			//Completed
			if ($res->status == "3") { $options = "<td id=status" . $res->id . ">" . __('Completed', 'timebank'); }
			//Rejected
			if ($res->status == "4") { $options = "<td id=status" . $res->id . ">" . __('Rejected', 'timebank'); }
			//Cancelled
			if ($res->status == "5") { $options = "<td id=status" . $res->id . ">" . __('Cancelled', 'timebank'); }

			echo "$options</td>";

		}
		echo "<tr>";
	}
	echo '</table>';
	echo "</div>
	 <script src=" . TB_PLUGIN_URL . "/user/jsclick_functions.js></script> ";
	die(); //hack for ajax not echo 0
}

function ajax_list_received_exchanges(){
	$config = getConfiguration();

	check_ajax_referer( "list_exchanges" );
	$userId = $_POST["userId"];

	// SHOW SALES
	$result = salesView ($userId);

	echo '<div class="sales">';
	echo '<div style="font-size:20px; margin-bottom:15px; text-align:center;"><strong>' . __('Time Received', 'timebank') . '</strong></div>';
	echo '<table style="background-color:#fff; width:99%;">';
	echo "<th>" . __('Date', 'timebank') . "</th>";
	echo "<th>" . __('Sender', 'timebank') . "</th>";
	echo "<th>" . __('Concept', 'timebank') . "</th>";
	echo "<th>" . $_POST["currency"] . "</th>";
	echo "<th>" . __('Rating', 'timebank') . "</th>";
	echo "<th>" . __('Comment', 'timebank') . "</th>";
	echo "<th>" . __('Status', 'timebank') . "</th>";
	echo "<tr>";

	foreach ($result as $res) {

		echo "<td>" . $res->datetime_created . "</td>";
		echo "<td id=user_value" . $res->id . ">" . $res->user_login . "</td>";
		echo "<td id=concept_value" . $res->id . ">" . $res->concept . "</td>";
		echo "<td id=amount_value" . $res->id . ">" . $res->amount . "</td>";
		echo "<td id=rating_value" . $res->id . "><div class=rateit data-rateit-value=" . $res->rating_value . " data-rateit-ispreset=true data-rateit-readonly=true></div></td>";
		echo "<td id=rating_comment" . $res->id . ">" . $res->rating_comment . "</td>";

		//View $options if user is user viewer
		if ($res->id_seller == get_current_user_id()){
			//Pending
			if ($res->status == "1") { echo "<td id=status" . $res->id . " class=\"alert\">" . __('Pending', 'timebank') . "<br />"; if ($res->id_seller != $res->created_by) echo '<a id='. $res->id .' href=#TB_inline?width=460&height=250&inlineId=inline2 class="thickbox validate">' . __('Accept / Reject', 'timebank') . '</a>';
				echo "</td>";
			}else{
				//Accepted
				if ($res->status == "2") { $options = "<td id=status" . $res->id . " class=accepted>" . __('Accepted', 'timebank'); }
				//Completed
				if ($res->status == "3") { $options = "<td id=status" . $res->id . " class=completed>" . __('Completed', 'timebank'); }
				//Rejected
				if ($res->status == "4") { $options = "<td id=status" . $res->id . " class=rejected>" . __('Rejected', 'timebank'); }
				//Cancelled
				if ($res->status == "5") { $options = "<td id=status" . $res->id . " class=rejected>" . __('Cancelled', 'timebank'); }

			echo "$options</td>";
			}
		}else{

			//Pending
			if ($res->status == "1") { $options = "<td id=status" . $res->id . ">" . __('Pending', 'timebank'); }
			//Accepted
			if ($res->status == "2") { $options = "<td id=status" . $res->id . ">" . __('Accepted', 'timebank'); }
			//Completed
			if ($res->status == "3") { $options = "<td id=status" . $res->id . ">" . __('Completed', 'timebank'); }
			//Rejected
			if ($res->status == "4") { $options = "<td id=status" . $res->id . ">" . __('Rejected', 'timebank'); }
			//Cancelled
			if ($res->status == "5") { $options = "<td id=status" . $res->id . ">" . __('Cancelled', 'timebank'); }

			echo "$options</td>";

		}
		echo "<tr>";
	}
	echo '</table>';
	echo "</div><br /><br />
	 <script src=" . TB_PLUGIN_URL . "/user/jsclick_functions.js></script> ";

	//Activamos rate it despues de la carga
	//invoke it on all .rateit elements.
	die(); //hack for ajax not echo 0
}
