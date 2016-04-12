<?php
  $settings = SmartAppBanner::getSavedSettings();
?>

<form method="post" action="">
	<h3>Smart App Banner Settings</h3>

<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="SmartAppBanner-title">Smart App Banner Title:</label></th>
			<td>
				<input type="text" id="title-text" name="title-text" size="50" value="{{title}}" placeholder=""/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="SmartAppBanner-itunesstoreid">iTunes Store App Id:</label></th>
			<td>
				<input type="text" id="itunesstoreid-text" name="itunesstoreid-text" size="50" value="{{itunesstoreid}}" placeholder=""/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="SmartAppBanner-googleplayappid">Google Play App Id:</label></th>
			<td>
				<input type="text" id="googleplayappid-text" name="googleplayappid-text" size="50" value="{{googleplayappid}}" placeholder=""/>
			</td>
		</tr>
		<tr valign="top" id="uTSSettings" class="{{usetss}}">
			<th scope="row"><label for="usetownsquaresettings">Use Townsquare Settings for icon image?</label></th>
			<td>
				<input id="usetownsquaresettings" name="usetownsquaresettings" type="checkbox" value="1" <?php checked( $settings['usetownsquaresettings'], '1' )?> />
			</td>
		</tr>
		<tr valign="top" id="mobileAppIcon" class="">
			<th scope="row"><label for="SmartAppBanner-imageurl">Mobile App Icon Image URL:</label></th>
			<td>
				<input type="text" id="imageurl-text" name="imageurl-text" size="50" value="{{imageurl}}" placeholder=""/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="SmartAppBanner-dayshidden">Days Hidden:</label></th>
			<td>
				<input type="text" id="dayshidden-text" name="dayshidden-text" size="50" value="{{dayshidden}}" placeholder=""/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="SmartAppBanner-daysreminder">Days Reminder:</label></th>
			<td>
				<input type="text" id="daysreminder-text" name="daysreminder-text" size="50" value="{{daysreminder}}" placeholder=""/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="SmartAppBanner-buttontext">Button Text: </label></th>
			<td>
				<input type="text" id="button-text" name="button-text" size="50" value="{{button}}" placeholder=""/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="SmartAppBanner-pricetext">App Price: </label></th>
			<td>
				<input type="text" id="price-text" name="price-text" size="50" value="{{price}}" placeholder=""/>
			</td>
		</tr>
	</tbody>
</table>

<p>
	<input type="submit" value="Save" class="button-primary" id="SmartAppBanner-save" name="SmartAppBanner-save" />
</p>

</form>