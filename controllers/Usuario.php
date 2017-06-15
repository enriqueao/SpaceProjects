<?php

class Usuario extends Controller{
    function __construct() {
        parent::__construct();
    }

    public function index(){
      $this->view->render($this,'perfil');
    }

    public function iniciarSesion(){
        if(isset($_POST["username"], $_POST["password"]) ){
            echo $this->model->iniciarSesion($_POST["username"], $_POST["password"]);
        }
    }

    public function registro(){
      if(isset($_POST['nombrecompleto'],$_POST['username'],$_POST['correo'])){
        if($this->checkStatusUsername($_POST['username'])){
          $registro = array($_POST['nombrecompleto'],$_POST['username'],$_POST['correo'],$_POST['pass'],$_POST['descrpcion']);
          echo $this->model->registro($registro);
        } else {
          echo '0';
        }
      }
    }

    /**
    * @author Enrique Aguilar Orozco
    *
    */
    public function updateImgPro(){
        if(isset($_FILES['images']) ){
          if ($_FILES['images']['size'] < 16777216) {
            $imagenType = $_FILES['images']['type'];
            if ($imagenType == "image/jpeg" || $imagenType == "image/jpg" || $imagenType == "image/png"){
                $ext     = explode(".", $_FILES['images']['name']);
                $dir     = 'IMG_'.$this->getKeyImg(Session::getValue('idUsuario')).".".end($ext);
                $dirmove = "public/images/usuarios/".$dir;

                $upload  = $this->comprimirImagenAndUpload($imagenType,$dirmove,$_FILES['images']['tmp_name']);
                if($upload){
                    $this->model->actualizarInformacion('imgProfile','usuarios/'.$dir,Session::getValue('idUsuario'),'usuarios');
                    $this->deleteAnterior(Session::getValue('imagenPerfil'));
                    Session::setValue('imagenPerfil',$dir);
                } else {
                    echo '0';
                }
                echo ($upload) ? '1' : '0' ;
            } else {
                echo 2; //formato no admitido
            }
           } else {
               echo 'Tamaño Superado'; //tamaño superadoaaa
           }
        }
    }

    /**
    * @author Enrique Aguilar Orozco
    *
    */
    private function deleteAnterior($img){
        if(file_exists('./public/images/usuarios'.$img)){
            if($img != 'user.svg'){
                unlink('./public/images/usuarios'.$img);
            }
        }
    }

    /**
    * @author Enrique Aguilar Orozco
    *
    */
    private function getKeyImg($cuenta){
        $key = implode(getDate());
        $key.=$cuenta;
        $ran = rand(1,6);
        for ($i=0; $i < $ran; $i++) {
            $key = base64_encode($key);
        }
        $key = substr($key,5,40);
        return $key;
    }

    public function perfil($user = ''){
      $username = ($user == '') ? Session::getValue('username') : $user ;
      if($this->perfilExist($username)){
          $this->view->publicaciones = $this->publicacionesPerfil($username);
          $this->view->datos = $this->model->statusUsername($username);
          $this->view->proyectoDestacado = $this->model->proyectoDestacado($username);
          $this->view->proyectoMasVisto = $this->model->proyectoMasVisto($username);
          $this->view->perfilDestacado = $this->model->perfilDestacado();
          $this->view->numUsers = $this->model->numUsers();
          $this->view->numVistas = $this->model->numVistas();
          $this->view->numProyectos = $this->model->numProyectos();

          $this->view->render($this,'perfil');
        } else {
          $this->pageHistoryBack();
        }
    }


    public function perfilExist($username){
      return is_array($this->model->statusUsername($username));
    }

    public function publicacionesPerfil($username){
      $publicaciones = $this->model->publicacionesPerfil($username);
      $proyectos = "<div class='perfil-lateral'>";
      if(is_array($publicaciones)){
        foreach ($publicaciones as $key => $value) {
          $proyectos .=
          "<div class='perfil-proyecto'>
      					<h2>{$value['nombrePublicacion']}</h2>
      					<p class='perfil-parrafo'>{$value['descripcionCorta']}</p>
      					<h5><a href='".URL."Index/proyecto/".$value['idPublicacion']."'>Ver más</a></h5>
      				</div>";
        }
        return $proyectos.="</div>";
      } else {
        #Corregir ruta para ver proyectos *************************************
        return $proyectos .=
        '<div class="perfil-proyecto">
            <h2>Aún No tiene proyectos</h2>
            <p>Para comenzar Con esta gran comunidad sube un proyecto.</p>
            <h5><a href="'.URL.'Usuario/subeProyecto">Haz click aqui para ver más</a></h5>
          </div>';
      }
    }


    public function cerrarSesion(){
        Session::destroy();
        $_SESSION = [];
        // header("Refresh:0");
        header("Location: ".URL);
    }

    public function comentar(){
        $this->loadOtherModel('Comentarios');
        echo $this->Comentarios->comentar(Session::getValue('idUsuario'), $_POST['idPublicacion'], $_POST['comentario'], $_POST['idTipoReaccion']);
    }

    public function subirProyecto(){
        $this->loadOtherModel('Publicaciones');
        echo $this->Publicaciones->subirPublicacion(Session::getValue('idUsuario'), $_POST['idCategoria'], $_POST['nombrePublicacion'], $_POST['descripcionCorta'], $_POST['descripcionLarga'], $_POST['imgs']);
    }

    // public function comentariosPublicacion(){
    //   $this->loadOtherModel("Comentarios");
    //   echo $this->Comentarios->obtenerComentariosPublicacion($_POST['id']);
    // }

    public function subeProyecto(){
      if (!Session::exist()) {
        header("Location: ".URL);
      }
      $this->loadOtherModel('Categorias');
      $this->view->categorias=$this->Categorias->obtenerCategorias();
      $this->view->render($this,'subirProyecto');
    }

    public function editaPerfil(){
      if (!Session::exist()) {
        header("Location: ".URL);
      }
      $this->view->datos = $this->model->statusUsername(Session::getValue('username'));
      $this->view->render($this,'editarPerfil');
      $this->view->render($this,'editarPerfil');
    }

    public function editarPerfil(){
        echo $this->model->editarPerfil($_POST,Session::getValue('idUsuario'));
    }

    public function revisarPass(){
        echo $this->model->revisarPass(Session::getValue('idUsuario'),$_POST['pass']);
    }

    public function statusUsername(){
      if(isset($_POST['username'])){
        if(is_array($this->model->statusUsername($_POST['username']))){
          // echo '<p class="ocupado">No Disponible</p>';
          echo 0;
        } else {
          // echo '<p class="disponible">Disponible</p>';
          echo 1;
        }
      }
    }

    public function checkStatusUsername($username){
        if(is_array($this->model->statusUsername($username))){
          return 0;
        } else {
          return 1;
        }
    }

    public function buscar(){
      if(isset($_POST['search'])){
        $publicaciones = $this->model->publicaciones($_POST['search']);
        $usuarios = $this->model->usuarios($_POST['search']);

        $resultados =
        '<p class="info-busqueda">Titulo del proyecto o nombre del usuario</p>
        <hr>
        <h5>Proyectos</h5>';
        if(is_array($publicaciones)){
          foreach ($publicaciones as $key => $value) {
            $resultados .= "<p>+<a href='".URL."Index/proyecto/{$value['idPublicacion']}'>{$value['nombrePublicacion']}</a></p>";
          }
        } else{
          $resultados .= "<p>Sin resultados</p>";
        }

        $resultados .=
        '<h5>Usuarios</h5>
        <hr>';
        if(is_array($usuarios)){
          foreach ($usuarios as $key => $value) {
            $resultados .= "<p>@<a href='".URL."Usuario/perfil/{$value['username']}'>{$value['username']}</a></p>";
          }
        } else{
          $resultados .= "<p>Sin resultados</p>";
        }
        echo $resultados;
      }
    }

}
?>
