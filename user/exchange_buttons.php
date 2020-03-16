<div class="fixedbuttons">
  <div class="bigbutton"><a id="requestbutton" onclick="showExchangeRequestWindow()"><?php _e('Request', 'timebank') ?> ></a></div>
  <div class="bigbutton"><a id="sendbutton" onclick="showExchangeSendWindow()"><?php _e('Send', 'timebank') ?> ></a></div>
</div>

<script>
// Acciones de los dos botones + overlaybox
	function showExchangeRequestWindow(){
		jQuery('#showExchangeWindow').parents('.timebank').addClass('TBoverlay');
		jQuery('#showExchangeWindow').show();
		jQuery(".tbrequest").addClass('show');
		jQuery(".tbrequest").removeClass('hide');
		jQuery(".tbsend").addClass('hide');
		jQuery(".tbsend").removeClass('show');
	}

	function showExchangeSendWindow(){
		jQuery('#showExchangeWindow').parents('.timebank').addClass('TBoverlay');
		jQuery('#showExchangeWindow').show();
		jQuery(".tbrequest").addClass('hide');
		jQuery(".tbrequest").removeClass('show');
		jQuery(".tbsend").addClass('show');
		jQuery(".tbsend").removeClass('hide');
	}

jQuery(function(){
		jQuery('.TBoverlayClose').click(function(){
			jQuery('.timebank').removeClass('TBoverlay');
			jQuery('#showExchangeWindow').hide();
			jQuery(".tbrequest").addClass('hide');
			jQuery(".tbrequest").removeClass('show');
			jQuery(".tbsend").addClass('show');
			jQuery(".tbsend").removeClass('hide');
		});
});
</script>
