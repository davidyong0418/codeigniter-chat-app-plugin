<?php

// error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/acinstaller_class.php';

$acinstaller = new Acinstaller();

/*Assets*/
if(isset( $_POST['config_path'] ) && isset( $_POST['d_logged'] ) ) 
{
    $acinstaller->configure_config();
}
if(isset( $_POST['css_path'] ) && isset( $_POST['js_path'] ) && isset( $_POST['img_path'] ) && isset( $_POST['sound_path'] ) && isset( $_POST['upload_path'] ) && isset( $_POST['lib_path'] ) && isset( $_POST['cont_path'] ) && isset( $_POST['view_path'] )) 
{
    $acinstaller->transfer_files();
}
else if( isset( $_POST['usertable'] ) && isset( $_POST['id'] ) && isset( $_POST['firstname'] ) && isset( $_POST['lastname'] ) && isset( $_POST['d_username'] ) && isset( $_POST['email'] ) && isset( $_POST['hostname'] ) && isset( $_POST['username'] ) && isset( $_POST['password'] ) && isset( $_POST['database'] )) 
{
    $acinstaller->alter_usertable();
}
else if( isset( $_POST['hostname'] ) && isset( $_POST['username'] ) && isset( $_POST['password'] ) && isset( $_POST['database'] ) ) 
{
    $acinstaller->configure_database();
}


?>
<!doctype html>
<html lang="en">
<head>

	<title>Installation | Codeigniter Chat</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="bootstrap/cover.css">

</head>
<body>
<div class="container">
    <div class="row">
    	<div class="offset-md-3 col-md-6">
			<div class="jumbotron">
				<h2 class="form-signin-heading text-center">Codeigniter Chat V2 | Installation</h2><br>
				<h5>Installation Instructions</h5>
				
				<ol type="1">
					<li>Read the Codeigniter Chat Requirements and Installation instructions carefully given in the documentation.</li>
					<li>The installer is now fully automated, just submit the appropriate info in the input boxes.</li>
					<li>All the below fields are required.</li>
			    	<li>Don't include any slash in starting of any path.</li>
			    </ol>
			</div>
    	</div>
    </div>
    <br>


	<!-- Step 1 -->
	<section id="step_1">
		<div class="row">
			<div class="offset-md-3 col-md-6">
				<h4>Step 1</h4>
				<p>Enter your existing database credentials to import Codeigniter Chat tables.</p>
			</div>
		</div>
		<div class="row">
			<div class="offset-md-3 col-md-6">

				<!-- Hostname -->
				<label for="d_hostname">1. Enter Database Hostname</label>
				<div class="input-group">
					<input type="text" id="d_hostname" name="d_hostname" placeholder="e.g localhost" class="form-control" autocomplete="false" />
				</div>
				
				<!-- Username -->
				<br>
				<label for="d_dbusername">2. Enter Database Username</label>
				<div class="input-group">
					<input type="text" id="d_dbusername" name="d_dbusername" placeholder="e.g root" class="form-control" autocomplete="false" />
				</div>
				
				<!-- Password -->
				<br>
				<label for="d_password">3. Enter Database Password</label>
				<div class="input-group">
					<input type="password" id="d_password" name="d_password" placeholder="*******" class="form-control"  autocomplete="false" />
				</div>
				
				<!-- Database  name -->
				<br>
				<label for="d_database">4. Enter Database Name</label>
				<div class="input-group">
					<input type="text" id="d_database" name="d_database" placeholder="e.g example_db" class="form-control"  autocomplete="false" />
				</div>
				
				<br>
				<button class="btn btn-outline-secondary" type="button" id="d_database_btn">Continue <i class="fa fa-cog"></i></button>
				<div class="form-text text-danger d_database_text" ></div>
				<div class="form-text text-success d_database_text"></div>
			</div>
		</div>
	</section>

	<br>
	<section id="step_2">
		<div class="row">
			<div class="offset-md-3 col-md-6">
				<h4>Step 2</h4>
				<p>Enter existing 'Users' table and it's columns names to let Codeigniter Chat get familiar with your existing users base.</p>
			</div>
		</div>
		<div class="row">
			<div class="offset-md-3 col-md-6">

				<label for="d_usertable">1. Enter existing User's table name</label>
				<div class="input-group">
					<input type="text" id="d_usertable" name="d_usertable" placeholder="e.g users" class="form-control" autocomplete="false" />
				</div>
				
				<br>
				<label for="d_userid">2. 'User id' column name</label>
				<div class="input-group">
					<input type="text" id="d_userid" name="d_userid" placeholder="e.g user_id" class="form-control" autocomplete="false" />
				</div>
				
				<br>
				<label for="d_firstname">3. 'Firstname' column name</label>
				<div class="input-group">
					<input type="text" id="d_firstname" name="d_firstname" placeholder="e.g first_name" class="form-control" autocomplete="false" />
				</div>
				
				<br>
				<label for="d_lastname">4. 'Lastname' column name</label>
				<div class="input-group">
					<input type="text" id="d_lastname" name="d_lastname" placeholder="e.g last_name" class="form-control"  autocomplete="false" />
				</div>
				
				<br>
				<label for="d_username">5. 'Username' column name</label>
				<div class="input-group">
					<input type="text" id="d_username" name="d_username" placeholder="e.g username" class="form-control"  autocomplete="false" />
				</div>
				
				<br>
				<label for="d_email">6. 'Email' column name</label>
				<div class="input-group">
					<input type="text" id="d_email" name="d_email" placeholder="e.g email" class="form-control"  autocomplete="false" />
				</div>
				
				<br>
				<button class="btn btn-outline-secondary" type="button" id="d_usertable_btn">Continue <i class="fa fa-cog"></i></button>
				<div class="form-text text-danger d_usertable_text" ></div>
				<div class="form-text text-success d_usertable_text"></div>
			</div>
		</div>
	</section>


    <!-- Step 2 -->
    <br>
    <section id="step_3">
	    <div class="row">
			<div class="offset-md-3 col-md-6">
				<h4>Step 3</h4>
				<p>Move the Codeigniter Chat assets and main files into your website directory.</p>
			</div>
		</div>
		<div class="row">
			<div class="offset-md-3 col-md-6">

				<!-- Transfer CSS files -->
				<label for="d_css">1. Enter path to your css folder.</label>
				<div class="input-group">
					<input type="text" id="d_css" name="d_css" placeholder="e.g assets/css/" class="form-control" autocomplete="false" />
				</div>
				
				<!-- Transfer JS files -->
				<br>
				<label for="d_js">2. Enter path to your js folder.</label>
				<div class="input-group">
					<input type="text" id="d_js" name="d_js" placeholder="e.g assets/js/" class="form-control" autocomplete="false" />
				</div>
				
				<!-- Transfer Images files -->
				<br>
				<label for="d_img">3. Enter path to your images folder.</label>
				<div class="input-group">
					<input type="text" id="d_img" name="d_img" placeholder="e.g assets/images/" class="form-control"  autocomplete="false" />
				</div>
				
				<!-- Transfer Sound files -->
				<br>
				<label for="d_sound">
					4. Enter path for a new sound folder (for a sound.mp3 notication file)<br>
					<small class="form-text text-muted">Just add the path, a new folder will be created automatically if not exist</small>
				</label>
				<div class="input-group">
					<input type="text" id="d_sound" name="d_sound" placeholder="e.g assets/sound/" class="form-control"  autocomplete="false" />
				</div>

				<!-- Transfer User Image upload files -->
				<br>
				<label for="d_upload">
					5. Enter path for a new Profile pics upload folder<br>
					<small class="form-text text-muted">Just add the path, a new folder will be created automatically if not exist</small>
				</label>
				<div class="input-group">
					<input type="text" id="d_upload" name="d_upload" placeholder="e.g ac_upload/profile_pics/" class="form-control"  autocomplete="false" />
				</div>

				<!-- Transfer Library files -->
				<br>
				<label for="d_lib">6. Enter path to your libraries folder</label>
				<div class="input-group">
					<input type="text" id="d_lib" name="d_lib" placeholder="e.g application/libraries/" class="form-control" autocomplete="false" />
				</div>
				
				<!-- Transfer Controllers files -->
				<br>
				<label for="d_cont">7. Enter path to your controllers folder</label>
				<div class="input-group">
					<input type="text" id="d_cont" name="d_cont" placeholder="e.g application/controllers/" class="form-control"  autocomplete="false" />
				</div>
				
				<!-- Transfer Views files -->
				<br>
				<label for="d_view">
					8. Enter path to your views folder<br>
				</label>
				<div class="input-group">
					<input type="text" id="d_view" name="d_view" placeholder="e.g application/views/" class="form-control"  autocomplete="false" />
				</div>
				
				<br>
				<button class="btn btn-outline-secondary" type="button" id="d_transfer_btn">Continue <i class="fa fa-cog"></i></button>
				<div class="form-text text-danger d_transfer_text" ></div>
				<div class="form-text text-success d_transfer_text"></div>
			</div>
		</div>
	</section>
				
				      
	<!-- Step 4 -->
	<br>
	<section id="step_4">
		<div class="row">
			<div class="offset-md-3 col-md-6">
				<h4>Step 4</h4>
				<p>Configure the chat_config file according to your website configuration.</p>
			</div>
		</div>
		<div class="row">
			<div class="offset-md-3 col-md-6">
				<!-- Transfer Config files -->
				<br>
				<label for="d_config">1. Enter path to your config folder</label>
				<div class="input-group">
					<input type="text" id="d_config" name="d_config" placeholder="e.g application/config/" class="form-control" autocomplete="false" />
				</div>
				
				<label for="d_logged">2. Enter the SESSION variable contains logged in User Id
					<small class="form-text text-muted">The session variable name in which you store the id of a User after he/she login</small>
				</label>
				<div class="input-group">
					<input type="text" id="d_logged" name="d_logged" placeholder="e.g $_SESSION['user']['id']" class="form-control" autocomplete="false" />
				</div>
				<small class="text-muted">Make sure you've autoload the session library in autoload.php</small>
				<blockquote>
					<code>
						$autoload['libraries'] = array('session');
					</code>
				</blockquote>

				<br>
				<button class="btn btn-outline-secondary" type="button" id="d_config_btn">Continue <i class="fa fa-cog"></i></button>
				<div class="form-text text-danger d_config_text" ></div>
				<div class="form-text text-success d_config_text"></div>
			</div>
		</div>		
	</section>

	<!-- Step 5 -->
	<br>
	<section id="step_5">
		<div class="row">
			<div class="offset-md-3 col-md-6">
				<h4>Step 5</h4>
				<p>Congrats. Final step. Just do two things yourself manually.</p>
			</div>
		</div>
		<div class="row">
			<div class="offset-md-3 col-md-6">
				<ol type="1">
					<li>
						<p>Now we need to enable the Smileys Storage into your database.</p>
						<p>Go to your website's database.php file <code>application/config/database.php</code> and set <code>'char_set' = 'utf8mb4'</code> </p>
						<p>Copy the code in blue color and paste it into your database file.</p>

<pre class="d-code">
<code data-lang="php">
  $db['default'] = array(
  	.
  	.
	<strong>'char_set' => 'utf8mb4',</strong>
	.
	.
  ); 
</code>
</pre>
						
						<div class="form-check">
						  	<label class="form-check-label" for="dbcharset"><input class="form-check-input" type="checkbox" value="1" id="dbcharset" name="dbcharset">Check this after completing this step.</label>
						</div>
					</li>
					<li>
						<p>Last, just link Codeigniter Chat CSS and JS to your website and initiate the Codeigniter Chat by adding <code>&lt;div id="Chat_initiate"&gt;&lt;/div&gt;</code> and two JavaScript variables into your website's index.php file. The file in which the <code>&lt;head&gt;</code> and <code>&lt;body&gt;</code> tags exists.</p>
						<p>Copy the code in blue color and paste it into your index file</p>

						<p>NOTE:</p>
						<p>1. Change the path of CSS and JS according to your website</p>
						<p>2. Change the <code data-lang="php">$_SESSION['logged_in']['id']</code> by your SESSION variable that contains logged in User Id e.g <code data-lang="php">$_SESSION['user']['id']</code></p>
<pre class="d-code">
<code data-lang="php">
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;!-- Twitter Bootstrap & Font-awesome --&gt;
    <strong>
    &lt;!-- Codeigniter Chat CSS Files --&gt;
    &lt;link rel="stylesheet" type="text/css" href="&lt;?php echo base_url() ?&gt;assets/css/emoji.css"&gt;
    &lt;link href="&lt;?php echo base_url() ?&gt;assets/css/chat-app.css" rel="stylesheet"&gt;
    &lt;!-- End Codeigniter Chat CSS Files --&gt;
	</strong>
&lt;body&gt;
	<strong>
    &lt;!-- Chat Application --&gt;
    &lt;div id="Chat_initiate"&gt;&lt;/div&gt;
    &lt;!-- End Chat Application --&gt;
	</strong>
    &lt;!-- Your other html contents here --&gt;
    &lt;!-- jQuery & Bootstrap --&gt;
    <strong>
    &lt;script type="text/javascript"&gt;
       var user_id  ="&lt;?php echo isset($_SESSION['logged_in']['id']) ? $_SESSION['logged_in']['id'] : NULL; ?&gt;"
       var base_url  ="&lt;?php echo base_url(); ?&gt;";
    &lt;/script&gt;
		
	&lt;!-- Chat JS Files --&gt;
    &lt;script src="&lt;?php echo base_url() ?&gt;/assets/js/jquery-ui.min.js"&gt;&lt;/script&gt;
    &lt;script src="&lt;?php echo base_url() ?&gt;/assets/js/jquery.slimscroll.min.js"&gt;&lt;/script&gt;
    &lt;script type="text/javascript" src="&lt;?php echo base_url() ?&gt;assets/js/config.js"&gt;&lt;/script&gt;
    &lt;script type="text/javascript" src="&lt;?php echo base_url() ?&gt;assets/js/util.js"&gt;&lt;/script&gt;
    &lt;script type="text/javascript" src="&lt;?php echo base_url() ?&gt;assets/js/jquery.emojiarea.js"&gt;&lt;/script&gt;
    &lt;script type="text/javascript" src="&lt;?php echo base_url() ?&gt;assets/js/emoji-picker.js"&gt;&lt;/script&gt;
    &lt;script src="&lt;?php echo base_url() ?&gt;/assets/js/chat-app.js"&gt;&lt;/script&gt;
    &lt;!-- End Chat JS Files --&gt;
    </strong>
&lt;/body&gt;
&lt;/html&gt;
</code>
</pre>
						<div class="form-check">
						  	<label class="form-check-label" for="ac_init"><input class="form-check-input" type="checkbox" value="1" id="ac_init" name="ac_init">Check this after completing the final step.</label>
						</div>
					</li>
				</ol>

				<br>
				<button class="btn btn-outline-secondary" type="button" id="d_finish_btn">Finish Setup <i class="fa fa-cog"></i></button>
				<div class="form-text text-danger d_finish_text" ></div>
			</div>
		</div>		
	</section>

	<br><br><br><br><br><br>
  	<footer class="mastfoot text-right">
		<div class="inner">
		<p>A Product By <a href="https://classiebit.com/">Classiebit</a></p>
		</div>
	</footer>

</div> <!-- /container -->

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="bootstrap/jquery.min.js"></script>
<script src="bootstrap/popper.min.js"></script>
<script src="bootstrap/bootstrap.min.js"></script>
<script type="text/javascript">
	var base_url = "<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/'; ?>";
</script>
<script src="bootstrap/installer.js"></script>
</body>
</html>