<?php
$t = $this->tarjetas;
// echo var_dump($t);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Proyectum | Inicio</title>
  <meta http-equiv="pragma" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?=CSS;?>estilosSpace.css">

	<script type="text/javascript" src="<?=JS;?>config.js"></script>
	<script defer type="text/javascript" src="<?=JS;?>slider.js"></script>
</head>
<body class="principal">
	<?=$this->render('Default','alert',true);?>
	<?=$this->render('Default','userorlogin',true);?>
	<div class="slider" id="slider">
		<div class="prev">
			<a href="#" onclick="slider(0)" ><img src="<?=IMG?>back.svg"></a>
		</div>
		<div class="next">
			<a href="#" onclick="slider(1)" ><img src="<?=IMG?>next.svg"></a>
		</div>
			<ul id="slide">
				<li>
					<div class="titulos-slider">
						<h2>Miles de proyectos</h2>
						<h4>Comparte tu proyecto para buscar ayuda y consejos</4>
						<h6><input type="submit" value="Unirme Ahora" onclick="registro()"></h6>
					</div>
					<img src="<?=IMG?>2.png"></li>
				<li>
					<div class="titulos-slider">
						<h2>Haz que tu proyecto crezca y sea un éxito</h2>
						<h4>Solo publícalo y deja que la comunidad te ayude</4>
						<h6></h6>
					</div>
					<img src="<?=IMG?>1.png">
				</li>
				<li>
					<div class="titulos-slider">
						<h2>Únete a esta gran comunidad</h2>
						<h4>Sé parte de Proyectum</4>
						<h6></h6>
					</div>
					<img src="<?=IMG?>webstr.png">
				</li>
			</ul>
	</div>
	<div class="proyectos" id="proyectos">
	<p id="titulo">Proyectos</p>
		<div class="contenido-proyectos">
			<?php

			function formatoTarjeta($t){
				$coments = 0;
				if (!is_null($t['reacciones'])) {
					foreach ($t['reacciones'] as $v) {
						$coments+=(int)$v;
					}
				}
				$e = isset($t['reacciones']['Excelente'])?$t['reacciones']['Excelente']:'0';
				$b = isset($t['reacciones']['Bueno'])?$t['reacciones']['Bueno']:'0';
				$r = isset($t['reacciones']['Regular'])?$t['reacciones']['Regular']:'0';
				$m = isset($t['reacciones']['Malo'])?$t['reacciones']['Malo']:'0';
				$w = isset($t['reacciones']['Wacala'])?$t['reacciones']['Wacala']:'0';

				return '
				<div class="proyecto">
				<img src="'.IMG.'proyectos/'.$t['idPublicacion'].'/'.$t['media1'].'">
				<h3>'.$t['nombrePublicacion'].'</h3>
				<p>'.$t['descripcionCorta'].'</p>
				<div class="detalles-proyecto">
					<div class="icono-detalles">
						<img src="'.IMG.'eye.svg">
						<p>'.$t['vistas']['num'].'</p>
					</div>
					<div class="icono-detalles">
						<img src="'.IMG.'mensajes.svg">
						<p>'.$coments.'</p>
					</div>
					<div class="icono-detalles">
						<img src="'.IMG.'/reacciones/excelente.svg">
						<p>'.$e.'</p>
					</div>
					<div class="icono-detalles">
						<img src="'.IMG.'/reacciones/bien.svg">
						<p>'.$b.'</p>
					</div>
					<div class="icono-detalles">
						<img src="'.IMG.'/reacciones/regular.svg">
						<p>'.$r.'</p>
					</div>
					<div class="icono-detalles">
						<img src="'.IMG.'/reacciones/malo.svg">
						<p>'.$m.'</p>
					</div>
					<div class="icono-detalles">
						<img src="'.IMG.'/reacciones/wacala.svg">
						<p>'.$w.'</p>
					</div>
					<div class="icono-detalles-ver">
						<a href="'.URL.'Index/proyecto/'.$t['idPublicacion'].'">Ver Más</a>
					</div>
				</div>
			</div>';
			}


			if (!is_array($t)){
            	echo("No hay ningún proyecto");
	        }
	        elseif (isset($t['idPublicacion'])) {
	            echo formatoTarjeta($t);
	        }
	        else{
	            foreach ($t as $ta) {
	                echo formatoTarjeta($ta);
	            }
	        }
			 ?>

		</div>
		<div class="proyectos-ver-mas">
			<a href="<?=URL?>Index/proyectos">Ver más proyectos</a>
		</div>
	</div>
  <footer class="footer">
    <img src="<?=IMG?>logo.png">
    <p>© 2017 Proyectum. Aguilar Orozco Enrique, Osornio Velazquez Edgar. All Rights Reserved.</p>
  </footer>

<script type="text/javascript">
	function registro() {
		location.href = config.url+'Index/registro';
	}
</script>
</body>
</html>
