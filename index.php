<?php
  require_once 'Model.php';

  $cont = new Model;
  $data['rooms'] = $cont->readRooms();
  $data['desk_ocupados'] = $cont->readDeskOcupados();

  $escritorios = [];

  foreach($data['desk_ocupados'] as $value) {
    $escritorios[ 'desk_' . $value->desk_num] = $value->desk_time;
  }

?>

<?php require './partials/header.php'; ?>
<?php require './partials/navbar.php'; ?>


  <div class=" w-full mx-auto flex flex-col space-y-8 my-2 break-words p-3 ">
    <?php if( !empty($data['rooms'])) : ?>      

      <?php foreach($data['rooms'] as $row) : ?>
      <div class=" w-full flex flex-col space-y-4 px-4 py-8 break-words rounded bg-white ">

        <h2 class="text-dark text-4xl text-center"> <?php echo $row->room_title ?>  </h2>

        <div class="w-full grid grid-cols-3 md:grid-cols-12 gap-4 ">
          <?php for ($i=1; $i < intval($row->room_desks) +1 ; $i++) : ?>
            <?php checkDeskUsuario($row, $i, $escritorios); ?>
          <?php endfor; ?>
        </div>

      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

<script>
  setInterval(()=> { window.location.href = "index.php"}, 10000)
</script>

<?php require './partials/footer.php'; ?>
  