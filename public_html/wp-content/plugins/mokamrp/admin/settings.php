<?php include(MOKAMRP_PATH . "/includes/header.php"); ?>

<legend>MokaMRP Settings</legend>

<form action="options.php" method="post" id="mokamrp-options-form">
<?php 
	settings_fields('mokamrp_options'); 
?>
<strong>Weight Unit (Required)</strong> Use a consistant weight unit for all weight measures. Examples: lbs, kgs, grams, etc. 
<br><br>
<label for="mokamrp_weight_unit">Weight unit (Required): </label> <input
	required="required" class="input-xxlarge" type="text" id="mokamrp_weight_unit" name="mokamrp_weight_unit"
	value="<?php echo esc_attr( get_option('mokamrp_weight_unit') ); ?>" />
<?php submit_button(); ?>
</form>

<?php if(get_option('timezone_string') == FALSE) { ?>
<div class="alert alert-danger">
<strong>Warning:</strong> No timezone has been set. 
Visit the Wordpress Dashboard >> Settings >> General and set the timezone to a city near you.
</div>
<?php
}
	
require(MOKAMRP_PATH . "/includes/footer.php"); ?>