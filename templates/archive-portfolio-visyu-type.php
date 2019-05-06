<?php
    /**
     * The template for displaying all archive Testimonial
     *
     */
    defined('ABSPATH') || exit;
    get_header();
    
    $queryTaxonomy = array_key_exists('taxonomy', $_GET);
    if ($queryTaxonomy && $_GET['taxonomy'] == '') {
        wp_redirect(get_site_url().'/portfolio');
        exit;
    }
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
            
            $head_content = '
    <div class="container">
    <section id="work" class="portfolio-mf sect-pt4 route">
        <div class="container">
            <div class="row">
                <div class="container">
                    <div class="title-box text-center">
                        <h3 class="title-a">
                            UNSERE PROJEKTE
                        </h3>
                        <div class="line-mf"></div>
                    </div>
                </div>
            </div>
            ';
            echo $head_content;
        ?>
        <?php $taxonomies = get_terms('project_category'); ?>
        
        <form action="<?php bloginfo('url'); ?>/portfolio/" method="get">
            <div class="form-row align-items-center">
                <div class="col-auto my-3">
                    <select name="taxonomy" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                        <option value='' selected>Alle Kategorien..</option>
                        <?php foreach ($taxonomies as $taxonomy) { ?>
                            <option value="<?= $taxonomy->slug; ?>"><?= $taxonomy->name; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
</form>

<?php
    
    if ($queryTaxonomy) {
        $taxQuery = array(
            array(
                'taxonomy' => 'project_category',
                'field' => 'slug',
                'terms' => $_GET['taxonomy']
            )
        );
    }
    
    $args = array('post_type' => 'portfolio-visyu-type', 'tax_query' => $taxQuery, 'posts_per_page' => 10);
    $loop = new WP_Query($args);
    $projects = [];
    
    while ($loop->have_posts()) : $loop->the_post();
        
        $post_id = $post->ID;
        $portfolio = esc_html(get_post_field('post_content', $post_id));
        $project = esc_html(get_post_meta($post_id, 'portfolio_project', true));
        $category = esc_html(get_post_meta($post_id, 'portfolio_category', true));
        $client = esc_html(get_post_meta($post_id, 'portfolio_client', true));
        $img = esc_html(get_post_meta($post_id, 'image_portfolio', true));
        $title = esc_html(get_the_title($post_id));
        $resume = esc_html(get_the_excerpt($post_id));
        
        //Array for save of the one Project
        $single_project = array(
            'img' => $img,
            'title' => $title,
            'company' => $client,
            'category' => $category,
            'client' => $client,
            'content' => $portfolio,
            'resume' => $resume,
            'project' => $project,
            'id' => $post_id,
        
        );
        
        array_push($projects, $single_project);
        
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
    
    endwhile;
    
    
    echo '<div class="album py-5 bg-light">
            <div class="container-fluid">
                <div class="row">';
    for ($i = 0; $i < count($projects); $i++) {
        
        echo '
                <div class="col-md-4 ">
                    <div class="work-box text-center">
                        <div class="work-img text-center" >
                            <a href="' . $projects[$i][img] . '" data-lightbox="gallery-mf">
                            <img src="' . $projects[$i][img] . '" alt="" class="img-fluid"  >
                            </a>
                        </div>
                        <div class="work-content text-center">
                            <div class="row">
                                <div class="container">
                                    <h2 class="w-title text-center">' . $projects[$i][title] . '</h2>
                                    <div class="w-more text-center">
                                        <span class="w-date justify-content-center text-center">' . $projects[$i][resume] . '</span>
                                    </div>
                                     <div class="btn-group">
                                      <a href="' . get_post_permalink($projects[$i][id]) . '" >
                                    <button type="button" class="btn-sm btn-outline-secondary">Mehr</button>
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           ';
    }
    
    $footer_content = '
                </div>
            </div>
        </div>
    </div>
     ';
    
    echo $footer_content;
?>

</main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>

