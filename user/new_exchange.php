
<!-- START NEW_EXCHANGE.PHP: HTML with ajax -->
<!-- classes tbrequest and tbsend are shown or hidden on click selection -->
<div class="timebank">
    <div id="showExchangeWindow" style="display: none;">
    <div class="TBoverlayClose"> X </div>

		<div style="width:100%; position:relative; float:left;">

			<div id="result"></div>
			<input id="sellerUserId" class="tbrequest" name="sellerUserId" type="hidden" value="<?php echo $user = isWpUser(); ?>" />
			<input id="buyerUserId" class="tbsend" name="buyerUserId" type="hidden" value="<?php echo $user = isWpUser(); ?>" />

			<div id="popupexchange">
				<div class="tbrequest clearboth"><?php _e('Request' , 'timebank') . ' ' . $config->currency . ' ' . __('from', 'timebank') ?>:</div>
				<div class="tbrequest"><input id="buyerUserName" name="buyerusername" placeholder="username" type="text" size="19" maxlength="200" /></div>
				<div class="tbsend clearboth"><?php _e('Send' , 'timebank') . ' ' . $config->currency . ' ' . __('to', 'timebank') ?>:</div>
				<div class="tbsend"><input id="sellerUserName" name="sellerusername" placeholder="username" type="text" size="19" maxlength="20" /></div>

				<div class="clearboth"><?php _e('Amount', 'timebank'); ?>: </div>
				<div><input id="amount" name="amount" type=number step=5 min=0 max=1000 size="4" maxlength="5" style="text-align:right;" /> <?php echo $config->currency ?></div>

				<div class="clearboth"><?php _e('Description', 'timebank'); ?>: </div>
				<div><input id="description" name="description" type="text" size="33" maxlength="37" /></div>

			</div>
		</div>
	</div>

<?php $nonce = wp_create_nonce( 'new_transfer' ); ?>

      <script type="text/javascript">
      jQuery(document).ready(function(){

          /* Clear inputs on load */
          jQuery("#buyerUserName").val(''); jQuery("#amount").val(''); jQuery("#description").val(''); jQuery("#TBREQUEST, TBHIDE").prop('disabled', false);

		// Routine check -> checkdata
		jQuery( ".tbcheck" ).click(checkdata);

		// TB Request insert
          jQuery( "#TBREQUEST" ).click(function() {


				if ( jQuery( "#buyerUserName" ).val() == "" ){
					alert ("<?php _e('Please enter a user name', 'timebank') ?>");
					jQuery("#TBREQUEST, #TBSEND").show();
					return 0;
				}
				if (jQuery.ajax({
					type: "post",
					url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
					async: true,
					data: { action: 'new_transfer', _ajax_nonce: '<?php echo $nonce; ?>', sellerUserId: jQuery( "#sellerUserId" ).val(), buyerUserName: jQuery( "#buyerUserName" ).val(), amount: jQuery( "#amount" ).val(), description: jQuery( "#description" ).val(), createdBy: jQuery( "#sellerUserId" ).val()  },
					success: function(html){
							jQuery("#result").html(html + "<br />").fadeOut().fadeIn();
							//thickbox auto fade: setTimeout( function() {parent.tb_remove(); },4000);
							}
				})){
					jQuery("#result").html("<center><?php _e('Please wait ...', 'timebank') ?> </center><br />").fadeOut().fadeIn();
					setTimeout( function() { jQuery("#TBREQUEST, #TBSEND").show(); },3000);
				}

          });

		// TB Send insert
          jQuery( "#TBSEND" ).click(function() {

				if ( jQuery( "#sellerUserName" ).val() == "" ){
					alert ("<?php _e('Please enter a user name', 'timebank') ?>");
					jQuery("#TBREQUEST, #TBSEND").show();
					return 0;
				}
				if (jQuery.ajax({
					type: "post",
					url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
					async: true,
					data: { action: 'new_transfer', _ajax_nonce: '<?php echo $nonce; ?>', sellerUserName: jQuery( "#sellerUserName" ).val(), buyerUserId: jQuery( "#buyerUserId" ).val(), amount: jQuery( "#amount" ).val(), description: jQuery( "#description" ).val(), createdBy: jQuery( "#buyerUserId" ).val()  },
					success: function(html){
							jQuery("#result").html(html + "<br />").fadeOut().fadeIn();
							//thickbox auto fade: setTimeout( function() {parent.tb_remove(); },4000);
							}
				})){
					jQuery("#result").html("<center><?php _e('Please wait ...', 'timebank') ?></center><br />").fadeOut().fadeIn();
					setTimeout( function() { jQuery("#TBREQUEST, #TBSEND").show(); },5000);
				}

          });

		// Routine check on both forms
		function checkdata() {

			//First prevent multiples clicks during 5seconds
			jQuery("#TBREQUEST, #TBSEND").hide();

              if ( jQuery( "#amount" ).val() < 1 ){
                  alert ("<?php _e('Amount has to be set and positive integer', 'timebank') ?>");
				jQuery("#TBREQUEST, #TBSEND").show();
                  return 0;
              }
              if ( jQuery( "#description" ).val() == "" ){
                  alert ("<?php _e('Please enter description', 'timebank') ?>");
				jQuery("#TBREQUEST, #TBSEND").show();
                  return 0;
              }
			return 1;
		};

		//button click actions
		jQuery(".tbsend").addClass('hide');

      });
      </script>
</div>
<!-- END NEW_REQUEST.PHP -->
