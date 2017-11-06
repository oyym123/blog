jQuery(document).on( 'click', '.wpdiscuz_addon_note .notice-dismiss', function() {
	jQuery.ajax({url: ajaxurl, data: { action: 'dismiss_wpdiscuz_addon_note'}})
})
jQuery(document).on( 'click', '.wpdiscuz_tip_note .notice-dismiss', function() {
	var tipid = jQuery('#wpdiscuz_tip_note_value').val();
	jQuery.ajax({url: ajaxurl, data: { action: 'dismiss_wpdiscuz_tip_note', tip: tipid}})
})