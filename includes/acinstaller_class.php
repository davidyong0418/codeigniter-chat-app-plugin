<?php

// Load the classes and create the new objects
require_once('Core_class.php');
require_once('Database_class.php');

class Acinstaller {

	var $core;
	var $database;

	/**
     * Constructor
     */
    function __construct()
    {
        $this->core = new Core();
		$this->database = new Database();
    }

    function configure_config()
    {
    	// first transfer the chat_config.php file to user's website directory
    	$trans 					= array();
    	$trans['path']			= 'Config Path - '.trim($_POST['config_path']);
    	$trans['source'] 		= 'setup/config/';
    	$trans['destination'] 	= '../'.trim($_POST['config_path']);
    	$trans['file_check'] 	= '/chat_config.php';
		$trans['make_dir']		= false;

		$flag 					= $this->core->xcopy($trans);

		// now check if file is transferred or not
		if(! file_exists($trans['destination'].$trans['file_check']))
		{
			$response   = array('response'=>false, 'error'=>"Invalid ".$trans['path']);

			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}		

    	// now let's update the chat_config file according to the website
    	$c_data 						= array();
    	$c_data['logged_user_id']		= trim($_POST['d_logged']); // %LOGGED_USER_ID%
    	$c_data['upload_path']			= trim($_POST['upload_path']); // %UPLOAD_PATH%
    	$c_data['asset_img_path']		= trim($_POST['img_path']); // %ASSET_IMG_PATH%
    	$c_data['asset_sound_path']		= trim($_POST['sound_path']); // %ASSET_SOUND_PATH%
    	$c_data['usertable']			= trim($_POST['usertable']); // %USERTABLE%
    	$c_data['userid']				= trim($_POST['id']); // %USERID%
    	$c_data['firstname']			= trim($_POST['firstname']); // %FIRSTNAME%
    	$c_data['lastname']				= trim($_POST['lastname']); // %LASTNAME%
    	$c_data['username']				= trim($_POST['d_username']); // %USERNAME%
    	$c_data['email']				= trim($_POST['email']); // %EMAIL%
    	
    	// Config path
    	$source 		= $trans['source'].'chat_config.php';
		$destination 	= $trans['destination'].'/chat_config.php';
		
		// Open the file
		$config_file 	= file_get_contents($source);

		$new_config  	= str_replace("%LOGGED_USER_ID%", $c_data['logged_user_id'], $config_file);
		$new_config  	= str_replace("%UPLOAD_PATH%", $c_data['upload_path'] , $new_config);
		$new_config  	= str_replace("%ASSET_IMG_PATH%", $c_data['asset_img_path'], $new_config);
		$new_config  	= str_replace("%ASSET_SOUND_PATH%", $c_data['asset_sound_path'], $new_config);
		$new_config  	= str_replace("%USERTABLE%", $c_data['usertable'], $new_config);
		$new_config  	= str_replace("%USERID%", $c_data['userid'], $new_config);
		$new_config  	= str_replace("%FIRSTNAME%", $c_data['firstname'], $new_config);
		$new_config  	= str_replace("%LASTNAME%", $c_data['lastname'], $new_config);
		$new_config  	= str_replace("%USERNAME%", $c_data['username'], $new_config);
		$new_config  	= str_replace("%EMAIL%", $c_data['email'], $new_config);

		// Write the new chat_config.php file
		$handle 		= fopen($destination, 'w+');

		// Chmod the file, in case the user forgot
		@chmod($destination, 0777);
		
		// Verify file permissions
		$flag = FALSE;
		if(is_writable($destination))
			if(fwrite($handle, $new_config))
				$flag = TRUE;

		if($flag)
			$response   = array('response'=>true);
		else
			$response   = array('response'=>false, 'error'=>'Failed to write config file, make sure config directory is writable!');

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
    }

	function transfer_files() 
	{
		$transfer 					= array();
		
		// css
		$transfer[0]['path']		= 'CSS Path - '.trim($_POST['css_path']);
		$transfer[0]['source'] 		= 'setup/assets/css/';
		$transfer[0]['destination'] = '../'.trim($_POST['css_path']);
		$transfer[0]['file_check'] 	= '/chat-app.css';
		$transfer[0]['make_dir']	= false;
		
		// js
		$transfer[1]['path']		= 'JS Path - '.trim($_POST['js_path']);
		$transfer[1]['source'] 		= 'setup/assets/js/';
		$transfer[1]['destination'] = '../'.trim($_POST['js_path']);
		$transfer[1]['file_check'] 	= '/chat-app.js';
		$transfer[1]['make_dir']	= false;

		// images
		$transfer[2]['path']		= 'Images Path - '.trim($_POST['img_path']);
		$transfer[2]['source'] 		= 'setup/assets/images/';
		$transfer[2]['destination'] = '../'.trim($_POST['img_path']);
		$transfer[2]['file_check'] 	= '/avatar.png';
		$transfer[2]['make_dir']	= false;
		
		// sound
		$transfer[3]['path']		= 'Sound Path - '.trim($_POST['sound_path']);
		$transfer[3]['source'] 		= 'setup/assets/sound/';
		$transfer[3]['destination'] = '../'.trim($_POST['sound_path']);
		$transfer[3]['file_check'] 	= '/sound.mp3';
		$transfer[3]['make_dir']	= TRUE;

		// upload
		$transfer[4]['path']		= 'Upload Path - '.trim($_POST['upload_path']);
		$transfer[4]['source'] 		= 'setup/upload/';
		$transfer[4]['destination'] = '../'.trim($_POST['upload_path']);
		$transfer[4]['file_check'] 	= '/index.html';
		$transfer[4]['make_dir']	= TRUE;
		
		// lib
		$transfer[5]['path']		= 'Libraries Path - '.trim($_POST['lib_path']);
		$transfer[5]['source'] 		= 'setup/libraries/';
		$transfer[5]['destination'] = '../'.trim($_POST['lib_path']);
		$transfer[5]['file_check'] 	= '/chat_lib.php';
		$transfer[5]['make_dir']	= false;
		
		// controller
		$transfer[6]['path']		= 'Controllers Path - '.trim($_POST['cont_path']);
		$transfer[6]['source'] 		= 'setup/controllers/';
		$transfer[6]['destination'] = '../'.trim($_POST['cont_path']);
		$transfer[6]['file_check'] 	= '/Chat_init.php';
		$transfer[6]['make_dir']	= false;

		// views
		$transfer[7]['path']		= 'Views Path - '.trim($_POST['view_path']);
		$transfer[7]['source'] 		= 'setup/views/';
		$transfer[7]['destination'] = '../'.trim($_POST['view_path']);
		$transfer[7]['file_check'] 	= '/chat_view.php';
		$transfer[7]['make_dir']	= false;

		foreach($transfer as $trans)
		{
			$flag 					= $this->core->xcopy($trans);

			// now check if files are transferred or not
			if(! file_exists($trans['destination'].$trans['file_check']))
			{
				$response   = array('response'=>false, 'error'=>"Invalid ".$trans['path']);

				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		}

		$response   = array('response'=>true);

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	}

	function configure_database()
	{
		$data 				= array();
		$data['hostname'] 	= trim($_POST['hostname']);
		$data['username'] 	= trim($_POST['username']);
		$data['password'] 	= trim($_POST['password']);
		$data['database'] 	= trim($_POST['database']);

		$flag 				= $this->database->create_tables($data);

		if(isset($flag['error']))
			$response   = array('response'=>false, 'error'=>$flag['error']);
		else
			$response   = array('response'=>true);

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	}

	function alter_usertable()
	{
		$data 				= array();
		$data['hostname'] 	= trim($_POST['hostname']);
		$data['username'] 	= trim($_POST['username']);
		$data['password'] 	= trim($_POST['password']);
		$data['database'] 	= trim($_POST['database']);
		$data['usertable'] 	= trim($_POST['usertable']);

		$flag 				= $this->database->alter_table($data);

		if(isset($flag['error']))
		{
			$response   = array('response'=>false, 'error'=>$flag['error']);
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}

		header('Content-Type: application/json');
		echo json_encode(array('response'=>true));
		exit;
	}

}





?>