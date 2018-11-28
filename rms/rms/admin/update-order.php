<?php
    //Start session
    session_start();

	//checking connection and connecting to a database
	require_once('connection/config.php');
	//Connect to mysqli server
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysqli_error());
	}

	//Select database
	$db = mysqli_select_db($link,DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

     // check if the 'id' variable is set in URL
     if (isset($_GET['id']))
     {
         // get id value
         $id = $_GET['id'];
         $flag_1 = 1;
         $timezones=mysqli_query($link,"SELECT * FROM timezones WHERE flag='$flag_1'")
         or die("Something is wrong. ".mysqli_error());

         $row=mysqli_fetch_assoc($timezones); //gets retrieved row

         $active_reference = $row['timezone_reference']; //gets active timezone

         date_default_timezone_set($active_reference); //sets the default timezone for use

         $current_date = date("Y-m-d"); //gets the current date

         $current_time = date("H:i:s"); //gets the current time

     	//Sanitize the POST values
         $new_subject = $id;
     	  $new_message = "Finished";

         $from = "administrator"; //sets default to the administrator (it can be changed if PM will be implemented in the future)

          // update the entry
          $result = mysqli_query($link,"INSERT INTO messages(message_from,message_date,message_time,message_subject,message_text) VALUES('$from','$current_date','$current_time','$new_subject','$new_message')")
          or die("Message sending failed ..." . mysqli_error());

         // update the entry
        $result = mysqli_query($link,"UPDATE orders_details SET flag=$flag_1 WHERE order_id='$id'")
         or die("There was a problem while deleting the order ... \n" . mysqli_error());
         //$result = mysqli_query($link,"INSERT INTO messages(category_name) VALUES('$name')")
         // redirect back to the orders
         header("Location: index.php");
     }
     else
        // if id isn't set, redirect back to the orders
     {
        header("Location: orders.php");
     }

?>
