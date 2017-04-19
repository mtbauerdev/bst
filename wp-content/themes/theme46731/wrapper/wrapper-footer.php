<?php /* Wrapper Name: Footer */ ?>
<div class="footer-widgets">
	<div class="row ">
			<div class="span3" data-motopress-type="dynamic-sidebar" data-motopress-sidebar-id="footer-sidebar-1">
				<?php dynamic_sidebar("footer-sidebar-1"); ?>
			</div>
			<div class="span1"></div>
			<div class="span3" data-motopress-type="dynamic-sidebar" data-motopress-sidebar-id="footer-sidebar-2">
				<?php dynamic_sidebar("footer-sidebar-2"); ?>
			</div>
			<div class="span1"></div>
			<div class="span4" data-motopress-type="dynamic-sidebar" data-motopress-sidebar-id="footer-sidebar-3">
				<?php dynamic_sidebar("footer-sidebar-3"); ?>
			</div>
		<div class="span12" data-motopress-type="dynamic-sidebar" data-motopress-sidebar-id="footer-sidebar-4">
			<?php dynamic_sidebar("footer-sidebar-4"); ?>
		</div>
		<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-footer-nav.php">
			<?php get_template_part("static/static-footer-nav"); ?>
		</div>
	</div>
</div>
<div class="row copyright">
	<div class="span12 totop-wrap">
		<div id="back-top-wrapper" class="visible-desktop">
			<p id="back-top">
				<a href="#top"><span></span></a>
			</p>
		</div>
	</div>
	<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-footer-text.php">
		<?php get_template_part("static/static-footer-text"); ?>
	</div>
</div>