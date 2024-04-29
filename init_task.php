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

    $desk = $model->readDeskByNum($cols['desk_num']);

    if ( $desk ) {
      $response = $model->updateDesk($cols['room_id'], $cols['desk_num'], $cols['desk_time']);
    } else {
      $response = $model->createDesk($cols['room_id'], $cols['desk_num'], $cols['desk_time']);
    }


  	if ($response) {
  		echo 'Saved.';
  	} else {
  		echo 'Error';
  	}

	}