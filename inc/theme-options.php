<?php
    /**
     * ReduxFramework Config File
     * For full documentation, please visit: https://docs.reduxframework.com
     * */

    if ( ! class_exists( 'Redux_Framework_dwp_config' ) ) {

        class Redux_Framework_dwp_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                // If Redux is running as a plugin, this will remove the demo notice and links
                //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

                // Function to test the compiler hook and demo CSS output.
                // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
                //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);

                // Change the arguments after they've been declared, but before the panel is created
                //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

                // Change the default value of a field after it's been set, but before it's been useds
                //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

                // Dynamically add a section. Can be also used to modify sections/fields
                //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
                //print_r($options); //Option values
                //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

                /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'diamondwp' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'diamondwp' ),
                    'icon'   => 'el-icon-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array(
                        ReduxFrameworkPlugin::instance(),
                        'plugin_metalinks'
                    ), null, 2 );

                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {



                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'diamondwp' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'diamondwp' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'diamondwp' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'diamondwp' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'diamondwp' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'diamondwp' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'diamondwp' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'diamondwp' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    Redux_Functions::initWpFilesystem();

                    global $wp_filesystem;

                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                // ACTUAL DECLARATION OF SECTIONS

                //Homepage                  
                $this->sections[] = array(
                    'icon'      => 'el-icon-home',
                    'title'     => __('Homepage', 'diamondwp'),
                    //'subsection' => true,
                    'fields'    => array(
                        array(
                            'id'        => 'homepage-layout',
                            'type'      => 'sorter',
                            'title'     => __('Homepage Layout Manager', 'diamondwp'),
                            'desc'      => __('Organize how you want the layout to appear on the homepage', 'diamondwp'),
                            'options'   => array(
                                'enabled'   => array(
                                    'herocontent'   => 'Hero Content',
                                    'service'       => 'Service',
                                ),
                                'disabled'  => array(
                                    'herocontent2'   => 'Hero Content2',
                                    'heropost'      => 'Hero Post',
                                ),
                                
                            ),
                        ),
                        array(   
                        'title'     => __('Banner Title', 'diamondwp'), 
                        'subtitle'  => __('Text for the hero banner title', 'diamondwp'),
                        'id'        => 'home-hero-title',
                        'default'   => 'Hero Banner Text',
                        'type'      => 'textarea',
                        ),
                        array(   
                        'title'     => __('Banner Image', 'diamondwp'), 
                        'subtitle'  => __('Image for the hero banner', 'diamondwp'),
                        'id'        => 'home-banner-img',
                        'type'      => 'media',
                        'url'      => 'true',
                        ),
                        array(   
                        'title'     => __('Service Display Number', 'diamondwp'), 
                        'subtitle'  => __('Max number of services to display', 'diamondwp'),
                        'id'        => 'home-number-services',
                        'type'      => 'text',
                        ),
                        array(   
                        'title'     => __('Heading', 'diamondwp'), 
                        'subtitle'  => __('Two column section heading', 'diamondwp'),
                        'id'        => 'heading-two-col',
                        'type'      => 'text',
                        ),
                        array(   
                        'title'     => __('Left Text Area', 'diamondwp'), 
                        'subtitle'  => __('Text for left', 'diamondwp'),
                        'id'        => 'left-text',
                        'default'   => 'Left text area',
                        'type'      => 'textarea',
                        ),
                         array(   
                        'title'     => __('Right Text Area', 'diamondwp'), 
                        'subtitle'  => __('Text for right', 'diamondwp'),
                        'id'        => 'right-text',
                        'default'   => 'Right text area',
                        'type'      => 'textarea',
                        ),
                    )
                );

                //blog            
                $this->sections[] = array(
                    'icon'      => 'el-icon-cog',
                    'title'     => __('Blog', 'diamondwp'),
                    'fields'    => array(
                         array(   
                        'title'     => __('Banner Title', 'diamondwp'), 
                        'subtitle'  => __('Text for the banner title', 'diamondwp'),
                        'id'        => 'hp-banner-title',
                        'default'   => 'Blog Banner Title',
                        'type'      => 'text',
                        ),
                        array(   
                        'title'     => __('Banner Image', 'diamondwp'), 
                        'subtitle'  => __('Image for the Blog banner', 'diamondwp'),
                        'id'        => 'hp-banner-img',
                        'type'      => 'media',
                        'url'      => 'true',
                        ),
                    )
                );

                //Portfolio            
                $this->sections[] = array(
                    'icon'      => 'el-icon-cog',
                    'title'     => __('Portfolio', 'diamondwp'),
                    'fields'    => array(
                         array(   
                        'title'     => __('Banner Title', 'diamondwp'), 
                        'subtitle'  => __('Text for the Portfolio banner title', 'diamondwp'),
                        'id'        => 'portfolio-banner-title',
                        'default'   => 'Portfolio Banner Title',
                        'type'      => 'text',
                        ),
                        array(   
                        'title'     => __('Banner Image', 'diamondwp'), 
                        'subtitle'  => __('Image for the Portfolio banner', 'diamondwp'),
                        'id'        => 'portfolio-banner-img',
                        'type'      => 'media',
                        'url'      => 'true',
                        ),
                    )
                );
                //Service            
                $this->sections[] = array(
                    'icon'      => 'el-icon-cog',
                    'title'     => __('Service', 'diamondwp'),
                    'fields'    => array(
                         array(   
                        'title'     => __('Banner Title', 'diamondwp'), 
                        'subtitle'  => __('Text for the Service banner title', 'diamondwp'),
                        'id'        => 'service-banner-title',
                        'default'   => 'Service Banner Title',
                        'type'      => 'text',
                        ),
                        array(   
                        'title'     => __('Banner Image', 'diamondwp'), 
                        'subtitle'  => __('Image for the Service banner', 'diamondwp'),
                        'id'        => 'service-banner-img',
                        'type'      => 'media',
                        'url'      => 'true',
                        ),
                        array(   
                        'title'     => __('Page Title', 'diamondwp'), 
                        'subtitle'  => __('Page Title for the Services Page', 'diamondwp'),
                        'id'        => 'page-title-text',
                        'type'      => 'textarea',
                        ),
                        array(   
                        'title'     => __('Intro Copy', 'diamondwp'), 
                        'subtitle'  => __('Intro Copy for the Services Page', 'diamondwp'),
                        'id'        => 'page-intro-copy',
                        'type'      => 'textarea',
                        ),
                        array(   
                        'id'        => 'editor-text',
                        'type'      => 'editor',
                        'title'     => __('Editor Text', 'text edited here'), 
                        'subtitle'  => __('Subtitle text would go here.', 'subtitle edited here'),
                        'default'   => '<h3 class="text-center">Services We Offer </h3>
                        <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque aliquam mattis ex, ut imperdiet magna imperdiet at. Proin eget pulvinar lorem. Curabitur rhoncus vehicula libero, nec porttitor lectus. Fusce mattis eu leo id tempus. Donec in ultrices nisl. </p>',
                        ),
                    )
                );




                

                if ( file_exists( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) ) {
                    $tabs['docs'] = array(
                        'icon'    => 'el-icon-book',
                        'title'   => __( 'Documentation', 'diamondwp' ),
                        'content' => nl2br( file_get_contents( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) )
                    );
                }
            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'diamondwp' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'diamondwp' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'diamondwp' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'diamondwp' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'diamondwp' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'dwp_options',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'diamondwp' ),
                    'page_title'           => __( 'Theme Options', 'diamondwp' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => true,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => '_options',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );

                // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-docs',
                    'href'   => 'http://docs.reduxframework.com/',
                    'title' => __( 'Documentation', 'diamondwp' ),
                );

                $this->args['admin_bar_links'][] = array(
                    //'id'    => 'redux-support',
                    'href'   => 'https://github.com/ReduxFramework/redux-framework/issues',
                    'title' => __( 'Support', 'diamondwp' ),
                );

                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-extensions',
                    'href'   => 'reduxframework.com/extensions',
                    'title' => __( 'Extensions', 'diamondwp' ),
                );

                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                // $this->args['share_icons'][] = array(
                //     'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                //     'title' => 'Visit us on GitHub',
                //     'icon'  => 'el-icon-github'
                //     //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                // );
                // $this->args['share_icons'][] = array(
                //     'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                //     'title' => 'Like us on Facebook',
                //     'icon'  => 'el-icon-facebook'
                // );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://twitter.com/ozzyandlayla',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el-icon-twitter'
                );
                // $this->args['share_icons'][] = array(
                //     'url'   => 'http://www.linkedin.com/company/redux-framework',
                //     'title' => 'Find us on LinkedIn',
                //     'icon'  => 'el-icon-linkedin'
                // );

                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    $this->args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'diamondwp' ), $v );
                } else {
                    $this->args['intro_text'] = __( '<p>Something in here</p>', 'diamondwp' );
                }

                // Add content after the form.
                $this->args['footer_text'] = __( '<p>Some text can go in here</p>', 'diamondwp' );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_dwp_config();
    } else {
        echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;