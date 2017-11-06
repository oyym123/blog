<?php

if (!defined('ABSPATH')) {
    exit();
}
$wpcCurrentThemDir = get_stylesheet_directory();
$wpdiscuzWalkerThemePath = $wpcCurrentThemDir . DIRECTORY_SEPARATOR . 'wpdiscuz' . DIRECTORY_SEPARATOR . 'class.WpdiscuzWalker.php';
if (file_exists($wpdiscuzWalkerThemePath)) {
    include_once $wpdiscuzWalkerThemePath;
} elseif (file_exists(get_template_directory() . DIRECTORY_SEPARATOR . 'wpdiscuz' . DIRECTORY_SEPARATOR . 'class.WpdiscuzWalker.php')) {
    include_once get_template_directory() . DIRECTORY_SEPARATOR . 'wpdiscuz' . DIRECTORY_SEPARATOR . 'class.WpdiscuzWalker.php';
} else {
    include_once apply_filters('wpdiscuz_walker_include', WPDISCUZ_DIR_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'comment' . DIRECTORY_SEPARATOR . 'class.WpdiscuzWalker.php');
}
