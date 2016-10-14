var NestedPages = NestedPages || {};

/**
* Manual Sync functionality for nested view
* @package Nested Pages
* @author Kyle Phillips - https://github.com/kylephillips/wp-nested-pages
*/
NestedPages.ManualSync = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.activeBtn = '';

	plugin.bindEvents = function()
	{
		$(document).on('click', NestedPages.selectors.manualMenuSync, function(e){
			e.preventDefault();
			plugin.activeBtn = $(this);
			plugin.syncMenu();
		});
	}

	plugin.syncMenu = function()
	{
		plugin.loading(true);

		$.ajax({
			url: ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : NestedPages.formActions.manualMenuSync,
				nonce : NestedPages.jsData.nonce,
				syncmenu : 'sync'
			},
			success: function(data){
				console.log(data);
				if (data.status === 'error'){
					$(NestedPages.selectors.errorDiv).text(data.message).show();
					$(NestedPages.selectors.loadingIndicator).hide();
				} else {
					plugin.loading(false);
				}
			}
		});
	}

	plugin.loading = function(loading)
	{
		if ( loading ){
			$(plugin.activeBtn).addClass('disabled');
			$(NestedPages.selectors.loadingIndicator).show();
			return;
		}
		$(plugin.activeBtn).removeClass('disabled');
		$(NestedPages.selectors.loadingIndicator).hide();
	}

	return plugin.bindEvents();
}