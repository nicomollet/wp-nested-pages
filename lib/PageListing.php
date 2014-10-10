<?php namespace NestedPages;

/**
* Register the Admin Page Listing & Show View (Overwrites Pages Menu Item)
*/
class PageListing {

	/**
	* Post Type
	* @var object
	*/
	private $post_type;


	public function __construct()
	{
		$this->post_type = get_post_type_object('page');
		add_action( 'admin_menu', [ $this, 'adminMenu' ] );
		add_action( 'admin_menu', [ $this, 'submenu' ] );
	}


	/**
	* Add the admin menu item
	*/
	public function adminMenu()
	{
		add_menu_page( 
			$this->post_type->labels->name,
			$this->post_type->labels->name,
			'delete_pages',
			'nestedpages', 
			array( $this, 'pageListing' ),
			'dashicons-admin-page',
			20
		);
	}


	/**
	* Add Submenu
	*/
	public function submenu()
	{
		global $submenu;
    	$submenu['nestedpages'][50] = array( 'All Pages', 'publish_pages', esc_url(admin_url('admin.php?page=nestedpages')) );
    	$submenu['nestedpages'][60] = array( 'Add New', 'publish_pages', $this->addNewPageLink() );
    	$submenu['nestedpages'][70] = array( 'Default Listing', 'publish_pages', $this->defaultPagesLink() );
	}


	/**
	* Add New Page Link
	* @return string
	*/
	private function addNewPageLink()
	{
		$link = esc_url( admin_url('post-new.php?post_type=page') );
		return $link;
	}


	/**
	* Link to the default WP Pages listing
	* @return string
	*/
	private function defaultPagesLink()
	{
		$link = esc_url( admin_url('edit.php?post_type=page') );
		return $link;
	}


	/**
	* Loop through all the pages and create the nested list
	*/
	private function loopPages($parent_id = 0, $count = 0)
	{
		$pages = new \WP_Query(array(
			'post_type' => 'page',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'post_parent' => $parent_id,
			'order' => 'ASC'
		));
		if ( $pages->have_posts() ) :
			$count++;
			echo ( $count == 1 ) ? '<ol class="sortable nplist">' : '<ol class="nplist">';
			while ( $pages->have_posts() ) : $pages->the_post();
				echo '<li id="menuItem_' . get_the_id() . '" class="page-row">';
					$count++;
					include( dirname( dirname(__FILE__) ) . '/views/row.php');
				$this->loopPages(get_the_id(), $count);
				echo '</li>';
			endwhile;
			echo '</ol>';
		endif; wp_reset_postdata();
	}


	/**
	* The Main View
	* Replaces Default Pages Listing
	*/
	public function pageListing()
	{
		include( dirname( dirname(__FILE__) ) . '/views/pages.php');
	}

}