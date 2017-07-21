
jQuery(document).ready(function($) 
{

	$(".dashicons-set div.dashicons").click(function(e)
	{
	
		var pt = $(this).data('posttype');
		var before_content = $(this).data('before');
	
		if( $(this).hasClass('selected') )
		{
			// REMOVE CURRENT SELECTED

			$(this).removeClass('selected');
			$("#dashicons-liveview-" + pt).html("#adminmenu #menu-posts-" + pt + " div.wp-menu-image:before { content: '';");

			var data = 
			{
				action: 'dashicon-cpt-remove',
				post_type: pt,
				before_content: before_content
			};
			
			$.post(ajaxurl, data, function() 
			{
				// no response
				
			});
		}
		else 
		{
			// SET ICON
			
			// REMOVE SELECTED CLASS FOR THIS POST TYPE
			$(".dashicons-cpt-wrap .post-type." + pt + " .dashicons").removeClass('selected');
			$(this).addClass('selected');
			
			var data = 
			{
				action: 'dashicon-cpt-save',
				post_type: pt,
				before_content: before_content
			};
	
			$.post(ajaxurl, data, function() 
			{
				// no response
				
			});
			
			$("#dashicons-liveview-" + pt).html("#adminmenu #menu-posts-" + pt + " div.wp-menu-image:before { content: '\\" + before_content + "';");
		}

		e.preventDefault();
	});
});