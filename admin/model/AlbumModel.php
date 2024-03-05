<?php


// define("ROOT_PATH", str_replace("\\", "/", dirname(__DIR__)));

class AlbumModel
{


  private $conn;

  public $table_name = "album";

  public function __construct()
  {
    $path = substr(str_replace("\\", "/", dirname(__DIR__)), 0, -6);

    $this->conn = require $path . "/config.php";;
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
    $sql = "SELECT * FROM {$this->table_name} ORDER BY album_order DESC";
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
    $sql = "SELECT * FROM {$this->table_name} WHERE status='1' ORDER BY album_order DESC";
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
    $sql = "SELECT * FROM {$this->table_name} where $columnName=$columnValue  ORDER BY album_order DESC";
    $stmt = $this->conn->prepare($sql);
    $result = $stmt->execute();


    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {
      return   $data;
    } else {
      return false;
    }
  }

  public function findAllActiveByColumName($columnName, $columnValue)
  {
    $sql = "SELECT * FROM {$this->table_name} where $columnName=$columnValue AND status='1' ORDER BY album_order DESC";
    $stmt = $this->conn->prepare($sql);
    $result = $stmt->execute();


    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {
      return   $data;
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
        $isUpdated = $this->updateOneByColumnName("id", $lastInsertedId, ['album_order' => $lastInsertedId]); // Corrected column name to 'position'
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







  public function reorderAlbumsByYearId($newOrder, $year_id)
  {

    $ablumIdsArr = $newOrder;
    // Get the order values in descending order of IDs for the given year
    $orderArr = $this->getIdsInDesc($year_id);





    // Iterate over the new order of album IDs
    foreach ($ablumIdsArr as $i => $album_id) {
      // Update the order of the album with the corresponding order from $orderArr
      $album_order = isset($orderArr[$i]) ? $orderArr[$i] : null;
      if ($album_order !== null) {
        $sql = "UPDATE {$this->table_name} SET album_order=$album_order WHERE id=$album_id";
        echo "  $sql <br>";
        $stmt = $this->conn->prepare($sql);
        // $stmt->bindParam(':album_order', $album_order, PDO::PARAM_INT);
        // $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
        $stmt->execute();
      }
    }
  }

  public function getIdsInDesc($year_id)
  {
    $sql = "SELECT id FROM {$this->table_name} WHERE year_id=:year_id ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':year_id', $year_id, PDO::PARAM_INT);
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


  public function deleteMultipleByIds($ids)
  {
    // Begin a transaction
    $this->conn->beginTransaction();

    try {
      // Create a placeholder for the IDs
      $placeholders = implode(',', array_fill(0, count($ids), '?'));

      // Prepare the SQL query to delete multiple records
      $sql = "DELETE FROM {$this->table_name} WHERE id IN ($placeholders)";
      $stmt = $this->conn->prepare($sql);

      // Bind the IDs to the placeholders
      foreach ($ids as $index => $id) {
        $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
      }

      // Execute the query
      $stmt->execute();

      // Commit the transaction
      $this->conn->commit();

      return true;
    } catch (PDOException $e) {
      // Rollback the transaction in case of an exception
      $this->conn->rollBack();

      // Handle the exception (you can log the error or throw it)
      error_log("Error deleting records: " . $e->getMessage());
      return false;
    }
  }
}