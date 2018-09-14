<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Chat config variables
| -------------------------------------------------------------------------
| 
*/
$config['chat']			=  (object) array(

	/* ============ General Settings ============*/

	/* Logged in user_id
	 * The user_id field of the user who in logged in
	 * 
	 * e.g $_SESSION['logged_in_user_id']
	*/			
	'l_i_usr_id'     => isset(%LOGGED_USER_ID%) ? %LOGGED_USER_ID% : NULL,

	/* Chat users list limit
	 * The total number of users that should shown in the chat box
	 * 
	 * e.g 10
	*/	
	'usrs_lmt'     		=> '10',

	/* Conversations limit
	 * The total number conversations in the conversation box
	 * 
	 * e.g 10
	*/	
	'cnvs_lmt'     		=> '10',

	/* User image upload folder path
	 * The folder path in which the user's profile pics will be saved
	 * 
	 * e.g ac_upload/user_imgs/
	 *
	 * NOTE: Don't forget to put the ending slash
	*/	
	'img_upld_pth'     	=> '%UPLOAD_PATH%',

	
	/* Assets Images folder path
	 * The folder path of Chat js file
	 * 
	 * e.g ac_assets/images/
	*/	
	'ast_img_pth'     	=> '%ASSET_IMG_PATH%',

	/* Assets sound folder path
	 * The folder path of Chat sound notification file
	 * 
	 * e.g ac_assets/images/
	*/	
	'ast_sound_pth'     	=> '%ASSET_SOUND_PATH%',

	
	/* ============ Database table names ============*/

	/* Users table name
	 * Your existing Users table name
	 * 
	 * e.g users
	*/	
	'usrs_tb'			=> '%USERTABLE%',
	
		/* Users table columns names
		 * 
		 * Column Name - user id
		 * 
		 * e.g user_id
		*/	
		'usrs_usr_id'		=> '%USERID%',

		/* Column Name - username
		 * 
		 * e.g username
		*/	
		'usrs_usrnme'	=> '%USERNAME%',

		/* Column Name - firstname
		 * 
		 * e.g firstname
		*/	
		'usrs_frstnme'	=> '%FIRSTNAME%',

		/* Column Name - lastname
		 * 
		 * e.g lastname
		*/	
		'usrs_lstnme'	=> '%LASTNAME%',

		/* Column Name - email
		 * 
		 * e.g email
		*/	
		'usrs_eml'		=> '%EMAIL%',

		/* Column Name - online
		 * 
		 * e.g online
		*/	
		'usrs_online'		=> 'ac_online',

		/* Column Name - user image
		 * 
		 * e.g chat_image
		*/	
		'usrs_img'		=> 'ac_image',


	/* Messages table name
	 * The messages table name
	 * (you can change the default table name and it's columns name and then update the names below)
	 * 
	 * e.g chat_messages
	*/	
	'ac_msgs_tb'		=> 'ac_msgs',
	
		/* Messages table columns names
		 * 
		 * Column Name - id
		 * 
		 * e.g id
		*/	
		'ac_msgs_id'	=> 'id',

		/* Column Name - from
		 * 
		 * e.g from
		*/	
		'ac_msgs_frm'	=> 'm_frm',

		/* Column Name - to
		 * 
		 * e.g to
		*/	
		'ac_msgs_to'	=> 'm_to',

		/* Column Name - message
		 * 
		 * e.g message
		*/	
		'ac_msgs_msg'	=> 'msg',

		/* Column Name - is_read
		 * 
		 * e.g is_read
		*/	
		'ac_msgs_is_read'	=> 'is_read',

		/* Column Name - from_delete
		 * 
		 * e.g from_delete
		*/	
		'ac_msgs_frm_del'	=> 'frm_del',

		/* Column Name - to_delete
		 * 
		 * e.g to_delete
		*/	
		'ac_msgs_to_del'	=> 'to_del',

		/* Column Name - date_updated
		 * 
		 * e.g date_updated
		*/	
		'ac_msgs_dt_upd'	=> 'dt_upd',


	/* Users Messages group table name
	 * The users_messages table name
	 * (you can change the default table name and it's columns name and then update the names below)
	 * 
	 * e.g chat_users_messages
	*/	
	'ac_usrs_msgs_tb'		=> 'ac_usrs_msgs',
	
		/* Users Messages table columns names
		 * 
		 * Column Name - id
		 * 
		 * e.g id
		*/	
		'ac_usrs_msgs_id'		=> 'id',

		/* Column Name - user_id
		 * 
		 * e.g user_id
		*/	
		'ac_usrs_msgs_usr_id'	=> 'usr_id',

		/* Column Name - messages_id
		 * 
		 * e.g messages_id
		*/	
		'ac_usrs_msgs_msg_id'	=> 'msg_id',

		/* Column Name - date_updated
		 * 
		 * e.g date_updated
		*/	
		'ac_usrs_msgs_dt_upd'	=> 'dt_upd',


	/* Users Block user table name
	 * The block_user table name
	 * (you can change the default table name and it's columns name and then update the names below)
	 * 
	 * e.g chat_block_user
	*/	
	'ac_blck_tb'		=> 'ac_blck_usr',
	
		/* Users Block user table columns names
		 * 
		 * Column Name - user_id
		 * 
		 * e.g user_id
		*/	
		'ac_blck_usr_id'		=> 'usr_id',

		/* Column Name - user_id
		 * 
		 * e.g user_id
		*/	
		'ac_blck_usr_blckd_usr_id'	=> 'blckd_usr_id',

);

/*End of Chat config file*/