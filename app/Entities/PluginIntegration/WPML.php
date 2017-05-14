<?php 

namespace NestedPages\Entities\PluginIntegration;

/**
* WPML Integration
* @link https://wpml.org/
*/

class WPML 
{
	/**
	* Installed
	* @var boolean
	*/
	public $installed = false;

	/**
	* Global Sitepress object
	*/
	private $sitepress;

	/**
	* WPML Settings
	*/
	private $settings;

	public function __construct()
	{
		if ( defined('ICL_SITEPRESS_VERSION') ){
			$this->installed = true;
			global $sitepress;
			$this->sitepress = $sitepress;
			$this->settings = get_option('icl_sitepress_settings');
			return;
		} 
	}

	/**
	* Get the current language
	* @return string
	*/
	public function getCurrentLanguage($return_type = 'code')
	{
		if ( $return_type == 'code' ) return ICL_LANGUAGE_CODE;
		if ( $return_type == 'name' ) return ICL_LANGUAGE_NAME;
		if ( $return_type == 'name_en' ) return ICL_LANGUAGE_NAME_EN;
	}

	/**
	* Get all available languages
	* @return array
	*/
	public function getLanguages()
	{
		return icl_get_languages();
	}

	/**
	* Get the default language
	* @return array
	*/
	public function getDefaultLanguage()
	{
		return $this->sitepress->get_default_language();
	}

	/**
	* Get a single language
	* @return array
	*/
	public function getSingleLanguage($language = 'en')
	{
		$languages = $this->getLanguages();
		return ( array_key_exists($language, $languages) ) ? $languages[$language] : array();
	}

	/**
	* Is the default language currently being shown
	* @return boolean
	*/
	public function isDefaultLanguage()
	{
		return ( $this->getCurrentLanguage() == $this->getDefaultLanguage() ) ? true : false;
	}

	/**
	* Get all translations for a post
	* @return array or int
	*/
	public function getAllTranslations($post_id, $return = 'array')
	{
		if ( !$post_id && $return == 'array' ) return array();
		if ( !$post_id && $return == 'count' ) return 0;
		$true_id = $this->sitepress->get_element_trid($post_id);
		if ( $return == 'array' ) return $this->sitepress->get_element_translations($true_id);
		if ( $return == 'count' ) return count($this->sitepress->get_element_translations($true_id));
	}

	/**
	* Sync Post Order among translations
	* @param array of posts with children
	*/
	public function syncPostOrder($posts)
	{
		if ( $this->settings['sync_page_ordering'] !== 1 ) return;
		global $wpdb;
		if ( !is_array($posts) ) return;
		foreach ( $posts as $order => $post ) :
			$translations = $this->getAllTranslations($post['id']);
			foreach ( $translations as $lang_code => $post_info ) :
				$post_id = $post_info->element_id;
				$query = "UPDATE $wpdb->posts SET menu_order = '$order' WHERE ID = '$post_id'";
				$wpdb->query( $query );
			endforeach;
			if ( isset($post['children']) ) $this->syncPostOrder($post['children']);
		endforeach;
	}

	/**
	* Output the sync menus button
	* @return html
	*/
	public function syncMenusButton()
	{
		$url = esc_url(admin_url('admin.php?page=sitepress-multilingual-cms/menu/menu-sync/menus-sync.php'));
		return '<a href="' . $url . '" class="np-btn">' . __('Sync WPML Menus', 'wp-nested-pages') . '</a>';
	}

	/**
	* Get the Translated IDs of an array of post IDs
	* @param array $post_ids
	* @return array of post ids
	*/
	public function getAllTranslatedIds($post_ids)
	{
		if ( !is_array($post_ids) ) return array();
		foreach ( $post_ids as $id ){
			$translations = $this->getAllTranslations($id);
			if ( empty($translations) ) continue;
			foreach ( $translations as $lang => $post ){
				if ( $post->element_id == $id ) continue;
				array_push($post_ids, $post->element_id);
			}
		}
		return $post_ids;
	}

	/**
	* Get the number of translated posts for a post type
	*/
	public function translatedPostCount($language_code = '', $post_type = '')
	{
		global $wpdb;

		$default_language_code = $this->getDefaultLanguage();
		$post_type = 'post_' . $post_type;

		$query = $wpdb->prepare("SELECT COUNT( {$wpdb->prefix}posts.ID ) FROM {$wpdb->prefix}posts LEFT JOIN {$wpdb->prefix}icl_translations ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}icl_translations.element_id WHERE {$wpdb->prefix}icl_translations.language_code = '%s' AND {$wpdb->prefix}icl_translations.source_language_code = '%s' AND {$wpdb->prefix}icl_translations.element_type = '%s'", $language_code, $default_language_code, $post_type);

		return $wpdb->get_var( $query );
	}

	/**
	* Get the number of default language posts for a post type
	*/
	public function defaultPostCount($post_type = '')
	{
		global $wpdb;
		$default_language_code = $this->getDefaultLanguage();
		$query = $wpdb->prepare("SELECT COUNT(p.ID) FROM {$wpdb->prefix}posts AS p LEFT JOIN {$wpdb->prefix}icl_translations AS t ON t.element_id = p.ID WHERE p.post_type = '%s' AND p.post_status = 'publish' AND t.language_code = '%s'", $post_type, $default_language_code);
		return $wpdb->get_var( $query );
	}

	/**
	* Output a list of language links in the tools section of the listing head
	* @param string $post_type
	* @return string html
	* @example English (2) | German (1) | Spanish (0)
	*/
	public function languageToolLinks($post_type)
	{
		$html = '<ul class="subsubsub" style="clear:both;">';
		$c = 1;
		$languages = $this->getLanguages();
		foreach ( $languages as $lang_code => $lang ){
			$translated_language = $this->getTranslatedLanguageName($this->getDefaultLanguage(), $lang_code);
			if ( !$translated_language ) continue;
			$post_count = ( $lang_code == $this->getDefaultLanguage() )
				? $this->defaultPostCount($post_type)
				: $this->translatedPostCount($lang_code, $post_type);
			$html .= '<li>';
			if ( $c > 1 ) $html .= '|&nbsp;';
			if ( $lang['active'] ) $html .= '<strong>';
			if ( $post_count > 0 ) $html .= '<a href="' . $this->getLanguageFilteredLink($lang_code, $post_type) . '">';
			$html .= $translated_language . ' ';
			if ( $lang_code !== $this->getDefaultLanguage() ) $html .= '(' . $post_count . ')';
			if ( $lang_code == $this->getDefaultLanguage() ) $html .= '(' . $post_count . ')';
			if ( $lang['active'] ) $html .= '</strong>';
			if ( $post_count > 0 ) $html .= '</a>';
			$html .= '&nbsp;</li>';
			$c++;
		}
		$html .= '<li>|&nbsp;<a href="' . $this->getLanguageFilteredLink('all', $post_type) . '">' . __('All Languages', 'wp-nested-pages') . '</a></li>';
		$html .= '</ul>';
		return $html;
	}

	/**
	* Get the Links to a filtered language
	*/
	public function getLanguageFilteredLink($language, $post_type)
	{
		$url = 'admin.php?page=nestedpages';
		if ( $post_type !== 'page' ) $url .= '-' . $post_type;
		$url .= '&lang=' . $language;
		return esc_url(admin_url($url));
	}

	/**
	* Get a translated language name for a given language
	* @param string $translated_language - the language to return as
	* @param string $target_language - the language to translate
	* @return string - translated name of language (ex: if default is english, and target is spanish, will return "Spanish" rather than "Espanol")
	*/
	public function getTranslatedLanguageName($translated_language, $target_language)
	{
		global $wpdb;
		$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}icl_languages_translations WHERE language_code = '%s' AND display_language_code = '%s'",  $target_language, $translated_language);
		$results = $wpdb->get_results( $query );
		return ( $results[0]->name ) ? $results[0]->name : null;
	}

}