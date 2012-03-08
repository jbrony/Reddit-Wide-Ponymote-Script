<?php
/*
'subredditname' => true
Will display the subreddit name.

'subredditname' => array('A Better name', true)
Will display the name given in the array.
*/
$checkbox_subs = array(
	'extracss' => array('<a href="http://www.reddit.com/or02y">CSS effects</a>', true),
	'mylittleandysonic1' => true,
	'mlas1animotes' => true,
	'mylittlewtf' => true,
	'mylittlepony' => false,
	'idliketobeatree' => false,
	'mylittlelivestream' => false,
	'vinylscratch' => false,
	'daylightemotes' => false,
	'mylittlesquidward' => false,
	'mylittlenopenopenope' => false,
	'mylittlenanners' => false,
	'mylittlenosleep' => false,
	'mylittledamon' => false,
	'thebestpony' => false,
	'roseluck' => false,
	'applejack' => false,
	'mylittlemusician' => false,
	'mylittlecelestias' => false,
	'mylittlechaos' => false,
	'mylittlealcoholic' => false,
	'mylittlelistentothis' => false,
	'surprise' => false,
	'pinkiepie' => false,
	'twilightSparkle' => false,
	'minuette' => false,
);

$globalVersion = 1.3;
$derp = false;

function sublist_to_array($string) {
	$array = explode(',', $string);
	
	$result = array();
	
	foreach ($array as $index => $sub) {
		$sub = trim($sub);
		$sub = preg_replace('/[^\w_]/', '', $sub);
		
		if ($sub) {
			$result[$index] = $sub;
		}
	}
	
	return $result;
}

if (isset($_GET['derp'])) {
	$subreddit_array = array();

	foreach ($checkbox_subs as $subName => $default) {
		if (isset($_GET[$subName]) && $_GET[$subName] == 'true') {
			$subreddit_array[] = $subName;
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
	
	if (!empty($_GET['list'])) {
		$subreddit_list = sublist_to_array($_GET['list'], ',');
	}
	else if (!empty($_COOKIE['subreddit_list'])) {
		$subreddit_list = sublist_to_array($_COOKIE['subreddit_list'], ',');
	}
		
	if ($subreddit_list) {	
		$custom_subs = array();
		
		// When using a cookie or a parameter, first reset the default subs.
		foreach ($checkbox_subs as $sub => $value) {
			if (is_array($value)) {
				$checkbox_subs[$sub] = array($value[0], false);
			}
			else {
				$checkbox_subs[$sub] = false;
			}
		}
		
		foreach ($subreddit_list as $sub) {
			if (array_key_exists($sub, $checkbox_subs)) {
				if (is_array($checkbox_subs[$sub])) {
					$checkbox_subs[$sub] = array($checkbox_subs[$sub][0], true);
				}
				else {
					$checkbox_subs[$sub] = true;
				}
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
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28726533-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
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
			  foreach ($checkbox_subs as $subName => $checked) {
				if (is_array($checked)) {
					$subDisplayName = $checked[0];
					$checked = $checked[1];
				}
				else {
					$subDisplayName = $subName;
				}
			  
				echo '<li>
                  <label>
                    <input type="checkbox" value="true"';
				
				if ($checked) {
					echo ' checked="checked" ';
				}

				echo 'name="'.$subName.'">
                    <span>'.$subDisplayName.'</span>
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
			  <span class="help-inline error hidden" id="customNote">Adding non-supported subs may break emotes from others such as mylittleonions, mylittlefortress, etc !</span>
			  <span class="help-block">
                Comma separated values: "mylittledaww, mylittlefortress, etc".
              </span>
            </div>
          </div>
          <div class="actions">
            <input type="submit" value="Get the script!" class="btn primary"> <a href="#" id="customUrlButton" class="btn">Get a Custom URL!</a>
			<input type="hidden" value=".user.js" name="derp">
          </div>
        </fieldset>
      </form>
</div>
<div class="footer">
user script by <a href="http://www.reddit.com/user/cheesemoo">cheesemoo</a> - generation script by <a href="http://www.reddit.com/user/badjokemostlikely">badjokemostlikely</a>
</div>

<!-- Get URL Popup -->
<div class="modal fade" id="customUrlPopup">
	<div class="modal-header">
		<a href="#" class="close">x</a>
		<h3>Custom URL</h3>
	</div>
	<div class="modal-body">
		<p>The URL below will generate a script with the same configuration you have now.</p>
		<div class="input">
		<input type="text" name="customUrlInput" id="customUrlInput" value="NUTHIN" class="xxlarge">
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn dismiss stopEvent">Close</a>
	</div>
</div>

</body>
</html>
