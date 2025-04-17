<?php
// if (empty(defined('DIR_PATH'))) { echo "<script>window.location=window.location.origin+'/404';</script>"; exit; }
if (empty(defined('DIR_PATH'))) {
  http_response_code(403);
  die('Access forbidden!');
}
?>
