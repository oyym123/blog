<?php

class WpdiscuzHelper {

    public static $datetime = 'datetime';
    public static $year = 'wc_year_text';
    public static $years = 'wc_year_text_plural';
    public static $month = 'wc_month_text';
    public static $months = 'wc_month_text_plural';
    public static $day = 'wc_day_text';
    public static $days = 'wc_day_text_plural';
    public static $hour = 'wc_hour_text';
    public static $hours = 'wc_hour_text_plural';
    public static $minute = 'wc_minute_text';
    public static $minutes = 'wc_minute_text_plural';
    public static $second = 'wc_second_text';
    public static $seconds = 'wc_second_text_plural';
    private $spoilerPattern = '@\[(\[?)(spoiler)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)@is';
    private $optionsSerialized;
    private $dbManager;
    private $wpdiscuzForm;

    function __construct($optionsSerialized, $dbManager, $wpdiscuzForm) {
        $this->optionsSerialized = $optionsSerialized;
        $this->dbManager = $dbManager;
        $this->wpdiscuzForm = $wpdiscuzForm;
        add_filter('the_champ_login_interface_filter', array(&$this, 'wpDiscuzSuperSocializerLogin'), 15, 2);
    }

    public function filterKses() {
        $allowedtags = array();
        $allowedtags['br'] = array();
        $allowedtags['a'] = array('href' => array(), 'title' => array(), 'target' => array(), 'rel' => array(), 'download' => array(), 'hreflang' => array(), 'media' => array(), 'type' => array());
        $allowedtags['i'] = array('class' => array());
        $allowedtags['b'] = array();
        $allowedtags['u'] = array();
        $allowedtags['strong'] = array();
        $allowedtags['s'] = array();
        $allowedtags['p'] = array();
        $allowedtags['img'] = array('src' => array(), 'width' => array(), 'height' => array(), 'alt' => array(), 'title' => array());
        $allowedtags['blockquote'] = array('cite' => array());
        $allowedtags['ul'] = array();
        $allowedtags['li'] = array();
        $allowedtags['ol'] = array();
        $allowedtags['code'] = array();
        $allowedtags['em'] = array();
        $allowedtags['abbr'] = array('title' => array());
        $allowedtags['q'] = array('cite' => array());
        $allowedtags['acronym'] = array('title' => array());
        $allowedtags['cite'] = array();
        $allowedtags['strike'] = array();
        $allowedtags['del'] = array('datetime' => array());
        $allowedtags['span'] = array('id' => array(), 'class' => array(), 'title' => array());
        $allowedtags['pre'] = array();
        return apply_filters('wpdiscuz_allowedtags', $allowedtags);
    }

    public function filterCommentText($commentContent) {
        kses_remove_filters();
        remove_filter( 'comment_text', 'wp_kses_post' );
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		$commentContent = wp_kses($commentContent, $this->filterKses());
	}
        return $commentContent;
    }

// Set timezone
// Time format is UNIX timestamp or
// PHP strtotime compatible strings
    public function dateDiff($time1, $time2, $precision = 2) {

// If not numeric then convert texts to unix timestamps
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }

// If time1 is bigger than time2
// Then swap time1 and time2
        if ($time1 > $time2) {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
        }

// Set up intervals and diffs arrays
        $intervals = array(
            $this->optionsSerialized->phrases['wc_year_text']['datetime'][1],
            $this->optionsSerialized->phrases['wc_month_text']['datetime'][1],
            $this->optionsSerialized->phrases['wc_day_text']['datetime'][1],
            $this->optionsSerialized->phrases['wc_hour_text']['datetime'][1],
            $this->optionsSerialized->phrases['wc_minute_text']['datetime'][1],
            $this->optionsSerialized->phrases['wc_second_text']['datetime'][1]
        );
        $diffs = array();
// Loop thru all intervals
        foreach ($intervals as $interval) {
// Create temp time from time1 and interval
            $interval = $this->dateComparisionByIndex($interval);
            $ttime = strtotime('+1 ' . $interval, $time1);
// Set initial values
            $add = 1;
            $looped = 0;
// Loop until temp time is smaller than time2
            while ($time2 >= $ttime) {
// Create new temp time from time1 and interval
                $add++;
                $ttime = strtotime("+" . $add . " " . $interval, $time1);
                $looped++;
            }

            $time1 = strtotime("+" . $looped . " " . $interval, $time1);
            $diffs[$interval] = $looped;
        }

        $count = 0;
        $times = array();
// Loop thru all diffs
        foreach ($diffs as $interval => $value) {
            $interval = $this->dateTextByIndex($interval, $value);
// Break if we have needed precission
            if ($count >= $precision) {
                break;
            }
// Add value and interval
// if value is bigger than 0
            if ($value > 0) {
// Add value and interval to times array
                $times[] = $value . " " . $interval;
                $count++;
            }
        }

// Return string with times
        $ago = ($times) ? $this->optionsSerialized->phrases['wc_ago_text'] : $this->optionsSerialized->phrases['wc_right_now_text'];
        return implode(" ", $times) . ' ' . $ago;
    }

    public static function initPhraseKeyValue($phrase) {
        $phrase_value = stripslashes($phrase['phrase_value']);
        switch ($phrase['phrase_key']) {
            case WpdiscuzHelper::$year:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 1));
            case WpdiscuzHelper::$years:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 1));
            case WpdiscuzHelper::$month:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 2));
            case WpdiscuzHelper::$months:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 2));
            case WpdiscuzHelper::$day:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 3));
            case WpdiscuzHelper::$days:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 3));
            case WpdiscuzHelper::$hour:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 4));
            case WpdiscuzHelper::$hours:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 4));
            case WpdiscuzHelper::$minute:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 5));
            case WpdiscuzHelper::$minutes:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 5));
            case WpdiscuzHelper::$second:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 6));
            case WpdiscuzHelper::$seconds:
                return array(WpdiscuzHelper::$datetime => array($phrase_value, 6));
            default :
                return $phrase_value;
        }
    }

    private function dateComparisionByIndex($index) {
        switch ($index) {
            case 1:
                return 'year';
            case 2:
                return 'month';
            case 3:
                return 'day';
            case 4:
                return 'hour';
            case 5:
                return 'minute';
            case 6:
                return 'second';
        }
    }

    private function dateTextByIndex($index, $value) {
        switch ($index) {
            case 'year':
                return ($value > 1) ? $this->optionsSerialized->phrases['wc_year_text_plural']['datetime'][0] : $this->optionsSerialized->phrases['wc_year_text']['datetime'][0];
            case 'month':
                return ($value > 1) ? $this->optionsSerialized->phrases['wc_month_text_plural']['datetime'][0] : $this->optionsSerialized->phrases['wc_month_text']['datetime'][0];
            case 'day':
                return ($value > 1) ? $this->optionsSerialized->phrases['wc_day_text_plural']['datetime'][0] : $this->optionsSerialized->phrases['wc_day_text']['datetime'][0];
            case 'hour':
                return ($value > 1) ? $this->optionsSerialized->phrases['wc_hour_text_plural']['datetime'][0] : $this->optionsSerialized->phrases['wc_hour_text']['datetime'][0];
            case 'minute':
                return ($value > 1) ? $this->optionsSerialized->phrases['wc_minute_text_plural']['datetime'][0] : $this->optionsSerialized->phrases['wc_minute_text']['datetime'][0];
            case 'second':
                return ($value > 1) ? $this->optionsSerialized->phrases['wc_second_text_plural']['datetime'][0] : $this->optionsSerialized->phrases['wc_second_text']['datetime'][0];
        }
    }

    public static function getArray($array) {
        $new_array = array();
        foreach ($array as $value) {
            $new_array[] = $value[0];
        }
        return $new_array;
    }

    public function makeClickable($ret) {
        $ret = ' ' . $ret;
        $hook = '?';
        if (is_ssl() && $this->optionsSerialized->commentLinkFilter == 1) {
            $hook = '';
        }
        $ret = preg_replace_callback('#[^\"|\'](https' . $hook . ':\/\/[^\s]+(\.jpe?g|\.png|\.gif|\.bmp))#i', array(&$this, 'replaceUrlToImg'), $ret);
        // this one is not in an array because we need it to run last, for cleanup of accidental links within links
        $ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
        $ret = trim($ret);
        return $ret;
    }

    public function replaceUrlToImg($matches) {
        $url = $matches[1];
        if (is_ssl() && $this->optionsSerialized->commentLinkFilter == 2 && !strstr($matches[1], 'https://')) {
            $url = str_replace('http://', 'https://', $url);
        }
        return '<a rel="nofollow" target="_blank" href="' . esc_url($url) . '"><img alt="comment image" src="' . esc_url($url) . '" /></a>';
    }

    /**
     * check if comment has been posted today or not
     * return boolean
     */
    public static function isPostedToday($comment) {
        return date('Ymd', strtotime(current_time('Ymd'))) <= date('Ymd', strtotime($comment->comment_date));
    }

    /**
     * check if comment is still editable or not
     * return boolean
     */
    public function isCommentEditable($comment) {
        $editableTimeLimit = isset($this->optionsSerialized->commentEditableTime) ? $this->optionsSerialized->commentEditableTime : 0;
        $timeDiff = (time() - strtotime($comment->comment_date_gmt));
        $editableTimeLimit = ($editableTimeLimit == 'unlimit') ? $timeDiff + 1 : intval($editableTimeLimit);
        return $editableTimeLimit && ($timeDiff < $editableTimeLimit);
    }

    /**
     * checks if the current comment content is in min/max range defined in options
     */
    public function isContentInRange($commentContent) {
        $commentMinLength = intval($this->optionsSerialized->commentTextMinLength);
        $commentMaxLength = intval($this->optionsSerialized->commentTextMaxLength);
        $commentContent = trim(strip_tags($commentContent));
        $contentLength = function_exists('mb_strlen') ? mb_strlen($commentContent) : strlen($commentContent);
        return ($commentMinLength && $contentLength >= $commentMinLength) && ($commentMaxLength == 0 || $contentLength <= $commentMaxLength);
    }

    /**
     * return client real ip
     */
    public function getRealIPAddr() {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getUIDData($uid) {
        $id_strings = explode('_', $uid);
        return $id_strings;
    }

    public function superSocializerFix() {
        if (function_exists('the_champ_login_button')) {
            ?>
            <div id="comments" style="width: 0;height: 0;clear: both;margin: 0;padding: 0;"></div>
            <div id="respond" class="comments-area">
        <?php } else { ?>
                <div id="comments" class="comments-area">
                    <div id="respond" style="width: 0;height: 0;clear: both;margin: 0;padding: 0;"></div>
                    <?php
                }
            }

            public function getCommentExcerpt($commentContent, $uniqueId) {
                $readMoreLink = '<span id="wpdiscuz-readmore-' . $uniqueId . '"><span class="wpdiscuz-hellip">&hellip;&nbsp;</span><span class="wpdiscuz-readmore" title="' . $this->optionsSerialized->phrases['wc_read_more'] . '">' . $this->optionsSerialized->phrases['wc_read_more'] . '</span></span>';
                return wp_trim_words($commentContent, $this->optionsSerialized->commentReadMoreLimit, $readMoreLink);
            }

            public function isLoadWpdiscuz($post) {
                if (!$post || !is_object($post) || (is_front_page() && !$this->optionsSerialized->isEnableOnHome)) {
                    return false;
                }
                $form = $this->wpdiscuzForm->getForm($post->ID);
                return $form->getFormID() && (comments_open($post) || $post->comment_count) && is_singular() && post_type_supports($post->post_type, 'comments');
            }

            public function replaceCommentContentCode($content) {
                if (is_ssl()) {
                    $content = preg_replace_callback('#<\s*?img[^>]*src*=*["\']?([^"\']*)[^>]+>#is', array(&$this, 'replaceImageToURL'), $content);
                }
                return preg_replace_callback('#`(.*?)`#is', array(&$this, 'replaceCodeContent'), stripslashes($content));
            }

            private function replaceImageToURL($matches) {
                if (!strstr($matches[1], 'https://') && $this->optionsSerialized->commentLinkFilter == 1) {
                    return "\r\n" . $matches[1] . "\r\n";
                } elseif (!strstr($matches[1], 'https://') && $this->optionsSerialized->commentLinkFilter == 2) {
                    return str_replace('http://', 'https://', $matches[0]);
                } else {
                    return $matches[0];
                }
            }

            private function replaceCodeContent($matches) {
                $codeContent = trim($matches[1]);
                $codeContent = str_replace(array('<', '>'), array('&lt;', '&gt;'), $codeContent);
                return '<code>' . $codeContent . '</code>';
            }

            public function spoiler($content) {
                return preg_replace_callback($this->spoilerPattern, array(&$this, '_spoiler'), $content);
            }

            private function _spoiler($matches) {
                $html = '<div class="wpdiscuz-spoiler-wrap">';
                $title = __('Spoiler', 'wpdiscuz');
                $matches[3] = str_replace(array('&#8221;', '&#8220;'), '"', $matches[3]);
                if (preg_match('@title[^\S]*=[^\S]*"*([^\"]+)"@is', $matches[3], $titleMatch)) {
                    $title = trim($titleMatch[1]) ? trim($titleMatch[1]) : __('Spoiler', 'wpdiscuz');
                }

                $html .= '<div class="wpdiscuz-spoiler wpdiscuz-spoiler-closed"><i class="fa fa-plus" aria-hidden="true"></i>' . $title . '</div>';
                $html .= '<div class="wpdiscuz-spoiler-content">' . $matches[5] . '</div>';
                $html .= '</div>';
                return $html;
            }

            public function getCurrentUserDisplayName($current_user) {
                $displayName = trim($current_user->display_name);
                if (!$displayName) {
                    $displayName = trim($current_user->user_nicename) ? trim($current_user->user_nicename) : trim($current_user->user_login);
                }
                return $displayName;
            }

            public function registerWpDiscuzStyle($version) {
                $templateDir = get_stylesheet_directory();
                $wpcTemplateStyleFile = $templateDir . DIRECTORY_SEPARATOR . 'wpdiscuz' . DIRECTORY_SEPARATOR . 'wpdiscuz.css';
                $wpdiscuzStyleURL = plugins_url(WPDISCUZ_DIR_NAME . '/assets/css/wpdiscuz.css');
                if (file_exists($wpcTemplateStyleFile)) {
                    $wpdiscuzStyleURL = get_stylesheet_directory_uri() . '/wpdiscuz/wpdiscuz.css';
                } elseif (file_exists(get_template_directory() . DIRECTORY_SEPARATOR . 'wpdiscuz' . DIRECTORY_SEPARATOR . 'wpdiscuz.css')) {
                    $wpdiscuzStyleURL = get_template_directory_uri() . '/wpdiscuz/wpdiscuz.css';
                }
                wp_register_style('wpdiscuz-frontend-css', $wpdiscuzStyleURL, null, $version);
            }

            public function wpDiscuzSuperSocializerLogin($html, $theChampLoginOptions) {
                global $wp_current_filter;
                if (in_array('comment_form_top', $wp_current_filter) && isset($theChampLoginOptions['providers']) && is_array($theChampLoginOptions['providers']) && count($theChampLoginOptions['providers']) > 0) {
                    $html = '<style type="text/css">#wpcomm .wc_social_plugin_wrapper .wp-social-login-connect-with_by_the_champ{float:left;font-size:13px;padding:5px 7px 0 0;text-transform:uppercase}#wpcomm .wc_social_plugin_wrapper ul.wc_social_login_by_the_champ{list-style:none outside none!important;margin:0!important;padding-left:0!important}#wpcomm .wc_social_plugin_wrapper ul.wc_social_login_by_the_champ .theChampLogin{width:24px!important;height:24px!important}#wpcomm .wc-secondary-forms-social-content ul.wc_social_login_by_the_champ{list-style:none outside none!important;margin:0!important;padding-left:0!important}#wpcomm .wc-secondary-forms-social-content ul.wc_social_login_by_the_champ .theChampLogin{width:24px!important;height:24px!important}#wpcomm .wc-secondary-forms-social-content ul.wc_social_login_by_the_champ li{float:right!important}#wpcomm .wc_social_plugin_wrapper .theChampFacebookButton{ display:block!important; }#wpcomm .theChampTwitterButton{background-position:-4px -68px!important}#wpcomm .theChampGoogleButton{background-position:-36px -2px!important}#wpcomm .theChampVkontakteButton{background-position:-35px -67px!important}#wpcomm .theChampLinkedinButton{background-position:-34px -34px!important;}.theChampCommentingTabs #wpcomm li{ margin:0px 1px 10px 0px!important; }</style>';
                    $html .= '<div class="wp-social-login-widget">';
                    $html .= '<div class="wp-social-login-connect-with_by_the_champ">' . $this->optionsSerialized->phrases['wc_connect_with'] . ':</div>';
                    $html .= '<div class="wp-social-login-provider-list">';
                    $html .= '<ul class="wc_social_login_by_the_champ">';
                    foreach ($theChampLoginOptions['providers'] as $provider) {
                        $html .= '<li><i ';
                        if ($provider == 'google') {
                            $html .= 'id="theChamp' . ucfirst($provider) . 'Button" ';
                        }
                        $html .= 'class="theChampLogin theChamp' . ucfirst($provider) . 'Background theChamp' . ucfirst($provider) . 'Login" ';
                        $html .= 'alt="Login with ';
                        $html .= ucfirst($provider);
                        $html .= '" title="Login with ';
                        if ($provider == 'live') {
                            $html .= 'Windows Live';
                        } else {
                            $html .= ucfirst($provider);
                        }
                        $html .= '" onclick="theChampCommentFormLogin = true; theChampInitiateLogin(this)" >';
                        $html .= '<ss style="display:block" class="theChampLoginSvg theChamp' . ucfirst($provider) . 'LoginSvg"></ss></i></li>';
                    }
                    $html .= '</ul><div class="wpdiscuz_clear"></div></div></div>';
                }
                return $html;
            }

            public function getAuthorPostsUrl($author_id, $author_nicename = '') {
                $authorURL = '';
                $post_types = apply_filters('wpdiscuz_author_post_types', array('post'));
                if (count_user_posts($author_id, $post_types)) {
                    return get_author_posts_url($author_id, $author_nicename);
                }
                $authorURL = apply_filters('author_link', $authorURL, $author_id, $author_nicename);
                return $authorURL;
            }
            
            public function loadMoreLink($parentCommentID,$post_id){
                global $wp_rewrite;
                $loadMoreLink = !$wp_rewrite->using_permalinks() ? get_permalink($post_id) . "&" : get_permalink($post_id) . "?";
                $loadMoreLink.= 'wpdParentID='.$parentCommentID;
                return $loadMoreLink;
            }

        }
        