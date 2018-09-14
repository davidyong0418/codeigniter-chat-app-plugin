<?php

class Core {

	// Function to validate the post data
	function validate_post($data)
	{
		/* Validating the hostname, the database name and the username. The password is optional. */
		return !empty($data['hostname']) && !empty($data['username']) && !empty($data['database']);
	}

	// Function to show an error
	function show_message($type,$message) {
		return $message;
	}

	// Function to write the config file
	function write_config($data) 
	{
		// Config path
		$template_path 	= 'config/database.php';
		$output_path 	= '../application/config/development/database.php';
		
		// Open the file
		$database_file = file_get_contents($template_path);

		$new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
		$new  = str_replace("%USERNAME%",$data['username'],$new);
		$new  = str_replace("%PASSWORD%",$data['password'],$new);
		$new  = str_replace("%DATABASE%",$data['database'],$new);

		// Write the new database.php file
		$handle = fopen($output_path,'w+');

		// Chmod the file, in case the user forgot
		@chmod($output_path,0777);
		
		// Verify file permissions
		if(is_writable($output_path)) {

			// Write the file
			if(fwrite($handle,$new)) {
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}

	/**
	 * Copy a file, or recursively copy a folder and its contents
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.0.1
	 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
	 * @param       string   $source    Source path
	 * @param       string   $dest      Destination path
	 * @param       int      $permissions New folder creation permissions
	 * @return      bool     Returns true on success, false on failure
	 */
	function xcopy($trans = array())
	{
		// Simple copy for a file
	    if (is_file($trans['source'])) {
	        return copy($trans['source'], $trans['destination']);
	    }

	    // Make destination directory
	    if (!is_dir($trans['destination'])) 
	    {
	    	if($trans['make_dir'])
	    	{
	    		mkdir($trans['destination'], 0777, true);
	    	}
	    	else
	    	{
	        	return false;
	    	}
	    }

	    // Loop through the folder
	    $dir = dir($trans['source']);
	    while (false !== $entry = $dir->read()) {
	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }

	        // Deep copy directories
	        $this->xcopy(array('source'=>"".$trans['source']."/".$entry."", 'destination'=>"".$trans['destination']."/".$entry."", 'make_dir'=>$trans['make_dir']));
	    }

	    // Clean up
	    $dir->close();
	    return true;
	}

}