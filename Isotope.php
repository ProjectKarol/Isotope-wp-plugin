<?php
/**
 * Plugin Name: Isotope-wp-plugin
 * Plugin URI: https://github.com/ProjectKarol/Isotope-wp-plugin
 * Description: Plugin use Jquery isotope to display masonry list. Use shortcode [isotopeshorcode]
  atributt is( post-type='slug'  post-category= "slug" post-category-filter = "slug" )
 * Version: 1.0.2
 * Author: Karol Szczesny
 * Author URI: http://www.karolszczesny.com
 * License: GPL2
 */


//* Prevent direct access to the plugin
if ( ! defined( 'ABSPATH' ) ) {
    die( __( 'Sorry, you are not allowed to access this page directly.', 'genesis-simple-share' ) );
}

function my_isotope_style () {
wp_register_style( 'isotope', plugins_url('isotope-wp-plugin/css/style.css')  );
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





function my_isotope_func($atts) {
  

 $pull_quote_atts = shortcode_atts( array(
       
        /* Basic setting to display post type + category */
        'post-type' => 'post', //default post type to change
        'post-category' => 'kategoria', //default post categoryy assingned for filterss
         
         /*filters attributes */
         'post-category-filter' => '', // for filtering 
         // 'kategorie-filtr' => '15', //default attr 

    ), $atts );

  ob_start();
  ?> 



    


<ul id="filters">
    <li><a href="#" data-filter="*" class="selected">Wszystko</a></li>
  <?php 


    $terms = get_terms( ( $pull_quote_atts[ 'post-category' ] ),array(
   
            
        
        
     //   'include'                => array('10', '11'), //include category you want
       'taxonomy'               => ( $pull_quote_atts[ 'kategorie-filtr' ] ),
      //  'orderby'                => 'name',
      //  'order'                  => 'ASC',
      //  'hide_empty'             => true,
      // 'include'                => array(),
      //  'exclude'                => array(),
      //   'exclude_tree'           => array(),
      //   'number'                 => '',
      //   'offset'                 => '',
      //   'fields'                 => 'all',
      //  'name'                   => '',
        'slug'                   => ( $pull_quote_atts[ 'post-category-filter' ] ),
      //  'hierarchical'           => true,
      //  'search'                 => '',
      //  'name__like'             => '',
      //  'description__like'      => '',
      //  'pad_counts'             => false,
      //  'get'                    => '',
      //  'child_of'               => 0,
      //    'parent'                 => ( $pull_quote_atts[ 'kategorie-filtr' ] ),
      //  'childless'              => false,
      //  'cache_domain'           => 'core',
      //  'update_term_meta_cache' => true,
      // 'meta_query'             => ''

        ));
   


     
    $count = count($terms); //How many are they?
    if ( $count > 0 ){  //If there are more than 0 terms
      foreach ( $terms as $term ) {  //for each term:
        echo "<li><a href='#' data-filter='.".$term->slug."'>" . $term->name . "</a></li>\n";
        //create a list item with the current term slug for sorting, and name for label
      }
    } 
  ?>
</ul>

<?php
$args = array (
  'post_type'    =>   ( $pull_quote_atts[ 'post-type' ] ),
  ( $pull_quote_atts[ 'post-category' ] )    =>   ( $pull_quote_atts[ 'post-category-filter' ] ),
    
     'post_parent'         => 0,
     'include_children'    => true,  
     'posts_per_page'      => '50',
);
 $the_query = new WP_Query( $args ); //Check the WP_Query docs to see how you can limit which posts to display ?>
<?php if ( $the_query->have_posts() ) : ?>
    <div id="isotope-list">
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
  $termsArray = get_the_terms( get_the_ID(), ( $pull_quote_atts[ 'post-category' ] ) );  //Get the terms for this particular item
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