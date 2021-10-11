<?php require_once 'config.php';

try {
  // festival_id is valid of it is present, is an integer with a minimum value of 1
  $rules = [
    'festival_id' => 'present|integer|min:1'
  ];

  // you can have a look at the validate() function in HttpRequest.php (line 415)
  $request->validate($rules);
  if (!$request->is_valid()) {
    throw new Exception("Illegal request");
  }
  //the festival_id was passed in from festival-index.php
  // now you extract it from the request object and assign the value to the variable $festival_id  
  $festival_id = $request->input('festival_id');


  /*Retrieving the correct Festival object ($festival) from the Database*/
  //Call findById(id) function to check if this festival exists in the Database
  $festival = Festival::findById($festival_id);
  // if festival does not exist display error message 
  if ($festival === null) {
    throw new Exception("No festival exists or Illegal request parameter");
  }

  // $festival is an object - created from the Festival class
  // calling $festival->delete() calls the delete function in the Festival class
  $festival->delete();

  // Display redirect to the list of festivals and display the correct message - Deleted or Not Deleted
  $request->session()->set("flash_message", "The festival was successfully deleted from the database");
  $request->session()->set("flash_message_class", "alert-info");
  $request->redirect("/festival-index.php");
} catch (Exception $ex) {
  /*If something goes wrong, catch the message and store it as a flash message.*/
  $request->session()->set("flash_message", $ex->getMessage());
  $request->session()->set("flash_message_class", "alert-warning");

  $request->redirect("/festival-index.php");
}