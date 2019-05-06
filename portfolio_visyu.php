<?php
    /**
     * Plugin Name: Portfolio Visyu
     * Plugin URI:  visyu.de
     * Description: Create custom meta boxes and custom fields in WordPress for portfolios.
     * Version:     1.0
     * Author:      Carlos Ziegler
     *
     */
    
    
    defined('ABSPATH') || exit;
    
    
    /**
     * Register Post Type : portfolio-visyu-type
     */
    function register_portfolios_visyu()
    {
        $labels = array(
            'name' => _x('Project', 'portfolios-post'),
            'singular_name' => _x('Portfolio', 'portfolio-post'),
            'menu_name' => _x('Portfolios', 'admin menu'),
            'name_admin_bar' => _x('Project', 'add new on admin bar'),
            'add_new' => _x('Add Project', 'portfolio'),
            'add_new_item' => __('Add New Project'),
            'new_item' => __('New Project'),
            'edit_item' => __('Edit Project'),
            'view_item' => __('View Portfolio'),
            'all_items' => __('All Projects'),
            'search_items' => __('Search Project'),
            'parent_item_colon' => __('Parent Projects:'),
            'not_found' => __('No Project found.'),
            'not_found_in_trash' => __('No Project found in Trash.'),
        
        );
        
        $args = array(
            'labels' => $labels,
            'description' => 'Shortecode for Projects',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'portfolio'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-portfolio   ',
            'supports' => array('title', 'editor')
        
        );
        
        register_post_type('portfolio-visyu-type', $args);
        
    }
    
    add_action('init', 'register_portfolios_visyu');
    
    
    /**
     * Button Add Media in Admin page
     */
    function load_admin_scripts_portfolio()
    {
        
        wp_register_script('portfolio_custom_admin_js', plugins_url('/assets/js/portfolio_admin.js', __FILE__), array('jquery'));
        wp_enqueue_script('portfolio_custom_admin_js');
        
       
    }
    
    add_action('admin_enqueue_scripts', 'load_admin_scripts_portfolio');
    
    
    /**
     * Load javascripts files for Bootstrap and Lightbox galery
     */
    function register_portfolios_script()
    {
    
        
        wp_register_script(
            'portfolio_bootstrap_js',
            plugin_dir_url(__FILE__) . 'assets/bootstrap-4.3.1-dist/js/bootstrap.js'
        );
        wp_enqueue_script('portfolio_bootstrap_js');
    
        wp_register_script(
            'portfolio_bootstrap_bundle_js',
            plugin_dir_url(__FILE__) . 'assets/bootstrap-4.3.1-dist/js/bootstrap.bundle.js'
        );
        wp_enqueue_script('portfolio_bootstrap_bundle_js');
        
    
        wp_register_script(
                'lightbox_portfolio_js',
                plugin_dir_url(__FILE__) . 'lib/lightbox/js/lightbox.js'
        );
        wp_enqueue_script('lightbox_portfolio_js');
    
    }
    
    add_action('wp_enqueue_scripts', 'register_portfolios_script');
    
    
    /**
     * Register Style
     */
    function register_portfolio_style()
    {
    
        
        wp_register_style(
            'lightbox_portfolio',
            plugin_dir_url(__FILE__) . 'lib/lightbox/css/lightbox.min.css'
        
        );
        wp_enqueue_style('lightbox_portfolio');
        
        
        wp_register_style(
            'style_Simple_portfolio',
            plugin_dir_url(__FILE__) . 'assets/css/style.css'
        
        );
        wp_enqueue_style('style_Simple_portfolio');
        
        wp_register_style(
            'bootstrap_portfolio_single',
            plugin_dir_url(__FILE__) . 'assets/css/bootstrap_portfolio_single_example.css'
        
        );
        wp_enqueue_style('bootstrap_portfolio_single');
    
        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.css');
    
        
        wp_register_style(
            'carousel_style_portfolio',
            plugin_dir_url(__FILE__) . 'assets/css/carouselstyle.css'
    
        );
        wp_enqueue_style('carousel_style_portfolio');
    
        
    }
    
    add_action('wp_enqueue_scripts', 'register_portfolio_style');
    
    /**
     * Create Custom Type
     */
    function portfolio_add_custom_box()
    {
        
        $screen = 'portfolio-visyu-type';
        add_meta_box(
            'name_portfolio',
            'Portfolios',
            'portfolio_box_admin',
            $screen
        
        );
        
    }
    
    add_action('add_meta_boxes', 'portfolio_add_custom_box');
    
    /**
     * Form für Postmeta in admin page
     * @param $post
     */
    function portfolio_box_admin($post)
    {
       
        wp_nonce_field(basename(__FILE__), "portfolio-meta-box-nonce");
        
        ?>
        <table class="form-table">
           <tr>
                <th scope="row"><label for="portfolio_image"> Image : </label></th>
                <td><input id="portfolio_image" type="text" name="portfolio_image"
                           value="<?php echo trim(get_post_meta($post->ID, 'image_portfolio', true)) ?>"/>
                    <input class="btn btn-primary" type="button" id="portfolio_upload_button" value="Upload"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="portfolio_project"> Projekt : </label></th>
                <td><input id="portfolio_project" type="text" name="portfolio_project"
                           value="<?php echo trim(get_post_meta($post->ID, 'portfolio_project', true)) ?> "></td>
            </tr>
            <tr>
                <th scope="row"><label for="portfolio_client"> Kunde : </label></th>
                <td><input id="portfolio_client" type="text" name="portfolio_client"
                           value="<?php echo trim(get_post_meta($post->ID, 'portfolio_client', true)) ?> ">
                <td>
            </tr>
            
        
        </table>
        
        <?php
        
    }
    
    /**
     * Save Post Data
     * @param $post_id
     */
    function portfolio_save_postdata($post_id)
    {
        $category_array = get_the_terms($post_id, 'project_category');
        
        if (sizeof($category_array) != 0){
            $category = trim($category_array[0]->name);
        }
        else{
            $category = '';
        }
        
        if (!isset($_POST["portfolio-meta-box-nonce"]) || !wp_verify_nonce($_POST["portfolio-meta-box-nonce"], basename(__FILE__)))
            return;
        
        if (!current_user_can("edit_post", $post_id))
            return;
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        
        if (isset($_POST['portfolio_image'])) {
            update_post_meta(
                $post_id,
                'image_portfolio',
                $_POST['portfolio_image']
            );
        }
        
        if (isset($_POST['portfolio_project'])) {
            update_post_meta(
                $post_id,
                'portfolio_project',
                $_POST['portfolio_project']
            );
        }
        
        if (isset($_POST['portfolio_category'])) {
            update_post_meta(
                $post_id,
                'portfolio_category',
                $category
            );
        }
        
        if (isset($_POST['portfolio_client'])) {
            update_post_meta(
                $post_id,
                'portfolio_client',
                $_POST['portfolio_client']
            );
        }
        
        
        
        
    }
    
    add_action('save_post', 'portfolio_save_postdata');
    
    
    /**
     * Single page Projects
     * @param $single
     * @return string
     */
    function get_custom_single_portfolio($single)
    {
        global $post;
        $plugin_path = plugin_dir_path(__FILE__);
        
        if ($post->post_type == 'portfolio-visyu-type') {
            if (file_exists($plugin_path . '/templates/single-portfolio-visyu-type.php')) {
                
                return $plugin_path . '/templates/single-portfolio-visyu-type.php';
            }
        }
        return $single;
        
    }
    
    add_filter('single_template', 'get_custom_single_portfolio');
    
    /**
     * @param $archive
     * @return string
     */
    function get_custom_archive_portfolio($archive)
    {
        global $post;
        $plugin_path = plugin_dir_path(__FILE__);
        
        if ($post->post_type == 'portfolio-visyu-type') {
            if (file_exists($plugin_path . '/templates/archive-portfolio-visyu-type.php')) {
                
                return $plugin_path . '/templates/archive-portfolio-visyu-type.php';
            }
        }
        return $archive;
        
    }
    
    add_filter('archive_template', 'get_custom_archive_portfolio');
    
    
    /**
     * New length excerpt in Post-Type
     * @param $length
     * @return int
     */
    function custom_excerpt_length($length)
    {
        return 10;
    }
    
    add_filter('excerpt_length', 'custom_excerpt_length');
    
    /**
     * Carrousel Shortcode
     * @param $atts
     */
    function projects_visyu_shortcode()
    {
        
        $slider = get_posts(array('post_type' => 'portfolio-visyu-type', 'posts_per_page' =>5, 'orderby'=> 'date','order'=> 'DESC', 'post_status' =>'publish'));
        
        echo'
        <h2 class="center">Projekte</h2>
            <article id="slider">';
        echo sizeof($slider);
        //control
        if (sizeof($slider)>0){ echo'<input class="input_car"  checked type="radio" name="slider" id="slide1"/>';}
        if (sizeof($slider)>1){ echo'<input class="input_car" type="radio" name="slider" id="slide2"/>';}
        if (sizeof($slider)>2){ echo'<input class="input_car"  type="radio" name="slider" id="slide3"/>';}
        if (sizeof($slider)>3){ echo'<input class="input_car"  type="radio" name="slider" id="slide4"/>';}
        if (sizeof($slider)>4){ echo'<input class="input_car"  type="radio" name="slider" id="slide5"/>';}
        echo'
    
                <div id="slides">
                    <div id="container">
                        <div class="inner">
           ';
        $count=0;
        echo sizeof($slider);
        //Sliders
        foreach ($slider as $slide):
    
            $post_id = $slide->ID;
            
            $img = esc_html(get_post_meta($post_id, 'image_portfolio', true));
            $project = esc_html(get_post_meta($post_id, 'portfolio_project', true));
            
            echo'
            
                        <article>
                            <div class="caption">
                                <bar>' . $project . $count. '</bar>
                            </div>
                             <a href="' . get_post_permalink($post_id) . '" >
                            <img src="' . $img . '"/>
                            </a>
                        </article>
          ';
            $count++;
        endforeach;
    
        echo '

                         </div>
                    </div>
               </div>
               <div id="commands">
                   ';
        
        // Arrows
        if (sizeof($slider)>0){ echo '<label for="slide1"></label>';}
        if (sizeof($slider)>1){ echo '<label for="slide2"></label>';}
        if (sizeof($slider)>2){ echo '<label for="slide3"></label>';}
        if (sizeof($slider)>3){ echo '<label for="slide4"></label>';}
        if (sizeof($slider)>4){ echo '<label for="slide5"></label>';}
       
            echo'
                </div>
                <br>
                <div id="active"> ';
        // Bullets
        if (sizeof($slider)>0){ echo '     <label for="slide1"></label>';}
        if (sizeof($slider)>1){ echo ' <label for="slide2"></label>';}
        if (sizeof($slider)>2){ echo ' <label for="slide3"></label>';}
        if (sizeof($slider)>3){ echo ' <label for="slide4"></label>';}
        if (sizeof($slider)>4){ echo ' <label for="slide5"></label>';}echo'
        
                 </div>
            </article>
             ';
        
              
    }
    
    add_shortcode('projects_visyu_shortcode', 'projects_visyu_shortcode');
    
    
    /**
     * Notice display shortcode
     */
    function this_screen()
    {
        $current_screen = get_current_screen();
        if ($current_screen->id === "edit-portfolio-visyu-type") {
            echo '<div class="updated notice">
                    <p>Use the shortcode <strong><h3>[projects_visyu_shortcode]</h3></strong> to display a carrousel with last 5 projects.</p>
                  </div>';
        }
    }
    
    add_action('current_screen', 'this_screen');
    
    /**
     * Label Title 'PROJECT DESCRIPTION' before EDIT BAR
     */
    function add_title_to_editor()
    {
        global $post;
        if (get_post_type($post) == 'portfolio-visyu-type') { ?>
            <script> jQuery('<h1>Project description :</h1>').insertBefore('#postdivrich'); </script>
        <?php }
    }
    
    add_action('admin_footer', 'add_title_to_editor');
    
    /**
     * Create Categories for Projects
     */
    function register_custom_taxonomies_categories()
    {
        $args = array(
            'labels' => [
                'name' => 'Project Categories',
                'singular_name' => 'Project Category'
            ],
            'show_ui' => true,
            'menu_name' => 'Project Categories',
            'public' => true,
            'hierarchical' => true
        );
        register_taxonomy('project_category', 'portfolio-visyu-type', $args);
    }
    
    add_action('init', 'register_custom_taxonomies_categories');
    
    