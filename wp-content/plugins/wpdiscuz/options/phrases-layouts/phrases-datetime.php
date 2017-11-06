<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <h2 style="padding:5px 10px 10px 10px; margin:0px;"><?php _e('Date/Time Phrases', 'wpdiscuz'); ?></h2>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wc_year_text"><?php _e('Year', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_year_text']['datetime'][0]; ?>" name="wc_year_text" id="wc_year_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_year_text_plural"><?php _e('Years (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_year_text_plural']['datetime'][0]) ? $this->optionsSerialized->phrases['wc_year_text_plural']['datetime'][0] : __('Years', 'wpdiscuz'); ?>" name="wc_year_text_plural" id="wc_year_text_plural" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_month_text"><?php _e('Month', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_month_text']['datetime'][0]; ?>" name="wc_month_text" id="wc_month_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_month_text_plural"><?php _e('Months (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_month_text_plural']['datetime'][0]; ?>" name="wc_month_text_plural" id="wc_month_text_plural" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_day_text"><?php _e('Day', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_day_text']['datetime'][0]; ?>" name="wc_day_text" id="wc_day_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_day_text_plural"><?php _e('Days (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_day_text_plural']['datetime'][0]; ?>" name="wc_day_text_plural" id="wc_day_text_plural" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_hour_text"><?php _e('Hour', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_hour_text']['datetime'][0]; ?>" name="wc_hour_text" id="wc_hour_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_hour_text_plural"><?php _e('Hours (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_hour_text_plural']['datetime'][0]; ?>" name="wc_hour_text_plural" id="wc_hour_text_plural" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_minute_text"><?php _e('Minute', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_minute_text']['datetime'][0]; ?>" name="wc_minute_text" id="wc_minute_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_minute_text_plural"><?php _e('Minutes (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_minute_text_plural']['datetime'][0]; ?>" name="wc_minute_text_plural" id="wc_minute_text_plural" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_second_text"><?php _e('Second', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_second_text']['datetime'][0]; ?>" name="wc_second_text" id="wc_second_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_second_text_plural"><?php _e('Seconds (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_second_text_plural']['datetime'][0]; ?>" name="wc_second_text_plural" id="wc_second_text_plural" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_right_now_text"><?php _e('Commented "right now" text', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_right_now_text']; ?>" name="wc_right_now_text" id="wc_right_now_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_ago_text"><?php _e('Ago text', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_ago_text']; ?>" name="wc_ago_text" id="wc_ago_text" /></td>
            </tr>            
        </tbody>
    </table>
</div>