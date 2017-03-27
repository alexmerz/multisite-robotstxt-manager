<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }


/**
 * @about Admin Area Display
 * @location multisite-robotstxt-manager.php
 * @call add_action( 'wp_loaded', array( 'MsRobotstxtManager_AdminArea', 'instance' ) );
 * 
 * @method init()       Init Admin Actions
 * @method menu()       Load Admin Area Menu
 * @method enqueue()    Enqueue Stylesheet and jQuery
 * @method website()    Display Website Admin Templates
 * @method network()    Display Network Admin Templates
 * @method tabs()       Load Admin Area Tabs
 * @method instance()   Class Instance
 */
if ( ! class_exists( 'MsRobotstxtManager_AdminArea' ) )
{
    class MsRobotstxtManager_AdminArea extends MsRobotstxtManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Init Admin Actions
         */
        final public function init()
        {
            // Website Menu Link
            add_action( 'admin_menu', array( $this, 'menu' ) );

            // Network Menu Link
            add_action( 'network_admin_menu', array( $this, 'menu' ) );

            // Unqueue Scripts Within Plugin Admin Area
            if ( $this->qString( 'page' ) == $this->plugin_name ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
            }
        }


        /**
         * @about Plugin Menus
         */
        final public function menu()
        {
            // Website Menu
            add_submenu_page(
                'options-general.php',
                $this->plugin_title,
                $this->menu_name,
                'manage_options',
                $this->plugin_name,
                array( $this, 'website' )
            );

            // Network Menu
            add_submenu_page(
                'settings.php',
                $this->plugin_title,
                $this->menu_name,
                'manage_options',
                $this->plugin_name,
                array( $this, 'network' )
            );
        }


        /**
         * @about Enqueue Stylesheet and jQuery
         */
        final public function enqueue()
        {
            wp_enqueue_style( $this->plugin_name, plugins_url( '/assets/style.css', $this->plugin_file ), '', date( 'YmdHis', time() ), 'all' );
        }


        /**
         * @about Display Website Admin Templates
         */
        final public function website()
        {
            // Admin Header
            require_once( $this->templates .'/header.php' );

            // Switch Between Tabs
            switch ( $this->qString( 'tab' ) ) {
                case 'website':
                default:
                    require_once( $this->templates .'/website.php' );
                break;
            }

            // Admin Footer
            require_once( $this->templates .'/footer.php' );
        }


        /**
         * @about Display Network Admin Templates
         */
        final public function network()
        {
            // Admin Header
            require_once( $this->templates .'/header.php' );

            // Switch Between Tabs
            switch ( $this->qString( 'tab' ) ) {
                case 'network':
                default:
                    require_once( $this->templates .'/network.php' );
                break;

                case 'cleaner':
                    require_once( $this->templates .'/cleaner.php' );
                break;
            }

            // Admin Footer
            require_once( $this->templates .'/footer.php' );
        }


        /**
         * @about Admin Area Tabs
         * @return string $html Tab Display
         */
        final public function tabs()
        {
            $html = '<h2 class="nav-tab-wrapper">';

            // Set Current Tab
            $current = ( $this->qString( 'tab' ) ) ? $this->qString( 'tab' ) : key( $this->tabs );

            foreach( $this->tabs as $tab => $name ) {
                // Current Tab Class
                $class = ( $tab == $current ) ? ' nav-tab-active' : '';

                // Tab Links
                $html .= '<a href="?page='. $this->qString( 'page' ) .'&tab='. $tab .'" class="nav-tab'. $class .'">'. $name .'</a>';
            }

            $html .= '</h2><br />';

            return $html;
        }


        /**
         * @about Create Instance
         */
        public static function instance()
        {
            if ( ! self::$instance ) {
                self::$instance = new self();
                self::$instance->init();
            }

            return self::$instance;
        }
    }
}