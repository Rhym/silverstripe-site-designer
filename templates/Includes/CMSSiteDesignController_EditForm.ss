<form $FormAttributes data-layout-type="border">
	<div class="cms-content-fields center">
		<% if $Message %>
		<p id="{$FormName}_error" class="message $MessageType">$Message</p><!-- /.message -->
		<% else %>
		<p id="{$FormName}_error" class="message $MessageType" style="display: none"></p><!-- /.message -->
		<% end_if %>
		<fieldset>
			<% if $Legend %><legend>$Legend</legend><% end_if %>
			<% loop $Fields %>
				$FieldHolder
			<% end_loop %>
			<div class="clear"><!-- --></div>
		</fieldset>
	</div><!-- /.cms-content-fields center -->
	<div class="cms-content-actions cms-content-controls south">
		<% if $Actions %>
		<div class="Actions">
			<% loop $Actions %>
				$Field
			<% end_loop %>
			<% if $Controller.LinkPreview %>
				<a href="$Controller.LinkPreview" class="cms-preview-toggle-link ss-ui-button" data-icon="preview">
					<% _t('LeftAndMain.PreviewButton', 'Preview') %> &raquo;
				</a><!-- /.cms-preview-toggle-link ss-ui-button -->
			<% end_if %>
		</div><!-- /.Actions -->
		<% end_if %>
	</div><!-- /.cms-content-actions cms-content-controls south -->
</form>