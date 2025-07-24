<?php
if ($actionsRequired) {
    require_once "../models/correoModel.php";
} else {
    require_once "./models/correoModel.php";
}

// Incluir PHPMailer
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class correoController extends correoModel {

    /*----------  Método: Enviar Correo  ----------*/
    public function send_email_controller() {
        try {
            $mail = new PHPMailer(true);

            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host       = 'mail.creditofamiliar.com.ec';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'capacitacionesccf@creditofamiliar.com.ec';
            $mail->Password   = 'Coac2025*'; 
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            // Remitente
            $mail->setFrom('capacitacionesccf@creditofamiliar.com.ec', 'Capacitaciones Crédito Familiar' ?? 'Capacitaciones CCF');

            // Validar tipo de envío
            if (!isset($_POST['bd_correos']) || empty($_POST['bd_correos'])) {
                throw new Exception("Debe seleccionar un tipo de envío.");
            }

            $tipoEnvio = $_POST['bd_correos'];

            // Envío individual
            if ($tipoEnvio === 'individual') {
                if (empty($_POST['correo_individual']) || !filter_var($_POST['correo_individual'], FILTER_VALIDATE_EMAIL)) {
                    $dataAlert = [
                        "title" => "¡Correo no válido!",
                        "text" => "El correo individual no es correcto.",
                        "type" => "error"
                    ];
                    $url="correo/";
                	return self::sweet_alert_url_reload($dataAlert,$url);
                }
                $mail->addAddress($_POST['correo_individual']);
            }

            // Envío por lista
            elseif ($tipoEnvio === 'lista') {
                if (empty($_POST['alumnos']) || !is_array($_POST['alumnos'])) {
                    $dataAlert = [
                        "title" => "¡Sin destinatarios!",
                        "text" => "Debe seleccionar al menos un alumno.",
                        "type" => "error"
                    ];
                    $url="correo/";
                	return self::sweet_alert_url_reload($dataAlert,$url);
                }
                foreach ($_POST['alumnos'] as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $mail->addAddress($email);
                    }
                }
            }

            // Envío por CSV
            elseif ($tipoEnvio === 'csv') {
                if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
                    $dataAlert = [
                        "title" => "¡Error en archivo CSV!",
                        "text" => "Error al cargar el archivo CSV.",
                        "type" => "error"
                    ];
                    $url="correo/";
                	return self::sweet_alert_url_reload($dataAlert,$url);
                }
                $csvFile = $_FILES['csv_file']['tmp_name'];
                if (($handle = fopen($csvFile, 'r')) !== false) {
                    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                        $email = filter_var($row[0], FILTER_VALIDATE_EMAIL);
                        if ($email) {
                            $mail->addAddress($email);
                        }
                    }
                    fclose($handle);
                } else {
                    $dataAlert = [
                        "title" => "¡Error en archivo CSV!",
                        "text" => "No se pudo leer el archivo CSV.",
                        "type" => "error"
                    ];
                    $url="correo/";
                	return self::sweet_alert_url_reload($dataAlert,$url);
                }
            }

            // Asunto y cuerpo del mensaje
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $this->clean_string($_POST['subject']);
            $mail->Body    = $this->clean_string($_POST['description']);

            // Adjuntar archivos
            $AttDir = "../attachments/email/";
            $AttMaxSize = 5120; // 5MB
            $archivosAdjuntos = [];

            if (!empty($_FILES['attachments']['name'][0])) {
                foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['attachments']['error'][$key] !== UPLOAD_ERR_OK) continue;

                    $AttName = basename($_FILES['attachments']['name'][$key]);
                    $AttName = preg_replace('/\s+/', '_', $AttName);
                    $AttName = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $AttName);
                    $AttType = $_FILES['attachments']['type'][$key];
                    $AttSize = $_FILES['attachments']['size'][$key];

                    $allowedTypes = [
                        "image/jpeg","image/jpg", "image/png", "application/pdf",
                        "application/msword", "application/vnd.ms-powerpoint",
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                        "application/vnd.openxmlformats-officedocument.presentationml.presentation"
                    ];

                    if (in_array($AttType, $allowedTypes)) {
                        if (($AttSize / 1024) <= $AttMaxSize) {
                            $finalDir = $AttDir . $AttName;
                            if (move_uploaded_file($tmp_name, $finalDir)) {
                                $mail->addAttachment($finalDir, $AttName);
                                $archivosAdjuntos[] = $AttName;
                            } else {
                                $dataAlert = [
                                    "title" => "¡Error en adjunto!",
                                    "text" => "Error al subir el archivo adjunto: " . $AttName,
                                    "type" => "error"
                                ];
                                $url="correo/";
                				return self::sweet_alert_url_reload($dataAlert,$url);
                            }
                        } else {
                            $dataAlert = [
                                "title" => "¡Tamaño excedido!",
                                "text" => "El archivo " . $AttName . " supera los 5MB permitidos.",
                                "type" => "error"
                            ];
                            $url="correo/";
            				return self::sweet_alert_url_reload($dataAlert,$url);
                        }
                    } else {
                        $dataAlert = [
                            "title" => "¡Tipo de archivo no permitido!",
                            "text" => "Tipo de archivo no permitido: " . $AttName,
                            "type" => "error"
                        ];
                        $url="correo/";
    				    return self::sweet_alert_url_reload($dataAlert,$url);
                    }
                }
            }

            // Obtener destinatarios correctamente
            $destinatariosArray = [];
            foreach ($mail->getToAddresses() as $address) {
                $destinatariosArray[] = $address[0]; // Solo el email
            }
            $destinatarios = implode(", ", $destinatariosArray);

            // Enviar correo
            if ($mail->send()) {
                $data = [
                    "destinatarios" => $destinatarios,
                    "asunto" => $mail->Subject,
                    "contenido" => $mail->Body,
                    "adjuntos" => implode(", ", $archivosAdjuntos),
                    "fecha_envio" => date("Y-m-d H:i:s"),
                    "usuario_id" => $_SESSION['userKey']
                ];

                // Eliminar archivos adjuntos después del envío
                //foreach ($archivosAdjuntos as $archivo) {
                 //   @unlink($AttDir . $archivo);
               // }

                if (self::save_email_model($data)) {
                    $dataAlert = [
                        "title" => "¡Éxito!",
                        "text" => "Correo enviado correctamente y registrado.",
                        "type" => "success"
                    ];
				    $url="correo/";
    				return self::sweet_alert_url_reload($dataAlert,$url);
                } else {
                    $dataAlert = [
                        "title" => "¡Éxito parcial!",
                        "text" => "Correo enviado pero no se pudo registrar en la base de datos.",
                        "type" => "warning"
                    ];
                    $url="correo/";
    				return self::sweet_alert_url_reload($dataAlert,$url);
                }
            } else {
                $dataAlert = [
                    "title" => "¡Error al enviar correo!",
                    "text" => "Error al enviar el correo: " . $mail->ErrorInfo,
                    "type" => "error"
                ];
                $url="correo/";
    			return self::sweet_alert_url_reload($dataAlert,$url);
            }

        } catch (Exception $e) {
            $dataAlert = [
                "title" => "¡Error!",
                "text" => $e->getMessage(),
                "type" => "error"
            ];
            $url="correo/";
    		return self::sweet_alert_url_reload($dataAlert,$url);
        }
    }
    public function pagination_correo_controller(){

			$Datos=self::execute_single_query("SELECT ce.*, concat(e.Nombres+' '+e.Apellidos) as nombres, c.Usuario FROM `correos_enviados` ce LEFT JOIN estudiante e on e.Codigo=ce.usuario_id LEFT JOIN cuenta c on c.Codigo = ce.usuario_id ORDER BY ce.`fecha_envio` DESC
			");
			$Datos=$Datos->fetchAll();

	
			$table='
			<table id="tabla-global" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Emisor</th>
						<th class="text-center">Asunto</th>
						<th class="text-center">Adjuntos</th>
						<th class="text-center">Destinatarios</th>
						<th class="text-center">Ver</th>
						
					</tr>
				</thead>
				<tbody>
			';
			$cont=1;


			foreach($Datos as $rows){
                        $nombres=$rows['nombres'];
                        $Usuario=$rows['Usuario'];
                        if ($nombres!=NULL) {
                            # code...
                            $emisor=$nombres;
                        }else {
                            # code...
                            $emisor=$Usuario;
                        }
                        $Adjuntos=$rows['archivos_adjuntos'];
                        if ($Adjuntos!='') {
                            # code...
                            $dadjunto=$Adjuntos;
                        }else{
                            $dadjunto= "No se Adjuntaron Archivos";
                        }
					$table.='
					<tr>
						<td>'.$cont.'</td>
						<td>'.date("d/m/Y", strtotime($rows['fecha_envio'])).'</td>
						<td>'.$emisor.'</td>
                        <td>'.$rows['asunto'].'</td>
						<td>'.$dadjunto.'</td>
						<td>'.$rows['destinatarios'].'</td>
						<td>
							<a href="'.SERVERURL.'correosview/'.$rows['id'].'/" class="btn btn-info btn-raised btn-xs">
								<i class="zmdi zmdi-tv"></i>
							</a>
						</td>
											
					</tr>
					';
					$cont++;
				}

			$table.='
				</tbody>
			</table>
			';

		
			return $table;
		}
        /*----------  Data correo Controller  ----------*/
		public function data_correo_controller($Type,$Code){
			$Type=self::clean_string($Type);
			$Code=self::clean_string($Code);

			$data=[
				"Tipo"=>$Type,
				"id"=>$Code
			];

			if($videodata=self::data_correo_model($data)){
				return $videodata;
			}else{
				$dataAlert=[
					"title"=>"¡Ocurrió un error inesperado!",
					"text"=>"No hemos podido seleccionar los datos de la clase",
					"type"=>"error"
				];
				return self::sweet_alert_single($dataAlert);
			}
		}
}