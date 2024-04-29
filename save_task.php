<?php

  require_once 'Model.php';

  $model = new Model;
  $asignaciones = $model->readAsignaciones();


	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$json = json_decode(file_get_contents('php://input'));

    if (is_object($json)) {
      $data = get_object_vars($json);
    }

    $cols = array();

    foreach ($data as $key => $value) {
    	$cols[$key] = $value;
    }

    foreach ($asignaciones as $row) {
    	if ($row->asignacion_tiempo == $cols['asignacion'] ) {
    		$cols['asignacion'] = $row->asignacion_title;
    	}
    }

    // print_r($cols);
    // die();

		$created = $model->saveTareasCompletadas($cols['puesto'], $cols['asignacion'], $cols['cantidad']);

  	if ($created) {
      $model->deleteDesk($cols['desk_num']);
  		echo 'Saved.';
  	} else {
  		echo 'Error';
  	}

	}