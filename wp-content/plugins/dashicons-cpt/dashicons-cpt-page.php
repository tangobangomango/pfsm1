
	<div class="wrap dashicons-cpt-wrap">
	
		<h2><?php _e('Dashicons + Custom Post Types', 'dashicons-cpt'); ?></h2>
	
		<?php foreach( $post_types as $post_type ) : if( !$post_type->show_in_admin_bar) continue; ?>
		
		<div class="post-type <?php echo $post_type->name; ?>">
			<strong><?php echo $post_type->label; ?></strong>
		
			<div class="dashicons-set postbox">
			<?php foreach( $dashicons as $before_content => $dashicon ) : ?>
			
				<?php $selected_class = ( isset($current_icons[ $post_type->name ]) AND $before_content == $current_icons[ $post_type->name ] ) ? "selected " : ""; ?>
				<div class="dashicons <?php echo $selected_class; ?>dashicons-<?php echo $dashicon; ?>" data-before="<?php echo $before_content; ?>" data-posttype="<?php echo $post_type->name; ?>"></div>
			<?php endforeach; ?>
			</div>
		
		</div>
		
		<style id="dashicons-liveview-<?php echo $post_type->name; ?>"></style>

		<?php endforeach; ?>
	
	</div>