<?php
  require_once 'Model.php';
  $model = new Model;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $json = json_decode(file_get_contents('php://input'));

    if (is_object($json)) {
      $data = get_object_vars($json);
    }

    $cols = array();

    foreach ($data as $key => $value) {
      $cols[$key] = $value;
    }

    // print_r($cols);
    // die();

    $response = $model->updatePauseDesk($cols['desk_num'], $cols['desk_paused']);


    if ($response) {
      echo 'Paused Saved.';
    } else {
      echo 'Paused Error';
    }

  }

?>
