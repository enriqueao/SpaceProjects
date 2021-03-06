<!DOCTYPE html>
<html>
  <head>
  <?=$this->render('Default','loading',true);?>
    <meta charset="utf-8">
    <meta http-equiv="pragma" content="no-cache">
    <title>Únete | Proyectum</title>
    <link rel="stylesheet" type="text/css" href="<?=CSS;?>estilosSpace.css">
    <link rel="stylesheet" type="text/css" href="<?=CSS;?>subirProyecto.css">

  	<script type="text/javascript" src="<?=JS;?>config.js"></script>
    <script defer type="text/javascript" src="<?=JS;?>registro.js"></script>
  </head>
  <body>
    <?=$this->render('Default','userorlogin',true);?>
    <?=$this->render('Default','alert',true);?>
    <div id="fakeBody">
    <div id="contenedorPrincipal">
    	<h1>Únete a Proyectum</h1>
    	<div id="fondo">
    		<form name="subirProyecto" onsubmit="return false">
          <p>Nombre Completo</p>
          <input class="registro-inputs" placeholder="Ej. Juan Eduardo Avalos" type="text" id="nombreCompleto" autocomplete="off" onchange="est('objNomc')">
          <p>Nombre de Usuario</p>
          <input class="registro-inputs" type="text" id="user" autocomplete="off" onchange="est('objUsername')">
          <h4 id="usernamecomp">Introduce tu nombre de usuario mayor a 5 caracteres</h4>
          <p>Correo</p>
          <input class="registro-inputs" type="email" id="correo" placeholder="Ej. correo@ejemplo.com" value="" autocomplete="off" onchange="est('objCorreo')">
          <p>Descripción de usuario:</p>
          <textarea maxlength="250" onchange="est('objDesc')" size="250" type="text" placeholder="Ej. Soy alguien comprometido con mis metas y día a día comento los proyectos de mis compañeros..." id="inputDesc"></textarea>
          <p>Contraseña</p>
          <input class="registro-inputs" type="password" id="pass" value="" onchange="est('objPass')">
          <p>Confirma Tu Contraseña</p>
          <input class="registro-inputs" type="password" id="pass2" value="" onchange="est('objPass2')">
    		<div id="contFinal">
    			<button id="botonSu" onclick="registrar()">Registrarme</button>
    		</div>
    		</form>
    	</div>
    </div>
    </div>
    <?=$this->render('Index','footer',true);?>
  </body>
</html>
