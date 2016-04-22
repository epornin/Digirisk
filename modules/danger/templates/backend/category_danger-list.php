<?php if ( !defined( 'ABSPATH' ) ) exit; ?>
<?php if ( !empty( $danger_category_list ) ) : ?>
	<input type="hidden" name="risk_danger_id" />
	<toggle class="wp-digi-summon-list" data-target="wp-digi-select-list">Select a risk <i class="dashicons dashicons-arrow-down"></i></toggle>
	<div class="wp-digi-select-list digi-popup grid icon hidden">
	<?php foreach( $danger_category_list as $danger_category ): ?>
		<ul>
			<?php global $wpdigi_danger_ctr; $danger_of_category = $wpdigi_danger_ctr->index( array( 'parent' => $danger_category->id, ) ); ?>
			<?php if( !empty( $danger_of_category ) ): ?>
				<?php foreach( $danger_of_category as $danger ): ?>
					<li class="child" data-id="<?php echo $danger->id; ?>"><?php echo wp_get_attachment_image( $danger->option['thumbnail_id'], 'thumbnail', false, array( 'title' => $danger->name ) ); ?></li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	<?php endforeach; ?>
	</div>
<?php else: ?>
	<?php _e( 'There are no danger category to display here. Please create some danger category before.', 'wpdigi-i18n' ); ?>
<?php endif; ?>