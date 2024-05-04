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
  $data['admin'] = true;
?>

<?php require './partials/header.php'; ?>
<?php require './partials/navbar.php'; ?>

<?php 
  $data['rooms'] = $model->readRooms();
  $data['asignaciones'] = $model->readAsignaciones();
  if ( !empty($_GET['desk_num']) ) {

    $data['tiempo'] = $model->readTiempoDesk($_GET['desk_num']);
    $data['desk_title'] = "Escritorio N°" . $_GET['desk_num'];
    $data['puesto_value'] = 'desk_' . $_GET['desk_num'];

    if ( $data['tiempo'] ) {
      $data["desk_tiempo"] = $data['tiempo']->desk_time >= 0 ? setTimeRestante(intval($data['tiempo']->desk_time)) : '';
      $data["desk_paused"] = $data['tiempo']->desk_paused == 1 ? true : false ;
      $data["desk_restante"] = $data['tiempo']->desk_time ;
      $data["hidden_element"] = $data["desk_restante"] <= 20 && !$data["desk_paused"] ? '' : 'hidden';
      $data["hidden_el"] = $data["desk_restante"] <= 20 && !$data["desk_paused"] ? 'hidden' : '';

    } else {
      $data["desk_restante"] = 21;
      $data["desk_tiempo"] = '00 : 00 : 00';
      $data["desk_paused"] = false;
      $data["hidden_element"] = $data["desk_restante"] <= 20 && !$data["desk_paused"]  ? '' : 'hidden';
      $data["hidden_el"] = $data["desk_restante"] <= 20 && !$data["desk_paused"]  ? 'hidden' : '';
    }
    
  }

  $data['index_admin_page'] = "https://board.nicedev90.pro/panel_tareas/admin.php";
  // $data['index_admin_page'] = "http://localhost/board.nicedev90.pro/panel_tareas/admin.php";

?>


  <div class=" w-full md:w-1/3 mx-auto flex flex-col space-y-8 px-4 py-8 break-words ">

    <div class=" relative flex space-x-8 items-center bg-white text-cta h-12 rounded-xl mb-10 p-4 w-full font-bold mx-auto ">
      <div id="alerta_tiempo" class=" <?php echo $data["hidden_element"]  ?> absolute -bottom-14 p-2 font-light bg-red text-white text-xl rounded w-fit">El tiempo esta por agotarse</div>
      <h3 class="text-dark text-2xl ">Tiempo Restante : </h3>

      <div class="clock flex w-fit space-between text-2xl md:text-4xl text-ctaLight ">  
        <?php echo $data["desk_tiempo"] ?>  
       </div>
    </div>

    <form id="form_login" action="" class="flex flex-col space-y-4 p-4 bg-white rounded-xl ">
      <div class="flex flex-col space-y-2 pb-6 ">
        <h2 class="text-dark text-4xl text-center"> <?php echo $data['desk_title'] ?>  </h2>
      </div>


      <input type="hidden" id="puesto" name="puesto" value="<?php echo $data['puesto_value'] ?>" class="border border-neutralDark rounded focus:border-ctaLight outline-none p-2 " placeholder="" required>


      <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4  ">
        <div class="w-full flex flex-col">
          <label for="asignacion" class="w-fit text-dark text-sm">Asignacion </label>
          <select name="asignacion" id="asignacion" class="border border-neutralDark rounded focus:border-ctaLight outline-none p-2 " required>
            <option value="">Seleccionar</option>
            <?php if( !empty($data['asignaciones'])) : ?>   
              <?php foreach($data['asignaciones'] as $asignacion) : ?>
                <option data-asig="<?php echo $asignacion->asignacion_title ?>" value="<?php echo $asignacion->asignacion_tiempo ?>"><?php echo $asignacion->asignacion_title ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <div class="w-full flex flex-col ">
          <label for="cantidad" class="w-fit text-dark text-sm">Cantidad</label>
          <input type="text" id="cantidad" name="cantidad" class="border border-neutralDark rounded focus:border-ctaLight outline-none p-2 " placeholder="Colocar cantidad" required>
        </div>
      </div>


      <div class="flex space-x-8 pt-6 ">
        <?php if ( $data["desk_paused"] ) : ?>
          <div id="btn-reanudar" class="text-center cursor-pointer p-3 w-full bg-green text-dark font-bold tracking-wider opacity-90 text-2xl rounded hover:opacity-100 "> <i class="fas fa-play"></i> Reanudar</div>
        <?php elseif  ( $data["desk_tiempo"] == '00 : 00 : 00' && !$data["desk_paused"] ) : ?>
          <div id="btn-start" class=" <?php echo $data["hidden_el"] ?> text-center cursor-pointer p-3 w-full bg-green text-dark font-bold tracking-wider opacity-90 text-2xl rounded hover:opacity-100 "> <i class="fas fa-play"></i> Iniciar</div>

        <?php elseif  ( $data["desk_tiempo"] >= 0 && !$data["desk_paused"] ) : ?>

          <div id="btn-pause" class="<?php echo $data["hidden_el"] ?> text-center cursor-pointer p-3 w-full bg-gold text-dark font-bold tracking-wider opacity-90 text-2xl rounded hover:opacity-100 "> <i class="fas fa-pause"></i> Pausar</div>

        <?php endif; ?>


      </div>


      <div class="flex flex-col pt-6 ">
        <button  id="btn_form" class="<?php echo $data["hidden_element"] ?> p-3 w-full bg-cta text-white text-2xl rounded ">Enviar Datos</button>
      </div>

    </form>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>

  <script>

<?php if ( $data["desk_tiempo"] >= 0 && !$data["desk_paused"] ) : ?>
  setInterval(()=> { window.location.reload() }, 10000);
<?php endif; ?>

  let asignacion = document.querySelector('#asignacion');
  let cantidad = document.querySelector('#cantidad');
  const clock = document.querySelector('.clock');
  const alerta = document.querySelector('#alerta_tiempo');


  let time_seconds;


  let headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };

  const init_tiempo = (e) => {
    if ( asignacion.value && cantidad.value ) {
      time_seconds = parseInt(asignacion.value) * parseInt(cantidad.value);

      // console.log(time_seconds)

      let formData = {
        'room_id': '<?php echo $_GET['room_id'] ?>',
        'desk_num': '<?php echo $_GET['desk_num'] ?>',
        'desk_time': time_seconds
      };


      axios({ method: 'POST', url: 'desk_admin.php', data: formData, headers: headers })
        .then( response => {
          console.log(response.data);
        })
        .catch( error => {
          console.log(error.response.data)
        })


      setTimeout(() => {
        e.target.removeEventListener('click', init_tiempo, false);
        e.target.remove();
        window.location.reload();
      }, 1000); 

    }
  }


  const reanudar_tiempo = (e) => {

    let formData = {
      'desk_num': '<?php echo $_GET['desk_num'] ?>',
      'desk_paused': 0
    };

    axios({ method: 'POST', url: 'pause_task.php', data: formData, headers: headers })
      .then( response => {
        console.log(response.data);
      })
      .catch( error => {
        console.log(error.response.data)
      })

    setTimeout(() => {
      e.target.remove();
      window.location.reload();
    }, 1000); 
    
  }




  const btn_start = document.querySelector('#btn-start')
  btn_start?.addEventListener('click', init_tiempo, false);

  const btn_reanudar = document.querySelector('#btn-reanudar')
  btn_reanudar?.addEventListener('click', reanudar_tiempo, false);




  const btn_pause = document.querySelector('#btn-pause')
  btn_pause?.addEventListener('click', (e) => {

    let formData = {
      'desk_num': '<?php echo $_GET['desk_num'] ?>',
      'desk_paused': 1
    };

    axios({ method: 'POST', url: 'pause_task.php', data: formData, headers: headers })
      .then( response => {
        console.log(response.data);
      })
      .catch( error => {
        console.log(error.response.data)
      })

    setTimeout(() => {
      e.target.remove();
      window.location.reload();
    }, 1000); 

  })




  const form_login = document.querySelector('#form_login');
  let btn = form_login.querySelector('button');

  form_login.addEventListener('submit', (e) => {

    e.preventDefault();

    let formData = new FormData(form_login);
    formData.append('room_id', '<?php echo $_GET['room_id'] ?>');
    formData.append('desk_num', '<?php echo $_GET['desk_num'] ?>');

    axios({ method: 'POST', url: 'save_task.php', data: formData, headers: headers })
      .then( response => {
        console.log(response.data);
        form_login.reset();
        clock.innerHTML = '';
        setTimeout(() => {window.location.href = "<?php echo $data['index_admin_page'] ?>";}, 2000);  
        
      })
      .catch( error => {
        console.log(error.response.data)
      })

      
    btn.disabled = true;
    btn.innerHTML = 'Enviando ...';
    btn.classList.toggle('bg-cta');
    btn.classList.toggle('bg-red');

  });

  </script>

<?php require './partials/footer.php'; ?>

