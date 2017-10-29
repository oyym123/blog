<?php
/**
 * Plugin Name:WordPress中文加速插件
 * Plugin URI: http://www.themeforest.cn
 * Description: WordPress中文加速插件
 * Version: 0.9.4
 * Author: themeforest.cn
 * Author URI:  http://www.themeforest.cn
 * Author Email: yundic@126.com
 * Requires at least: WP 3.8
 * Tested up to: WP 4.4.2
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

if (!class_exists('WP_AcceleratorForChinese')) {
    class WP_AcceleratorForChinese {
        protected static $_instance = null;
        protected static $is_debug = false;
        private static $bing_cache_hour = 4;
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function __clone() {}
        public function __wakeup() {}
        public function __construct() {

            register_uninstall_hook(__FILE__, array(__CLASS__, 'handle_uninstall_hook'));
            register_activation_hook(__FILE__, array($this, 'handle_activation_hook'));
            add_action('plugins_loaded', array($this, 'plugins_loaded'), 11);

        }

        public function plugins_loaded() {

            $this->defaults_o = apply_filters('speedup4cn_default_o', array(
                'open_sans' => 1,
                'head_cleaner' => 1,
                'feed_link' => 0,
                'yahei' => 0,
                'ver_info' => 0,
                'meta_widget' => 0,
                'logo' => 0,
                'enable_emoji' => 0,
                'emoji' => '//twemoji.maxcdn.com/72x72/',
                'bing' => 1,
                'bing_cache_hour' => 4,
            ));

            $this->o = $this->get_options();
             self::$bing_cache_hour = $this->o['bing_cache_hour' ];
            if ($this->o['open_sans']) {
                add_filter('gettext_with_context', array(__CLASS__, 'remove_default_google_fonts'), 10, 4);
            }
            if ($this->o['yahei']) {

                /*add_action('login_head', array(__CLASS__, 'change_default_font'), 11);*/
                add_action('admin_head', array(__CLASS__, 'change_default_font'), 11);
                add_action('wp_head', array(__CLASS__, 'change_default_font'), 11);

            }

            if ($this->o['ver_info']) {
                add_filter('script_loader_src', array(__CLASS__, 'script_loader_src'), 12, 2);
                add_filter('style_loader_src', array(__CLASS__, 'script_loader_src'), 12, 2);

            }

            if ($this->o['meta_widget']) {
                add_action('widgets_init', array(__CLASS__, 'remove_widgets'), 11);
            }
            if ($this->o['logo']) {
                add_action('admin_bar_menu', array(__CLASS__, 'remove_wp_logo_from_admin_bar'), 24);
            }
            if ($this->o['head_cleaner']) {

                remove_action('wp_head', 'rsd_link');
                remove_action('wp_head', 'wlwmanifest_link');
                remove_action('wp_head', 'index_rel_link');
                remove_action('wp_head', 'parent_post_rel_link', 10, 0);
                remove_action('wp_head', 'start_post_rel_link', 10, 0);
                remove_action('wp_head', 'wp_shortlink_wp_head');
                remove_action('template_redirect', 'wp_shortlink_header', 11);
                remove_action('wp_head', 'wp_generator');

            }

            if (!$this->o['enable_emoji'] && !empty($this->o['emoji'])) {
                add_filter('emoji_url', array($this, 'emoji_url'));
            }

            if (is_admin()) {

                require_once 'includes/o.php';

                /* self */
                add_action('admin_init', array('WP_AcceleratorForChinese_o', 'register_settings'));
                add_action('admin_menu', array('WP_AcceleratorForChinese_o', 'add_settings_page'));
                add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(__CLASS__, 'add_action_link'));
            }
            /*
            x_prefetch
             */
            add_action('wp_head', array($this, 'add_x_prefetch'), 0);

            /*
            relative urls: Only for Local development environment
            NOTICE: please do NOT use relative URLs for your online site!!!
             */
            if(self::$is_debug){
                add_action('template_redirect',array(__CLASS__,'allow_relative_urls'));
            }

            /*
            handle ping action
             */
            add_action('pre_ping', array(__CLASS__, 'donot_ping_self'));
            add_filter('xmlrpc_methods', array(__CLASS__, 'remove_xmlrpc_pingback_ping'));
            /*
            misc
             */
            add_action('init', array($this, 'init'), 11);
            /*
            remove useless dashboard widgets
             */
            add_action('wp_dashboard_setup', array(__CLASS__, 'dashboard_widgets'), 11);

            /*
            change avatar source
             */

            add_filter('get_avatar_data', array(__CLASS__,'get_avatar_data'),10,2);

            /*
            bing bg for login screen
             */
            if ($this->o['bing']) {
                    add_action('login_head', array(__CLASS__,'set_bingbg_for_login'));
            }

            /*
            google2useso: only for local development environment.
             */
            if(self::$is_debug){
                /*add_action('init', array(__CLASS__,'google2useso_init'));*/
                add_action('shutdown', array(__CLASS__,'google2useso_end'));
            }
            /*
            allow useing Chinese in user_login
             */
            add_filter('sanitize_user',  array(__CLASS__,'allow_chinese_username'), 9, 3);

        }

        public static function allow_chinese_username($username, $raw_username, $strict){

            $username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', strip_tags($raw_username));
            $username = preg_replace('/&.+?;/', '', $username);
            if ($strict){
                $username = preg_replace('|[^a-z0-9 _.\-@\x80-\xFF]|i', '', $username);
            }
            $username = preg_replace('|\s+|', ' ', $username);
            return $username;

        }

        public static function google2useso($buffer){
            /*
            NOTICE: fonts.useso.com do NOT support combined query.
             */
            /*$buffer = str_replace("fonts.googleapis.com", "fonts.useso.com", $buffer);*/
            $buffer = str_replace("ajax.googleapis.com", "ajax.useso.com", $buffer);
            return $buffer;
        }
        public static function google2useso_init(){
            ob_start(array(__CLASS__,'google2useso'));
        }
        public static function google2useso_end(){
            if (ob_get_contents()){
                ob_end_clean();
            }
        }

        public static function set_bingbg_for_login(){

            $img_url = get_transient('bing_image');
            if($img_url&&filter_var($img_url, FILTER_VALIDATE_URL )){

            }else{

                $base_url = set_url_scheme('http://s.cn.bing.net');
                $url = $base_url.'/HPImageArchive.aspx?format=js&idx=0&n=1';
                $args = array(
                    'timeout'     => 10,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'user-agent'  => 'WordPress; ' . site_url(),

                );

                $resp = wp_remote_get($url,$args);
                if(!is_wp_error($resp)&&is_array($resp)){

                    $body = json_decode(wp_remote_retrieve_body( $resp ));
                    $img =  $body->images[0]->url;
                    $img_url = filter_var ($body->images[0]->url, FILTER_VALIDATE_URL )?$body->images[0]->url:false;
                    if (!$img_url) {
                        return;
                    }


                    set_transient('bing_image',$img_url,HOUR_IN_SECONDS*self::$bing_cache_hour);
                }

            }
            if (!$img_url||!filter_var ($img_url, FILTER_VALIDATE_URL )) {

                delete_transient('bing_image');
                return;
            }

            echo '<style type="text/css">body{ font-family: "Microsoft Yahei",STXihei,"Source Sans Pro",sans-serif !important;background: url("' . $img_url  . '");width:100%;height:100%;background-image:url("' . $img_url  . '");-moz-background-size: 100% 100%;-o-background-size: 100% 100%;-webkit-background-size: 100% 100%;background-size: 100% 100%;-moz-border-image: url("' . $img_url  . '") 0;background-repeat:no-repeat\9;background-image:none\9;}.login #login_error, .login .message,.login #backtoblog, .login #nav{font-size:16px;}.login #backtoblog a, .login #nav a, .login h1 a{color:#444;}</style>';
        }

        /**
         * based on wordpress doc.
         * @return [type] [description]
         */
        public static function dashboard_widgets() {

            global $wp_meta_boxes;
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Right Now
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
            remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); // Incoming Links
            remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // Plugins
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Press
            remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // Recent Drafts
            remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress blog
            remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // Other WordPress News

            update_user_meta(get_current_user_id(), 'show_welcome_panel', false);

        }

        /**
         * Clean the default meta widget.
         * @return [type] [description]
         */
        public static function remove_widgets() {

            unregister_widget('WP_Widget_Meta');
            require_once dirname(__FILE__) . '/includes/widgets.php';
            register_widget('WP_Widget_Meta_Mod');

        }

        /**
         * Disable Only Pingbacks
         * @param  [type] $methods [description]
         * @return [type]          [description]
         */
        public static function remove_xmlrpc_pingback_ping($methods) {
            unset($methods['pingback.ping']);
            return $methods;
        }
        /**
         * [donot_ping_self description]
         * @param  [type] $links [description]
         * @return [type]        [description]
         */
        public static function donot_ping_self(&$links) {

            $self = get_option('home');
            foreach ($links as $l => $link) {
                if (false === strpos($link, $self)) {
                    unset($links[$l]);
                }
            }

        }
        /**
         * Change emoji BASE URI.
         * @param  [type] $base [description]
         * @return [type]       [description]
         */
        public function emoji_url($base) {

            $base = set_url_scheme($this->o['emoji']);

            return $base;

        }
        /**
         * Prepare.
         * @return [type] [description]
         */
        public function handle_activation_hook() {
            //add_option('speedup4cn', $this->defaults_o);
        }
        /**
         * Bye!
         * @return [type] [description]
         */
        public static function handle_uninstall_hook() {
            delete_option('speedup4cn');
        }
        /**
         * parse options.
         * @return [type] [description]
         */
        public function get_options() {
            return wp_parse_args(get_option('speedup4cn'), $this->defaults_o);
        }
        /**
         * Setting btn.
         * @param [type] $data [description]
         */
        public static function add_action_link($data) {
            return array_merge($data, array(sprintf('<a href="%s">%s</a>', add_query_arg(array('page' => 'speedup4cn'), admin_url('options-general.php')), __("Settings"))));
        }

        /**
         * X DNX Prefetch.
         * @return [type] [description]
         */
        public function add_x_prefetch() {

            ?><meta http-equiv="x-dns-prefetch-control" content="on"/><?php

        }

        public static function change_default_font() {

            ?>
            <style>body,input,select,radio,textarea,submit,.press-this a.wp-switch-editor,#wpadminbar .quicklinks>ul>li>a,#wpadminbar .quicklinks .menupop ul li .ab-item,#wpadminbar #wp-admin-bar-user-info .display-name,#wpadminbar>#wp-toolbar span.ab-label{
              font-family: "Microsoft Yahei",STXihei,"Source Sans Pro",sans-serif !important;}.avatar{max-width:60px;max-height:60px;}</style>
              <?php

        }
        /**
         * Remove WP Logo From admin bar.
         * @param  [type] $wp_admin_bar [description]
         * @return [type]               [description]
         */
        public static function remove_wp_logo_from_admin_bar($wp_admin_bar) {

            $wp_admin_bar->remove_node('wp-logo');

        }


        /**
         * Remove fonts load by default themes.
         * @param  [type] $translations [description]
         * @param  [type] $text         [description]
         * @param  [type] $context      [description]
         * @param  [type] $domain       [description]
         * @return [type]               [description]
         */
        public static function remove_default_google_fonts($translations, $text, $context, $domain) {
            if (

                ('Open Sans font: on or off' == $context && 'on' == $text)
                /*for twentyfourteen*/
                || ('Lato font: on or off' == $context && 'on' == $text)
                /*for twentyfifteen*/
                || ('Noto Sans font: on or off' == $context && 'on' == $text)
                || ('Noto Serif font: on or off' == $context && 'on' == $text)
                /*for twentysixteen*/
                || ('Inconsolata font: on or off' == $context && 'on' == $text)
                || ('Merriweather font: on or off' == $context && 'on' == $text)
                || ('Montserrat font: on or off' == $context && 'on' == $text)
            ) {
                $translations = 'off';
            }
            return $translations;
        }
        /**
         * Remove the "?ver=x.x.x" from links of scripts.
         * @param  [type] $src    [description]
         * @param  [type] $handle [description]
         * @return [type]         [description]
         */
        public static function script_loader_src($src, $handle) {

            return remove_query_arg('ver', $src);

        }
        /**
         * Misc.
         * @return [type] [description]
         */
        public function init() {

            if ($this->o['feed_link']) {
                remove_action('wp_head', 'feed_links', 2);
                remove_action('wp_head', 'feed_links_extra', 3);
            }

            /*
            remove Open Sans from WP core
             */
            if ($this->o['open_sans']) {
                wp_deregister_style('open-sans');
                wp_register_style('open-sans', false);
                wp_enqueue_style('open-sans', '');
            }
            /*
                From: Disable Emojis v1.5 by Ryan Hellyer
                url: https://geek.hellyer.kiwi/plugins/disable-emojis/
            */

            if ($this->o['enable_emoji']) {

                remove_action('wp_head', 'print_emoji_detection_script', 7);
                remove_action('admin_print_scripts', 'print_emoji_detection_script');
                remove_action('wp_print_styles', 'print_emoji_styles');
                remove_action('admin_print_styles', 'print_emoji_styles');
                remove_action('embed_head', 'print_emoji_detection_script');
                remove_filter('comment_text', 'convert_smilies', 20);
                remove_filter('the_excerpt', 'convert_smilies');
                remove_filter('the_content', 'convert_smilies');
                remove_filter('the_content_feed', 'wp_staticize_emoji');
                remove_filter('comment_text_rss', 'wp_staticize_emoji');
                remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

                add_filter('tiny_mce_plugins', array(__CLASS__, 'enable_emojis_tinymce'));

            }
            if(self::$is_debug){
                self::google2useso_init();
            }

        }
        /**
         * Remove emoji helper.
         * @param  [type] $plugins [description]
         * @return [type]          [description]
         */
        public static function enable_emojis_tinymce($plugins) {

            if (is_array($plugins)) {
                return array_diff($plugins, array('wpemoji'));
            } else {
                return array();
            }

        }

        public static function allow_relative_urls(){

            if (in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ||is_feed() || get_query_var('sitemap')||is_admin()){
                 return;
            }

            $hooks = array(
                'post_link',
                'post_type_link',
                'page_link',
                'attachment_link',
                'get_shortlink',
                'post_type_archive_link',
                'get_pagenum_link',
                'get_comments_pagenum_link',
                'term_link',
                'search_link',
                'day_link',
                'month_link',
                'year_link'
            );
            foreach ($hooks as $h) {
                add_filter($h, 'wp_make_link_relative',11);
            }

        }

        public static function get_avatar_data($args, $id_or_email){

             $args['url'] = str_replace( array('www.gravatar.com','secure.gravatar.com', '0.gravatar.com', '1.gravatar.com', '2.gravatar.com'), 'cn.gravatar.com', $args['url'] );
            return $args;

        }


        /**
         * [debug description]
         * @param  [type] $str [description]
         * @return void
         */
        public static function debug($str){

            if(self::$is_debug){
                var_dump($str);
            }
            return;

        }

    } /*//CLASS*/
    $GLOBALS['WP_AcceleratorForChinese'] = WP_AcceleratorForChinese::instance();

}
