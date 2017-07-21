<?php
/**
 * The Template for displaying all single property listing posts.
 *
 * @package Shape
 * @since Shape 1.0
 */



 ?>
   

 <?php
get_header(); ?>
 
        <div id="primary" class="content-area">
            <div id="contentleft" class="clearfix">
            <div id="content" class="site-content" role="main">
 
            <?php while ( have_posts() ) : the_post(); ?>
 
                
 
               <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>

                    <?php if(get_field('english_translation')) : ?>
                    <p class="entry-subtitle"><?php the_field('english_translation'); ?> </p>
                    <?php endif; ?>

                    <div class="entry-meta">
                        <?php /*pl_property_listing_posted_on();*/ ?>
                    </div><!-- .entry-meta -->
                </header><!-- .entry-header -->

                <section class="pl-property-summary-section">
                    <table class="pl-property-summary-table">
                        <?php if(get_field('condition')) : ?>
                        <tr>
                            <td>Condition</td>
                            <td><?php the_field('condition'); ?> </td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php if(get_field('province')) : ?>
                        <tr>
                            <td>Province</td>
                            <td><?php the_field('province'); ?> </td>
                        </tr>
                        <?php endif; ?>

                        <?php if(get_field('town')) : ?>
                        <tr>
                            <td>Town</td>
                            <td><?php the_field('town'); ?> </td>
                        </tr>
                        <?php endif; ?>

                        <?php if(get_field('price')) : ?>
                        <?php $price = get_field('price'); ?>
                        <tr>
                            <td>Price</td>
                            <td class="pl-property-price"><?php echo number_format(get_field('price')); ?> </td>
                        </tr>
                        <?php endif; ?>




                    </table><!-- .pl-property-summary-table -->

                        
    
                        

                </section><!-- .pl-property-summary-section -->

                <div id="pl-map"></div>
 
                    <div class="entry-content">
                        <div class="pl-property-introduction"><?php echo get_field('introduction') ; ?> </div>
                        <div class="pl-content"><?php the_content(); ?></div>
                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'property-listings' ), 'after' => '</div>' ) ); ?>
                    </div><!-- .entry-content -->
                 
                    <footer class="entry-meta">
                     <?php
                        /* translators: used between list items, there is a space after the comma */
                        $categories_list = get_the_terms($post->ID, 'pl_property_listings_categories');

                        if ($categories_list) :
                        $list = "";
                       foreach ( $categories_list as $term ) {
                        $list = $list . $term->name . ", ";
                            }
                        $list = rtrim($list, ', ');
                    ?>
                    <span class="cat-links">
                        <?php printf( 'Posted in %1$s' , $list); ?>
                    </span>
                    
                    <?php endif; ?>
                    <?php
                        /* translators: used between list items, there is a space after the comma */
                        $tags_list = get_the_terms($post->ID, 'pl_property_listings_tags' );
                        
                        if ( $tags_list ) :
                        $list="";

                        
                       foreach ( $tags_list as $term ) {
                        $list = $list . $term->name. ", " ;
                            }
                         $list = rtrim($list, ', '); 
                        
                    ?>
                    <span class="sep"> | </span>
                    <span class="tag-links">
                        <?php printf( __( 'Tagged: %1$s', 'pl_property-listings' ), $list ); ?>
                    </span>
                    <?php endif; // End if $tags_list ?>   
                    </footer><!-- .entry-meta -->
                </article><!-- #post-<?php the_ID(); ?> -->
 
                
 
            <?php endwhile; // end of the loop. ?>
 
            </div><!-- #content .site-content -->
        </div>
        </div><!-- #primary .content-area -->
 
<?php if ( is_active_sidebar( 'sidebar-pl' )  ) : ?>
    <aside id="secondary" class="sidebar widget-area" role="complementary">
        <?php dynamic_sidebar( 'sidebar-pl' ); ?>
    </aside><!-- .sidebar .widget-area -->
<?php endif; ?>
<?php get_footer(); ?>