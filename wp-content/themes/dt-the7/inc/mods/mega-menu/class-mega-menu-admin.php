<?php
/**
 * Dt Mega menu admin class.
 *
 * inspired by http://www.wpexplorer.com/adding-custom-attributes-to-wordpress-menus/
 */

if ( ! class_exists( 'Presscore_Modules_MegaMenu_Admin', false ) ) :

	class Presscore_Modules_MegaMenu_Admin {

		public static function execute() {
			return new Presscore_Modules_MegaMenu_Admin();
		}

		function __construct() {

			// setup custom menu fields
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_custom_nav_fields' ) );

			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields' ), 10, 2 );

			// replace menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'replace_walker_class' ), 90, 2 );

			// add menu item custom fields
			add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'wp_nav_menu_item_custom_fields' ), 99, 4 );

			// add admin css
			add_action( 'admin_print_styles-nav-menus.php', array( $this, 'add_admin_menu_inline_css' ), 15 );

			// add some javascript
			add_action( 'admin_print_footer_scripts', array( $this, 'javascript_magic' ), 99 );
		}

		/**
		 * Setup custom menu item fields before output.
		 */
		function setup_custom_nav_fields( $menu_item ) {

			// common
			$menu_item->dt_mega_menu_icon = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_icon', true );
			$menu_item->dt_mega_menu_iconfont = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_iconfont', true );

			// first level
			$menu_item->dt_mega_menu_enabled = (bool) get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_enabled', true );
			$menu_item->dt_mega_menu_fullwidth = (bool) get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_fullwidth', true );
			$menu_item->dt_mega_menu_columns = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_columns', true );

			// second level
			$menu_item->dt_mega_menu_hide_title = (bool) get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_hide_title', true );
			$menu_item->dt_mega_menu_remove_link = (bool) get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_remove_link', true );
			$menu_item->dt_mega_menu_new_row = (bool) get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_new_row', true );

			// third level
			$menu_item->dt_mega_menu_new_column = (bool) get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_new_column', true );

			return $menu_item;
		}

		/**
		 * Update custom menu item fields.
		 */
		function update_custom_nav_fields( $menu_id, $menu_db_id ) {
		    if ( isset( $_POST['menu-item-dt-icon'][ $menu_db_id ] ) && in_array( $_POST['menu-item-dt-icon'][ $menu_db_id ], array(
				    'image',
				    'iconfont',
			    ) ) ) {
			    $icon = $_POST['menu-item-dt-icon'][ $menu_db_id ];
            } else {
		        $icon = 'none';
            }

			if ( 'none' !== $icon ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_icon', $icon );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_icon' );
			}

			if ( ! empty( $_POST['menu-item-dt-iconfont'][ $menu_db_id ] ) && 'iconfont' == $icon ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_iconfont', wp_kses( $_POST['menu-item-dt-iconfont'][ $menu_db_id ], array( 'i' => array( 'class' => array(), 'aria-hidden' => array() ) ) ) );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_iconfont' );
			}

			$mega_menu_enabled = isset( $_POST['menu-item-dt-enable-mega-menu'][ $menu_db_id ] );
			if ( $mega_menu_enabled ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_enabled', 'on' );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_enabled' );
			}

			if ( $mega_menu_enabled && isset( $_POST['menu-item-dt-fullwidth-menu'][ $menu_db_id ] ) ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_fullwidth', 'on' );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_fullwidth' );
			}

			if ( $mega_menu_enabled && ! empty( $_POST['menu-item-dt-columns'][ $menu_db_id ] ) ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_columns', absint( $_POST['menu-item-dt-columns'][ $menu_db_id ] ) );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_columns' );
			}

			if ( $mega_menu_enabled && isset( $_POST['menu-item-dt-hide-title'][ $menu_db_id ] ) ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_hide_title', 'on' );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_hide_title' );
			}

			if ( $mega_menu_enabled && isset( $_POST['menu-item-dt-remove-link'][ $menu_db_id ] ) ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_remove_link', 'on' );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_remove_link' );
			}

			if ( $mega_menu_enabled && isset( $_POST['menu-item-dt-new-row'][ $menu_db_id ] ) ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_new_row', 'on' );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_new_row' );
			}

			if ( $mega_menu_enabled && isset( $_POST['menu-item-dt-new-column'][ $menu_db_id ] ) ) {
				update_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_new_column', 'on' );
			} else {
				delete_post_meta( $menu_db_id, '_menu_item_dt_mega_menu_new_column' );
			}
		}

		/**
		 * Replace Walker_Nav_Menu_Edit with custom one.
		 */
		function replace_walker_class( $walker, $menu_id ) {

			if ( 'Walker_Nav_Menu_Edit' == $walker ) {
				$walker = 'Presscore_Modules_MegaMenu_EditMenuWalker';
			}

			return $walker;
		}

		/**
		 * Add custom menu item fields.
		 */
		public function wp_nav_menu_item_custom_fields( $item_id, $item, $depth, $args ) {
			// set default item fields
			$default_mega_menu_fields = array(
				'dt_mega_menu_icon' => 'none',
				'dt_mega_menu_iconfont' => '',
				'dt_mega_menu_enabled' => 0,
				'dt_mega_menu_fullwidth' => 0,
				'dt_mega_menu_columns' => 3,
				'dt_mega_menu_hide_title' => 0,
				'dt_mega_menu_remove_link' => 0,
				'dt_mega_menu_new_row' => 0,
				'dt_mega_menu_new_column' => 0
			);

			// set defaults
			foreach ( $default_mega_menu_fields as $field=>$value ) {
				if ( !isset($item->$field) ) {
					$item->$field = $value;
				}
			}

			// for ajax added items
			if ( empty( $item->dt_mega_menu_icon ) ) {
				$item->dt_mega_menu_icon = 'none';
			}

			if ( empty( $item->dt_mega_menu_columns ) ) {
				$item->dt_mega_menu_columns = 3;
			}

			$mega_menu_container_classes = array( 'dt-mega-menu-feilds' );
			switch ( $item->dt_mega_menu_icon ) {
				case 'iconfont': $mega_menu_container_classes[] = 'field-dt-mega-menu-iconfont-icon';
			}

			$mega_menu_container_classes = implode( ' ', $mega_menu_container_classes );
			?>

			<!-- DT Mega Menu Start -->

			<div class="<?php echo esc_attr( $mega_menu_container_classes ); ?>">

				<p class="field-dt-icon description description-wide">
					<?php _ex( 'Icon :', 'edit menu walker', 'the7mk2' ); ?>
					<label>
						<input type="radio" name="menu-item-dt-icon[<?php echo $item_id; ?>]" value="none" <?php checked( $item->dt_mega_menu_icon, 'none' ); ?>/>
						<?php _ex( 'no', 'edit menu walker', 'the7mk2' ); ?>
					</label>
					<label>
						<input type="radio" name="menu-item-dt-icon[<?php echo $item_id; ?>]" value="iconfont" <?php checked( $item->dt_mega_menu_icon, 'iconfont' ); ?>/>
						<?php _ex( 'iconfont', 'edit menu walker', 'the7mk2' ); ?>
					</label>
				</p>
				<p class="field-dt-iconfont description description-wide">
					<label>
						<?php _ex( 'Iconfont code', 'edit menu walker', 'the7mk2' ); ?><br />
						<textarea class="widefat edit-menu-item-iconfont" rows="3" cols="20" name="menu-item-dt-iconfont[<?php echo $item_id; ?>]"><?php echo esc_html( $item->dt_mega_menu_iconfont ); // textarea_escaped ?></textarea>
					</label>
				</p>

				<!-- first level -->
				<p class="field-dt-enable-mega-menu">
					<label for="edit-menu-item-dt-enable-mega-menu-<?php echo $item_id; ?>">
						<input id="edit-menu-item-dt-enable-mega-menu-<?php echo $item_id; ?>" type="checkbox" class="menu-item-dt-enable-mega-menu" name="menu-item-dt-enable-mega-menu[<?php echo $item_id; ?>]" <?php checked( $item->dt_mega_menu_enabled ); ?>/>
						<?php _ex( 'Enable Mega Menu', 'edit menu walker', 'the7mk2' ); ?>
					</label>
				</p>
				<p class="field-dt-fullwidth-menu">
					<label for="edit-menu-item-dt-fullwidth-menu-<?php echo $item_id; ?>">
						<input id="edit-menu-item-dt-fullwidth-menu-<?php echo $item_id; ?>" type="checkbox" name="menu-item-dt-fullwidth-menu[<?php echo $item_id; ?>]" <?php checked( $item->dt_mega_menu_fullwidth ); ?>/>
						<?php _ex( 'Fullwidth', 'edit menu walker', 'the7mk2' ); ?>
					</label>
				</p>
				<p class="field-dt-columns description description-wide">
					<?php _ex( 'Number of columns: ', 'edit menu walker', 'the7mk2' ); ?>
					<select name="menu-item-dt-columns[<?php echo $item_id; ?>]" id="edit-menu-item-dt-columns-<?php echo $item_id; ?>">
						<?php foreach( array( '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5 ) as $title=>$value): ?>
							<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->dt_mega_menu_columns); ?>><?php echo esc_html($title); ?></option>
						<?php endforeach; ?>
					</select>
				</p>

				<!-- second level -->
				<p class="field-dt-hide-title">
					<label for="edit-menu-item-dt-hide-title-<?php echo $item_id; ?>">
						<input id="edit-menu-item-dt-hide-title-<?php echo $item_id; ?>" type="checkbox" name="menu-item-dt-hide-title[<?php echo $item_id; ?>]" <?php checked( $item->dt_mega_menu_hide_title ); ?>/>
						<?php _ex( 'Hide title in mega menu', 'edit menu walker', 'the7mk2' ); ?>
					</label>
				</p>
				<p class="field-dt-remove-link">
					<label for="edit-menu-item-dt-remove-link-<?php echo $item_id; ?>">
						<input id="edit-menu-item-dt-remove-link-<?php echo $item_id; ?>" type="checkbox" name="menu-item-dt-remove-link[<?php echo $item_id; ?>]" <?php checked( $item->dt_mega_menu_remove_link ); ?>/>
						<?php _ex( 'Remove link', 'edit menu walker', 'the7mk2' ); ?>
					</label>
				</p>
				<p class="field-dt-new-row">
					<label for="edit-menu-item-dt-new-row-<?php echo $item_id; ?>">
						<input id="edit-menu-item-dt-new-row-<?php echo $item_id; ?>" type="checkbox" name="menu-item-dt-new-row[<?php echo $item_id; ?>]" <?php checked( $item->dt_mega_menu_new_row ); ?>/>
						<?php _ex( 'This item should start a new row', 'edit menu walker', 'the7mk2' ); ?>
					</label>
				</p>

				<!-- third level -->
				<p class="field-dt-new-column">
					<label for="edit-menu-item-dt-new-column-<?php echo $item_id; ?>">
						<input id="edit-menu-item-dt-new-column-<?php echo $item_id; ?>" type="checkbox" name="menu-item-dt-new-column[<?php echo $item_id; ?>]" <?php checked( $item->dt_mega_menu_new_column ); ?>/>
						<?php _ex( 'This item should start a new column', 'edit menu walker', 'the7mk2' ); ?>
					</label>
				</p>

			</div>

			<!-- DT Mega Menu End -->

			<?php
		}

		/**
		 * Add some beautiful inline css for admin menus.
		 */
		function add_admin_menu_inline_css() {
			$css = '
				.menu.ui-sortable .dt-mega-menu-feilds p,
				.menu.ui-sortable .dt-mega-menu-feilds .field-dt-image {
					display: none;
				}

				.menu.ui-sortable .menu-item-depth-0 .dt-mega-menu-feilds .field-dt-enable-mega-menu,
				.menu.ui-sortable .dt-mega-menu-feilds .field-dt-icon,
				.menu.ui-sortable .dt-mega-menu-feilds.field-dt-mega-menu-image-icon .field-dt-image,
				.menu.ui-sortable .dt-mega-menu-feilds.field-dt-mega-menu-iconfont-icon .field-dt-iconfont {
					display: block;
				}

				.menu.ui-sortable .menu-item-depth-0.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-fullwidth-menu,
				.menu.ui-sortable .menu-item-depth-0.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-columns,

				.menu.ui-sortable .menu-item-depth-1.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-hide-title,
				.menu.ui-sortable .menu-item-depth-1.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-remove-link,
				.menu.ui-sortable .menu-item-depth-1.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-new-row,

				.menu.ui-sortable .menu-item-depth-2.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-new-column {
					display: block;
				}
			';
			wp_add_inline_style( 'wp-admin', $css );
		}

		/**
		 * Javascript magic.
		 */
		function javascript_magic() {
			?>
			<script type="text/javascript">
				(function($){
				    $(function() {
                        var dt_fat_menu = {
                            reTimeout: false,

                            recalc: function () {
                                $menuItems = $('.menu-item', this.menuObj);

                                $menuItems.each(function (i) {
                                    var $item = $(this),
                                        $checkbox = $item.find('.menu-item-dt-enable-mega-menu');

                                    if ($item.is('.menu-item-depth-0')) {
                                        if ($checkbox.is(':checked')) {
                                            $item.addClass('field-dt-mega-menu-enabled');
                                        }
                                    } else {
                                        var checkItem = $menuItems.filter(':eq(' + (i - 1) + ')');
                                        if (checkItem.is('.field-dt-mega-menu-enabled')) {
                                            console.log('check');
                                            $item.addClass('field-dt-mega-menu-enabled');
                                            $checkbox.attr('checked', 'checked');
                                        } else {
                                            console.log('un check', $checkbox);
                                            $item.removeClass('field-dt-mega-menu-enabled');
                                            $checkbox.removeAttr('checked');
                                        }
                                    }
                                });
                            },

                            binds: function () {

                                this.menuObj.on('click', '.menu-item-dt-enable-mega-menu', function () {
                                    var $checkbox = $(this),
                                        $container = $checkbox.parents('.menu-item:eq(0)');

                                    if ($checkbox.is(':checked')) {
                                        $container.addClass('field-dt-mega-menu-enabled');
                                    } else {
                                        $container.removeClass('field-dt-mega-menu-enabled');
                                    }

                                    dt_fat_menu.recalc();

                                    return true;
                                });

                                this.menuObj.on('change', '.field-dt-icon input[type="radio"]', function () {
                                    var $this = $(this),
                                        $parentContainer = $this.parents('.dt-mega-menu-feilds');

                                    if ($this.val() == 'iconfont') {
                                        $parentContainer.addClass('field-dt-mega-menu-iconfont-icon').removeClass('field-dt-mega-menu-image-icon');
                                    } else {
                                        $parentContainer.removeClass('field-dt-mega-menu-iconfont-icon field-dt-mega-menu-image-icon');
                                    }

                                    return true;
                                });

                            },

                            init: function () {
                                var self = this;

                                this.menuObj = $('#menu-to-edit');
                                this.binds();
                                this.recalc();

                                $('.menu-item-bar').live('mouseup', function (event) {
                                    if (!$(event.target).is('a')) {
                                        clearTimeout(self.reTimeout);
                                        self.reTimeout = setTimeout(self.recalc, 700);
                                    }
                                });
                            }
                        };

                        dt_fat_menu.init();
                    });
				})(jQuery);
			</script>
			<?php
		}
	}

endif;
