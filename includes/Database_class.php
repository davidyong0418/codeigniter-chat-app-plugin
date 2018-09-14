<?php

class Database {

	// Function to the database and tables and fill them with the default data
	function create_database($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],'');

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Create the prepared statement
		$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['database']);

		// Close the connection
		$mysqli->close();

		return true;
	}

	// Function to create the tables and fill them with the default data
	function create_tables($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);

		if ($mysqli->connect_errno) 
		{
		    return array('error'=>sprintf("Connect failed: %s\n", $mysqli->connect_error));
		}

		// Open the default SQL file
		$query = file_get_contents('setup/database/chat_tables.sql');

		if (!$mysqli->multi_query($query)) 
		{
			return array('error'=>sprintf("Errormessage: %s\n", $mysqli->error));
		}

		// Close the connection
		$mysqli->close();

		return true;
	}

	// Function to create the tables and fill them with the default data
	function alter_table($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);

		if ($mysqli->connect_errno) 
		{
		    return array('error'=>sprintf("Connect failed: %s\n", $mysqli->connect_error));
		}

		// first check if the given user table exists or not
		$find_table = "SELECT 1 FROM `".$data['usertable']."` LIMIT 1";
		if (!$mysqli->query($find_table)) 
		{
			return array('error'=>sprintf("Errormessage: %s\n", $mysqli->error));
		}
		
		// Open alter_table.sql file and update usertable field
		$db_file 		= file_get_contents('setup/database/alter_table.sql');

		$query  		= str_replace("%USERTABLE%", $data['usertable'], $db_file);
		
		if (!$mysqli->multi_query($query)) 
		{
			if($mysqli->error === "Duplicate column name 'ac_online'")
				return true;
			else
				return array('error'=>sprintf("Errormessage: %s\n", $mysqli->error));
		}

		// Close the connection
		$mysqli->close();

		return true;
	}
}