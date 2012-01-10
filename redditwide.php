<?php
$checkbox_subs = array(
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

$globalversion = 1.1;

$derp = false;

if (isset($_GET['derp'])) {
	$subreddit_array = array();

	foreach ($checkbox_subs as $sub_name => $default) {
		if (isset($_GET[$sub_name]) && $_GET[$sub_name] == 'true') {
			$subreddit_array[] = $sub_name;
		}
	}
	
	if ($_GET['custom']) {
		$customList = explode(',', $_GET['custom']);
		
		foreach ($customList as $index => $sub) {
			$sub = trim($sub);
			$sub = preg_replace('/[^\w_]/', '', $sub);
			
			if ($sub)
				$customList[$index] = $sub;
		}
		
		$subreddit_array = array_merge($subreddit_array, $customList);
	}
	
	$subreddit_list = '';
	foreach ($subreddit_array as $sub) {
		$subreddit_list .= ' "'.$sub.'",';
	}
	$subreddit_list = rtrim($subreddit_list, ","); // Because lazy
	
	if ($subreddit_list) {
		header('Content-Type: application/javascript');
		header('Content-Disposition: attachment; filename="redditwide.user.js"');
		include ('script_template.php');
		exit;
	}
	else {
		$derp = 'You didn\'t select any subreddits!';
	}
}
?><!doctype html>
<html>
<head>
<title>Reddit-wide Ponymotes!</title>
<meta charset="utf-8">
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
<style>
html, body {
	background: #dddddd;
}

.container {
	background: #fcfcfc;
	margin-top: 20px;
	padding-top: 20px;
	border: 1px solid #8e8e8e;
	border-radius: 4px;
	box-shadow: 0px 0px 18px #9d9d9d;
}

form, fieldset, .actions {
	margin-bottom: 0;
}

li {
	margin: 4px 0;
}

.footer {
	text-align: center;
	color: #4d4d4d;
	padding: 12px;
}

.container .alert-message {
	margin: 5px 20px;
}

.error {
   color: #B94A48;
   margin-top: 4px;
}

</style>
</head>
<body>
<div class="container">
      <form action="redditwide.user.js" method="get">
        <fieldset>
          <legend>Reddit-wide Ponymotes
		  <span class="help-inline">Version <?php echo $globalversion; ?></span>
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
              <ul class="inputs-list">
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
            </div>
          </div><!-- /clearfix -->
		  <div class="clearfix">
            <label for="customList">Custom list:</label>
            <div class="input">
              <input type="text" size="30" name="custom" id="custom" value="<?php if (isset($_GET['custom'])) { echo htmlspecialchars($_GET['custom']); } ?>" class="xlarge">
			  <span class="help-inline">Comma separated values: "mylittledaww, idliketobeatree, mylittlesquidward"</span>
			  <span class="help-block error">
                <strong>Note:</strong> Adding non-supported subs may break emotes from others such as mylittlepony!
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
user script by <a href="http://www.reddit.com/message/compose/?to=cheesemoo">cheesemoo</a> - generation script by <a href="http://www.reddit.com/user/badjokemostlikely">badjokemostlikely</a>
</div>
</body>
</html>
<?php

?>