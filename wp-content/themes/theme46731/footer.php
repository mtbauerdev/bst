		<footer class="motopress-wrapper footer">
			<div class="container">
				<div class="row">
					<div class="span12" data-motopress-wrapper-file="wrapper/wrapper-footer.php" data-motopress-wrapper-type="footer" data-motopress-id="<?php echo uniqid() ?>">
						<?php get_template_part('wrapper/wrapper-footer'); ?>
					</div>
				</div>
			</div>
		</footer>
		<!--End #motopress-main-->
	</div>

	<?php if(of_get_option('ga_code')) { ?>
		<script type="text/javascript">
			<?php echo stripslashes(of_get_option('ga_code')); ?>
		</script>
		<!-- Show Google Analytics -->
	<?php } ?>
	<?php wp_footer(); ?> <!-- this is used by many Wordpress features and for plugins to work properly -->
	
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$('.beneath-rotator-boxes li').hover(function() {
				$(this).toggleClass('highlight');
			});
		});
	</script>
	
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.breadcrumb a.Buy.Tickets').attr('href','javascript: return false;');
			$('.breadcrumb a.The.Shows').attr('href','javascript: return false;');
			$('.breadcrumb a.About.Us').attr('href','javascript: return false;');
			$('.breadcrumb a.Take.Classes').attr('href','javascript: return false;');
			$('.breadcrumb a.For.Schools').attr('href','javascript: return false;');
			$('.breadcrumb a.New.Theatre').attr('href','javascript: return false;');
			$('.breadcrumb a.Support.Us').attr('href','javascript: return false;');
		});
	</script>

	<script type="text/javascript">
	// Classie.js 
	!function(s){"use strict";function e(s){return new RegExp("(^|\\s+)"+s+"(\\s+|$)")}function n(s,e){var n=a(s,e)?c:t;n(s,e)}var a,t,c;"classList"in document.documentElement?(a=function(s,e){return s.classList.contains(e)},t=function(s,e){s.classList.add(e)},c=function(s,e){s.classList.remove(e)}):(a=function(s,n){return e(n).test(s.className)},t=function(s,e){a(s,e)||(s.className=s.className+" "+e)},c=function(s,n){s.className=s.className.replace(e(n)," ")});var i={hasClass:a,addClass:t,removeClass:c,toggleClass:n,has:a,add:t,remove:c,toggle:n};"function"==typeof define&&define.amd?define(i):s.classie=i}(window);
	</script>

	<script type="text/javascript">
		function init() {
	        window.addEventListener('scroll', function(e){
	            var distanceY = window.pageYOffset || document.documentElement.scrollTop,
	                shrinkOn = 170,
	                header = document.querySelector("body");
	            if (distanceY > shrinkOn) {
	                classie.add(header,"fix-nav");
	            } else {
	                if (classie.has(header,"fix-nav")) {
	                    classie.remove(header,"fix-nav");
	                }
	            }
	        });
	    }
	    window.onload = init();
    </script>
</body>
</html>