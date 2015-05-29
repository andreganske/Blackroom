$(document).ready(function() {

	$('.ui.checkbox').checkbox();

	$('.ui.dropdown').dropdown();

	validationRules = {
		user_email: {
			identifier : 'user_email',
			rules: [{
				type: 'empty',
				prompt: 'Please enter an e-mail'
			},{
				type: 'email',
				prompt: 'Please enter a valid e-mail'
			}]
		},
		user_name: {
			identifier  : 'user_name',
			rules: [{
				type   : 'empty',
				prompt : 'Please enter your name'
			}]
		},
		user_password: {
			identifier  : 'user_password',
			rules: [{
				type   : 'empty',
				prompt : 'Please enter your password'
			},{
				type   : 'length[6]',
				prompt : 'Your password must be at least 6 characters'
			}]
		},
		terms: {
			identifier : 'terms',
			rules: [{
				type   : 'checked',
				prompt : 'You must agree to the terms and conditions'
			}]
		}
	};

	$('.ui.form').form(validationRules, {on: 'blur', onSuccess: 'submitForm'});

	function submitForm() {
		var formData = {
			field1: getFieldValue('someId')
		};

		$.ajax({ type: 'POST', url: '/api/someRestEndpoint', data: formData, success: onFormSubmitted });
	}

	function getFieldValue(fieldId) {
		return $('.ui.form').form('get field', fieldId).val();
	}

	function onFormSubmitted(response) {
		// Do something with response ...
	}

});