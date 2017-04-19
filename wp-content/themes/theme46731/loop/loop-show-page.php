<?php /* Loop Name: Show */ ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<?php
		$start_date = get_field('show_start_date', false, false);
		$end_date = get_field('show_end_date', false, false);
		$show_star_ratings = get_field('show_star_ratings');

		//make date object
		$date_start = new DateTime($start_date);
		$date_end = new DateTime($end_date);
		$today = date('Ymd');
		$unix_today = strtotime($today);
		$unix_start_date = strtotime($start_date);
	?>

	<div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
		<?php if( get_field('show_image') ): ?><img class="show-image" src="<?php the_field('show_image'); ?>"><?php endif; ?>
		<?php if( get_field('show_author') ): ?><h4><strong>By <?php the_field('show_author'); ?></strong></h4><?php endif; ?>
		<!-- <?php if( get_field('show_dates') ): ?><h4><?php the_field('show_dates'); ?></h4><?php endif; ?><br> -->
		<?php if( get_field('show_start_date') && get_field('show_end_date') ): ?>
			<h4><?php echo $date_start->format('M j, Y'); ?> - <?php echo $date_end->format('M j, Y'); ?></h4>
		<?php endif; ?>
		<h5 style="margin-bottom: 5px !important;">Ratings and Reviews:</h5>
		<div id="ratings">
			<?php if( $unix_today >= $unix_start_date || $show_star_ratings == 'yes' ) : ?>
			<div class="stars"><?php echo do_shortcode('[kkratings]'); ?></div>
			<?php endif; ?>
			<div class="quotes">
			<?php if( get_field('quote_1_content') ): ?>
				<p class="quote">"<?php the_field('quote_1_content'); ?>"</p>
				<p class="quote-attribution">- <a href="<?php the_field('quote_1_link'); ?>" target="_blank"><?php the_field('quote_1_attribution'); ?></a></p>
			<?php endif; ?>

			<?php if( get_field('quote_2_content') ): ?>
				<p class="quote">"<?php the_field('quote_2_content'); ?>"</p>
				<p class="quote-attribution">- <a href="<?php the_field('quote_2_link'); ?>" target="_blank"><?php the_field('quote_2_attribution'); ?></a></p>
			<?php endif; ?>

			<?php if( get_field('quote_3_content') ): ?>
				<p class="quote">"<?php the_field('quote_3_content'); ?>"</p>
				<p class="quote-attribution">- <a href="<?php the_field('quote_3_link'); ?>" target="_blank"><?php the_field('quote_3_attribution'); ?></a></p>
			<?php endif; ?>

			</div>
		</div>
		<h5 style="margin-bottom: 5px !important;">About the Show:</h5>
		<?php the_content(); ?>
		<div class="clear"></div>
		<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?><!--.pagination-->
	<?php if( $unix_today >= $unix_start_date ): comments_template(); endif; ?></div><!--#post-->
<?php endwhile; ?>
