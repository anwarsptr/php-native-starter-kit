<?php
require_once DIR_PATH . 'init.php';

function json_datatables($getParams=[])
{
   global $con;

   $tbl = @$getParams['tbl'];
   $select = (!empty($getParams['select'])) ? $getParams['select']: '*';
   $where = @$getParams['where'];
   $join = (!empty($getParams['join'])) ? $getParams['join']: [];
   $orderBy = @$getParams['orderBy'];
   $search = @$getParams['search'];
   $groupBy = @$getParams['groupBy'];
   $encrypt_id = (!empty($getParams['encrypt_id'])) ? $getParams['encrypt_id']:false;

   $params = $totalRecords = $data = array();

   $params = $_REQUEST;

   $where_condition = $sqlTot = $sqlRec = $join_condition = "";

   if (!empty($join)) {
     for ($i=0; $i < count($join); $i++) {
       $join_x = (!empty($join[$i][2])) ? $join[$i][2]:'';
       $inJOIN = "INNER JOIN";
       if (strtolower($join_x)=="left") {
         $inJOIN = "LEFT JOIN";
       }elseif (strtolower($join_x)=="right") {
         $inJOIN = "RIGHT JOIN";
       }
       $join_condition .= " $inJOIN ".$join[$i][0]." ON ".$join[$i][1]." ";
     }
   }

   if( !empty($where) ) {
     $where_condition .= " WHERE ";
     $where_condition .= $where;
   }

   $search_value = $_REQUEST['search']['value'];
   if (!empty($search_value)) {
     if (empty($search)) {
       if (!empty($_REQUEST['columns'])) {
         // foreach ($_REQUEST['columns'] as $key => $value) {
         //   if (!empty($value['data'])) {
         //     $search[] = $value['data'];
         //   }
         // }
         if (!empty($select) && $select!='*') {
           $getSelect = explode(',', $select);
           $search=[];
           foreach ($getSelect as $key => $value) {
             $checkAS = explode(' as ', $value);
             $search[] = trim($checkAS[0]);
           }
         }
       }
     }

     if (!empty($search)) {
       if (empty($where)) { $where_condition .= " WHERE "; }else{ $where_condition .= " AND "; }
       $field=''; $or=''; $jml_search = count($search) - 1;
       foreach ($search as $key => $value) {
         $or = ($key==$jml_search) ? '':' or ';
         $field .= " $value LIKE '%$search_value%' $or ";
       }
       if ($field!='') { $where_condition .= " ($field) "; }
     }
   }

   $sql_query = " SELECT $select FROM $tbl ";
   $sqlTot .= $sql_query;
   $sqlRec .= $sql_query;

   if(isset($join_condition) && $join_condition != '') {
     $sqlTot .= $join_condition;
     $sqlRec .= $join_condition;
   }

   if (isset($where_condition) && $where_condition != '') {
     $sqlTot .= $where_condition;
     $sqlRec .= $where_condition;
   }

   if (!empty($groupBy)) {
     $group = " GROUP BY $groupBy ";
     $sqlTot .= $group;
     $sqlRec .= $group;
   }

   if (!empty($_REQUEST['order'][0]['column']) && !empty($_REQUEST['order'][0]['dir'])) {
     $order_column = $_REQUEST['order'][0]['column'];
     $order_dir = $_REQUEST['order'][0]['dir'];
     if (!empty($_REQUEST['columns'][$order_column]['data'])) {
       $field = $_REQUEST['columns'][$order_column]['data'];
       $order_dir = (strtolower($order_dir)=='asc') ? 'desc':'asc';
       $sqlRec .= " ORDER BY ";
       if (!empty($orderBy) && @$_GET['draw']==1) { $sqlRec .=  " $orderBy, "; }
       $sqlRec .= " $field $order_dir ";
     }else {
       if (!empty($orderBy)) { $sqlRec .=  " ORDER BY $orderBy "; }
     }
   }else {
     if (!empty($orderBy)) { $sqlRec .=  " ORDER BY $orderBy "; }
   }
   if ($params['length'] != '-1') {
     $sqlRec .= " LIMIT ".$params['start']." ,".$params['length']." ";
   }

   // log_r($sqlRec);
   $queryTot = mysqli_query($con, $sqlTot) or die("Database Error:". mysqli_error($con));

   $totalRecords = mysqli_num_rows($queryTot);
   // log_r($sqlRec);
   $queryRecords = mysqli_query($con, $sqlRec) or die("Error to Get the Post details.");
   // log_r($sqlRec);
   $data=[];
   while ($baris = mysqli_fetch_assoc($queryRecords)) {
     if ($encrypt_id && !empty($baris['id'])) {
       $baris['id'] = encrypt($baris['id']);
     }
     $data[] = $baris;
   }

   $json_data = array(
     "draw"            => intval( $params['draw'] ),
     "recordsTotal"    => intval( $totalRecords ),
     "recordsFiltered" => intval( $totalRecords ),
     "data"            => $data
   );

   echo json_encode($json_data); exit;
}
?>
