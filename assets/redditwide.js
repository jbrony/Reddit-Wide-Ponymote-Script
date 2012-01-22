document.addEvent('domready', function() {
	/*
	* Custom subreddit note fade-in and check all button.
	*/
	
	var customNote = $('customNote');
	var custom = $('custom');
	var checkButton = $('checkButton');
	var checkboxes = $('checkboxes').getElements('input')

	if (custom.get('value') == '') {		
		customNote.setStyles({'opacity': '0', 'visibility': 'visible'});		
	}
	else {
		customNote.fade('in');
	}
	
	checkboxes.addEvent('change', function(e) {
		checkButton.removeClass('active');

		if (checkboxes.getProperty('checked').every(function(item) { return (item == true); })) {
			checkButton.addClass('active');
		}
	});
	
	checkButton.addEvent('click', function(e) {
		e.stop();
		
		var check;
		if (checkButton.hasClass('active')) {
			checkButton.removeClass('active');
			check = false;
		}
		else {
			checkButton.addClass('active');
			check = true;
		}

		$('checkboxes').getElements('input').setProperty('checked', check);
	});
	
	custom.addEvents({
		'focus': function() {
			if (custom.get('value') == '') {
				customNote.fade('in');
			}
		},
		'blur': function() {
			if (custom.get('value') == '') {
				customNote.fade('out');
			}
		}	
	});
	
	/*
	* Get URL button
	*/
	
	var popup = new Bootstrap.Popup('customUrlPopup');
	var customUrlInput = $('customUrlInput');
	
	$('customUrlButton').addEvent('click', function(event) {
		event.stop();
		
		var list = new Array();
		
		checkboxes.each(function (checkbox) {
			if (checkbox.get('checked'))
				list[list.length] = checkbox.get('name');
		});
		
		if ($('custom').get('value').trim() != '')
			list = list.concat($('custom').get('value').split(','));
		
		list = list.map(function(a) { return a.trim(); });
				
		if (list.length == 0) {
			customUrlInput.set('value', 'No subreddits selected :(')
		}
		else {
			var url = 'http://jbrony.nfshost.com/redditwidescriptgen/redditwide.php?list=' + list.join(',');
			
			customUrlInput.set('value', url);
			customUrlInput.focus();
			customUrlInput.select();
		}
		
		popup.show();
	});	
});