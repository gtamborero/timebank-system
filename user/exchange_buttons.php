<div class="fixedbuttons">
  <div class="bigbutton"><a id="requestbutton"><?php _e('Request', 'timebank') ?> ></a></div>
  <div class="bigbutton"><a id="sendbutton"><?php _e('Send', 'timebank') ?> ></a></div>
</div>

<script>
jQuery(function(){
	jQuery('#requestbutton').click(function() {
		jQuery(".tbrequest").addClass('show');
		jQuery(".tbrequest").removeClass('hide');
		jQuery(".tbsend").addClass('hide');
		jQuery(".tbsend").removeClass('show');
	});
	jQuery('#sendbutton').click(function() {
		jQuery(".tbrequest").addClass('hide');
		jQuery(".tbrequest").removeClass('show');
		jQuery(".tbsend").addClass('show');
		jQuery(".tbsend").removeClass('hide');
	});
})
</script>
