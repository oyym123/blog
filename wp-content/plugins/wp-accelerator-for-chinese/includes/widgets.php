<?php
/*
自定义小工具扩展类
 */
defined('ABSPATH') or exit;

if (!class_exists('WP_Widget_Meta_Mod')) {
	class WP_Widget_Meta_Mod extends WP_Widget {
		function __construct() {
			$widget_ops = array('classname' => 'widget_meta', 'description' => '登录工具');
			parent::__construct('meta', __('Meta'), $widget_ops);
		}
		function widget($args, $instance) {
			extract($args);
			$title = apply_filters('widget_title', empty($instance['title']) ? __('Meta') : $instance['title'], $instance, $this->id_base);
			echo $before_widget;
			if ($title) {
				echo $before_title . $title . $after_title;
			}

			?>
        <ul>
            <?php wp_register();?>
            <li><?php wp_loginout();?></li>
            <?php wp_meta();?>
        </ul>
        <?php
echo $after_widget;
		}
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			return $instance;
		}
		function form($instance) {
			$instance = wp_parse_args((array) $instance, array('title' => ''));
			$title = strip_tags($instance['title']);
			?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:');?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <?php
}
	}
}
?>