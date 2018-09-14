<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Library Chat_lib
 *
 * This class handles all the functionality
 *
 * @package     Codeigniter Chat
 * @author      classiebit
**/

class Chat_lib {

	var $AC_LIB;
	var $AC_CONFIG;
	
    function __construct()
    {
        $this->AC_LIB =& get_instance();
        $this->AC_LIB->load->helper(array('form', 'url', 'smiley'));
        $this->AC_LIB->load->library(array('chat_db_lib', 'form_validation'));
        $this->AC_LIB->config->load('chat_config', TRUE);
        $this->AC_CONFIG = $this->AC_LIB->config->item('chat', 'chat_config');
    }

	/*
    * Get users list get_users
    */
    public function get_users()
    {
    	$data		= array('logged_user'=>NULL);

    	// check authentication
    	if($this->AC_CONFIG->l_i_usr_id)
    	{
    		$data['logged_user'] 			= $this->AC_LIB->chat_db_lib->get_user($this->AC_CONFIG->l_i_usr_id);

    		$data['logged_user']->avatar	= $data['logged_user']->avatar ? base_url().'/'.$this->AC_CONFIG->img_upld_pth.'/'.$data['logged_user']->avatar : base_url().'/'.$this->AC_CONFIG->ast_img_pth.'/avatar.png'; 
    		
    		$blocked_by 		 = array();
    		$blocked_by_t 		 = $this->AC_LIB->chat_db_lib->get_blocked_by_users($this->AC_CONFIG->l_i_usr_id);
    		foreach ($blocked_by_t as $val) 
    			$blocked_by[] = $val->user_id;	
    		
	        $users           	 = $this->AC_LIB->chat_db_lib->get_users($this->AC_CONFIG->l_i_usr_id, $blocked_by, $this->AC_CONFIG->usrs_lmt);

	        $data['users'] 		= $users;

	        // upload image path
	        $data['img_upld_pth'] = $this->AC_CONFIG->img_upld_pth;

	        // assets image path
	        $data['ast_img_pth'] = $this->AC_CONFIG->ast_img_pth;
    	}

    	$this->AC_LIB->load->view('chat_view', $data);
    }

    /*
    * Search users search_users
    */
    public function search_users()
    {
    	// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{	
        	header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));
    		exit;
    	}

    	/* Validate form input */
        $this->AC_LIB->form_validation
        ->set_rules('search_user', 'Search', 'required|trim|alpha_numeric_spaces|min_length[1]|max_length[32]', array('alpha_numeric_spaces'=>'Invalid search! Search by name or username', 'required'=>''));

        if($this->AC_LIB->form_validation->run() === FALSE)
        {
        	header('Content-Type: application/json');
        	echo json_encode(array('status' => false, 'response'=> validation_errors()));
        	exit;	
        }

    	$search_user 		= $this->AC_LIB->input->post('search_user');

    	// filterout blocked users
		$blocked_by 		= array();
		$blocked_by_t 		= $this->AC_LIB->chat_db_lib->get_blocked_by_users($this->AC_CONFIG->l_i_usr_id);
		foreach ($blocked_by_t as $val) 
			$blocked_by[] = $val->user_id;	
		
		// get search result
        $users           	= $this->AC_LIB->chat_db_lib->get_users($this->AC_CONFIG->l_i_usr_id, $blocked_by, $this->AC_CONFIG->usrs_lmt, $search_user);

        header('Content-Type: application/json');
        echo json_encode(array('status' => true, 'users'=> $users));
        exit;
    }

    /*
    * Get realtime updates of messages get_updates
    */
    public function get_updates()
	{
		// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{
    		header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));
    		exit;
    	}

		$last_seen_id  	= $this->AC_LIB->chat_db_lib->get_recent_chat_users($this->AC_CONFIG->l_i_usr_id);

		// if no recent chats between current users then set last_seen_id = 0
		$last_seen_id  	= empty($last_seen_id) ? 0 : $last_seen_id->message_id;

		// Now check for latest messages if any
	    $unread_messages = $this->AC_LIB->chat_db_lib->get_unread_messages($this->AC_CONFIG->l_i_usr_id, $last_seen_id);

	    // if no messages then do nothing
	    if(empty($unread_messages))
	    {
	    	header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'no updates'));
    		exit;
	    }

	    $messages 	= array();
		$senders 	= array();
		foreach ($unread_messages as $message) 
		{
			if(!isset($senders[$message->m_from]))
				$senders[$message->m_from]['count'] = 1;
			else
				$senders[$message->m_from]['count'] += 1;
			
			$m_chat = array(
				'message_id' 	=> $message->id,
				'sender' 		=> $message->m_from, 
				'recipient' 	=> $message->m_to,
				'avatar' 		=> $message->avatar ? $this->AC_CONFIG->img_upld_pth.'/'.$message->avatar : $this->AC_CONFIG->ast_img_pth.'/avatar.png',
				'message' 		=> $message->message,
				'dt_updated' 	=> date("M j, Y, g:i a", strtotime($message->dt_updated)),
				'is_read' 		=> $message->is_read,
				'type'			=> $message->m_from == $this->AC_CONFIG->l_i_usr_id ? 'out' : 'in',
				'name'			=> $message->m_from == $this->AC_CONFIG->l_i_usr_id ? 'You' : ucwords($message->firstname.' '.$message->lastname)
				);

			array_push($messages, $m_chat);
		}

		/*Count unread messages for inline notification*/
		$groups = array();
		foreach ($senders as $key=>$sender) 
		{
			$sender = array('user'=> $key, 'count'=>$sender['count']);
			array_push($groups, $sender);
		}

		// if the message is already read then mark it as read or else insert unread message
		$this->AC_LIB->chat_db_lib->mark_as_read($this->AC_CONFIG->l_i_usr_id);

		header('Content-Type: application/json');
		echo json_encode(array('status' => true, 'messages' => $messages, 'senders' => $groups, 'ast_sound_pth' => $this->AC_CONFIG->ast_sound_pth));
		exit;
	     
	}

	/*
	* Change status change_status
	*/
    public function change_status()
    {
    	// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{
    		header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));exit;
    	}

		$user 	= $this->AC_LIB->chat_db_lib->get_user($this->AC_CONFIG->l_i_usr_id);
		$status = $user->online == '0' ? '1' : '0';
			
		// update user status
		$this->AC_LIB->chat_db_lib->update_user($this->AC_CONFIG->l_i_usr_id, array($this->AC_CONFIG->usrs_online=>$status));	

		header('Content-Type: application/json');
		echo json_encode(array('status' => $status));
		exit;
	}

	/*
	* Block user block_user
	*/
	public function block_user()
    {
    	// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{
    		header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));
    		exit;
    	}

    	/* Validate form input */
        $this->AC_LIB->form_validation
        ->set_rules('sub_user_id', 'User id', 'required|trim|is_natural_no_zero');

        if($this->AC_LIB->form_validation->run() === FALSE)
        {
        	header('Content-Type: application/json');
        	echo json_encode(array('status' => false, 'response'=> validation_errors()));
        	exit;	
        }

		$sub_user_id 	= $this->AC_LIB->input->post('sub_user_id');
		
		// block user
		$flag 			= $this->AC_LIB->chat_db_lib->block_user($this->AC_CONFIG->l_i_usr_id, $sub_user_id);

		header('Content-Type: application/json');
		echo json_encode(array('status'=> $flag));
		exit;
	}

	/*
	* Delete chat history delete_chat
	*/
	public function delete_chat()
    {
    	// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{
    		header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));
    		exit;
    	}

    	/* Validate form input */
        $this->AC_LIB->form_validation
        ->set_rules('sub_user_id', 'User id', 'required|trim|is_natural_no_zero');

        if($this->AC_LIB->form_validation->run() === FALSE)
        {
        	header('Content-Type: application/json');
        	echo json_encode(array('status' => false, 'response'=> validation_errors()));
        	exit;	
        }

		$sub_user_id 	= $this->AC_LIB->input->post('sub_user_id');
		
		$flag 			= $this->AC_LIB->chat_db_lib->delete_chat($this->AC_CONFIG->l_i_usr_id, $sub_user_id);

		// add the header here
		header('Content-Type: application/json');
		echo json_encode(array('status'=> $flag));
	}
	
	/*
	* Get messages get_messages
	*/
	public function get_messages()
	{
		// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{
    		header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));
    		exit;
    	}

    	/* Validate form input */
        $this->AC_LIB->form_validation
        ->set_rules('user', 'User id', 'trim|is_natural_no_zero')
        ->set_rules('limit', 'Limit', 'trim|numeric');

        if($this->AC_LIB->form_validation->run() === FALSE)
        {
        	header('Content-Type: application/json');
        	echo json_encode(array('status' => false, 'response'=> validation_errors()));
        	exit;	
        }

		$buddy 				= (int) $this->AC_LIB->input->post('user');
		$limit 				= isset($_POST['limit']) ? (int) $this->AC_LIB->input->post('limit') : $this->AC_CONFIG->cnvs_lmt;
		$messages 			= array_reverse($this->AC_LIB->chat_db_lib->get_messages($this->AC_CONFIG->l_i_usr_id, $buddy, $limit));
		$total 				= $this->AC_LIB->chat_db_lib->paginate_messages($this->AC_CONFIG->l_i_usr_id, $buddy);

		$user 				= $this->AC_LIB->chat_db_lib->get_user($this->AC_CONFIG->l_i_usr_id);
		$chatbuddy 			= $this->AC_LIB->chat_db_lib->get_user($buddy);

		$thread 			= array();
		foreach ($messages as $message) 
		{
			$chat = array();
			$chat['message_id'] 	= $message->id;
			$chat['sender'] 		= $message->m_from;
			$chat['recipient'] 		= $message->m_to;
			$chat['message'] 		= $message->message;
			$chat['dt_updated'] 	= date("M j, Y, g:i a", strtotime($message->dt_updated));
			$chat['is_read'] 		= $message->is_read;
			$chat['type']			= $message->m_from == $this->AC_CONFIG->l_i_usr_id ? 'out' : 'in';

			if($message->m_from == $this->AC_CONFIG->l_i_usr_id)
			{
				$chat['avatar']     = $user->avatar 
										? base_url().'/'.$this->AC_CONFIG->img_upld_pth.'/'.$user->avatar  
										: base_url().'/'.$this->AC_CONFIG->ast_img_pth.'/avatar.png';
				$chat['name']		= 'You';
			}
			else
			{
				$chat['avatar']		= $chatbuddy->avatar 
										? base_url().'/'.$this->AC_CONFIG->img_upld_pth.'/'.$chatbuddy->avatar 
										: base_url().'/'.$this->AC_CONFIG->ast_img_pth.'/avatar.png';
				$chat['name']		= ucwords($chatbuddy->firstname.' '.$chatbuddy->lastname);
			}

			array_push($thread, $chat);
		}

		$c_buddy = array(
			'name' 		 	=> ucwords($chatbuddy->firstname.' '.$chatbuddy->lastname),
			'status' 	 	=> $chatbuddy->online,
			'is_blocked' 	=> $chatbuddy->is_blocked,
			'id' 		 	=> $chatbuddy->id,
			'limit' 	 	=> $limit + $this->AC_CONFIG->cnvs_lmt,
			'more' 		 	=> $total  <= $limit ? false : true, 
			'scroll' 	 	=> $limit > $this->AC_CONFIG->cnvs_lmt  ?  false : true,
			'remaining' 	=> $total - $limit
			);

		header('Content-Type: application/json');
		echo json_encode(array('status' => true, 'buddy' => $c_buddy, 'thread'  => $thread));
		exit;
	}

	/*
	* Send message send_message
	*/
	public function send_message()
	{
		// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{
    		header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));
    		exit;
    	}

    	/* Validate form input */
        $this->AC_LIB->form_validation
        ->set_rules('user', 'User id', 'required|trim|is_natural_no_zero')
        ->set_rules('message', 'Message', 'required|trim');

        if($this->AC_LIB->form_validation->run() === FALSE)
        {
        	header('Content-Type: application/json');
        	echo json_encode(array('status' => false, 'response'=> validation_errors()));
        	exit;	
        }

		$buddy 		= $this->AC_LIB->input->post('user');
		$message 	= nl2br($this->AC_LIB->input->post('message'));

		if($message != '' && $buddy != '')
		{
			$msg    = array(
						$this->AC_CONFIG->ac_msgs_frm 		=> $this->AC_CONFIG->l_i_usr_id,
						$this->AC_CONFIG->ac_msgs_to 		=> $buddy,
						$this->AC_CONFIG->ac_msgs_msg 		=> $message,
						$this->AC_CONFIG->ac_msgs_dt_upd 	=> date('Y-m-d H:i:s'),
					);
			
			$owner 		= $this->AC_LIB->chat_db_lib->get_user($msg[$this->AC_CONFIG->ac_msgs_frm]);
			
			// reject if user is blocked
			$blocked_by = $this->AC_LIB->chat_db_lib->blocked_by($this->AC_CONFIG->l_i_usr_id, $buddy);
			
			if(!empty($blocked_by))
			{
				$response = array(
					'status' => false,
					'response' => 'You are blocked'
				);
			}
			else
			{
				$msg_id = $this->AC_LIB->chat_db_lib->send_message($msg);
			
				$chat = array(
					'message_id' 	=> $msg_id,
					'sender' 		=> $msg[$this->AC_CONFIG->ac_msgs_frm], 
					'recipient' 	=> $msg[$this->AC_CONFIG->ac_msgs_to],
					'avatar' 		=> $owner->avatar ? $this->AC_CONFIG->img_upld_pth.'/'.$owner->avatar : $this->AC_CONFIG->ast_img_pth.'/avatar.png',
					'message' 		=> $msg[$this->AC_CONFIG->ac_msgs_msg],
					'dt_updated' 	=> date("M j, Y, g:i a", strtotime($msg[$this->AC_CONFIG->ac_msgs_dt_upd])),
					'is_read' 		=> 0,
					'type'			=> $msg[$this->AC_CONFIG->ac_msgs_frm] == $this->AC_CONFIG->l_i_usr_id ? 'out' : 'in',
					'name'			=> $msg[$this->AC_CONFIG->ac_msgs_frm] == $this->AC_CONFIG->l_i_usr_id ? 'You' : ucwords($owner->firstname)
					);

				$response = array(
					'status' 	=> true,
					'message' 	=> $chat 	  
				);
			}
		}
		else
		{
			$response = array(
				'status' => false,
				'response' => 'Empty fields exists'
			);
		}
		//add the header here
		header('Content-Type: application/json');
		echo json_encode( $response );
	}


	/*
    * Upload profile pic upload_profile_pic
    */
    public function upload_profile_pic()
    {
    	// check authentication
    	if(!$this->AC_CONFIG->l_i_usr_id) 
    	{
    		header('Content-Type: application/json');
    		echo json_encode(array('status' => false, 'response'=> 'access denied'));
    		exit;
    	}

    	// upload users image
        $filename               = NULL;
        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file               = array('folder'=>$this->AC_CONFIG->img_upld_pth, 'input_file'=>'image');
            $filename           = $this->upload_file($file);
            // through image upload error
            if(!empty($filename['error']))
            {
	            header('Content-Type: application/json');
	        	echo json_encode(array('status' => false, 'response'=> 'Please, upload an image! (png, jpg or jpeg only)'));
	        	exit;	
            }
        }
        else
        {
        	header('Content-Type: application/json');
        	echo json_encode(array('status' => false, 'response'=> 'Please, upload an image!'));exit;	
        }

        // update user profile after successful upload
        $flag 		= $this->AC_LIB->chat_db_lib->update_user($this->AC_CONFIG->l_i_usr_id, array($this->AC_CONFIG->usrs_img=>$filename));

        header('Content-Type: application/json');
        echo json_encode(array('status' => true, 'response'=> 'Image saved successfully!'));exit;	

    }

    /* ========== PRIVATE FUNCTIONS ==========*/
	/**
    * Upload File
    */
    private function upload_file($data = array())
    {
        $this->AC_LIB->load->library(array('upload', 'image_lib'));
        
        $config                         = array();
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = './'.$data['folder'].'/';
        
        if (!is_dir($config['upload_path']))
            mkdir($config['upload_path'], 0777, TRUE);
        
        $filename                       = time().rand(1,988);
        $extension                      = strtolower(pathinfo($_FILES[$data['input_file']]['name'], PATHINFO_EXTENSION));
        
        // original file for resizing
        $config['file_name']            = $filename.'_large'.'.'.$extension;

        // file name for further use
        $filename                       = $filename.'.'.$extension;
        
        $this->AC_LIB->upload->initialize($config);

        if (! $this->AC_LIB->upload->do_upload($data['input_file'])) 
        {            
            // remove all uploaded files in case of error
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->AC_LIB->upload->display_errors());
        }

        // cropped thumbnail
        $thumb                          = array();
        $thumb['image_library']         = 'gd2';
        $thumb['source_image']          = $config['upload_path'].$config['file_name'];
        $thumb['new_image']             = $config['upload_path'].$filename;
        $thumb['maintain_ratio']        = TRUE;
        $thumb['width']                 = 350;
        $thumb['height']                = 350;
        $thumn['quality']               = 60;
        $thumb['file_permissions']      = 0644;
        
        $this->AC_LIB->image_lib->initialize($thumb);  
        
        if (! $this->AC_LIB->image_lib->resize()) 
        {
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->AC_LIB->image_lib->display_errors());
        }

        $this->AC_LIB->image_lib->clear();        

        // remove the original image
        unlink($config['upload_path'].$config['file_name']);
        
        return $filename;
        
    } 

    private function reset_file($path = '', $data = '')
    {
        if(file_exists($path.$data))
            @unlink($path.$data);
        
        return 1;
    }

}
/*End Chat_lib Class*/