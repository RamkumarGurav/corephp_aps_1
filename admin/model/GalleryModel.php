<?php



class GalleryModel
{


  public $conn;

  public $table_name = "aps_images";

  public function __construct()
  {
    $path = substr(str_replace("\\", "/", dirname(__DIR__)), 0, -6);

    $this->conn = require $path . "/config.php";
    ;
  }

  public function findOneByColumnName($columnName, $columnValue)
  {

    $sql = "SELECT * FROM {$this->table_name} where $columnName=:c_value";
    $stmt = $this->conn->prepare($sql);

    $type = PDO::PARAM_STR; // Default type is string
    if (is_bool($columnValue)) {
      $type = PDO::PARAM_BOOL;
    } elseif (is_int($columnValue)) {
      $type = PDO::PARAM_INT;
    } elseif (is_null($columnValue)) {
      $type = PDO::PARAM_NULL;
    }
    $stmt->bindValue(":c_value", $columnValue, $type);

    if ($stmt->execute()) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      return false;
    }
  }

  public function findAll()
  {
    $sql = "SELECT * FROM {$this->table_name} ORDER BY image_order DESC";
    $stmt = $this->conn->prepare($sql);
    $result = $stmt->execute();
    if (!$result) {
      echo "failed to fetch records from table $this->table_name";
      exit;
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function findAllActive()
  {
    $sql = "SELECT * FROM {$this->table_name} WHERE status='1' ORDER BY image_order DESC";
    $stmt = $this->conn->prepare($sql);
    $result = $stmt->execute();
    if (!$result) {
      echo "failed to fetch records from table $this->table_name";
      exit;
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function findAllByColumName($columnName, $columnValue)
  {
    $sql = "SELECT * FROM {$this->table_name} where $columnName=$columnValue  ORDER BY image_order DESC";

    $stmt = $this->conn->prepare($sql);
    $result = $stmt->execute();



    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {
      return $data;
    } else {
      return false;
    }
  }
  public function findAllActiveByColumName($columnName, $columnValue)
  {
    $sql = "SELECT * FROM {$this->table_name} where {$columnName}=$columnValue AND status='1' ORDER BY image_order DESC";

    $stmt = $this->conn->prepare($sql);
    $result = $stmt->execute();



    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {
      return $data;
    } else {
      return false;
    }
  }


  public function findAllActiveAlbumImagesByAlbumId($album_id)
  {


    $sql = "SELECT album_image FROM {$this->table_name} WHERE album_id=:album_id AND status='1'";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch IDs in descending order
    $album_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Extract IDs into a simple array
    //array_column method converts $ids=[["id"=>15],["id"=>16]] into simple array ["0"=>15,"1"=>16]
    $data = array_column($album_images, 'album_image');



    if (!empty ($data)) {
      return $data;
    } else {
      return false;
    }
  }
  public function findAllAlbumImagesByAlbumId($album_id)
  {


    $sql = "SELECT album_image FROM {$this->table_name} WHERE album_id=:album_id ";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch IDs in descending order
    $album_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Extract IDs into a simple array
    //array_column method converts $ids=[["id"=>15],["id"=>16]] into simple array ["0"=>15,"1"=>16]
    $data = array_column($album_images, 'album_image');



    if (!empty ($data)) {
      return $data;
    } else {
      return false;
    }
  }

  // public  function createOne($data)
  // {






  //   $columns = implode(",", array_keys($data));
  //   $placeholders = implode(",", array_fill(0, count($data), "?"));
  //   $sql = "INSERT INTO {$this->table_name} ($columns) VALUES($placeholders)";

  //   $stmt = $this->conn->prepare($sql);

  //   $i = 1;
  //   foreach ($data as $value) {
  //     $type = PDO::PARAM_STR; // Default type is string
  //     if (is_bool($value)) {
  //       $type = PDO::PARAM_BOOL;
  //     } elseif (is_int($value)) {
  //       $type = PDO::PARAM_INT;
  //     } elseif (is_null($value)) {
  //       $type = PDO::PARAM_NULL;
  //     }
  //     $stmt->bindValue($i, $value, $type);
  //     $i++;
  //   }

  //   if ($stmt->execute()) {
  //     return $this->conn->lastInsertId();
  //   } else {
  //     return false;
  //   }
  // }

  public function createOneAlbumImage($data)
  {



    // Perform the album creation
    $columns = implode(",", array_keys($data));
    $placeholders = implode(",", array_fill(0, count($data), "?"));
    $sql = "INSERT INTO {$this->table_name} ($columns) VALUES($placeholders)";
    $stmt = $this->conn->prepare($sql);

    $i = 1;
    foreach ($data as $value) {
      $type = PDO::PARAM_STR; // Default type is string
      if (is_bool($value)) {
        $type = PDO::PARAM_BOOL;
      } elseif (is_int($value)) {
        $type = PDO::PARAM_INT;
      } elseif (is_null($value)) {
        $type = PDO::PARAM_NULL;
      }
      $stmt->bindValue($i, $value, $type);
      $i++;
    }

    if ($stmt->execute()) {
      $lastInsertedId = $this->conn->lastInsertId();
      if ($data['type'] == 1) {
        $isUpdated = $this->updateOneByColumnName("id", $lastInsertedId, ['image_order' => $lastInsertedId]); // Corrected column name to 'position'

      } else {
        $isUpdated = $this->updateOneByColumnName("id", $lastInsertedId, ['image_order' => 0]); // Corrected column name to 'position'

      }
      if ($isUpdated) {
        return $lastInsertedId;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }


  public function createOne($data)
  {

    // Start a transaction
    $this->conn->beginTransaction();

    try {
      // Perform the album creation
      $columns = implode(",", array_keys($data));
      $placeholders = implode(",", array_fill(0, count($data), "?"));
      $sql = "INSERT INTO {$this->table_name} ($columns) VALUES($placeholders)";
      $stmt = $this->conn->prepare($sql);

      $i = 1;
      foreach ($data as $value) {
        $type = PDO::PARAM_STR; // Default type is string
        if (is_bool($value)) {
          $type = PDO::PARAM_BOOL;
        } elseif (is_int($value)) {
          $type = PDO::PARAM_INT;
        } elseif (is_null($value)) {
          $type = PDO::PARAM_NULL;
        }
        $stmt->bindValue($i, $value, $type);
        $i++;
      }

      if ($stmt->execute()) {
        $lastInsertedId = $this->conn->lastInsertId();
        $isUpdated = $this->updateOneByColumnName("id", $lastInsertedId, ['image_order' => $lastInsertedId]); // Corrected column name to 'position'
        if ($isUpdated) {
          // Commit the transaction if everything is successful
          $this->conn->commit();
          return $lastInsertedId;
        } else {
          // Rollback the transaction if the update fails
          $this->conn->rollBack();
          return false;
        }
      } else {
        // Rollback the transaction if the album creation fails
        $this->conn->rollBack();
        return false;
      }
    } catch (Exception $e) {
      // Rollback the transaction in case of any exception
      $this->conn->rollBack();
      return false;
    }
  }

  public function reorderAlbumImagesByAlbumId($newOrder, $album_id)
  {


    $albumImagesIdsArr = $newOrder;




    // Get the order values in descending order of IDs for the given year
    $orderArr = $this->getIdsInDesc($album_id);

    // echo "<pre> <br>";
    // print_r($albumImagesIdsArr);
    // print_r($orderArr);
    // exit;



    // Iterate over the new order of album IDs
    foreach ($albumImagesIdsArr as $i => $albumImageId) {
      // Update the order of the album with the corresponding order from $orderArr
      $album_image_order = isset ($orderArr[$i]) ? $orderArr[$i] : null;
      if ($album_image_order !== null) {
        $sql = "UPDATE {$this->table_name} SET image_order=$album_image_order WHERE id=$albumImageId";
        echo "  $sql <br>";
        $stmt = $this->conn->prepare($sql);
        // $stmt->bindParam(':album_order', $album_order, PDO::PARAM_INT);
        // $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
        $stmt->execute();
      }
    }
  }

  public function getIdsInDesc($album_id)
  {
    $sql = "SELECT id FROM {$this->table_name} WHERE album_id=:album_id ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch IDs in descending order
    $ids = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Extract IDs into a simple array
    //array_column method converts $ids=[["id"=>15],["id"=>16]] into simple array ["0"=>15,"1"=>16]
    $idArr = array_column($ids, 'id');

    return $idArr;
  }
  public function updateOneByColumnName($columnName, $columnValue, $data)
  {

    $sql = "UPDATE {$this->table_name} ";

    $assignments = array_keys($data);
    array_walk($assignments, function (&$value) {
      $value = "$value = ?";
    });

    $sql .= " SET " . implode(", ", $assignments) . " WHERE $columnName = ?";


    $stmt = $this->conn->prepare($sql);

    $i = 1;
    foreach ($data as $value) {
      $type = PDO::PARAM_STR; // Default type is string
      if (is_bool($value)) {
        $type = PDO::PARAM_BOOL;
      } elseif (is_int($value)) {
        $type = PDO::PARAM_INT;
      } elseif (is_null($value)) {
        $type = PDO::PARAM_NULL;
      }
      $stmt->bindValue($i, $value, $type);
      $i++;
    }

    $type = PDO::PARAM_STR; // Default type is string
    if (is_bool($columnValue)) {
      $type = PDO::PARAM_BOOL;
    } elseif (is_int($columnValue)) {
      $type = PDO::PARAM_INT;
    } elseif (is_null($columnValue)) {
      $type = PDO::PARAM_NULL;
    }

    $stmt->bindValue($i, $columnValue, $type);

    return $stmt->execute();
  }
  public function deleteById($id)
  {



    $sql = "DELETE From {$this->table_name}  WHERE id = :id";


    $stmt = $this->conn->prepare($sql);

    $type = PDO::PARAM_STR; // Default type is string
    if (is_bool($id)) {
      $type = PDO::PARAM_BOOL;
    } elseif (is_int($id)) {
      $type = PDO::PARAM_INT;
    } elseif (is_null($id)) {
      $type = PDO::PARAM_NULL;
    }
    $stmt->bindValue(":id", $id, $type);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}
