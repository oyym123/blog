<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <h2 style="padding:5px 10px 10px 10px; margin:0px;"><?php _e('Form Template Phrases', 'wpdiscuz'); ?></h2>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_start_text"><?php _e('Comment Field Start', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_comment_start_text']; ?>" name="wc_comment_start_text" id="wc_comment_start_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_join_text"><?php _e('Comment Field Join', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_comment_join_text']; ?>" name="wc_comment_join_text" id="wc_comment_join_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_email_text"><?php _e('Email Field', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_email_text']; ?>" name="wc_email_text" id="wc_email_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_notify_of"><?php _e('Notify of', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_notify_of']) ? $this->optionsSerialized->phrases['wc_notify_of'] : __('Notify of', 'wpdiscuz'); ?>" name="wc_notify_of" id="wc_notify_of" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_notify_on_new_comment"><?php _e('Notify on new comments', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_notify_on_new_comment']) ? $this->optionsSerialized->phrases['wc_notify_on_new_comment'] : __('new follow-up comments', 'wpdiscuz'); ?>" name="wc_notify_on_new_comment" id="wc_notify_on_new_comment" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_notify_on_all_new_reply"><?php _e('Notify on all new replies', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_notify_on_all_new_reply']) ? $this->optionsSerialized->phrases['wc_notify_on_all_new_reply'] : __('new replies to all my comments', 'wpdiscuz'); ?>" name="wc_notify_on_all_new_reply" id="wc_notify_on_all_new_reply" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_notify_on_new_reply"><?php _e('Notify on new replies (checkbox)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_notify_on_new_reply']) ? $this->optionsSerialized->phrases['wc_notify_on_new_reply'] : __('Notify of new replies to this comment', 'wpdiscuz'); ?>" name="wc_notify_on_new_reply" id="wc_notify_on_new_reply" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_sort_by"><?php _e('Sort by', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_sort_by']) ? $this->optionsSerialized->phrases['wc_sort_by'] : __('Sort by', 'wpdiscuz'); ?>" name="wc_sort_by" id="wc_sort_by" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_newest"><?php _e('newest', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_newest']) ? $this->optionsSerialized->phrases['wc_newest'] : __('newest', 'wpdiscuz'); ?>" name="wc_newest" id="wc_newest" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_oldest"><?php _e('oldest', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_oldest']) ? $this->optionsSerialized->phrases['wc_oldest'] : __('oldest', 'wpdiscuz'); ?>" name="wc_oldest" id="wc_oldest" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_most_voted"><?php _e('most voted', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_most_voted']) ? $this->optionsSerialized->phrases['wc_most_voted'] : __('most voted', 'wpdiscuz'); ?>" name="wc_most_voted" id="wc_most_voted" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_subscribed_on_comment"><?php _e('Subscribed on this comment replies', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_subscribed_on_comment" id="wc_subscribed_on_comment"><?php echo $this->optionsSerialized->phrases['wc_subscribed_on_comment']; ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_subscribed_on_all_comment"><?php _e('Subscribed on all your comments replies', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_subscribed_on_all_comment" id="wc_subscribed_on_all_comment"><?php echo $this->optionsSerialized->phrases['wc_subscribed_on_all_comment']; ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_subscribed_on_post"><?php _e('Subscribed on this post', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_subscribed_on_post" id="wc_subscribed_on_post"><?php echo $this->optionsSerialized->phrases['wc_subscribed_on_post']; ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_connect_with"><?php _e('Connect with', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_connect_with']) ? $this->optionsSerialized->phrases['wc_connect_with'] : __('Connect with', 'wpdiscuz'); ?>" name="wc_connect_with" id="wc_connect_with" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_form_subscription_submit"><?php _e('Form subscription button', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_form_subscription_submit']) ? $this->optionsSerialized->phrases['wc_form_subscription_submit'] : __('&rsaquo;', 'wpdiscuz'); ?>" name="wc_form_subscription_submit" id="wc_form_subscription_submit" /></td>
            </tr>
        </tbody>
    </table>
</div>