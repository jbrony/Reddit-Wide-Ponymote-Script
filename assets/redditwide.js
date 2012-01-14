document.addEvent('domready', function() {
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
});