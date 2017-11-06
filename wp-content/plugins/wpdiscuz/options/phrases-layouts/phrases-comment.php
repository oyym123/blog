<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <h2 style="padding:5px 10px 10px 10px; margin:0px;"><?php _e('Comment Template Phrases', 'wpdiscuz'); ?></h2>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wc_reply_text"><?php _e('Reply', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_reply_text']; ?>" name="wc_reply_text" id="wc_submit_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_share_text"><?php _e('Share', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_share_text']; ?>" name="wc_share_text" id="wc_share_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_edit_text"><?php _e('Edit', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_edit_text']; ?>" name="wc_edit_text" id="wc_edit_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_share_facebook"><?php _e('Share On Facebook', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_share_facebook']; ?>" name="wc_share_facebook" id="wc_share_facebook" /></td>
            </tr>
            <tr valign="top" >
                <th scope="row"><label for="wc_share_twitter"><?php _e('Share On Twitter', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_share_twitter']; ?>" name="wc_share_twitter" id="wc_share_twitter" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_share_google"><?php _e('Share On Google', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_share_google']; ?>" name="wc_share_google" id="wc_share_google" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_share_vk"><?php _e('Share On VKontakte', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_share_vk']; ?>" name="wc_share_vk" id="wc_share_vk" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_share_ok"><?php _e('Share On Odnoklassniki', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_share_ok']; ?>" name="wc_share_ok" id="wc_share_ok" /></td>
            </tr>
            <tr valign="top" >
                <th scope="row"><label for="wc_hide_replies_text"><?php _e('Hide Replies', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_hide_replies_text']; ?>" name="wc_hide_replies_text" id="wc_hide_replies_text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_show_replies_text"><?php _e('Show Replies', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_show_replies_text']; ?>" name="wc_show_replies_text" id="wc_show_replies_text" /></td>
            </tr>
            <?php
            $roles = $this->optionsSerialized->blogRoles;
            foreach ($roles as $roleName => $color) {
                $phraseRoleLabel = ucfirst(str_replace('_', ' ', $roleName));

                if ($roleName == 'administrator') {
                    $roleTitle = isset($this->optionsSerialized->phrases['wc_blog_role_' . $roleName]) ? $this->optionsSerialized->phrases['wc_blog_role_' . $roleName] : __('Admin', 'wpdiscuz');
                } elseif ($roleName == 'post_author') {
                    $roleTitle = isset($this->optionsSerialized->phrases['wc_blog_role_' . $roleName]) ? $this->optionsSerialized->phrases['wc_blog_role_' . $roleName] : __('Author', 'wpdiscuz');
                } elseif ($roleName == 'editor') {
                    $roleTitle = isset($this->optionsSerialized->phrases['wc_blog_role_' . $roleName]) ? $this->optionsSerialized->phrases['wc_blog_role_' . $roleName] : ucfirst(str_replace('_', ' ', $roleName));
                } elseif ($roleName == 'guest') {
                    $roleTitle = isset($this->optionsSerialized->phrases['wc_blog_role_' . $roleName]) ? $this->optionsSerialized->phrases['wc_blog_role_' . $roleName] : __('Guest', 'wpdiscuz');
                } else {
                    $roleTitle = isset($this->optionsSerialized->phrases['wc_blog_role_' . $roleName]) ? $this->optionsSerialized->phrases['wc_blog_role_' . $roleName] : __('Member', 'wpdiscuz');
                }
                ?>
                <tr valign="top">
                    <th scope="row"><label for="wc_blog_role_<?php echo $roleName; ?>"><?php echo $phraseRoleLabel; ?></label></th>
                    <td><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_blog_role_' . $roleName]) ? $this->optionsSerialized->phrases['wc_blog_role_' . $roleName] : $roleTitle; ?>" id="wc_blog_role_<?php echo $roleName; ?>" name="wc_blog_role_<?php echo $roleName; ?>"/></td>
                </tr>
                <?php
            }
            ?>
            <tr valign="top">
                <th scope="row"><label for="wc_vote_up"><?php _e('Vote Up', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_vote_up']; ?>" name="wc_vote_up" id="wc_vote_up" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_vote_down"><?php _e('Vote Down', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo $this->optionsSerialized->phrases['wc_vote_down']; ?>" name="wc_vote_down" id="wc_vote_down" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_edit_save_button"><?php _e('Save edited comment button text', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_comment_edit_save_button']) ? $this->optionsSerialized->phrases['wc_comment_edit_save_button'] : __('Save', 'wpdisucz'); ?>" name="wc_comment_edit_save_button" id="wc_comment_edit_save_button" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_comment_edit_cancel_button"><?php _e('Cancel comment editing button text', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_comment_edit_cancel_button']) ? $this->optionsSerialized->phrases['wc_comment_edit_cancel_button'] : __('Cancel', 'wpdisucz'); ?>" name="wc_comment_edit_cancel_button" id="wc_comment_edit_cancel_button" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_read_more"><?php _e('Comment read more link text', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_read_more']) ? $this->optionsSerialized->phrases['wc_read_more'] : __('Read more &raquo;', 'wpdisucz'); ?>" name="wc_read_more" id="wc_read_more" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wc_anonymous"><?php _e('Anonymous commenter name', 'wpdiscuz'); ?></label></th>
                <td colspan="3"><input type="text" value="<?php echo isset($this->optionsSerialized->phrases['wc_anonymous']) ? $this->optionsSerialized->phrases['wc_anonymous'] : __('Anonymous', 'wpdisucz'); ?>" name="wc_anonymous" id="wc_anonymous" /></td>
            </tr>
        </tbody>
    </table>
</div>