<?php
function remove_menus () {
global $menu;
	$restricted = array(__(''));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');

register_sidebar( array(
	'name' => __( 'Beneath Rotator Widgets', 'twentyeleven' ),
	'id' => 'beneath-rotator-widgets',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget' => "</li>",
) );

// Add year and month query vars to WP
function parameter_queryvars( $qvars ) {
	array_push( $qvars, 'yr', 'month');
	return $qvars;
}
add_filter('query_vars', 'parameter_queryvars', 10, 1);

?>
