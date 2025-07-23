<?php 
	class viewsModel{
		public function get_views_model($views){
			if(
				$views=="home" ||
				$views=="dashboard" ||
				$views=="admin" ||
				$views=="adminlist" ||
				$views=="admininfo" ||
				$views=="account" ||
				$views=="student" ||
				$views=="studentlist" ||
				$views=="studentinfo" ||
				$views=="curso" ||
				$views=="listacursos" ||
				$views=="class" ||
				$views=="classlist" ||
				$views=="classinfo" ||
				$views=="classview" ||
				$views=="videonow" ||
				$views=="videolist" ||
				$views=="cursolist" ||
				$views=="cursoview" ||
				$views=="cursoinfo" ||
				$views=="cursoclave" ||
				$views=="cursoclases" ||				
				$views=="verificarcurso" ||				
				$views=="preguntas" ||				
				$views=="encuesta" ||	
				$views=="certificado" ||	
				$views=="encuestaadd" ||	
				$views=="evaluacionadd" ||	
				$views=="listaevaluacion" ||	
				$views=="listaencuesta" ||	
				$views=="reporteseducacion" ||	
				$views=="pruebareporte" ||	
				$views=="reportesCurso" ||	
                $views=="normas" ||	
				$views=="normaslist" ||	
				$views=="normasinfo" ||	
				$views=="organizacion" ||
				$views=="correo" ||	
				$views=="correolist" ||	
				$views=="correosview" ||
				$views=="backup" ||	
				$views=="juegoadd" ||	
				$views=="juego" ||	
				$views=="juegoslist" ||	
				$views=="juegosview" ||	



							
				$views=="search"
			){
				if(is_file("./views/contents/".$views."-view.php")){
					$contents="./views/contents/".$views."-view.php";
				}else{
					$contents="login";
				}
			}elseif($views=="index"){
				$contents="login";
			}elseif($views=="login"){
				$contents="login";
			}elseif($views=="registro"){
				$contents="registro";
			}else{
				$contents="login";
			}
			return $contents;
		}
	}