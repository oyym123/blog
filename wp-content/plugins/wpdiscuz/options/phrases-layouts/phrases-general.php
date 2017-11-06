<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <h2 style="padding:5px 10px 10px 10px; margin:0px;"><?php _e('General Phrases', 'wpdiscuz'); ?></h2>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wc_be_the_first_text"><?php _e('Be the first to comment', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_be_the_first_text']; ?>" name="wc_be_the_first_text" id="wc_be_the_first_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_header_text"><?php _e('Comment', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_header_text']; ?>" name="wc_header_text" id="wc_header_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_header_text_plural"><?php _e('Comment (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_header_text_plural']; ?>" name="wc_header_text_plural" id="wc_header_text_plural" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_header_on_text"><?php _e('On', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_header_on_text']; ?>" name="wc_header_on_text" id="wc_header_on_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_load_more_submit_text"><?php _e('Load More Button', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_load_more_submit_text']; ?>" name="wc_load_more_submit_text" id="wc_load_more_submit_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_load_rest_comments_submit_text"><?php _e('Load Rest of Comments', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_load_rest_comments_submit_text']) ? $this->optionsSerialized->phrases['wc_load_rest_comments_submit_text'] : 'Load Rest of Comments'; ?>" name="wc_load_rest_comments_submit_text" id="wc_load_rest_comments_submit_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_new_comment_button_text"><?php _e('Button text if has new comment', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_new_comment_button_text']) ? $this->optionsSerialized->phrases['wc_new_comment_button_text'] : __('New Comment', 'wpdisucz'); ?>" name="wc_new_comment_button_text" id="wc_new_comment_button_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_new_comments_button_text"><?php _e('Button text if has new comments (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_new_comments_button_text']) ? $this->optionsSerialized->phrases['wc_new_comments_button_text'] : __('New Comments', 'wpdisucz'); ?>" name="wc_new_comments_button_text" id="wc_new_comments_button_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_new_reply_button_text"><?php _e('Button text if has new reply', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_new_reply_button_text']) ? $this->optionsSerialized->phrases['wc_new_reply_button_text'] : __('New Reply', 'wpdisucz'); ?>" name="wc_new_reply_button_text" id="wc_new_reply_button_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_new_replies_button_text"><?php _e('Button text if has new replies (Plural Form)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_new_replies_button_text']) ? $this->optionsSerialized->phrases['wc_new_replies_button_text'] : __('New Replies', 'wpdisucz'); ?>" name="wc_new_replies_button_text" id="wc_new_replies_button_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_new_comments_text"><?php _e('Text on load more button if has new comment(s)', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_new_comments_text']) ? $this->optionsSerialized->phrases['wc_new_comments_text'] : __('New', 'wpdisucz'); ?>" name="wc_new_comments_text" id="wc_new_comments_text" /></td>
            </tr>
        </tbody>
    </table>
</div>