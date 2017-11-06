<?php


namespace wpdFormAttr\Field;

class TextAreaField extends Field {

    protected function dashboardForm() {
        ?>
        <div class="wpd-field-body" style="display: <?php echo $this->display; ?>">
            <div class="wpd-field-option wpdiscuz-item">
                <input class="wpd-field-type" type="hidden" value="<?php echo $this->type; ?>" name="<?php echo $this->fieldInputName; ?>[type]" />
                <label><?php _e('Name', 'wpdiscuz'); ?>:</label> 
                <input class="wpd-field-name" type="text" value="<?php echo $this->fieldData['name']; ?>" name="<?php echo $this->fieldInputName; ?>[name]" required />
                <p class="wpd-info"><?php _e('Also used for field placeholder', 'wpdiscuz'); ?></p>
            </div>
            <div class="wpd-field-option">
                <label><?php _e('Description', 'wpdiscuz'); ?>:</label> 
                <input type="text" value="<?php echo $this->fieldData['desc']; ?>" name="<?php echo $this->fieldInputName; ?>[desc]" />
                <p class="wpd-info"><?php _e('Field specific short description or some rule related to inserted information.', 'wpdiscuz'); ?></p>
            </div>
            <div class="wpd-field-option">
                <label><?php _e('Field is required', 'wpdiscuz'); ?>:</label> 
                <input type="checkbox" value="1" <?php checked($this->fieldData['required'], 1, true); ?> name="<?php echo $this->fieldInputName; ?>[required]" />
            </div>
            <div class="wpd-field-option">
                <label><?php _e('Display on reply form', 'wpdiscuz'); ?>:</label> 
                <input type="checkbox" value="1" <?php checked($this->fieldData['is_show_sform'], 1, true); ?> name="<?php echo $this->fieldInputName; ?>[is_show_sform]" />
            </div>
            <div class="wpd-field-option">
                <label><?php _e('Display on comment', 'wpdiscuz'); ?>:</label> 
                <input type="checkbox" value="1" <?php checked($this->fieldData['is_show_on_comment'], 1, true); ?> name="<?php echo $this->fieldInputName; ?>[is_show_on_comment]" />
            </div>
            <div class="wpd-advaced-options wpd-field-option">
                <small class="wpd-advaced-options-title"><?php _e('Advanced Options', 'wpdiscuz'); ?></small>
                <div class="wpd-field-option wpd-advaced-options-cont">
                    <div class="wpd-field-option">
                        <label><?php _e('Meta Key', 'wpdiscuz'); ?>:</label> 
                        <input type="text" value="<?php echo $this->name; ?>"  name="<?php echo $this->fieldInputName; ?>[meta_key]"  required="required"/>
                    </div>
                    <div class="wpd-field-option">
                        <label><?php _e('Replace old meta key', 'wpdiscuz'); ?>:</label> 
                        <input type="checkbox" value="1" checked="checked"  name="<?php echo $this->fieldInputName; ?>[meta_key_replace]" />
                    </div>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php
    }

    public function editCommentHtml($key, $value, $data,$comment) {
        if ($comment->comment_parent && !$data['is_show_sform']) {
            return '';
        }
        $html = '<tr><td class="first">';
        $html .= '<label for = "' . $key . '">' . $data['name'] . ': </label>';
        $html .= '</td><td>';
        $html .= '<div class="wpdiscuz-item">';
        $required = $data['required'] ? 'required="required"' : '';
        $html .= '<textarea  ' . $required . ' class="wpd-field wpd-field-textarea" id="' . $key . '"  name="' . $key . '">'.$value.'</textarea>';
        $html .= '</div>';
        $html .= '</td></tr >';
        return $html;
    }

    public function frontFormHtml($name, $args, $options, $currentUser, $uniqueId, $isMainForm) {
        if(!$isMainForm && !$args['is_show_sform']){
            return;
        }
        ?>
        <div class="wpdiscuz-item">
            <?php $required = $args['required'] ? 'required="required"' : ''; ?>
            <textarea <?php echo $required; ?> class="<?php echo $name; ?> wpd-field wpd-field-textarea"  name="<?php echo $name; ?>" value="" placeholder="<?php _e($args['name'], 'wpdiscuz'); ?>"></textarea>
            <?php if ($args['desc']) { ?>
            <div class="wpd-field-desc"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span><?php echo esc_html($args['desc']); ?></span></div>
            <?php } ?>
        </div>
        <?php
    }

    public function frontHtml($value, $args) {
        if(!$args['is_show_on_comment']){
            return '';
        }
        $html = '<div class="wpd-custom-field wpd-cf-text">';
        $html .= '<div class="wpd-cf-label">' . $args['name'] . '</div> <div class="wpd-cf-value"> ' . apply_filters('wpdiscuz_custom_field_textarea', $value , $args) . '</div>';
        $html .= '</div>';
        return $html;
    }

    public function validateFieldData($fieldName, $args, $options, $currentUser) {
        if(!$this->isCommentParentZero() && !$args['is_show_sform']){
            return '';
        }
        $value = filter_input(INPUT_POST, $fieldName, FILTER_SANITIZE_STRING);
        if (!$value && $args['required']) {
            wp_die(__($args['name'], 'wpdiscuz') . ' : ' . __('field is required!', 'wpdiscuz'));
        }
        return $value;
    }

}
