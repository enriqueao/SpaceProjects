var textarea = document.getElementById("inputDesc");

textarea.oninput = function(){
	textarea.style.height = "";
    textarea.style.height = 'height:auto';
    textarea.style.height = textarea.scrollHeight + "px";
  };

function baseName(str){
	str=str.toString();
   var base = new String(str).substring(str.lastIndexOf('\\') + 1);
    // if(base.lastIndexOf(".") != -1)
    //     base = base.substring(0, base.lastIndexOf("."));
   return base;
}

var objImg = document.forms.editarPerfil.img;
var colorImg = objImg.nextSibling.nextSibling.style.borderColor;

var divPass = document.getElementById('pass');
var objLastPass = document.forms.editarPerfil.lastPass;
var colorPass = objLastPass.style.borderColor;

var objNewPass = document.forms.editarPerfil.newPass;
var objNewPass2 = document.forms.editarPerfil.newPass2;

function editar(){
	var btn = document.getElementsByName("btnSend")[0];
	btn.disabled=true;
	var data = "";
	var nombre = document.forms.editarPerfil.nombre.value;
	var correo = document.forms.editarPerfil.correo.value;
	var img = baseName(objImg.value);
	var desc = document.forms.editarPerfil.desc.value;
	var lastPass = objLastPass.value;
	var newPass = objNewPass.value;
	var newPass2 = objNewPass2.value;

	if (nombre!="") {
		data += "nombrecompleto="+nombre+"&";
	}

	if (correo!="") {
		data += "correo="+correo+"&";
	}

	if (img!="" && img.indexOf(".jpeg")==-1 && img.indexOf(".jpg")==-1 && img.indexOf(".png")==-1) {
		objImg.nextSibling.nextSibling.style.borderColor="red";
		btn.disabled=false;
		alertP("Formato de imagen no soportado.","La imagen no tiene un formato soportado (jpg, jpeg o png).");
		return;
	}
	else if(img!=""){
		data += "imgPorfile="+img+"&"; /**********************************AQUÍ***************/
	}

	if (desc!="") {
		data += "descripcion="+desc+"&";
	}

	if (lastPass!="" || newPass!="" || newPass2!="") {
		if (!(lastPass!="" && newPass!="" && newPass2!="")) {
			divPass.style.borderColor="red";
			btn.disabled=false;
			alertP("Campos vacíos de contraseña.","Hacen falta campos de cambio de contraseña.");
			return;
		}
		if(newPass != newPass2){
				objNewPass.style.borderColor="red";
				objNewPass2.style.borderColor="red";
				btn.disabled=false;
				alertP("Verificación incorrecta.","La nueva contraseña no concuerda con la escrita en el campo de verificación.");
				return;
		}
		else{
			// ajax de verificacion de pass
			var isThePass = false;
			dts="pass="+lastPass;
			var url = config['url']+"Usuario/revisarPass";
		  	passwd = new XMLHttpRequest();
		    passwd.open("POST", url ,true);
		    passwd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		    passwd.send(data);

		    passwd.onreadystatechange = function (){
		      if (passwd.readyState == 4) {
		        if (parseInt(passwd.responseText)==0) {
		        	isThePass=true;
		        }else{
		        	isThePass=false;
		        }
		      }
		    }
			if (!isThePass) {
				objLastPass.style.borderColor="red";
				btn.disabled=false;
				alertP("Contraseña incorrecta.","La contraseña no es correcta.");
				return;
			}
			else{
				if (lastPass==newPass) {
					objLastPass.style.borderColor="red";
					btn.disabled=false;
					alertP("Misma contraseña.","La nueva contraseña no puede ser igual a la contraseña antigüa.");
					return;
				}
				else{
					data += "pass="+newPass+"&";
				}
			}
		}

	}
	if (data==""){
		btn.disabled=false;
		alertP("Sin cambios.","Ningún cambio qué guardar.");
		return;
	}
	data = data.substring(0, data.length - 1);
  	var url = config['url']+"Usuario/editarPerfil";
  	perfil = new XMLHttpRequest();
    perfil.open("POST", url ,true);
    perfil.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    perfil.send(data);

    perfil.onreadystatechange = function (){
      if (perfil.readyState == 4) {
        if (parseInt(perfil.responseText)==0) {
        	alertP("Perfil actualizado.",'Cambios guardados exitosamente.',1);
        	window.location.href=config['url']+"usuario/perfil"
        }else{
        	btn.disabled=false;
        	alertP("Error desconocido.","Ocurrió un problema al editar su perfil. Por favor intente más tarde.");
        }
      }
    }
}

function estImg(){
	var img = baseName(objImg.value);
	if (img!="" && img.indexOf(".jpeg")==-1 && img.indexOf(".jpg")==-1 && img.indexOf(".png")==-1) {
		objImg.nextSibling.nextSibling.style.borderColor="red";
		alertP("Formato de imagen no soportado.","La imagen no tiene un formato soportado (jpg, jpeg o png).");
		return;
	}else{
		objImg.nextSibling.nextSibling.style.borderColor=colorImg;
	}
}

function estLPass(){
	objLastPass.style.borderColor=colorPass;
	estDivPass();
}

function estNPass(){
	objNewPass.style.borderColor=colorPass;
	objNewPass2.style.borderColor=colorPass;
	estDivPass();
}
function estDivPass(){
	divPass.style.borderColor=colorPass;
}
