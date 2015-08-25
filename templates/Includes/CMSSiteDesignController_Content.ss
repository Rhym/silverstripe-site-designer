<div id="site-design-controller-cms-content" class="cms-content center cms-tabset {$BaseCSSClasses}" data-layout-type="border" data-pjax-fragment="Content CurrentForm" data-ignore-tab-state="true">
	<div class="cms-content-header north">
		<% with $EditForm %>
			<div class="cms-content-header-info">
				<% with $Controller %>
					<% include CMSBreadcrumbs %>
				<% end_with %>
			</div><!-- /.cms-content-header-info -->
			<% if $Fields.hasTabset %>
				<% with $Fields.fieldByName('Root') %>
				<div class="cms-content-header-tabs cms-tabset-nav-primary ss-ui-tabs-nav">
					<ul class="cms-tabset-nav-primary">
					<% loop $Tabs %>
						<li<% if $extraClass %> class="{$extraClass}"<% end_if %>><a href="#{$id}">{$Title}</a></li>
					<% end_loop %>
					</ul><!-- /.cms-tabset-nav-primary -->
				</div><!-- /.cms-content-header-tabs cms-tabset-nav-primary ss-ui-tabs-nav -->
				<% end_with %>
			<% end_if %>
		<% end_with %>
	</div><!-- /.cms-content-header north -->
	{$EditForm}
</div><!-- /#site-design-controller-cms-content .cms-content center cms-tabset -->