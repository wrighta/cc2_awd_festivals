<?php
class Image
{
  public $id;
  public $filename;

  public function __construct()
  {
    $this->id = null;
  }

  public function save()
  {
    try {
      // THe usual database connection code
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $params = [
        ":filename" => $this->filename
      ];

      // Construct and execute the SQL - i.e. pass it to the DB for execution
      if ($this->id === null) {
        // no id, so this is a new image - so use the INSERT command
        $sql = "INSERT INTO images (filename) VALUES (:filename)";
      } else {
        // else there is an id, therefore an image already exists so update the image
        $sql = "UPDATE images SET filename = :filename WHERE id = :id";
        $params[":id"] = $this->id;
      }
      $stmt = $conn->prepare($sql);

      // Remember if you see SQL exeception at the line number below (execute) check your SQL statements above
      $status = $stmt->execute($params);

      //If something went wrong throw an appropriate error - this will be displayed back on the browser (see the flash messages in other php code) 
      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code =  " . $error_info[0] . "; error message = " . $error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      // Make sure we've inserted one row
      if ($stmt->rowCount() !== 1) {
        throw new Exception("Failed to save image.");
      }

      // If we get this far the image was inserted correctly, so now get the newly generated ID back from the database
      // NOTE!! Make sure you have created the primary key for Image so it is 'auto-incremented'
      if ($this->id === null) {
        $this->id = $conn->lastInsertId();
      }
    } finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }
  }

  public function delete()
  {
    throw new Exception("Not yet implemented!");
  }

  public function findAll()
  {
    throw new Exception("Not yet implemented!");
  }

  public static function findById($id)
  {
    $image = null;
    try {
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $select_sql = "SELECT * FROM images WHERE id = :id";
      $params = [
        ':id' => $id
      ];
      $select_stmt = $conn->prepare($select_sql);
      $select_status = $select_stmt->execute($params);

      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code =  " . $error_info[0] . "; error message = " . $error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      /*If at least one row retrieved, store the details of that row in an image object.*/
      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->Fetch(PDO::FETCH_ASSOC);
        $image = new Image();
        $image->id = $row['id'];
        $image->filename = $row['filename'];
      }
    } finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    /*Return our image object.*/
    return $image;
  }
}