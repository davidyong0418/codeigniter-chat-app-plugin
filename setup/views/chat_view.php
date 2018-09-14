<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(empty($logged_user)) { ?>
<!-- Load this before login -->
<!-- Codeigniter Chat Header -->
<h2 class="chat-header">
    <i class="fa fa-comments-o"></i> Login to chat 
    <a href="javascript:;" class="chat-form-close pull-right"><i class="fa fa-remove"></i></a>
</h2>
<!-- End Codeigniter Chat Header -->

<!-- End Codeigniter Chat Body -->
<div class="row">
    <div class="col-md-offset-3 col-md-6 text-center">
        <br><br><br><br>
        <h4 class="chat-login">Login First</h4>  
    </div>
</div>
<!-- End Codeigniter Chat Body -->
<!-- End this before login -->
<?php } else { ?>

<!-- ================================================================================================================ -->

<!-- Load this after login -->
<!-- Codeigniter Chat Header -->
<h2 class="chat-header">
    <i class="fa fa-comments-o"></i> <?php echo $logged_user->username; ?> 
    <span class="label label-<?php echo $logged_user->online== 1 ? 'success' : 'mute'; ?>" id="current_status"><?php echo $logged_user->online== 1 ? 'online' : 'offline'; ?></span>
    <a href="javascript:;" class="chat-form-close pull-right"><i class="fa fa-remove"></i></a>
    <span class="dropdown user-dropdown">
        <a href="javascript:;" class="pull-right chat-config" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-cog"></i>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="javascript: void(0);" class="text-left">
                    <span>Status</span>
                    <div class="btn-group btn-toggle change-status pull-right"> 
                        <button class="btn btn-xs btn-<?php echo $logged_user->online== 1 ? 'success' : 'mute'; ?>"><?php echo 'online' ?></button>
                        <button class="btn btn-xs btn-<?php echo $logged_user->online== 0 ? 'success' : 'mute'; ?>"><?php echo 'offline' ?></button>
                    </div>
                </a>
            </li>
            <li>
                <div class="image-card">
                    <?php echo form_open_multipart('', array('id'=>'profile_pic_save')); ?>
                    <div class="picture-container">
                        <div class="picture">
                            <img src="<?php echo $logged_user->avatar; ?>" class="picture-src img-responsive" id="wizardPicturePreview" title=""/>
                            <input type="file" id="wizard-picture" name="image">
                        </div>
                        <span>Choose Profile Picture</span><br>
                        <span id="image-error"></span>
                        <span id="image-success"></span>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                    <?php echo form_close(); ?>
                </div>
            </li>
        </ul>
    </span>

    <!-- Search dropdown -->
    <span class="dropdown user-dropdown">
        <a href="javascript:;" class="pull-right chat-config" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-search"></i>
        </a>
        <ul class="dropdown-menu chat-search">
            <li>
                <a href="javascript: void(0);">
                    <div class="btn-toggle"> 
                        <input type="text" name="search_user" placeholder="Search users" class="form-control input-sm" autofocus="">
                    </div>
                </a>
            </li>
        </ul>
    </span>
</h2>
<!-- End Codeigniter Chat Header -->

<!-- Codeigniter Chat Body -->
<div class="chat-inner">
    <div class="chat-group" id="group_2">
    <!-- Ajax List -->
    </div>
    <div class="chat-group" id="group_1">
        <?php foreach ($users as $user) {  ?> 
        <a href="javascript: void(0)" data-toggle="popover" data-trigger="click">
            <div class="user-wrap">
                <input type="hidden" value="<?php echo $user->id; ?>" name="user_id" />
                <div class="user-profile-img">
                   <div class="profile-img">
                    <?php  $avatar = $user->avatar ? $img_upld_pth.'/'.$user->avatar : $ast_img_pth.'/avatar.png' ; ?>
                    <img src="<?php echo base_url($avatar); ?>" class="img-responsive img-circle">
                   </div>
                </div>
                <span class="user-name">
                    <small class="user-name"><?php echo ucwords($user->firstname.' '.$user->lastname); ?></small>
                    <span class="badge progress-bar-danger" rel="<?php echo $user->id; ?>"><?php echo $user->unread; ?></span>
                </span>
                <span class="user_status">
                    <?php $status = $user->online == 1 ? 'is-online' : 'is-offline'; ?> 
                    <span class="user-status <?php echo $status; ?>"></span>
                </span>
            </div>
        </a>
        <?php  } ?>
    </div>

</div>
<!-- End Codeigniter Chat Body -->

<!-- Codeigniter Chat User Profile Hover -->
<div class="popover" id="popover-content">
    <div id="user-image"></div>
</div>
<!-- End Codeigniter Chat User Profile Hover -->

<!-- Codeigniter Chat child container -->
<div id="chat-container">
    <input type="hidden" name="sub_user_id" value="">
    <div class="chat-container-header">
        <a href="javascript: void(0);" class="chat-container-close pull-right">
            <i class="fa fa-remove"></i>
        </a>
        <!-- Block user -->
        <span class="dropdown user-dropdown">
            <a href="javascript:;" class="pull-right chat-config" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-cog"></i>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript: void(0);" class="text-left">
                        <span>Block User</span>
                        <div class="btn-group btn-toggle block-user pull-right">
                        </div>
                    </a>
                </li>
            </ul>
        </span>
        <!-- Delete chat history -->
        <span class="dropdown user-dropdown">
            <a href="javascript:;" class="pull-right chat-config" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-trash"></i>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript: void(0);" class="text-left">
                        <span>Chat History</span>
                        <div class="btn-group btn-toggle delete-chat pull-right">
                            <button class="btn btn-xs btn-danger btn-confirm">Delete</button>
                        </div>
                    </a>
                </li>
            </ul>
        </span>
        <span class="display-name"></span>
        <small></small>
    </div>

    <div class="chat-container">
        <div class="chat-content">
            <input type="hidden" name="chat_user_id" id="chat_user_id"/>
            <ul class="chat-container-body"></ul>
        </div>
        <div class="chat-textarea">
            <textarea placeholder="Type your message..." class="form-control" id="send-text" data-emojiable="true" autofocus=""></textarea>
            <button class="btn btn-block send-btn" id="send-msg"><i class="fa fa-send"></i> Send Message</button>
        </div>
    </div>
</div>

<!-- Confirmation Popup -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete Chat History</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- End Codeigniter Chat child container -->
<script type="text/javascript">
    var logged_in_user = '<?php echo $logged_user->id; ?>';
    var img_upld_pth   = '<?php echo $img_upld_pth; ?>'
    var ast_img_pth    = '<?php echo $ast_img_pth; ?>'
</script>

<?php } ?>