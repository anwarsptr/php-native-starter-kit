<?php
require_once DIR_PATH . 'init.php';

include "database.php";

// Fungsi untuk mendapatkan data
function getData($table, $where = null, $select='*', $join='')
{
    global $con;
    $sql = "SELECT $select FROM $table";
    if ($join) { $sql .= " ".$join; }
    if ($where) { $sql .= " WHERE $where"; }
    $result = mysqli_query($con, $sql);
    // Periksa apakah query berhasil
    if (!$result) {
        die("Query failed : " . mysqli_error($con));
    }
    return $result;
}

// Fungsi untuk menambahkan data
function insertData($table, $data)
{
    global $con;
    $columns = implode(", ", array_keys($data));
    $valuesArray = array();
    foreach ($data as $val) {
      if ($val==='') {
        $valuesArray[] = "''"; // Langsung masukkan ''
      } else if (is_null($val)) {
        $valuesArray[] = "NULL"; // Langsung masukkan NULL tanpa tanda kutip
      } else {
        $valuesArray[] = "'" . mysqli_real_escape_string($con, $val) . "'";
      }
    }
    $values = implode(", ", $valuesArray);

    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    return mysqli_query($con, $sql);
}

// Fungsi untuk memperbarui data
function updateData($table, $data, $where)
{
    global $con;
    $setArray = array();
    foreach ($data as $key => $val) {
      if ($val==='') {
        $setArray[] = "$key = ''"; // Langsung masukkan ''
      } else if (is_null($val)) {
        $setArray[] = "$key = NULL"; // Langsung masukkan NULL tanpa tanda kutip
      } else {
        $setArray[] = "$key = '" . mysqli_real_escape_string($con, $val) . "'";
      }
    }
    $setClause = implode(", ", $setArray);

    $sql = "UPDATE $table SET $setClause WHERE $where";
    return mysqli_query($con, $sql);
}

// Fungsi untuk menghapus data
function deleteData($table, $where)
{
    global $con;
    $sql = "DELETE FROM $table WHERE $where";
    return mysqli_query($con, $sql);
}

function queryNumRows($tbl='', $where='')
{
  global $con;
  $where = (empty($where)) ? "":" WHERE $where";
  return mysqli_num_rows(mysqli_query($con, "SELECT * FROM $tbl $where"));
}

function begin()    { global $con; mysqli_query($con, "START TRANSACTION;"); }
function commit()   { global $con; mysqli_query($con, "COMMIT;"); mysqli_close($con); }
function rollback() { global $con; mysqli_query($con, "ROLLBACK;"); mysqli_close($con); }
?>
