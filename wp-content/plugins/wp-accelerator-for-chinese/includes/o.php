<?php
/**
 * @author: suifengtec coolwp.com
 * @date:   2016-01-31 14:20:42
 * @last Modified by:   suifengtec coolwp.com
 * @last Modified time: 2016-02-03 16:34:56
 */

defined('ABSPATH') || exit;

if (!class_exists('WP_AcceleratorForChinese_o')) {

	class WP_AcceleratorForChinese_o {
		public static function register_settings() {
			register_setting(
				'speedup4cn',
				'speedup4cn',
				array(
					__CLASS__,
					'validate_settings',
				)
			);
		}
        public static function add_settings_page() {
            $page = add_options_page(
                '静态加速',
                '静态加速',
                'manage_options',
                'speedup4cn',
                array(
                    __CLASS__,
                    'settings_page',
                )
            );
        }
		public static function validate_settings($data) {

            $bing_cache_hour  =  (int) ($data['bing_cache_hour']);
            if($bing_cache_hour<1){
                 $bing_cache_hour  =  1;
            }
            if($bing_cache_hour>24){
                 $bing_cache_hour  =  24;
            }
			$ss = apply_filters('speedup4cn_validate_settings', array(
				'enable_emoji' => (int) ($data['enable_emoji']),
				'emoji' => sanitize_text_field($data['emoji']),
				'open_sans' => (int) ($data['open_sans']),
				'head_cleaner' => (int) ($data['head_cleaner']),
				'feed_link' => (int) ($data['feed_link']),
				'yahei' => (int) ($data['yahei']),
				'ver_info' => (int) ($data['ver_info']),
				'meta_widget' => (int) ($data['meta_widget']),
                'logo' => (int) ($data['logo']),
                'bing' => (int) ($data['bing']),
				'bing_cache_hour' => $bing_cache_hour,
			));
			if (isset($data['meta_widget1'])) {
				$ss['meta_widget1'] = (int) ($data['meta_widget1']);
			}

			return $ss;
		}



		public static function settings_page() {
			?>
            <style> p.description{max-width: 25em; }</style>
        <div class="wrap">
            <h2>静态加速</h2>
            <form method="post" action="options.php">
            <?php

			global $WP_AcceleratorForChinese;
			settings_fields('speedup4cn');
			$options = $WP_AcceleratorForChinese->get_options();

			?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <h3>WordPress 设置</h3>
                        </th>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                          Emoji 表情的base uri
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_emoji">
                                    <input type="text" name="speedup4cn[emoji]" id="speedup4cn_emoji" value="<?php echo $options['emoji']; ?>" size="256" class="regular-text code" />
                                </label>
                                <p class="description">
                                 WordPress 自带的 Emoji 表情 BASE URL 是 <code>//s.w.org/images/core/emoji/72x72/</code>,由于某种原因，国内可能无法访问,如需更换，推荐使用 <code>//twemoji.maxcdn.com/72x72/</code>。
                                </p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                          禁用 Emoji 表情
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_enable_emoji">
                                    <input type="checkbox" name="speedup4cn[enable_emoji]" id="speedup4cn_enable_emoji" value="1"  <?php checked(1, $options['enable_emoji'])?>/>
                                </label>
                                <p class="description">
                                是否禁用 Emoji 表情
                                </p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            移除谷歌字体
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_open_sans">
                                    <input type="checkbox" name="speedup4cn[open_sans]" id="speedup4cn_open_sans" value="1" <?php checked(1, $options['open_sans'])?> />
                                    是否移除谷歌字体: 支持移除 WordPress 加载的 Open Sans 以及 WordPress 自带的 2014-2016 主题自带的谷歌字体。
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            移除工具条上的 WordPress Logo.
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_logo">
                                    <input type="checkbox" name="speedup4cn[logo]" id="speedup4cn_logo" value="1" <?php checked(1, $options['logo'])?> />
                                    是否移除工具条上的 WordPress Logo.
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            前后台默认为雅黑字体
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_yahei">
                                    <input type="checkbox" name="speedup4cn[yahei]" id="speedup4cn_yahei" value="1" <?php checked(1, $options['yahei'])?> />
                                    是否移在前后台默认采用微软雅黑、宋体细黑字体。
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                           清理 head
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_head_cleaner">
                                    <input type="checkbox" name="speedup4cn[head_cleaner]" id="speedup4cn_head_cleaner" value="1" <?php checked(1, $options['head_cleaner'])?> />
                                    是否移除WordPress加载在head部分的HTML输出:<code>wp_generator</code>、<code>wlwmanifest_link</code>、<code>rsd_link</code>、<code>wp_shortlink_wp_head</code>、<code>wp_shortlink_header</code>。
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                         移除Feed 链接
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_feed_link">
                                    <input type="checkbox" name="speedup4cn[feed_link]" id="speedup4cn_feed_link" value="1" <?php checked(1, $options['feed_link'])?> />
                                    是否移WordPress加载在head部分的<code>feed_link</code>。
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                         移除js和css的版本信息
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_ver_info">
                                    <input type="checkbox" name="speedup4cn[ver_info]" id="speedup4cn_ver_info" value="1" <?php checked(1, $options['ver_info'])?> />
                                    是否移除js和css的版本信息。
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                        替换/移除 WordPress 自带的 "功能小工具"
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_meta_widget">
                                    <input type="checkbox" name="speedup4cn[meta_widget]" id="speedup4cn_meta_widget" value="1" <?php checked(1, $options['meta_widget'])?> />
                                    指的是WP自带的那个有登录、注册以及 WP自身链接的小工具。不选则替换，选中则移除。
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                        是否使用 Bing 每日一图作为默认登录页面的背景图
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_bing">
                                    <input type="checkbox" name="speedup4cn[bing]" id="speedup4cn_bing" value="1" <?php checked(1, $options['bing'])?> />
                                   是否使用 Bing 每日一图作为默认登录页面的背景图
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                       Bing 每日一图 的链接缓存时间
                        </th>
                        <td>
                            <fieldset>
                                <label for="speedup4cn_bing_cache_hour">
                                    <input type="number" name="speedup4cn[bing_cache_hour]" id="speedup4cn_bing_cache_hour" value="<?php echo $options['bing_cache_hour']; ?>" min="1" max="24" step="1"/>
                                   小时
                                </label>
                                <p class="description"></p>
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <?php do_action('speedup4cn_after_settings', $options);?>
                <?php submit_button()?>
            </form>
        </div><?php
}
	}

}
