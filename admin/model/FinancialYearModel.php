<?php


// define("ROOT_PATH", str_replace("\\", "/", dirname(__DIR__)));

class FinancialYearModel
{


  private $conn;

  public $table_name = "year";

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
    $sql = "SELECT * FROM {$this->table_name} ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $result = $stmt->execute();
    if (!$result) {
      echo "failed to fetch records from table $this->table_name";
      exit;
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public  function createOne($data)
  {






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
      return $this->conn->lastInsertId();
    } else {
      return false;
    }
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