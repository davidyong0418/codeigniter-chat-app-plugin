'use strict';

function configure_config() {
	var flag 		= 1;
	var i_data 		= {
		'upload_path'	: $('#d_upload').val(),
		'img_path'		: $('#d_img').val(),
		'sound_path'	: $('#d_sound').val(),
		'usertable'		: $('#d_usertable').val(),
		'id'			: $('#d_userid').val(),
		'firstname'		: $('#d_firstname').val(),
		'lastname'		: $('#d_lastname').val(),
		'd_username'	: $('#d_username').val(),
		'email'			: $('#d_email').val(),

		'config_path'	: $('#d_config').val(),
		'd_logged'		: $('#d_logged').val(),
	};

	if(!i_data.config_path) {
		$('#d_config').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.d_logged) {
		$('#d_logged').addClass('is-invalid');
		flag = 0;
	} else {
		$('#d_config').removeClass('is-invalid');
		$('#d_logged').removeClass('is-invalid');
		
		$('#d_config').addClass('is-valid');
		$('#d_logged').addClass('is-valid');
		flag = 1;
	}

	if(flag) {
		$('#d_config_btn .fa-cog').addClass('fa-spin');
		$.ajax({
			type    : 'POST',
            url     : base_url + "index.php",
            async   : true,
            cache   : false,
            data 	: i_data,
			success: function(result) {
	        	if(result.response) {
					$('#d_config').removeClass('is-invalid');
					$('#d_logged').removeClass('is-invalid');
					
					$('#d_config').addClass('is-valid');
					$('#d_logged').addClass('is-valid');
					
					$('.text-success.d_config_text').html('Congrats! Your website successfully configured for chat!');
					$('.text-danger.d_config_text').html('');

					flag = 0;

					$('#step_4 input, #step_4 button').attr('disabled', true);
					$('#step_5 input, #step_5 button').attr('disabled', false);

					$('#d_config_btn .fa-cog').removeClass('fa-spin');

					$('html, body').animate({
				        scrollTop: $("#step_5").offset().top
				    }, 1000);

				} else {
					$('#d_config').removeClass('is-valid');
					$('#d_logged').removeClass('is-valid');
					
					$('#d_config').addClass('is-invalid');
					$('#d_logged').addClass('is-invalid');
					
					$('.text-success.d_config_text').html('');
					$('.text-danger.d_config_text').html(result.error);

					flag = 1;

					$('#d_config_btn .fa-cog').removeClass('fa-spin');
				}
	    	}
		});
	}
}

function transfer_files() {
	var flag 		= 1;
	var i_data 		= {
		'css_path'		: $('#d_css').val(),
		'js_path'		: $('#d_js').val(),
		'img_path'		: $('#d_img').val(),
		'sound_path'	: $('#d_sound').val(),
		'upload_path'	: $('#d_upload').val(),
		'lib_path'		: $('#d_lib').val(),
		'cont_path'		: $('#d_cont').val(),
		'view_path'		: $('#d_view').val(),
	};

	if(!i_data.css_path) {
		$('#d_css').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.js_path) {
		$('#d_js').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.img_path) {
		$('#d_img').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.sound_path) {
		$('#d_sound').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.upload_path) {
		$('#d_upload').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.lib_path) {
		$('#d_lib').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.cont_path) {
		$('#d_cont').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.view_path) {
		$('#d_view').addClass('is-invalid');
		flag = 0;
	} else {
		$('#d_css').removeClass('is-invalid');
		$('#d_js').removeClass('is-invalid');
		$('#d_img').removeClass('is-invalid');
		$('#d_sound').removeClass('is-invalid');
		$('#d_upload').removeClass('is-invalid');
		$('#d_lib').removeClass('is-invalid');
		$('#d_cont').removeClass('is-invalid');
		$('#d_view').removeClass('is-invalid');
		
		$('#d_css').addClass('is-valid');
		$('#d_js').addClass('is-valid');
		$('#d_img').addClass('is-valid');
		$('#d_sound').addClass('is-valid');
		$('#d_upload').addClass('is-valid');
		$('#d_lib').addClass('is-valid');
		$('#d_cont').addClass('is-valid');
		$('#d_view').addClass('is-valid');
		
		flag = 1;
	}

	if(flag) {
		$('#d_transfer_btn .fa-cog').addClass('fa-spin');
		$.ajax({
			type    : 'POST',
            url     : base_url + "index.php",
            async   : true,
            cache   : false,
            data 	: i_data,
			success: function(result) {
	        	if(result.response) {
					$('#d_css').removeClass('is-invalid');
					$('#d_js').removeClass('is-invalid');
					$('#d_img').removeClass('is-invalid');
					$('#d_sound').removeClass('is-invalid');
					$('#d_upload').removeClass('is-invalid');
					$('#d_lib').removeClass('is-invalid');
					$('#d_cont').removeClass('is-invalid');
					$('#d_view').removeClass('is-invalid');
					
					$('#d_css').addClass('is-valid');
					$('#d_js').addClass('is-valid');
					$('#d_img').addClass('is-valid');
					$('#d_sound').addClass('is-valid');
					$('#d_upload').addClass('is-valid');
					$('#d_lib').addClass('is-valid');
					$('#d_cont').addClass('is-valid');
					$('#d_view').addClass('is-valid');

					$('.text-success.d_transfer_text').html('Congrats! chat files transferred successfully!');
					$('.text-danger.d_transfer_text').html('');

					flag = 0;

					$('#step_3 input, #step_3 button').attr('disabled', true);
					$('#step_4 input, #step_4 button').attr('disabled', false);

					$('#d_transfer_btn .fa-cog').removeClass('fa-spin');

					$('html, body').animate({
				        scrollTop: $("#step_4").offset().top
				    }, 1000);

				} else {
					$('#d_css').removeClass('is-valid');
					$('#d_js').removeClass('is-valid');
					$('#d_img').removeClass('is-valid');
					$('#d_sound').removeClass('is-valid');
					$('#d_upload').removeClass('is-valid');
					$('#d_lib').removeClass('is-valid');
					$('#d_cont').removeClass('is-valid');
					$('#d_view').removeClass('is-valid');
					
					$('#d_css').addClass('is-invalid');
					$('#d_js').addClass('is-invalid');
					$('#d_img').addClass('is-invalid');
					$('#d_sound').addClass('is-invalid');
					$('#d_upload').addClass('is-invalid');
					$('#d_lib').addClass('is-invalid');
					$('#d_cont').addClass('is-invalid');
					$('#d_view').addClass('is-invalid');
					
					$('.text-success.d_transfer_text').html('');
					$('.text-danger.d_transfer_text').html(result.error);

					flag = 1;

					$('#d_transfer_btn .fa-cog').removeClass('fa-spin');
				}
	    	}
		});
	}
};

function configure_database() {
	var flag 		= 1;
	var i_data 		= {
		'hostname'	: $('#d_hostname').val(),
		'username'	: $('#d_dbusername').val(),
		'password'	: $('#d_password').val(),
		'database'	: $('#d_database').val(),
	};

	if(!i_data.hostname) {
		$('#d_hostname').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.username) {
		$('#d_dbusername').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.password) {
		$('#d_password').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.database) {
		$('#d_database').addClass('is-invalid');
		flag = 0;
	} else {
		$('#d_hostname').removeClass('is-invalid');		
		$('#d_dbusername').removeClass('is-invalid');		
		$('#d_password').removeClass('is-invalid');		
		$('#d_database').removeClass('is-invalid');		
		
		$('#d_hostname').addClass('is-valid');		
		$('#d_dbusername').addClass('is-valid');		
		$('#d_password').addClass('is-valid');		
		$('#d_database').addClass('is-valid');		
		
		flag = 1;
	}

	if(flag) {
		$('#d_database_btn .fa-cog').addClass('fa-spin');
		$.ajax({
			type    : 'POST',
            url     : base_url + "index.php",
            async   : true,
            cache   : false,
            data 	: i_data,
			success: function(result) {
	        	if(result.response) {
					$('#d_hostname').removeClass('is-invalid');		
					$('#d_dbusername').removeClass('is-invalid');		
					$('#d_password').removeClass('is-invalid');		
					$('#d_database').removeClass('is-invalid');		
					
					$('#d_hostname').addClass('is-valid');		
					$('#d_dbusername').addClass('is-valid');		
					$('#d_password').addClass('is-valid');		
					$('#d_database').addClass('is-valid');		

					$('.text-success.d_database_text').html('Congrats! chat Tables imported successfully!');
					$('.text-danger.d_database_text').html('');

					flag = 0;

					$('#step_1 input, #step_1 button').attr('disabled', true);
					$('#step_2 input, #step_2 button').attr('disabled', false);

					$('#d_database_btn .fa-cog').removeClass('fa-spin');

					$('html, body').animate({
				        scrollTop: $("#step_2").offset().top
				    }, 1000);

				} else {
					$('#d_hostname').removeClass('is-valid');		
					$('#d_dbusername').removeClass('is-valid');		
					$('#d_password').removeClass('is-valid');		
					$('#d_database').removeClass('is-valid');		

					$('#d_hostname').addClass('is-invalid');		
					$('#d_dbusername').addClass('is-invalid');		
					$('#d_password').addClass('is-invalid');		
					$('#d_database').addClass('is-invalid');		
					
					$('.text-success.d_database_text').html('');
					$('.text-danger.d_database_text').html(result.error);

					flag = 1;

					$('#d_database_btn .fa-cog').removeClass('fa-spin');
				}
	    	}
		});
	}
}

function alter_usertable() {
	var flag 		= 1;
	var i_data 		= {
		'hostname'	: $('#d_hostname').val(),
		'username'	: $('#d_dbusername').val(),
		'password'	: $('#d_password').val(),
		'database'	: $('#d_database').val(),

		'usertable'	: $('#d_usertable').val(),
		'id'		: $('#d_userid').val(),
		'firstname'	: $('#d_firstname').val(),
		'lastname'	: $('#d_lastname').val(),
		'd_username': $('#d_username').val(),
		'email'		: $('#d_email').val(),
	};

	if(!i_data.usertable) {
		$('#d_usertable').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.id) {
		$('#d_userid').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.firstname) {
		$('#d_firstname').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.lastname) {
		$('#d_lastname').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.username) {
		$('#d_username').addClass('is-invalid');
		flag = 0;
	} else if(!i_data.email) {
		$('#d_email').addClass('is-invalid');
		flag = 0;
	} else {
		$('#d_usertable').removeClass('is-invalid');		
		$('#d_userid').removeClass('is-invalid');		
		$('#d_firstname').removeClass('is-invalid');		
		$('#d_lastname').removeClass('is-invalid');		
		$('#d_username').removeClass('is-invalid');		
		$('#d_email').removeClass('is-invalid');		
		
		$('#d_usertable').addClass('is-valid');		
		$('#d_userid').addClass('is-valid');		
		$('#d_firstname').addClass('is-valid');		
		$('#d_lastname').addClass('is-valid');		
		$('#d_username').addClass('is-valid');		
		$('#d_email').addClass('is-valid');		
		
		flag = 1;
	}

	if(flag) {
		$('#d_usertable_btn .fa-cog').addClass('fa-spin');
		$.ajax({
			type    : 'POST',
            url     : base_url + "index.php",
            async   : true,
            cache   : false,
            data 	: i_data,
			success: function(result) {
	        	if(result.response) {
					$('#d_usertable').removeClass('is-invalid');		
					$('#d_userid').removeClass('is-invalid');		
					$('#d_firstname').removeClass('is-invalid');		
					$('#d_lastname').removeClass('is-invalid');		
					$('#d_username').removeClass('is-invalid');		
					$('#d_email').removeClass('is-invalid');		
					
					$('#d_usertable').addClass('is-valid');		
					$('#d_userid').addClass('is-valid');		
					$('#d_firstname').addClass('is-valid');		
					$('#d_lastname').addClass('is-valid');		
					$('#d_username').addClass('is-valid');		
					$('#d_email').addClass('is-valid');		

					$('.text-success.d_usertable_text').html('Congrats! existing user table altered successfully!');
					$('.text-danger.d_usertable_text').html('');

					flag = 0;

					$('#step_2 input, #step_2 button').attr('disabled', true);
					$('#step_3 input, #step_3 button').attr('disabled', false);

					$('#d_usertable_btn .fa-cog').removeClass('fa-spin');

					$('html, body').animate({
				        scrollTop: $("#step_3").offset().top
				    }, 1000);

				} else {
					$('#d_usertable').removeClass('is-valid');		
					$('#d_userid').removeClass('is-valid');		
					$('#d_firstname').removeClass('is-valid');		
					$('#d_lastname').removeClass('is-valid');		
					$('#d_username').removeClass('is-valid');		
					$('#d_email').removeClass('is-valid');		
					
					$('#d_usertable').addClass('is-invalid');		
					$('#d_userid').addClass('is-invalid');		
					$('#d_firstname').addClass('is-invalid');		
					$('#d_lastname').addClass('is-invalid');		
					$('#d_username').addClass('is-invalid');		
					$('#d_email').addClass('is-invalid');		
					
					$('.text-success.d_usertable_text').html('');
					$('.text-danger.d_usertable_text').html(result.error);

					flag = 1;

					$('#d_usertable_btn .fa-cog').removeClass('fa-spin');
				}
	    	}
		});
	}
}

$(function() {
	/*Disable the other steps by default*/
	$('#step_2 input, #step_2 button').attr('disabled', true);
	$('#step_3 input, #step_3 button').attr('disabled', true);
	$('#step_4 input, #step_4 button').attr('disabled', true);
	$('#step_5 input, #step_5 button').attr('disabled', true);

	/*=================== Step 1 ==================*/
	$('#d_database_btn').on('click', function() {
		configure_database();
	});

	/*=================== Step 2 ==================*/
	$('#d_usertable_btn').on('click', function() {
		alter_usertable();
	});

	/*=================== Step 3 ==================*/
	$('#d_transfer_btn').on('click', function() {
		transfer_files();
	});

	/*=================== Step 4 ==================*/
	$('#d_config_btn').on('click', function() {
		configure_config();
	});

	/*=================== Step 5 ==================*/
	$('#d_finish_btn').on('click', function() {
		if( ($('input[name="dbcharset"]:checked').length > 0) && ($('input[name="ac_init"]:checked').length > 0) ) {
			var w_url = base_url.replace('chat_installer/', '');
			window.location.href = w_url;
		} else {
			$('.text-danger.d_finish_text').html('Please complete the above steps and click both the checkboxes to proceed!');
		}

	});	

})