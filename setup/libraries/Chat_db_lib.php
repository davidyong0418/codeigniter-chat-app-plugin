<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Library chat_db_lib
 *
 * This class handles database interraction
 *
 * @package     Codeigniter Chat
 * @author      classiebit
**/

class Chat_db_lib
{
    var $AC_CONFIG;
    /*
    * DB Configuration
    */
    // users table
    var $usrs_tb;
    // user table columns
    var $usrs_usr_id;
    var $usrs_usrnme;
    var $usrs_frstnme;
    var $usrs_lstnme;
    var $usrs_eml;
    var $usrs_online;
    var $usrs_img;
    
    // chat_messages table
    var $ac_msgs_tb;
    // chat_messages columns
    var $ac_msgs_id;
    var $ac_msgs_frm;
    var $ac_msgs_to;
    var $ac_msgs_msg;
    var $ac_msgs_is_read;
    var $ac_msgs_frm_del;
    var $ac_msgs_to_del;
    var $ac_msgs_dt_upd;    

    // messages users table
    var $ac_usrs_msgs_tb;
    // messages users columns
    var $ac_usrs_msgs_id;
    var $ac_usrs_msgs_usr_id;
    var $ac_usrs_msgs_msg_id;
    var $ac_usrs_msgs_dt_upd;

    // block_user table
    var $ac_blck_tb;
    // block_user columns
    var $ac_blck_usr_id;
    var $ac_blck_usr_blckd_usr_id;

    public function __construct()
    {
        $this->AC_LIB =& get_instance();
        $this->AC_LIB->load->helper('inflector');
        $this->AC_LIB->load->database();
        $this->AC_LIB->config->load('chat_config', TRUE);
        $this->AC_CONFIG = $this->AC_LIB->config->item('chat', 'chat_config');

        // users table
        $this->usrs_tb                  = $this->AC_CONFIG->usrs_tb;
        $this->usrs_usr_id              = $this->AC_CONFIG->usrs_usr_id;
        $this->usrs_usrnme              = $this->AC_CONFIG->usrs_usrnme;
        $this->usrs_frstnme             = $this->AC_CONFIG->usrs_frstnme;
        $this->usrs_lstnme              = $this->AC_CONFIG->usrs_lstnme;
        $this->usrs_eml                 = $this->AC_CONFIG->usrs_eml;
        $this->usrs_online              = $this->AC_CONFIG->usrs_online;
        $this->usrs_img                 = $this->AC_CONFIG->usrs_img;
        
        // messages table
        $this->ac_msgs_tb               = $this->AC_CONFIG->ac_msgs_tb;
        $this->ac_msgs_id               = $this->AC_CONFIG->ac_msgs_id;
        $this->ac_msgs_frm              = $this->AC_CONFIG->ac_msgs_frm;
        $this->ac_msgs_to               = $this->AC_CONFIG->ac_msgs_to;
        $this->ac_msgs_msg              = $this->AC_CONFIG->ac_msgs_msg;
        $this->ac_msgs_is_read          = $this->AC_CONFIG->ac_msgs_is_read;
        $this->ac_msgs_frm_del          = $this->AC_CONFIG->ac_msgs_frm_del;
        $this->ac_msgs_to_del           = $this->AC_CONFIG->ac_msgs_to_del;
        $this->ac_msgs_dt_upd           = $this->AC_CONFIG->ac_msgs_dt_upd;

        // users messages table
        $this->ac_usrs_msgs_tb          = $this->AC_CONFIG->ac_usrs_msgs_tb;
        $this->ac_usrs_msgs_id          = $this->AC_CONFIG->ac_usrs_msgs_id;
        $this->ac_usrs_msgs_usr_id      = $this->AC_CONFIG->ac_usrs_msgs_usr_id;
        $this->ac_usrs_msgs_msg_id      = $this->AC_CONFIG->ac_usrs_msgs_msg_id;
        $this->ac_usrs_msgs_dt_upd      = $this->AC_CONFIG->ac_usrs_msgs_dt_upd;

        // block user table
        $this->ac_blck_tb               = $this->AC_CONFIG->ac_blck_tb;
        $this->ac_blck_usr_id           = $this->AC_CONFIG->ac_blck_usr_id;
        $this->ac_blck_usr_blckd_usr_id = $this->AC_CONFIG->ac_blck_usr_blckd_usr_id; 
    }

    /*
    * get_user by id
    */
    public function get_user($user_id = 0)
    {
        return  $this->AC_LIB->db
                ->select(array(
                    "$this->usrs_tb.$this->usrs_usr_id id",
                    "$this->usrs_tb.$this->usrs_usrnme username",
                    "$this->usrs_tb.$this->usrs_frstnme firstname",
                    "$this->usrs_tb.$this->usrs_lstnme lastname",
                    "$this->usrs_tb.$this->usrs_eml email",
                    "$this->usrs_tb.$this->usrs_img avatar",
                    "$this->usrs_tb.$this->usrs_online online",
                    "(SELECT BU.$this->ac_blck_usr_id FROM $this->ac_blck_tb BU WHERE BU.$this->ac_blck_usr_blckd_usr_id = $user_id) is_blocked",
                ))
                ->where("$this->usrs_tb.$this->usrs_usr_id", $user_id)
                ->get($this->usrs_tb)
                ->row();
    }

    /*
    * get blocked_by user (user blocked by another user)
    */
    public function blocked_by($logged_in_user_id = 0, $blocked_user_id = 0)
    {
        return  $this->AC_LIB->db
                ->select(array(
                            "$this->ac_blck_tb.$this->ac_blck_usr_id blocked_by",
                        ))
                ->where("(`$this->ac_blck_tb`.`$this->ac_blck_usr_id` = '$logged_in_user_id' AND `$this->ac_blck_tb`.`$this->ac_blck_usr_blckd_usr_id` = '$blocked_user_id')", NULL, FALSE)
                ->or_where("(`$this->ac_blck_tb`.`$this->ac_blck_usr_id` = '$blocked_user_id' AND `$this->ac_blck_tb`.`$this->ac_blck_usr_blckd_usr_id` = '$logged_in_user_id')", NULL, FALSE)
                ->get($this->ac_blck_tb)
                ->row();
    }    

    /*
    * get_users list
    */
    public function get_users($login_user_id = 0, $blocked_by = array(), $limit = 10, $search = '')
    {
        // first get the users with recent chats
        $recent_users_t =   $this->AC_LIB->db
                            ->select("$this->ac_usrs_msgs_usr_id user_id")
                            ->where(array("$this->ac_usrs_msgs_usr_id !="=>$login_user_id))
                            ->order_by("$this->ac_usrs_msgs_dt_upd")
                            ->limit($limit)
                            ->get($this->ac_usrs_msgs_tb)
                            ->result();

        if(count($recent_users_t))
        {
            $recent_users  = array();
            foreach($recent_users_t as $val)
                $recent_users[]  = $val->user_id;

            $this->AC_LIB->db
            ->select(array(
                "$this->usrs_tb.$this->usrs_usr_id id",
                "$this->usrs_tb.$this->usrs_usrnme username",
                "$this->usrs_tb.$this->usrs_frstnme firstname",
                "$this->usrs_tb.$this->usrs_lstnme lastname",
                "$this->usrs_tb.$this->usrs_eml email",
                "$this->usrs_tb.$this->usrs_img avatar",
                "$this->usrs_tb.$this->usrs_online online",
                "(SELECT IF(COUNT(ACM.$this->ac_msgs_id) > 0, COUNT(ACM.$this->ac_msgs_id), NULL) FROM $this->ac_msgs_tb ACM WHERE ACM.$this->ac_msgs_to = '$login_user_id' AND ACM.$this->ac_msgs_frm = $this->usrs_tb.$this->usrs_usr_id AND ACM.$this->ac_msgs_is_read = '0') unread",
            ));

            if(!empty($blocked_by))
                $this->AC_LIB->db->where_not_in("$this->usrs_usr_id", $blocked_by);  

            $this->AC_LIB->db->where_in("$this->usrs_usr_id", $recent_users);

            if($search)
                $this->AC_LIB->db
                ->group_start()
                ->or_like("$this->usrs_tb.$this->usrs_frstnme", $search, 'both')
                ->or_like("$this->usrs_tb.$this->usrs_lstnme", $search, 'both')
                ->or_like("$this->usrs_tb.$this->usrs_usrnme", $search, 'right')
                ->group_end();

            $result_r = $this->AC_LIB->db
                        ->limit($limit)
                        ->get($this->usrs_tb)
                        ->result();

            // now get more users if the recent users less than 20
            if(count($result_r) < $limit)
            {
                $this->AC_LIB->db
                ->select(array(
                    "$this->usrs_tb.$this->usrs_usr_id id",
                    "$this->usrs_tb.$this->usrs_usrnme username",
                    "$this->usrs_tb.$this->usrs_frstnme firstname",
                    "$this->usrs_tb.$this->usrs_lstnme lastname",
                    "$this->usrs_tb.$this->usrs_eml email",
                    "$this->usrs_tb.$this->usrs_img avatar",
                    "$this->usrs_tb.$this->usrs_online online",
                    "(SELECT IF(COUNT(ACM.$this->ac_msgs_id) > 0, COUNT(ACM.$this->ac_msgs_id), NULL) FROM $this->ac_msgs_tb ACM WHERE ACM.$this->ac_msgs_to = '$login_user_id' AND ACM.$this->ac_msgs_frm = '$this->usrs_tb.$this->usrs_usr_id' AND ACM.$this->ac_msgs_is_read = '0') unread",
                ));

                // exclude logged in user
                $this->AC_LIB->db->where(array("$this->usrs_tb.$this->usrs_usr_id !="=>$login_user_id));

                if(!empty($blocked_by))
                    $this->AC_LIB->db->where_not_in("$this->usrs_tb.$this->usrs_usr_id", $blocked_by);  

                $this->AC_LIB->db->where_not_in("$this->usrs_tb.$this->usrs_usr_id", $recent_users);

                if($search)
                    $this->AC_LIB->db
                    ->group_start()
                    ->or_like("$this->usrs_tb.$this->usrs_frstnme", $search, 'both')
                    ->or_like("$this->usrs_tb.$this->usrs_lstnme", $search, 'both')
                    ->or_like("$this->usrs_tb.$this->usrs_usrnme", $search, 'right')
                    ->group_end();

                // limit must be exact 20
                $limit    = $limit - count($result_r);

                $result   = $this->AC_LIB->db
                            ->limit($limit)
                            ->get($this->usrs_tb)
                            ->result();

                return array_merge($result_r, $result);
            }

            return $result_r;
        }
        
        // if no recent users
        $this->AC_LIB->db
        ->select(array(
            "$this->usrs_tb.$this->usrs_usr_id id",
            "$this->usrs_tb.$this->usrs_usrnme username",
            "$this->usrs_tb.$this->usrs_frstnme firstname",
            "$this->usrs_tb.$this->usrs_lstnme lastname",
            "$this->usrs_tb.$this->usrs_eml email",
            "$this->usrs_tb.$this->usrs_img avatar",
            "$this->usrs_tb.$this->usrs_online online",
            "(SELECT IF(COUNT(ACM.$this->ac_msgs_id) > 0, COUNT(ACM.$this->ac_msgs_id), NULL) FROM $this->ac_msgs_tb ACM WHERE ACM.$this->ac_msgs_to = '$login_user_id' AND ACM.$this->ac_msgs_frm = '$this->usrs_tb.$this->usrs_usr_id' AND ACM.$this->ac_msgs_is_read = '0') unread",
        ));

        // exclude logged in user
        $this->AC_LIB->db->where(array("$this->usrs_tb.$this->usrs_usr_id !=" =>$login_user_id));

        if(!empty($blocked_by))
            $this->AC_LIB->db->where_not_in("$this->usrs_tb.$this->usrs_usr_id", $blocked_by);  

        if($search)
            $this->AC_LIB->db
            ->group_start()
            ->or_like("$this->usrs_tb.$this->usrs_frstnme", $search, 'both')
            ->or_like("$this->usrs_tb.$this->usrs_lstnme", $search, 'both')
            ->or_like("$this->usrs_tb.$this->usrs_usrnme", $search, 'right')
            ->group_end();

        return  $this->AC_LIB->db
                ->limit($limit)
                ->get($this->usrs_tb)
                ->result();
    }

    /*
    * Update users update_user
    */
    public function update_user($user_id = 0, $data = array())
    {
        $this->AC_LIB->db
        ->where("$this->usrs_tb.$this->usrs_usr_id", $user_id)
        ->update("$this->usrs_tb", $data);

        return true;
    }

    /*
    * Users list blocked by specific user get_blocked_by_users
    */
    public function get_blocked_by_users($user_id = 0)
    {
        return  $this->AC_LIB->db
                ->select(array(
                            "$this->ac_blck_tb.$this->ac_blck_usr_id user_id",
                        ))
                ->where(array("$this->ac_blck_tb.$this->ac_blck_usr_blckd_usr_id"=>$user_id))
                ->get($this->ac_blck_tb)
                ->result();
    }

    /*
    * Block user block_user
    */
    public function block_user($user_id = 0, $blocked_user_id = 0)
    {
        $this->AC_LIB->db->delete($this->ac_blck_tb, array("$this->ac_blck_usr_id" => $user_id, "$this->ac_blck_usr_blckd_usr_id"=>$blocked_user_id));

        if($this->AC_LIB->db->affected_rows()) // if already blocked then unblock it
        {
            return 0;
        }
        else // block the user 
        {
            $this->AC_LIB->db->insert($this->ac_blck_tb, array("$this->ac_blck_usr_id" => $user_id, "$this->ac_blck_usr_blckd_usr_id"=>$blocked_user_id));
            return 1;            
        }

        return FALSE;
    }

    /*
    * Delete chat delete_chat
    */
    public function delete_chat($user_id = 0, $sub_user_id = 0)
    {
        $this->AC_LIB->db
        ->where(array("$this->ac_msgs_tb.$this->ac_msgs_frm"=>$user_id, "$this->ac_msgs_tb.$this->ac_msgs_to"=>$sub_user_id))
        ->update($this->ac_msgs_tb, array("$this->ac_msgs_frm_del"=>1));

        $this->AC_LIB->db
        ->where(array("$this->ac_msgs_tb.$this->ac_msgs_to"=>$user_id, "$this->ac_msgs_tb.$this->ac_msgs_frm"=>$sub_user_id))
        ->update($this->ac_msgs_tb, array("$this->ac_msgs_to_del"=>1));

        return TRUE;
    }

    /*
    * Send message send_message
    */
    public function send_message($data = array()) 
    {
        $this->AC_LIB->db->insert($this->ac_msgs_tb, $data);
        return $this->AC_LIB->db->insert_id();
    }

    /*
    * Get recent chat users message send_message
    */
    public function get_recent_chat_users($user_id = 0)
    {
        return  $this->AC_LIB->db
                ->select(array(
                    "$this->ac_usrs_msgs_tb.$this->ac_usrs_msgs_id id",
                    "$this->ac_usrs_msgs_tb.$this->ac_usrs_msgs_msg_id message_id",
                ))
                ->where("$this->ac_usrs_msgs_usr_id", $user_id)
                ->get($this->ac_usrs_msgs_tb)
                ->row();
    }

    /*
    * Get messages between two users get_messages
    */
    public function get_messages($user_id = 0, $chat_user = 0, $limit = 10)
    {
        $this->AC_LIB->db
        ->select(array(
            "$this->ac_msgs_tb.$this->ac_msgs_id id",
            "$this->ac_msgs_tb.$this->ac_msgs_frm m_from",
            "$this->ac_msgs_tb.$this->ac_msgs_to m_to",
            "$this->ac_msgs_tb.$this->ac_msgs_msg message",
            "$this->ac_msgs_tb.$this->ac_msgs_is_read is_read",
            "$this->ac_msgs_tb.$this->ac_msgs_dt_upd dt_updated",
        ));

        // group query for removing deleted messages
        $this->AC_LIB->db
        ->where("( (`$this->ac_msgs_tb`.`$this->ac_msgs_frm` = '$user_id' AND `$this->ac_msgs_tb`.`$this->ac_msgs_to` = '$chat_user')", NULL, FALSE)
        ->or_where("(`$this->ac_msgs_tb`.`$this->ac_msgs_frm` = '$chat_user' AND `$this->ac_msgs_tb`.`$this->ac_msgs_to` = '$user_id') )", NULL, FALSE)
        ->where("( (IF(`$this->ac_msgs_tb`.`$this->ac_msgs_frm` = '$user_id', `$this->ac_msgs_tb`.`$this->ac_msgs_frm_del`, `$this->ac_msgs_tb`.`$this->ac_msgs_to_del`) = 0) AND (IF(`$this->ac_msgs_tb`.`$this->ac_msgs_to` = '$user_id', `$this->ac_msgs_tb`.`$this->ac_msgs_to_del`, `$this->ac_msgs_tb`.`$this->ac_msgs_frm_del`) = 0) )", NULL, FALSE);

        $messages = $this->AC_LIB->db
                    ->order_by("$this->ac_msgs_tb.$this->ac_msgs_id", 'DESC')
                    ->limit($limit)
                    ->get($this->ac_msgs_tb);

        $this->AC_LIB->db
        ->where("$this->ac_msgs_tb.$this->ac_msgs_to", $user_id)
        ->where("$this->ac_msgs_tb.$this->ac_msgs_frm", $chat_user)
        ->update($this->ac_msgs_tb, array("$this->ac_msgs_tb.$this->ac_msgs_is_read"=>'1'));

        return $messages->result();
    }
    
    /*
    * Count messages for pagination paginate_messages
    */
    public function paginate_messages($user_id = 0, $chat_user = 0)
    {   
        // group query for removing deleted messages
        $this->AC_LIB->db
        ->where("( (`$this->ac_msgs_tb`.`$this->ac_msgs_frm` = '$user_id' AND `$this->ac_msgs_tb`.`$this->ac_msgs_to` = '$chat_user')", NULL, FALSE)
        ->or_where("(`$this->ac_msgs_tb`.`$this->ac_msgs_frm` = '$chat_user' AND `$this->ac_msgs_tb`.`$this->ac_msgs_to` = '$user_id') )", NULL, FALSE)
        ->where("( (IF(`$this->ac_msgs_tb`.`$this->ac_msgs_frm` = '$user_id', `$this->ac_msgs_tb`.`$this->ac_msgs_frm_del`, `$this->ac_msgs_tb`.`$this->ac_msgs_to_del`) = 0) AND (IF(`$this->ac_msgs_tb`.`$this->ac_msgs_to` = '$user_id', `$this->ac_msgs_tb`.`$this->ac_msgs_to_del`, `$this->ac_msgs_tb`.`$this->ac_msgs_frm_del`) = 0) )", NULL, FALSE)
        ->order_by("$this->ac_msgs_id", 'DESC');

        return $this->AC_LIB->db->count_all_results($this->ac_msgs_tb);
    }

    /*
    * Get all unread messages of single user get_unread_messages
    */
    public function get_unread_messages($user_id = 0, $latest_msg_id = 0)
    {
        return  $this->AC_LIB->db
                ->select(array(
                    "$this->ac_msgs_tb.$this->ac_msgs_id id",
                    "$this->ac_msgs_tb.$this->ac_msgs_frm m_from",
                    "$this->ac_msgs_tb.$this->ac_msgs_to m_to",
                    "$this->ac_msgs_tb.$this->ac_msgs_msg message",
                    "$this->ac_msgs_tb.$this->ac_msgs_is_read is_read",
                    "$this->ac_msgs_tb.$this->ac_msgs_frm_del f_delete",
                    "$this->ac_msgs_tb.$this->ac_msgs_to_del t_delete",
                    "$this->ac_msgs_tb.$this->ac_msgs_dt_upd dt_updated",

                    /*Join user*/
                    "$this->usrs_tb.$this->usrs_img avatar",
                    "$this->usrs_tb.$this->usrs_frstnme firstname",
                    "$this->usrs_tb.$this->usrs_lstnme lastname",
                ))
                ->join("$this->usrs_tb", "$this->usrs_tb.$this->usrs_usr_id = $this->ac_msgs_tb.$this->ac_msgs_frm", 'left')
                ->where(array("$this->ac_msgs_tb.$this->ac_msgs_to"=>"$user_id", "$this->ac_msgs_tb.$this->ac_msgs_is_read"=>'0', "$this->ac_msgs_tb.$this->ac_msgs_id >"=> "$latest_msg_id"))
                ->order_by("$this->ac_msgs_tb.$this->ac_msgs_dt_upd", 'ASC')
                ->get($this->ac_msgs_tb)
                ->result();
    }

    /*
    * Mark message as read mark_as_read
    */
    public function mark_as_read($user_id = 0)
    {
        $unread_msg   = $this->AC_LIB->db
                        ->select(array("$this->ac_msgs_id id"))
                        ->where("$this->ac_msgs_to", $user_id)
                        ->order_by("$this->ac_msgs_id", 'DESC')
                        ->limit(1)
                        ->get($this->ac_msgs_tb)
                        ->row();

        $data         = array("$this->ac_usrs_msgs_usr_id" => $user_id, "$this->ac_usrs_msgs_msg_id" => !empty($unread_msg) ? $unread_msg->id : 0);

        $result       = $this->get_recent_chat_users($user_id);

        if(empty($result))
            $this->AC_LIB->db->insert($this->ac_usrs_msgs_tb, $data);
        else
            $this->AC_LIB->db->where("$this->ac_usrs_msgs_id", $result->id)->update($this->ac_usrs_msgs_tb, $data);
    }
    
}

/*End Chat_db_lib class*/