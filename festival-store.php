<?php
require_once 'config.php';

try {
    

    // Array of locations, this is used for the location validation rule - see line 18
    $locations = [
        "USA",  "Belgium", "Brazil", "UK",
        "Germany", "Japan", "Netherlands",
        "Hungary", "Morocco", "Spain",
        "Canada", "Croatia", "Philippines"
    ];

    $rules = [
        "title" => "present|minlength:2|maxlength:64",
        "description" => "present|minlength:20|maxlength:2000",
        "location" => "present|in:" . implode(',', $locations),
        "start_date" => "present|match:/\A[0-9]{4}[-][0-9]{2}[-][0-9]{2}/",
        "end_date" => "present|match:/\A[0-9]{4}[-][0-9]{2}[-][0-9]{2}/",
        "contact_name" => "present|minlength:4|maxlength:64",
        "contact_email" => "present|email|minlength:7|maxlength:128",
        //2 or 3 digits then a heifen then 5-7 more digits
        "contact_phone" => "present|match:/\A[0-9]{2,3}[-][0-9]{5,7}\Z/"

    ];

    $request->validate($rules);
    if ($request->is_valid()) {
        //Pass the name of the file upload button as a parameter
        $file = new FileUpload("profile");
        //Get our new FileUpload object, which is stored in a temporary folder on our web server
        $filename = $file->get();
        //Create an image object and store the file path in that object.
        $image = new Image();
        /*Save the pathname for where the image is stored in the database*/
        $image->filename = $filename;
        $image->save();

        // !!Check .... If your Image is saved to the Database, but your 'Festival' has not, you know code is correct to at least this point ...

        // Create an empty $festival object
        $festival = new Festival();

        // festival-create.php passed title, description, location etc... in it's request object
        // not get title, description, location etc from the request object and assign these values to the appropriate attributes in the $festival object. 
        $festival->title = $request->input("title");
        $festival->description = $request->input("description");
        $festival->location = $request->input("location");
        $festival->start_date = $request->input("start_date");
        $festival->end_date = $request->input("end_date");
        $festival->contact_name = $request->input("contact_name");
        $festival->contact_email = $request->input("contact_email");
        $festival->contact_phone = $request->input("contact_phone");

        // When the Image was saved to the database ($image->save() above) and ID was created for that image. 
        // Now get that id from the $image, and assign it to $festival->image_id so it can be saved as in the festival table as a foreign key. 
        $festival->image_id = $image->id;
        
        // save() is a function in the Festival class, you will have written part of it - to update an existing festival
        // now you will add more code to the save() function so it can create a new festival or update an existing festival.  
        $festival->save();


        $request->session()->set("flash_message", "The festival was successfully added to the database");
        //Class that changes the appearance of the Bootstrap message.
        $request->session()->set("flash_message_class", "alert-info");
        $request->session()->forget("flash_data");
        $request->session()->forget("flash_errors");
        // redirect back to the home page - festival-index.php
        $request->redirect("/festival-index.php");
    } else {
        //Get all session data from the form and store under the key 'flash_data'.
        $request->session()->set("flash_data", $request->all());
        $request->session()->set("flash_errors", $request->errors());

        //Redirect the user to the create page.
        $request->redirect("/festival-create.php");
    }
} catch (Exception $ex) {
    /*Get all data and errors again and redirect.*/
    $request->session()->set("flash_message", $ex->getMessage());
    $request->session()->set("flash_message_class", "alert-warning");
    $request->session()->set("flash_data", $request->all());
    $request->session()->set("flash_errors", $request->errors());
    $request->redirect("/festival-create.php");
}
