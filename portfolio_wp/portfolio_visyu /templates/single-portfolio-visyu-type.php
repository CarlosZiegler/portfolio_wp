<?php
    /**
     * The template for displaying all single portfolio
     *
     */
    defined('ABSPATH') || exit;
    get_header();
    
    ?>

        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        
        <?php
     
            while ( have_posts() ) :
                the_post();
    
                global $category;
                $post_id = $post->ID;
                $portfolio = esc_html(get_post_field('post_content', $post_id));
                $project = esc_html(get_post_meta($post_id, 'portfolio_project', true));
                $client = esc_html(get_post_meta($post_id, 'portfolio_client', true));
                $img = esc_html(get_post_meta($post_id, 'image_portfolio', true));
                $title = esc_html(get_the_title($post_id));
                $resume = esc_html(get_the_excerpt($post_id));
    
                /** @var TYPE_NAME $category_array */
                $category_array = get_the_terms($post->ID, 'project_category');
                $text = '';
                foreach ($category_array as $category_single):
                    $text .= $category_single->name . ', ';
                endforeach;
                $text = rtrim($text, ', ');
                // if Client field is empty, not print in Frontend
                if (trim($client )!= ''){
                    $client_label = 'Client : ';
                }
                // if Category field is empty, not print in Frontend
                if (sizeof($category_array )!= 0){
                    $category_label = 'Category : ';
                }
               
                $display='
          <div class="row center">
            <div class="col-md-11 col-center m-auto">
                <!-- Project Details Go Here -->
                <h2 class="text-uppercase text-center">' . $title . '</h2>
                <img class="img-fluid d-block mx-auto" src="' . $img . '" alt="">
                <p class="text-justify">' . $portfolio . '</p>
                <ul class="list-inline">
                  <li class="font-weight-bold" >'.$client_label . $client . '</li>
                  <li class="font-weight-bold" >' .$category_label. $text. '</li>
                </ul>
            </div>
          </div>
       
                ';
                echo $display;
    
                the_post_navigation(
                    array(
                        'next_text' => '<span class="meta-nav" aria-hidden="true">' .  'Next: ' . '</span> ' .
                            '<span class="post-title">%title</span>',
                        'prev_text' => '<span class="meta-nav" aria-hidden="true">' . 'Previous: ' . '</span> ' .
                            '<span class="post-title">%title</span>',
                        'screen_reader_text' => __( 'Projects' ),
                    )
                );
            endwhile;
        ?>
            </main><!-- .site-main -->
        </div><!-- .content-area -->

<?php get_footer(); ?>
