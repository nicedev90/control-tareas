  <nav class="relative sticky top-0 z-50  flex justify-between items-center px-2 w-full mx-auto md:px-5.5 bg-neutralLight ">
    <div class="flex items-center w-fit h-14 "> 
      <a href="  <?php echo isset($data['admin']) ? 'admin.php' : 'index.php' ?> " class="w-full text-2xl md:text-4xl text-cta font-bold "> Tareas <?php echo isset($data['admin']) ? 'Admin' : 'Alumno' ?>  </a>
    </div>

    <?php if (isset($_GET['room_id'])): ?>
    <a href=" <?php echo isset($data['admin']) ? 'admin.php' : 'index.php' ?>" class="p-2 bg-red hover:text-dark w-fit text-2xl text-white text-center">
      <p> <i class="fas fa-home "></i> Ir a inicio </p> 
    </a>
    <?php endif; ?>


  </nav>

  <?php 

      function setTimeRestante($time_seconds) {

      $hours = floor($time_seconds / 3600);
      $minutes = floor(($time_seconds / 60) % 60);
      $seconds = $time_seconds % 60;

      $_hours = $hours % 24 < 10 ? "0" . ($hours % 24) :  ($hours % 24);
      $_minutes = $minutes % 60 < 10 ? "0" . ($minutes % 60) : ($minutes % 60);
      $_seconds = $seconds % 60 < 10 ? "0" . ($seconds % 60) : $seconds % 60;

      return "$_hours : $_minutes : $_seconds "; 
    }

    function setTime($time_seconds) {

      $hours = floor($time_seconds / 3600);
      $minutes = floor(($time_seconds / 60) % 60);
      $seconds = $time_seconds % 60;

      $_hours = $hours % 24 < 10 ? "0" . ($hours % 24) :  ($hours % 24);
      $_minutes = $minutes % 60 < 10 ? "0" . ($minutes % 60) : ($minutes % 60);
      $_seconds = $seconds % 60 < 10 ? "0" . ($seconds % 60) : $seconds % 60;

      $alert = $time_seconds < 20 ? 'bg-red' : 'bg-blue';

      return "<div class='px-2 pt-6 $alert text-white'> $_hours:$_minutes:$_seconds </div>"; 
    }

    function checkDesk($num, $arr) {
      $_num = 'desk_' . $num;

      if( isset($arr[$_num]) ) {
        echo setTime($arr[$_num]);
      } else {
        echo '<div class=" px-2 pt-6 "></div>';
      }
    }


    function checkDeskUsuario($row, $num, $escritorios = []) {
      $_num = 'desk_' . $num;

      if( isset($escritorios[$_num]) ) {
        $html = "<div class='relative bg-gold text-blue h-12 w-24  hover:text-white text-center'>";
        $html .= "<div class='absolute px-2 left-0 top-0  text-sm bg-blue text-white '>" . $num . "</div>";
        $html .= setTime($escritorios[$_num]);
        $html .= "</div>";

      } else {

        $html = "<a href='desk_user.php?room_id=". $row->id . "&desk_num=" . $num . "' class='relative hover:bg-blue bg-gray text-blue h-12 w-24  hover:text-white text-center'>";
        $html .= "<div class='absolute px-2 left-0 top-0  text-sm bg-blue text-white '>" . $num . "</div>";
        // $html .= setTime($escritorios[$_num]);
        $html .= "</a>";
      }

      echo $html;


    }


?>