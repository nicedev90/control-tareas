<?php
  require_once 'Model.php';

  $model = new Model;
  $data['rooms'] = $model->readRooms();
  $data['asignaciones'] = $model->readAsignaciones();
  if ( !empty($_GET['desk_num']) ) {
    $data['tiempo'] = $model->readTiempoDesk($_GET['desk_num']);
  }

  $data['admin'] = true;


?>

<?php require './partials/header.php'; ?>
<?php require './partials/navbar.php'; ?>


  <!-- main content -->
  <div class=" w-full md:w-1/3 mx-auto flex flex-col space-y-8 px-4 py-8 break-words ">

    <div class=" relative flex space-x-8 items-center bg-white text-cta h-12 rounded-xl mb-10 p-4 w-full font-bold mx-auto ">
      <div id="alerta_tiempo" class="hidden absolute -bottom-14 p-2 font-light bg-red text-white text-xl rounded w-fit">El tiempo esta por agotarse</div>
      <h3 class="text-dark text-2xl ">Tiempo Restante : </h3>

      <div class="clock flex w-fit space-between text-2xl md:text-4xl text-ctaLight ">  
        <?php if ( !empty($data['tiempo']) ) : ?>
        <?php echo $data['tiempo']->desk_time > 0 ? setTimeRestante(intval($data['tiempo']->desk_time)) : '' ?>  
        <?php endif; ?>
       </div>
    </div>

    <form id="form_login" action="" class="flex flex-col space-y-4 p-4 bg-white rounded-xl ">
      <div class="flex flex-col space-y-2 pb-6 ">
        <?php if (isset($_GET['desk_num'])): ?>
        <h2 class="text-dark text-4xl text-center"> Escritorio N° <?php echo $_GET['desk_num'] ?> </h2>
        <?php endif; ?>
      </div>


      <input type="hidden" id="puesto" name="puesto" value="<?php echo !empty($_GET['desk_num']) ? 'desk_' . $_GET['desk_num'] : '' ?>" class="border border-neutralDark rounded focus:border-ctaLight outline-none p-2 " placeholder="" required>


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
        <?php if (!empty($data['tiempo']) && $data['tiempo']->desk_time > 0): ?>
          <div id="btn-reanudar" class="text-center cursor-pointer p-3 w-full bg-green text-dark font-bold tracking-wider opacity-90 text-2xl rounded hover:opacity-100 "> <i class="fas fa-play"></i> Reanudar</div>
        <?php else : ?>
          <div id="btn-start" class="text-center cursor-pointer p-3 w-full bg-green text-dark font-bold tracking-wider opacity-90 text-2xl rounded hover:opacity-100 "> <i class="fas fa-play"></i> Iniciar</div>
        <?php endif; ?>

          <div id="btn-pause" class="text-center cursor-pointer p-3 w-full bg-gold text-dark font-bold tracking-wider opacity-90 text-2xl rounded hover:opacity-100 "> <i class="fas fa-pause"></i> Pausar</div>

      </div>


      <div class="flex flex-col pt-6 ">
        <button  id="btn_form" class="hidden p-3 w-full bg-cta text-white text-2xl rounded ">Enviar Datos</button>
      </div>

    </form>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>

  <script>

  let asignacion = document.querySelector('#asignacion');
  let cantidad = document.querySelector('#cantidad');
  const clock = document.querySelector('.clock');
  const alerta = document.querySelector('#alerta_tiempo');

  let countdown;
  let time_seconds;
  let secondsLeft;


  const init_tiempo = (e) => {
    if ( asignacion.value && cantidad.value ) {
      time_seconds = parseInt(asignacion.value) * parseInt(cantidad.value);

      console.log(time_seconds)

      let now = Date.now();
      let then = now + time_seconds * 1000;

      countdown = setInterval(() => {
        secondsLeft = Math.round((then - Date.now()) / 1000);

        if(secondsLeft <= 0) {
          clearInterval(countdown);
          return;
        }

        if ( secondsLeft <= 20 ) {
          alerta.classList.remove('hidden');
          btn_form.classList.remove('hidden');
        };

        let daysLeft = Math.floor(secondsLeft / 86400); 
        let hoursLeft = Math.floor((secondsLeft % 86400) / 3600); 
        let minutesLeft = Math.floor((secondsLeft % 86400) % 3600 / 60); 


        let days = daysLeft % 24 < 10 ? `0${daysLeft % 360} : ` : `${daysLeft % 360} : `;
        let hours = hoursLeft % 24 < 10 ? `0${hoursLeft % 24} : ` :  `${hoursLeft % 24} : `;
        let minutes = minutesLeft % 60 < 10 ? `0${minutesLeft % 60} : ` : `${minutesLeft % 60} : `;
        let seconds = secondsLeft % 60 < 10 ? `0${secondsLeft % 60}` : secondsLeft % 60;

        clock.innerHTML =  hours + minutes + seconds;
        // clock.innerHTML = days + hours + minutes + seconds;
        // clock.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        let formData = {
          'room_id': '<?php echo $_GET['room_id'] ?>',
          'desk_num': '<?php echo $_GET['desk_num'] ?>',
          'desk_time': secondsLeft
        };


        let headers = {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        };

        let endpoint = 'init_task.php';

        axios({ method: 'POST', url: endpoint, data: formData, headers: headers })
          .then( response => {
            console.log(response.data);
          })
          .catch( error => {
            console.log(error.response.data)
          })

      },1000);

        
      setTimeout(() => {
        e.target.disabled = true;
        e.target.innerHTML = 'Iniciado';
        e.target.removeEventListener('click', init_tiempo, false);
        e.target.remove();
      }, 1000); 

    }
  }


  const reanudar_tiempo = (e) => {

      time_seconds = '<?php echo !empty($data["tiempo"]) ? $data["tiempo"]->desk_time : 0?>';

      if ( time_seconds <= 10 ) {
        btn_form.classList.remove('hidden');
      };

      console.log(time_seconds)

      let now = Date.now();
      let then = now + time_seconds * 1000;

      countdown = setInterval(() => {
        secondsLeft = Math.round((then - Date.now()) / 1000);

        if(secondsLeft <= 0) {
          clearInterval(countdown);
          return;
        }

        if ( secondsLeft <= 20 ) {
          alerta.classList.remove('hidden');
          btn_form.classList.remove('hidden');
        };

        let daysLeft = Math.floor(secondsLeft / 86400); 
        let hoursLeft = Math.floor((secondsLeft % 86400) / 3600); 
        let minutesLeft = Math.floor((secondsLeft % 86400) % 3600 / 60); 


        let days = daysLeft % 24 < 10 ? `0${daysLeft % 360} : ` : `${daysLeft % 360} : `;
        let hours = hoursLeft % 24 < 10 ? `0${hoursLeft % 24} : ` :  `${hoursLeft % 24} : `;
        let minutes = minutesLeft % 60 < 10 ? `0${minutesLeft % 60} : ` : `${minutesLeft % 60} : `;
        let seconds = secondsLeft % 60 < 10 ? `0${secondsLeft % 60}` : secondsLeft % 60;

        clock.innerHTML =  hours + minutes + seconds;
        // clock.innerHTML = days + hours + minutes + seconds;
        // clock.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        let formData = {
          'room_id': '<?php echo $_GET['room_id'] ?>',
          'desk_num': '<?php echo $_GET['desk_num'] ?>',
          'desk_time': secondsLeft
        };


        let headers = {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        };

        let endpoint = 'init_task.php';

        axios({ method: 'POST', url: endpoint, data: formData, headers: headers })
          .then( response => {
            console.log(response.data);
          })
          .catch( error => {
            console.log(error.response.data)
          })

      },1000);

      setTimeout(() => {
        e.target.disabled = true;
        e.target.innerHTML = 'Iniciado';
        e.target.removeEventListener('click', init_tiempo, false);
        e.target.remove();
      }, 1000); 
    
  }




  const btn_start = document.querySelector('#btn-start')
  btn_start?.addEventListener('click', init_tiempo, false);

  const btn_reanudar = document.querySelector('#btn-reanudar')
  btn_reanudar?.addEventListener('click', reanudar_tiempo, false);




  const btn_pause = document.querySelector('#btn-pause')
  btn_pause?.addEventListener('click', () => {
    console.log(secondsLeft)
    clearInterval(countdown);

    // window.location.href = 'desk.php'
  })




    const form_login = document.querySelector('#form_login');
    let btn = form_login.querySelector('button');

    form_login.addEventListener('submit', (e) => {

      e.preventDefault();

      let formData = new FormData(form_login);
      formData.append('room_id', '<?php echo $_GET['room_id'] ?>');
      formData.append('desk_num', '<?php echo $_GET['desk_num'] ?>');

      let headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      };

      let endpoint = 'save_task.php';

      axios({ method: 'POST', url: endpoint, data: formData, headers: headers })
        .then( response => {
          console.log(response.data);
          form_login.reset();
          clock.innerHTML = '';
          setTimeout(() => {window.location.href = 'http://localhost/tareas/admin.php';}, 2000);  
          
        })
        .catch( error => {
          console.log(error.response.data)
        })

        

      btn.disabled = true;
      btn.innerHTML = 'Enviando ...';
      btn.classList.toggle('bg-cta');
      btn.classList.toggle('bg-red');

      // setTimeout(() => {
      //   btn.disabled = false;
      //   btn.innerHTML = 'Enviar Datos';
      //   btn.classList.toggle('bg-cta');
      //   btn.classList.toggle('bg-red');
      // }, 2000);  

    });

  </script>


<?php require './partials/footer.php'; ?>

