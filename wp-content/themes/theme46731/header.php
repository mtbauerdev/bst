<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" <?php language_attributes();?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes();?>> <!--<![endif]-->
<head>
	<title><?php if ( is_category() ) {
		echo theme_locals("category_for")." &quot;"; single_cat_title(); echo '&quot; | '; bloginfo( 'name' );
	} elseif ( is_tag() ) {
		echo theme_locals("tag_for")." &quot;"; single_tag_title(); echo '&quot; | '; bloginfo( 'name' );
	} elseif ( is_archive() ) {
		wp_title(''); echo " ".theme_locals("archive")." | "; bloginfo( 'name' );
	} elseif ( is_search() ) {
		echo theme_locals("fearch_for")." &quot;".esc_html($s).'&quot; | '; bloginfo( 'name' );
	} elseif ( is_home() || is_front_page()) {
		bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
	}  elseif ( is_404() ) {
		echo theme_locals("error_404")." | "; bloginfo( 'name' );
	} elseif ( is_single() ) {
		wp_title('');
	} else {
		wp_title( ' | ', true, 'right' ); bloginfo( 'name' );
	} ?></title>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="dcterms.title" content="B Street Theatre | Sacramento Theater | Sacramento Events" />
	<meta name="dcterms.identifier" content="https://bstreettheatre.org/" />
	<meta name="dcterms.description" content="Recognized as one of Northern California’s top professional theatres, B Street Theatre is located on B Street in Midtown Sacramento!" />
	<meta name="dcterms.language" content="en-US" />
	<meta name="dcterms.publisher" content="https://bstreettheatre.org/" />
	<meta name="dcterms.coverage" content="World" />
	<meta name="dcterms.type" content="Text" />
	<meta name="dcterms.format" content="text/html" />


	<link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="dns-prefetch" href="//netdna.bootstrapcdn.com">
        <link href="https://plus.google.com/107995331007672578227/" rel="publisher" />
	<?php if(of_get_option('favicon') != ''){ ?>
	<link rel="icon" href="<?php echo of_get_option('favicon', '' ); ?>" type="image/x-icon" />
	<?php } else { ?>
	<link rel="icon" href="<?php echo CHILD_URL; ?>/favicon.ico" type="image/x-icon" />
	<?php } ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo CHILD_URL; ?>/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo CHILD_URL; ?>/bootstrap/css/responsive.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo PARENT_URL; ?>/css/camera.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="stylesheet" type="text/css" media="print" href="<?php echo get_stylesheet_directory_uri();?>/print.css" />

	<?php
		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head();
	?>
	<?php
	/* The HTML5 Shim is required for older browsers, mainly older versions IE */ ?>
	<!--[if lt IE 8]>
	<div style=' clear: both; text-align:center; position: relative;'>
		<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" alt="" /></a>
	</div>
	<![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!-->
	<script src="<?php echo PARENT_URL; ?>/js/jquery.mobile.customized.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('.sf-menu').mobileMenu({defaultText: <?php echo '"'.of_get_option('mobile_menu_label').'"'; ?>});
		});
	</script>
	<!--<![endif]-->
	<script type="text/javascript">
		// Init navigation menu
		jQuery(function(){
		// main navigation init
			jQuery('ul.sf-menu').superfish({
				delay: <?php echo (of_get_option('sf_delay')!='') ? of_get_option('sf_delay') : 600; ?>, // the delay in milliseconds that the mouse can remain outside a sub-menu without it closing
				animation: {
					opacity: "<?php echo (of_get_option('sf_f_animation')!='') ? of_get_option('sf_f_animation') : 'show'; ?>",
					height: "<?php echo (of_get_option('sf_sl_animation')!='') ? of_get_option('sf_sl_animation') : 'show'; ?>"
				}, // used to animate the sub-menu open
				speed: "<?php echo (of_get_option('sf_speed')!='') ? of_get_option('sf_speed') : 'normal'; ?>", // animation speed
				autoArrows: <?php echo (of_get_option('sf_arrows')==false) ? 'false' : of_get_option('sf_arrows'); ?>, // generation of arrow mark-up (for submenu)
				disableHI: true // to disable hoverIntent detection
			});

		//Zoom fix
		//IPad/IPhone
			var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),
				ua = navigator.userAgent,
				gestureStart = function () {
					viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6";
				},
				scaleFix = function () {
					if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {
						viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0";
						document.addEventListener("gesturestart", gestureStart, false);
					}
				};
			scaleFix();
		})
	</script>
	<script src="https://use.fontawesome.com/d5d4aeee39.js"></script>

</head>

<body <?php body_class(); ?>>
<!-- Google Code for Remarketing Tag -->
	<!--
	Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
	-->
	<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 975694705;
	var google_custom_params = window.google_tag_params;
	var google_remarketing_only = true;
	/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/975694705/?value=0&amp;guid=ON&amp;script=0"/>
		</div>
	</noscript>
	<div id="motopress-main" class="main-holder">
		<!--Begin #motopress-main-->
		<header class="motopress-wrapper header">
			<div class="homepage-newsletter">
				<div class="newsletter-text"><h4>Get the latest news and announcements from B Street Theatre! Sign up for our email list!</h4></div>
				<!-- <form class="newsletter-signup" name="subscribe" action="//app.streamsend.com/public/fxvR/goU/subscribe" method="post" target="_blank">
					<input placeholder="E-mail Address" id="sign_up_email_address" type="text" name="person[email_address]"><input id="sign_up_button" type="submit" value="Sign-Up">
				</form> -->
				<div class="newsletter-signup" id="mc_embed_signup"><!-- Begin MailChimp Signup Form -->
				<form action="//bstreettheatre.us7.list-manage.com/subscribe/post?u=e021b46c368324c7bc6207850&amp;id=2a62de3eae" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
				    <div id="mc_embed_signup_scroll">
						<label for="mce-EMAIL">Subscribe to our mailing list</label>
						<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="E-mail Address" required="">
				    <input type="submit" value="Sign-Up" name="subscribe" id="mc-embedded-subscribe" class="button"><div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_e021b46c368324c7bc6207850_2a62de3eae" tabindex="-1" value=""></div>
				    </div>
				</form>
				</div><!--End mc_embed_signup-->
			</div>
			<div class="container">
				<div class="row">
					<div class="header-buttons-wrapper">
						<div class="header-buttons">
							<ul>
								<li id="giftideasitem"><a href="<?php bloginfo('url');?>/buy-tickets/gift-ideas/" title="Current Shows"><img src="<?php bloginfo('url');?>/wp-content/uploads/2014/12/gift-ideas-btn-2x.png" alt="Gift Ideas button" width="138" /><span class="button-text giftideas">Gift Ideas</span></a></li>
								<li><a href="<?php bloginfo('url');?>/the-shows/current-shows/" title="Current Shows"><img src="<?php bloginfo('url');?>/wp-content/uploads/2013/11/current-shows-btn-2x.png" alt="Current Shows button" width="138" /><span class="button-text currentshows">Current Shows</span></a></li>
								<li><a href="<?php bloginfo('url');?>/buy-tickets/current-upcoming-shows/" title="Buy Tickets"><img src="<?php bloginfo('url');?>/wp-content/uploads/2013/11/buy-tickets-btn-2x.png" alt="Buy Tickets button" width="138" /><span class="button-text buytickets">Buy Tickets</span></a></li>
								<li><a href="<?php bloginfo('url');?>/buy-tickets/daily-deals/" title="Daily Deals!"><img src="<?php bloginfo('url');?>/wp-content/uploads/2013/11/daily-deals-btn-2x.png" alt="Daily Deals button" width="138" /><span class="button-text dailydeals">Daily Deals</span></a></li>
								<li><a href="<?php bloginfo('url');?>/buy-tickets/subscriptions/" title="Subscribe"><img src="<?php bloginfo('url');?>/wp-content/uploads/2013/11/subscribe-btn-2x.png" alt="Subscribe button" width="138" /><span class="button-text subscribe">Subscribe</span></a></li>
							</ul>
						</div>
						<div class="header-social">
							<ul>
								<li><a href="https://www.youtube.com/bstreettheatre" title="Follow us on YouTube" target="_blank"><img src="<?php bloginfo('url');?>/wp-content/uploads/2013/11/youtube-2x.png" alt="YouTube icon" width="30" /></a></li>
								<li><a href="https://www.facebook.com/bstreettheatre" title="Find us on Facebook" target="_blank"><img src="<?php bloginfo('url');?>/wp-content/uploads/2013/11/facebook-2x.png" alt="Facebook icon" width="30" /></a></li>
								<li><a href="https://twitter.com/bstreettheatre" title="Follow us on Twitter" target="_blank"><img src="<?php bloginfo('url');?>/wp-content/uploads/2013/11/twitter-2x.png" alt="Twitter icon" width="30" /></a></li>
								<li><a href="https://instagram.com/bstreettheatre/" title="Check us out on Instagram" target="_blank"><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/03/instagram-2x.png" alt="Instagram icon" width="30" /></a></li>
							</ul>
						</div>
					</div>
					<div class="<?php echo cherry_get_layout_class( 'full_width_content' ); ?>" data-motopress-wrapper-file="wrapper/wrapper-header.php" data-motopress-wrapper-type="header" data-motopress-id="<?php echo uniqid() ?>">
						<?php get_template_part('wrapper/wrapper-header'); ?>
					</div>
				</div>
			</div>
		</header>
