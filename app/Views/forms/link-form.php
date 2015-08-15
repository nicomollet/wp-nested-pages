<?php
/**
* Modal Form for adding a new link
*/
$post_type_object = get_post_type_object( 'page' );
$can_publish = current_user_can( $post_type_object->cap->publish_posts );
?>
<div class="np-modal fade" id="np-link-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="np-tabs" data-np-tabs>
				<ul>
					<li><a href="#nplink" class="active" data-np-tab><?php _e('Add Link'); ?></a></li>
					<li><a href="#npmenuitems" data-np-tab><?php _e('Add Menu Item', 'nestedpages'); ?></a></li>
				</ul>
			</div>

			<div id="nplink" class="np-tabbed-content" data-np-tab-pane>

				<div class="modal-body">

					<form method="get" action="" class="np-modal-form np-new-link-form">
					<div class="form-interior">

					<div class="np-quickedit-error np-new-link-error" style="clear:both;display:none;"></div>
						
					<div class="left">

					<div class="form-control">
						<label><?php _e( 'Navigation Label' ); ?></label>
						<input type="text" name="np_link_title" class="np_link_title" value="" />
					</div>

					<div class="form-control">
						<label><?php _e( 'URL', 'nestedpages' ); ?></label>
						<input type="text" name="np_link_content" class="np_link_content" value="" />
					</div>

					<div class="form-control">
						<label><?php _e( 'Status' ); ?></label>
						<select name="_status" class="np_link_status">
						<?php if ( $can_publish ) : ?>
							<option value="publish"><?php _e( 'Published' ); ?></option>
							<option value="future"><?php _e( 'Scheduled' ); ?></option>
						<?php endif; ?>
							<option value="pending"><?php _e( 'Pending Review' ); ?></option>
							<option value="draft"><?php _e( 'Draft' ); ?></option>
						</select>
					</div>

					</div><!-- .left -->

					<div class="right">

					<?php if ( $this->user->canSortPages() ) : // Menu Options Button ?>
					<label class="checkbox">
						<input type="checkbox" name="nav_status" class="np_link_nav_status" value="hide" />
						<span class="checkbox-title"><?php _e( 'Hide in Nav Menu', 'nestedpages' ); ?></span>
					</label>

					<label class="checkbox">
						<input type="checkbox" name="nested_pages_status" class="np_link_status" value="hide" />
						<span class="checkbox-title"><?php _e( 'Hide in Nested Pages', 'nestedpages' ); ?></span>
					</label>

					<label class="checkbox">
						<input type="checkbox" name="link_target" class="new_link_target" value="_blank" />
						<span class="checkbox-title"><?php _e( 'Open link in a new window/tab' ); ?></span>
					</label>
					<?php endif; // Edit theme options ?>

					</div><!-- .right -->

					</div><!-- .form-interior -->
				</div>
				<div class="modal-footer">
					<input type="hidden" name="parent_id" class="parent_id" value="">
					<button type="button" class="button modal-close" data-dismiss="modal">
						<?php _e('Close'); ?>
					</button>
					<a accesskey="s" class="button-primary np-save-link alignright">
						<?php _e( 'Save Link', 'nestedpages' ); ?>
					</a>
					<span class="np-qe-loading np-link-loading"></span>
				</div>
				</form>
			</div><!-- #nplink -->

			
			<div id="npmenuitems" class="np-tabbed-content" style="display:none;" data-np-tab-pane>
				<div class="modal-body np-menu-item-form">
					<div class="np-menu-type-selection">
						<ul data-np-menu-accordion>
							<?php 
								// Taxonomies
								foreach ( $this->listing_repo->taxonomies() as $name => $taxonomy ) {
									$terms = $this->listing_repo->terms($name);
									if ( !$terms ){
										$out = '<li><a href="#" class="no-terms">' . $taxonomy->labels->name;
										$out .= '<span>' . __('No Items', 'nestedpages') . '</span></a>';
									} else {
										$out = '<li><a href="#" data-np-menu-accordion-item>' . $taxonomy->labels->name . '</a>';
										$out .= '<ul>';
										foreach ( $terms as $term ){
											$out .= '<li><a href="#" data-np-menu-object="' . $name . '" data-np-menu-type="taxonomy" data-np-menu-objectid="' . $term->term_id . '">' . $term->name . '</a></li>';
										}
										$out .= '</ul>';
									}
									$out .= '</li>';
									echo $out;
								}
							?>
						</ul>
					</div><!-- .np-menu-type-selection -->
					<div class="np-menu-link-object">

					</div>
				</div><!-- .modal-body -->

				<div class="modal-footer">
					<input type="hidden" name="parent_id" class="parent_id" value="">
					<button type="button" class="button modal-close" data-dismiss="modal">
						<?php _e('Cancel'); ?>
					</button>
					<a accesskey="s" class="button-primary np-save-link alignright">
						<?php _e( 'Add', 'nestedpages' ); ?>
					</a>
					<span class="np-qe-loading np-link-loading"></span>
				</div><!-- .modal-footer -->
			</div><!-- #npmenuitems -->
			

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->