<?php
/**
 * Plugin Name: Isotope Karol
 * Plugin URI: http://www.karolszczesny.com
 * Description: Plugin korzysta z Jquery isotope i wyświetla masonary listę elementów. Wystarczy użyć shortcode [isotopeshorcode]
 * Version: 1.0.0
 * Author: Karol Szczesny
 * Author URI: http://www.karolszczesny.com
 * License: GPL2
 */
/*
echo '<link rel="stylesheet" href="' . plugins_url('isotope/css/style.css') . '" type="css" />';
// Register Script
function isotope_plugin_scripts() {
    wp_register_script( 'isotope', plugins_url('script/script.js', __FILE__), '1.0.0',true  ); 
    wp_enqueue_script( 'isotope' );
     wp_register_script( 'isotope.pkgd',  plugin_dir_url( __FILE__ ) . 'script/isotope.pkgd.js' );
    wp_enqueue_script( 'isotope.pkgd' );
}
add_action( 'wp_enqueue_scripts', 'isotope_plugin_scripts' );
*/

function my_isotope_style () {
wp_register_style( 'isotope', plugins_url('isotope/css/style.css')  );
    wp_enqueue_style('isotope');
}
add_action( 'wp_enqueue_scripts', 'my_isotope_style' );

function add_isotope() {
    wp_register_script( 'isotope', plugins_url('/script/isotope.pkgd.js',__FILE__), array('jquery'),  true );
    wp_register_script( 'isotope-init', plugins_url('/script/script.js',__FILE__), array('jquery', 'isotope'),  true );
    
 	 wp_enqueue_script( 'isotope' );
    wp_enqueue_script('isotope-init');
   
}
 
add_action( 'wp_enqueue_scripts', 'add_isotope' );





function my_isotope_func() {
	ob_start();
	?> 



    


<ul id="filters">
    <li><a href="#" data-filter="*" class="selected">Wszystko</a></li>
	<?php 
		$terms = get_terms("portfolio-type"); // get all categories, but you can use any taxonomy
		$count = count($terms); //How many are they?
		if ( $count > 0 ){  //If there are more than 0 terms
			foreach ( $terms as $term ) {  //for each term:
				echo "<li><a href='#' data-filter='.".$term->slug."'>" . $term->name . "</a></li>\n";
				//create a list item with the current term slug for sorting, and name for label
			}
		} 
	?>
</ul>

<?php $the_query = new WP_Query( array( 'post_type' => 'portfolio' , 'posts_per_page=50') ); //Check the WP_Query docs to see how you can limit which posts to display ?>
<?php if ( $the_query->have_posts() ) : ?>
    <div id="isotope-list">
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
	$termsArray = get_the_terms( get_the_ID(), "portfolio-type" );  //Get the terms for this particular item
	$termsString = ""; //initialize the string that will contain the terms
		foreach ( $termsArray as $term ) { // for each term 
			$termsString .= $term->slug.' '; //create a string that has all the slugs 
		}
	?> 
	<div class="<?php echo $termsString; ?> item"> <?php // 'item' is used as an identifier (see Setp 5, line 6) ?>
		
	       
           <?php if ( has_post_thumbnail() ) {

?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
	<img src="<?php the_post_thumbnail_url(); ?>"/>
	</a><h4><?php the_title(); ?></h4> <?php
} else { ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/default-image.jpg'; ?>">
</a><h4><?php the_title(); ?></h4> 

<?php } ?>

	</div> <!-- end item -->
    <?php endwhile;  ?>
    </div> <!-- end isotope-list -->
<?php endif; ?><?php
	return ob_get_clean();
}

add_shortcode( 'isotopeshorcode', 'my_isotope_func' );