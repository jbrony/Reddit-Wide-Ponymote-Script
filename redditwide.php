<?php
$checkbox_subs = array(
	'extracss' => true,
	'mylittleandysonic1' => true,
	'mlas1animotes' => true,
	'mylittlewtf' => true,
	'mylittlelivestream' => false,
	'vinylscratch' => false,
	'daylightemotes' => false,
	'mylittlesquidward' => false,
	'mylittlenopenopenope' => false,
	'mylittlenanners' => false,
);

$globalVersion = 1.2;
$derp = false;

function sublist_to_array($string) {
	$array = explode(',', $string);
	
	foreach ($array as $index => $sub) {
		$sub = trim($sub);
		$sub = preg_replace('/[^\w_]/', '', $sub);
		
		if ($sub)
			$array[$index] = $sub;
	}
	
	return $array;
}

if (isset($_GET['derp'])) {
	$subreddit_array = array();

	foreach ($checkbox_subs as $sub_name => $default) {
		if (isset($_GET[$sub_name]) && $_GET[$sub_name] == 'true') {
			$subreddit_array[] = $sub_name;
		}
	}
	
	if ($_GET['custom']) {
		$subreddit_array = array_merge($subreddit_array, sublist_to_array($_GET['custom']));
	}
	
	$subreddit_list = '';
	
	foreach ($subreddit_array as $sub) {
		$subreddit_list .= ' "'.$sub.'",';
	}
	
	$subreddit_list = rtrim($subreddit_list, ","); // Because lazy
	
	if ($subreddit_list) {
		// Saving the list
		setcookie('subreddit_list', implode(',', $subreddit_array));
		
		// Script ahoy!
		header('Content-Type: application/javascript');
		header('Content-Disposition: attachment; filename="redditwide.user.js"');		
		include ('script_template.php');
		exit;
	}
	else {
		$derp = 'You didn\'t select any subreddits!';
	}
}
else {
	// Restoring the saved list, GET argument overrides the cookie.
	$subreddit_list = false;
	
	if (!empty($_COOKIE['subreddit_list']) && empty($_GET['list'])) {
		$subreddit_list = sublist_to_array($_COOKIE['subreddit_list'], ',');
	}
	else if (!empty($_GET['list'])) {
		$subreddit_list = sublist_to_array($_GET['list'], ',');
	}
	
	if ($subreddit_list) {	
		$custom_subs = array();
		
		// When using a cookie or a parameter, first reset the default subs.
		foreach ($checkbox_subs as $sub => $value) {
			$checkbox_subs[$sub] = false;
		}
		
		foreach ($subreddit_list as $sub) {
			if (array_key_exists($sub, $checkbox_subs)) {
				$checkbox_subs[$sub] = true;
			}
			else {
				$custom_subs[] = $sub;
			}
		}
		
		$custom = implode($custom_subs, ',');
	}
}

?><!doctype html>
<html>
<head>
<title>Reddit-wide Ponymotes!</title>
<meta charset="utf-8">
<link rel="stylesheet" href="assets/bootstrap.min.css">
<link rel="stylesheet" href="assets/redditwide.css">
<script src="assets/mootools.min.js"></script>
<script src="assets/redditwide.js"></script>
</head>
<body>
<div class="container">
      <form action="redditwide.user.js" method="get">
        <fieldset>
          <legend>Reddit-wide Ponymotes
		  <span class="help-inline">Version <?php echo $globalVersion; ?></span>
		  </legend>
		  <?php 
		  if ($derp) {
		  ?>
		  <div class="alert-message error">
			<p><strong>Oh snap!</strong> <?php echo $derp; ?></p>
		  </div>
		  <?php
		  }
		  ?>
          <div class="clearfix">
            <label id="optionsCheckboxes">Choose your subreddits:</label>
            <div class="input">
              <ul class="inputs-list" id="checkboxes">
			  <?php
			  foreach ($checkbox_subs as $sub_name => $checked) {
				echo '<li>
                  <label>
                    <input type="checkbox" value="true"';
				
				if ($checked) {
					echo ' checked="checked" ';
				}

				echo 'name="'.$sub_name.'">
                    <span>'.$sub_name.'</span>
                  </label>
                </li>';
			  }
			  ?>
              </ul>
			  <a href="#" id="checkButton" class="btn small">Check All</a>
            </div>
          </div><!-- /clearfix -->
		  <div class="clearfix">
            <label for="customList">Custom list:</label>
            <div class="input">
              <input type="text" size="30" name="custom" id="custom" value="<?php if (isset($custom) && $custom) { echo htmlspecialchars($custom); } ?>" class="xlarge">
			  <span class="help-inline error hidden" id="customNote">Adding non-supported subs may break emotes from others such as mylittlepony!</span>
			  <span class="help-block">
                Comma separated values: "mylittledaww, idliketobeatree, etc".
              </span>
            </div>
          </div>
          <div class="actions">
            <input type="submit" value="Get the script!" class="btn primary">
			<input type="hidden" value=".user.js" name="derp">
          </div>
        </fieldset>
      </form>
</div>
<div class="footer">
user script by <a href="http://www.reddit.com/user/cheesemoo">cheesemoo</a> - generation script by <a href="http://www.reddit.com/user/badjokemostlikely">badjokemostlikely</a>
</div>
</body>
</html>
