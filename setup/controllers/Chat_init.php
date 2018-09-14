<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Chat_init Controller
 *
 * This class handles Codeigniter Chat's complete functionality
 *
 * @package     chat
 * @author      classiebit
*/

class Chat_init extends CI_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // Chat Init
        $this->load->library('chat_lib');
    }

    public function get_users()
    {
        $this->chat_lib->get_users();
    }

    public function search_users()
    {
        $this->chat_lib->search_users();
    }

    public function upload_profile_pic()
    {
        $this->chat_lib->upload_profile_pic();
    }

    public function get_updates()
    {
        $this->chat_lib->get_updates();
    }

    public function change_status()
    {
        $this->chat_lib->change_status();
    }

    public function block_user()
    {
        $this->chat_lib->block_user();
    }

    public function delete_chat()
    {
        $this->chat_lib->delete_chat();
    }

    public function send_message()
    {
        $this->chat_lib->send_message();
    }

    public function get_messages()
    {
        $this->chat_lib->get_messages();
    }

}

/* Chat_init controller ends */