<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <h2 style="padding:5px 10px 10px 10px; margin:0px;"><?php _e('Notification Phrases', 'wpdiscuz'); ?></h2>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wc_subscribed_to"><?php _e("You\'re subscribed to", 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_subscribed_to']; ?>" name="wc_subscribed_to" id="wc_subscribed_to" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_subscribe_message"><?php _e('You\'ve successfully subscribed.', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_subscribe_message" id="wc_subscribe_message"><?php echo $this->optionsSerialized->phrases['wc_subscribe_message']; ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_unsubscribe_message"><?php _e('You\'ve successfully unsubscribed.', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_unsubscribe_message" id="wc_unsubscribe_message"><?php echo $this->optionsSerialized->phrases['wc_unsubscribe_message']; ?></textarea></td>
            </tr>
            <?php if (class_exists('Prompt_Comment_Form_Handling') && $this->optionsSerialized->usePostmaticForCommentNotification) { ?>
                <tr valign="top">
                    <th scope="row"><label for="wc_postmatic_subscription_label"><?php _e("Postmatic subscription label", 'wpdiscuz'); ?></label></th>
                    <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_postmatic_subscription_label']; ?>" name="wc_postmatic_subscription_label" id="wc_postmatic_subscription_label" /></td>
                </tr>
            <?php } ?> 
            <tr valign="top">
                <th scope="row"><label for="wc_error_empty_text"><?php _e('Error message for empty field', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_error_empty_text']; ?>" name="wc_error_empty_text" id="wc_error_empty_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_error_email_text"><?php _e('Error message for invalid email field', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_error_email_text']; ?>" name="wc_error_email_text" id="wc_error_email_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_error_url_text"><?php _e('Error message for invalid website url field', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_error_url_text']; ?>" name="wc_error_url_text" id="wc_error_url_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_you_must_be_text"><?php _e('You must be', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_you_must_be_text']; ?>" name="wc_you_must_be_text" id="wc_you_must_be_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_logged_in_as"><?php _e('Logged in as', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_logged_in_as']; ?>" name="wc_logged_in_as" id="wc_logged_in_as" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_log_out"><?php _e('Log out', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_log_out']; ?>" name="wc_log_out" id="wc_log_out" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_logged_in_text"><?php _e('Logged In', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_logged_in_text']; ?>" name="wc_logged_in_text" id="wc_logged_in_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_to_post_comment_text"><?php _e('To post a comment', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_to_post_comment_text']; ?>" name="wc_to_post_comment_text" id="wc_to_post_comment_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_vote_counted"><?php _e('Vote Counted', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_vote_counted']; ?>" name="wc_vote_counted" id="wc_vote_counted" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_vote_only_one_time"><?php _e('You can vote only 1 time', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_vote_only_one_time']; ?>" name="wc_vote_only_one_time" id="wc_vote_only_one_time" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_voting_error"><?php _e('Voting Error', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_voting_error']; ?>" name="wc_voting_error" id="wc_voting_error" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_login_to_vote"><?php _e('Login To Vote', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_login_to_vote']; ?>" name="wc_login_to_vote" id="wc_login_to_vote" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_self_vote"><?php _e('You Cannot Vote On Your Comment', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_self_vote']; ?>" name="wc_self_vote" id="wc_self_vote" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_deny_voting_from_same_ip"><?php _e('You are not allowed to vote for this comment (Voting from same IP)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_deny_voting_from_same_ip']) ? $this->optionsSerialized->phrases['wc_deny_voting_from_same_ip'] : 'You are not allowed to vote for this comment'; ?>" name="wc_deny_voting_from_same_ip" id="wc_deny_voting_from_same_ip" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_invalid_captcha"><?php _e('Invalid Captcha Code', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_invalid_captcha']; ?>" name="wc_invalid_captcha" id="wc_invalid_captcha" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_invalid_field"><?php _e('Some of field value is invalid', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_invalid_field']; ?>" name="wc_invalid_field" id="wc_invalid_field" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_held_for_moderate"><?php _e('Comment waiting moderation', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_held_for_moderate']; ?>" name="wc_held_for_moderate" id="wc_held_for_moderate" /></td>
            </tr>            
            <tr valign="top">
                <th scope="row"><label for="wc_msg_input_min_length"><?php _e('Message if input text length is too short', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_msg_input_min_length']; ?>" name="wc_msg_input_min_length" id="wc_msg_input_min_length" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_msg_input_max_length"><?php _e('Message if input text length is too long', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_msg_input_max_length']; ?>" name="wc_msg_input_max_length" id="wc_msg_input_max_length" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="wc_comment_not_updated"><?php _e('Message if comment was not updated', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_comment_not_updated']) ? $this->optionsSerialized->phrases['wc_comment_not_updated'] : __('Sorry, the comment was not updated', 'wpdisucz'); ?>" name="wc_comment_not_updated" id="wc_comment_not_updated" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_edit_not_possible"><?php _e('Message if comment no longer possible to edit', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_comment_edit_not_possible']) ? $this->optionsSerialized->phrases['wc_comment_edit_not_possible'] : __('Sorry, this comment no longer possible to edit', 'wpdisucz'); ?>" name="wc_comment_edit_not_possible" id="wc_comment_edit_not_possible" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_not_edited"><?php _e('Message if comment text not changed', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_comment_not_edited']) ? $this->optionsSerialized->phrases['wc_comment_not_edited'] : __('TYou\'ve not made any changes', 'wpdisucz'); ?>" name="wc_comment_not_edited" id="wc_comment_not_edited" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_msg_required_fields"><?php _e('Please fill out required fields', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_msg_required_fields']) ? $this->optionsSerialized->phrases['wc_msg_required_fields'] : __('Please fill out required fields', 'wpdisucz'); ?>" name="wc_msg_required_fields" id="wc_msg_required_fields" /></td>
            </tr>
        </tbody>
    </table>
</div>