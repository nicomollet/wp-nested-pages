# WP Nested Pages

WP Nested Pages provides an intuitive drag and drop interface for managing pages in the Wordpress admin, while maintaining quick edit functionality.

![Screenshot](https://raw.githubusercontent.com/kylephillips/wp-nested-pages/master/assets/screenshot-1.png)

## Description

**Nested Pages offers**
* A drag and drop interface - simple and intuitive
* Quick edit functionality
* An expandable, sortable tree view of your site's page structure
* A native Wordpress menu, automatically generated to match your Nested Pages screen
* A touch-friendly interface

For more information visit [nestedpages.com](http://nestedpages.com) or view the [Wordpress Plugin Page](https://wordpress.org/plugins/wp-nested-pages).

### Using Nested Pages

##### Generated Menu
The generated menu is available for use with the name `nestedpages`.

```
wp_nav_menu(array(
	'menu' => 'nestedpages')
);
```


##### Toggling the Page Tree

To toggle the child pages in and out of view, click the arrow to the left of a parent page. To quickly expand and collapse all pages, click the button in the upper right corner of the Nested Pages Screen. 

![Screenshot](https://raw.githubusercontent.com/kylephillips/wp-nested-pages/master/assets/screenshot-3.gif)

---

##### Theme Use

To order by nested pages ordering in your theme, use the `menu_order` order option in your queries. 

Additionally, the generated menu is available for use, with the name `nestedpages`. 

---

##### Hiding Pages from the Tree View

To hide a page from the tree view, open the quick edit form, select the option to “Hide in Nested Pages” and click Update to save the change.

To toggle the page back into view, click the “Show Hidden Pages” link at the top of the screen. The hidden pages are now visible, and can be re-edited to be shown.

---

##### Sorting Pages

To sort pages, hover over the page row. A menu icon (three lines) will appear. Click (or tap) this icon and drag to reorder within the menu. To drag a page underneath another, drag the page to the right and underneath the target parent. Visual indication is provided with an indentation. The drag and drop functionality works similarly to WordPress menus.

![Screenshot](https://raw.githubusercontent.com/kylephillips/wp-nested-pages/master/assets/screenshot-3.gif)

---

##### Menu Sync

After installing Nested Pages, a new menu will be available with the name `nestedpages`. By default, menu syncing is enabled. To disable the sync, uncheck “Sync Menu” at the top of the Nested Pages screen. Recheck the box to enable it again and to run the sync. 

**Saving Performance:** If your site has a very large number of pages, disabling page sync may help speed up the save time when using Nested Pages.

**Editing the generated menu:** Any manual changes made to the menu outside of the Nested Pages interface will be overwritten after the synchronization runs.

**Hiding Pages in the Menu:** To hide a page from the nestedpages menu, click “Quick Edit” on it’s row, select “Hide in Nav Menu”, and click “update”. If menu sync is disabled, enable it now to sync the setting. Hidden pages are marked “(Hidden)”. If a page with child pages is hidden from the menu, all of it’s child pages will also be hidden. 


## Installation
**Nested Pages requires Wordpress version 3.8 or higher, and PHP version 5.4 or higher.**

1. Upload wp-nested-pages to the wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Click on the Pages Menu item to begin ordering pages. Nested Pages replaces the default Page management screen.
4. To access the default the pages screen, select Default Pages located in the Pages submenu, or on the Nested Pages screen

## Frequently Asked Questions
**Can I use Nested Pages with other post types?**
Nested Pages is currently limited to the WordPress “Page” post type.

**How do I access the WordPress “Pages” screen?**
Click the “Default Pages” link in the page subnav, or on the Nested Pages screen.

**How do I save the order I create?**
Page sorting and nesting is saved in the background after changes are made to the structure.

**How do I edit in bulk?**
Bulk quick edits are not currently supported by Nested Pages. To edit in bulk, click on “Default Pages” to use the native interface.

**What about custom columns?**
Custom columns are not currently supported by Nested Pages. To view custom columns, click on “Default Pages” to view the native interface. If you are using WordPress SEO by Yoast, a page analysis indicator is shown.

**What are those dots in my page rows?**
If you have Wordpress SEO by Yoast installed, your page score indicators are shown along with the pages.

## Screenshots
![Screenshot](https://raw.githubusercontent.com/kylephillips/wp-nested-pages/master/assets/screenshot-2.png)
