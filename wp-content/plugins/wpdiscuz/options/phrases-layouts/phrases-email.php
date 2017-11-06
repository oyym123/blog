<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <h2 style="padding:5px 10px 10px 10px; margin:0px;"><?php _e('Email Template Phrases', 'wpdiscuz'); ?></h2>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wc_email_subject"><?php _e('New comment email subject', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_email_subject']; ?>" name="wc_email_subject" id="wc_email_subject" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_email_message"><?php _e('New comment email message', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_email_message" id="wc_email_message"><?php echo $this->optionsSerialized->phrases['wc_email_message']; ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_new_reply_email_subject"><?php _e('New reply subject', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_new_reply_email_subject']) ? $this->optionsSerialized->phrases['wc_new_reply_email_subject'] : _e('New Reply', 'wpdiscuz'); ?>" name="wc_new_reply_email_subject" id="wc_new_reply_email_subject" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_new_reply_email_message"><?php _e('New reply message', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_new_reply_email_message" id="wc_new_reply_email_message"><?php echo $this->optionsSerialized->phrases['wc_new_reply_email_message']; ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_unsubscribe"><?php _e('Unsubscribe', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" name="wc_unsubscribe" id="wc_unsubscribe" value="<?php echo $this->optionsSerialized->phrases['wc_unsubscribe']; ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_ignore_subscription"><?php _e('Ignore subscription', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" name="wc_ignore_subscription" id="wc_ignore_subscription" value="<?php echo isset($this->optionsSerialized->phrases['wc_ignore_subscription']) ? $this->optionsSerialized->phrases['wc_ignore_subscription'] : __('Cancel subscription', 'wpdiscuz'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_confirm_email"><?php _e('Confirm your subscription', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" name="wc_confirm_email" id="wc_confirm_email" value="<?php echo isset($this->optionsSerialized->phrases['wc_confirm_email']) ? $this->optionsSerialized->phrases['wc_confirm_email'] : __('Confirm your subscription', 'wpdiscuz'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comfirm_success_message"><?php _e('You\'ve successfully confirmed your subscription.', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_comfirm_success_message" id="wc_comfirm_success_message"><?php echo isset($this->optionsSerialized->phrases['wc_comfirm_success_message']) ? $this->optionsSerialized->phrases['wc_comfirm_success_message'] : __('You\'ve successfully confirmed your subscription.', 'wpdiscuz'); ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_confirm_email_subject"><?php _e('Subscribe confirmation email subject', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" name="wc_confirm_email_subject" id="wc_confirm_email_subject" value="<?php echo isset($this->optionsSerialized->phrases['wc_confirm_email_subject']) ? $this->optionsSerialized->phrases['wc_confirm_email_subject'] : __('Subscribe Confirmation', 'wpdiscuz'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_confirm_email_message"><?php _e('Subscribe confirmation email content', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_confirm_email_message" id="wc_confirm_email_message"><?php echo isset($this->optionsSerialized->phrases['wc_confirm_email_message']) ? $this->optionsSerialized->phrases['wc_confirm_email_message'] : __('Hi, <br/> You just subscribed for new comments on our website. This means you will receive an email when new comments are posted according to subscription option you\'ve chosen. <br/> To activate, click confirm below. If you believe this is an error, ignore this message and we\'ll never bother you again.', 'wpdiscuz'); ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_approved_email_subject"><?php _e('Comment approved subject', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" name="wc_comment_approved_email_subject" id="wc_comment_approved_email_subject" value="<?php echo isset($this->optionsSerialized->phrases['wc_comment_approved_email_subject']) ? $this->optionsSerialized->phrases['wc_comment_approved_email_subject'] : __('Comment was approved', 'wpdiscuz'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_approved_email_message"><?php _e('Comment approved message', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><textarea name="wc_comment_approved_email_message" id="wc_comment_approved_email_message"><?php echo isset($this->optionsSerialized->phrases['wc_comment_approved_email_message']) ? $this->optionsSerialized->phrases['wc_comment_approved_email_message'] : __('Hi, <br/> Your comment was approved', 'wpdiscuz'); ?></textarea></td>
            </tr>
        </tbody>
    </table>
</div>