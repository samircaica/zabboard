
text/x-generic Ws.php 
PHP script text
<?php

	class WsRest extends Rest {
		/*
		public $layout = "";
		*/
		private $logg;

		public function __construct() {
			$this->logg = new Logger(__CLASS__);
		}
		/**/
		public function index() {
			$this->logg->warn("Log warn");
			$this->logg->error("Log error");
			$this->logg->info("Log Info");
			$this->logg->debug("Log Info");
		}

		public function loginusuario() {
			
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);
			$datos = explode('|', $decrypted);

			$email = $datos[0];
			$password = $datos[1];
			$idDispositivo = $datos[2];
			//$codApp = $datos[3];

			/*
			$user = Usuario::find_all_by_iddispositivo_and_usuario_and_clave_and_CodAplicacion($idDispositivo, $usuario, $password, $codApp);
			if(empty($user)) {
				$this->response('NOK', 200);
			} else {
				$this->response('OK', 200);
			}
			*/
			$user = Usuario::find_by_email_and_password($email, $password);
			
			if(empty($user)) {
				$this->logg->error("1001: Usuario no encontrado en loginusuario ".$email. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1001", "mensaje" => "Usuario no Encontrado.");
				$this->response($this->json($result), 200);
			} else {
				$user->access_token_movil    = md5($this->udate('Y-m-d H:i:s:u'));
				$user->save();
				//$this->response('OK', 200);
				$result = array("estado" => "OK", "codResp" => "1002", "token" => $user->access_token_movil);
				$this->response($this->json($result), 200);
			}
			
		}
		
		public function logoutusuario() {
			
		}
		
		public function consultadispositivo() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$token_usuario = $datos[0];
			$idDispositivo = $datos[1];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);
			if(empty($user)) {
				$this->logg->error("1011: Usuario no Autorizado en consultadispositivo ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1011", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo = Dispositivo::find_by_id_dispositivo_and_usuario_id($idDispositivo, $user->id);
				if(empty($dispositivo)) {
					$this->logg->error("1012: Dispositivo No Asociado al Usuario en consultadispositivo ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1012", "mensaje" => "Dispositivo No Asociado al Usuario.");
					$this->response($this->json($result), 200);
				} else {
					$result = array("estado" => "OK", "codResp" => "1013", "mensaje" => "Dispositivo Asociado al Usuario.");
					$this->response($this->json($result), 200);
				}
			}
		}
		
		public function logindispositivo() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$token_usuario = $datos[0];
			$idDispositivo = $datos[1];
			$pin = $datos[2];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);
			//$user = Usuario::all(array('conditions' => array('usuario = ?', $usuario)));
			if(empty($user)) {
				$this->logg->error("1014: Usuario no Autorizado en logindispositivo ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1014", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivoAsoc = Dispositivo::find_by_id_dispositivo_and_usuario_id($idDispositivo, $user->id);
				if(empty($dispositivoAsoc)) {
					$this->logg->error("1015: Dispositivo No Asociado al Usuario en logindispositivo ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1015", "mensaje" => "Dispositivo No Asociado al Usuario.");
					$this->response($this->json($result), 200);
				} else {
					if($dispositivoAsoc->pin != $pin) {
						$this->logg->error("1016: Dispositivo No Autorizado en logindispositivo PIN incorrecto con dispositivo ".$idDispositivo);
						$result = array("estado" => "NOK", "codResp" => "1016", "mensaje" => "Dispositivo No Autorizado.");
						$this->response($this->json($result), 200);
					} else {
						$dispositivoAsoc->token    = md5($this->udate('Y-m-d H:i:s:u'));
						$dispositivoAsoc->save();
						//$this->response('OK', 200);
						$result = array("estado" => "OK", "codResp" => "1017", "token" => $dispositivoAsoc->token);
						$this->response($this->json($result), 200);
					}
				}
			}			
			
		}
		
		public function registrodispositivo() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$token_usuario = $datos[0];
			$idDispositivo = $datos[1];
			$pin = $datos[2];
			$os = $datos[3];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);

			if(empty($user)) {
				$this->logg->error("1018: Usuario no Autorizado en registrodispositivo ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1018", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo					= new Dispositivo();
				$dispositivo->id_dispositivo	= $idDispositivo;
				$dispositivo->pin				= $pin;
				$dispositivo->os				= $os;
				$dispositivo->estado			= "ACTIVO";
				$dispositivo->token				= md5($this->udate('Y-m-d H:i:s:u'));
				$dispositivo->usuario_id		= $user->id;
				$dispositivo->save();
				
				if(count($dispositivo->errors->full_messages()) != 0) {
					$this->logg->error("1019: Dispositivo No Asociado al Usuario en registrodispositivo ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1019", "mensaje" => "Dispositivo No Asociado.");
					$this->response($this->json($result), 200);
				} else {
					$result = array("estado" => "OK", "codResp" => "1020", "token" => $dispositivo->token);
					$this->response($this->json($result), 200);
				}
			}
		}
		
		public function logoutdispositivo() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$token_usuario		= $datos[0];
			$token_movil		= $datos[1];
			$idDispositivo 		= $datos[2];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);

			if(empty($user)) {
				$this->logg->error("1021: Usuario no Autorizado en logoutdispositivo ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1021", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo = Dispositivo::find_by_id_dispositivo_and_usuario_id_and_token($idDispositivo, $user->id, $token_movil);
				if(empty($dispositivo)) {
					$this->logg->error("1022: Dispositivo No Autorizado en logoutdispositivo ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1022", "mensaje" => "Dispositivo No Autorizado.");
					$this->response($this->json($result), 200);
				} else {
					$dispositivo->token	= "";
					$dispositivo->save();
					if(count($this->dispositivo->errors->full_messages()) != 0) {
						$this->logg->error("1023: Usuario no desloguado en logoutdispositivo ".$token_usuario. "con dispositivo ".$idDispositivo);
						$result = array("estado" => "NOK", "codResp" => "1023", "mensaje" => "No deslogueado.");
						$this->response($this->json($result), 200);
					} else {
						$result = array("estado" => "OK", "codResp" => "1024", "mensaje" => "Sesión Cerrada.");
						$this->response($this->json($result), 200);
					}
					
				}
			}
		}

		public function generatransaccion() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$monto = $datos[0];
			$numboleta = $datos[1];
			$token_usuario = $datos[2];
			$idDispositivo = $datos[3];
			$token_movil = $datos[4];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);
			
			if(empty($user)) {
				$this->logg->error("1025: Usuario no Autorizado en generatransaccion ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1025", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo = Dispositivo::find_by_id_dispositivo_and_usuario_id_and_token($idDispositivo, $user->id, $token_movil);

				if(empty($dispositivo)) {
					$this->logg->error("1026: Dispositivo No Autorizado al Usuario en registrodispositivo ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1026", "mensaje" => "Dispositivo No Autorizado.");
					$this->response($this->json($result), 200);
				} else {
					$date = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 3);

					$plaintext = $user->email."".$idDispositivo."".$date;

					$keyTransaccion = $this->encrypt($plaintext);

					while (strlen($keyTransaccion) < 32) {
						$keyTransaccion = "0" + $keyTransaccion;
					}

					$this->transaccion = new Transaccion();
					$this->transaccion->monto 				= $monto;
					$this->transaccion->numboleta			= $numboleta;	
					$this->transaccion->emisor	 			= $user->id;
					$this->transaccion->estado	 			= "PENDIENTE";
					$this->transaccion->dispositivo_emisor	= $dispositivo->id;
					$this->transaccion->keytransaccion 		= $keyTransaccion;
					$this->transaccion->elemento_id 		= "0";
					$this->transaccion->save();

					$this->cobro = new Cobro();
					$this->cobro->transaccion_id	= $this->transaccion->id;
					$this->cobro->dispositivo_id	= $this->transaccion->dispositivo_emisor;
					$this->cobro->estado 			= $this->transaccion->estado;
					$this->cobro->usuario_id		= $this->transaccion->emisor;
					$this->cobro->monto				= $this->transaccion->monto;
					$this->cobro->numboleta			= $this->transaccion->numboleta;
					$this->cobro->save();

					if(count($this->transaccion->errors->full_messages()) != 0) {
						$this->logg->error("1027: Transacción No Ingresada en generatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
						$result = array("estado" => "NOK", "codResp" => "1027", "mensaje" => "Transacción No Ingresada.");
						$this->response($this->json($result), 200);
					} else if(count($this->cobro->errors->full_messages()) != 0) { 
						$this->logg->error("1028: Cobro No Ingresado en generatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
						$result = array("estado" => "NOK", "codResp" => "1028", "mensaje" => "Cobro No Ingresado.");
						$this->response($this->json($result), 200);
					} else {
						//$this->response($keyTransaccion, 200);
						$result = array("estado" => "OK", "codResp" => "1029", "keyTransaccion" => $keyTransaccion);
						$this->response($this->json($result), 200);
					}
				}

			}

		}

		public function validatransaccion() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$token_usuario		= $datos[0];
			$token_movil		= $datos[1];
			$idDispositivo 		= $datos[2];
			$keyTransaccion 	= $datos[3];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);

			if(empty($user)) {
				$this->logg->error("1030: Usuario no Autorizado en validatransaccion ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1030", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo = Dispositivo::find_by_id_dispositivo_and_usuario_id_and_token($idDispositivo, $user->id, $token_movil);
				
				if(empty($dispositivo)) {
					$this->logg->error("1031: Dispositivo No Autorizado al Usuario en validatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1031", "mensaje" => "Dispositivo No Autorizado.");
					$this->response($this->json($result), 200);
				} else {

					$this->transaccion = Transaccion::find_by_keyTransaccion_and_estado($keyTransaccion, "PENDIENTE");
			
					if(empty($this->transaccion)) {
						$this->logg->error("1032: Transaccion No Encontrada en validatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
						$result = array("estado" => "NOK", "codResp" => "1032", "mensaje" => "Transaccion No Encontrada.");
						$this->response($this->json($result), 200);
					} else {
						$this->elementosUsuario = Elemento::find_all_by_usuario_id($user->id);
						if(empty($this->elementosUsuario)) {
							$this->logg->error("1033: No Se Encontraton Tarjetas en validatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
							$result = array("estado" => "NOK", "codResp" => "1033", "mensaje" => "No Se Encontraton Tarjetas.");
							$this->response($this->json($result), 200);
						} else {
							//$result = array("monto" => $this->transaccion->monto, "numboleta" => $this->transaccion->numboleta);
							$tarjetas = array();
							//array_push($result, {"monto" => $this->transaccion->monto});
							//array_push($result, {"numboleta" => $this->transaccion->numboleta});
							foreach($this->elementosUsuario as $elementosUsuario) {
								array_push($tarjetas, $elementosUsuario->to_json());
							}
							$result = array("estado" => "OK", "codResp" => "1034", "monto" => $this->transaccion->monto, "numboleta" => $this->transaccion->numboleta, "tarjetas" => $tarjetas);
							$this->response($this->json($result), 200);
						}
					}
				}
			}
		}
		
		public function buscaestadotransaccion() {
				$hash = $_GET['id'];
				$decrypted = $this->decrypt($hash);

				$datos = explode('|', $decrypted);

				$keyTransaccion	 		= $datos[0];

				$this->transaccion = Transaccion::find_by_keyTransaccion($keyTransaccion);

				if(empty($this->transaccion)) {
					$this->logg->error("1061: No Se Encontro Transaccion en buscaestadotransaccion ".$keyTransaccion." ");
					$result = array("estado" => "NOK", "codResp" => "1061", "mensaje" => "No Se Encontro Transaccion.");
					$this->response($this->json($result), 200);
				} else {
					//$this->response('OK', 200);
					//$this->response($this->transaccion->estado, 200);
					$result = array("estado" => $this->transaccion->estado);
					$this->response($this->json($result), 200);
				}
			}

		public function autorizatransaccion() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$numTarjeta 			= $datos[0];
			$keyTransaccion	 		= $datos[1];
			$token_usuario			= $datos[2];
			$token_movil			= $datos[3];
			$idDispositivo 			= $datos[4];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);
			
			if(empty($user)) {
				$this->logg->error("1035: Usuario no Autorizado en autorizatransaccion ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1035", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo = Dispositivo::find_by_id_dispositivo_and_usuario_id_and_token($idDispositivo, $user->id, $token_movil);

				if(empty($dispositivo)) {
					$this->logg->error("1036: Dispositivo No Autorizado al Usuario en autorizatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1036", "mensaje" => "Dispositivo No Autorizado.");
					$this->response($this->json($result), 200);
				} else {
					$transaccion = Transaccion::find_by_keyTransaccion($keyTransaccion);
					
					if(empty($transaccion)) {
						$this->logg->error("1037: Transaccion No Existe en autorizatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
						$result = array("estado" => "NOK", "codResp" => "1037", "mensaje" => "Transaccion No Existe.");
						$this->response($this->json($result), 200);
					} else {
						if($transaccion->estado == "PENDIENTE") {
							$tarjeta = Elemento::find_by_id($numTarjeta);
							if(empty($tarjeta)) {
								$this->logg->error("1038: Elemento de Pago No Existe en autorizatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
								$result = array("estado" => "NOK", "codResp" => "1038", "mensaje" => "Elemento de Pago No Existe.");
								$this->response($this->json($result), 200);
							} else {
								$transaccion->estado				= "APROBADO";
								$transaccion->elemento_id			= $tarjeta->id;
								$transaccion->pagador				= $user->id;
								$transaccion->dispositivo_pagador	= $dispositivo->id;
								$transaccion->save();


								if(count($transaccion->errors->full_messages()) != 0) {
									$this->logg->error("1039: Transaccion No Actualizada en autorizatransaccion ".$user->id. "con dispositivo ".$idDispositivo);
									$result = array("estado" => "NOK", "codResp" => "1039", "mensaje" => "Transaccion No Actualizada.");
									$this->response($this->json($result), 200);
								} else {
									$cobro = Cobro::find_by_transaccion_id($transaccion->id);
									if(empty($cobro)) {
										$result = array("estado" => "NOK", "codResp" => "10391", "mensaje" => "Cobro No Encontrado.");
										$this->response($this->json($result), 200);
									} else {
										$cobro->estado = "APROBADO";
										$cobro->save();

										if(count($cobro->errors->full_messages()) != 0) {
											$result = array("estado" => "NOK", "codResp" => "1040", "mensaje" => "Cobro No Actualizado.");
											$this->response($this->json($result), 200);
										} else {
											$pago = new Pago();
											$pago->transaccion_id	= $transaccion->id;
											$pago->dispositivo_id	= $transaccion->dispositivo_pagador;
											$pago->usuario_id		= $transaccion->pagador;
											$pago->elemento_id		= $transaccion->elemento_id;
											$pago->cobro_id			= $cobro->id;
											$pago->save();

											if(count($pago->errors->full_messages()) != 0) {
												$result = array("estado" => "NOK", "codResp" => "1041", "mensaje" => "Pago No Ingresado.");
												$this->response($this->json($result), 200);
											} else {
												$result = array("estado" => "OK", "codResp" => "1042", "mensaje" => "Pago Realizado.");
												$this->response($this->json($result), 200);
											}
										}
									}//Fin else cobro
								} //fin else transaccion
							}//Fin else tarjeta
						} else {
							$result = array("estado" => "NOK", "codResp" => "1043", "mensaje" => "Transaccion ya se Encuentra Cancelada.");
							$this->response($this->json($result), 200);
						}
					}
				}
			}
		}
		
		public function listatarjetas() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$token_usuario		= $datos[0];
			$token_movil		= $datos[1];
			$idDispositivo 		= $datos[2];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);

			if(empty($user)) {
				$this->logg->error("1044: Usuario no Autorizado en validatransaccion ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1044", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo = Dispositivo::find_by_id_dispositivo_and_usuario_id_and_token($idDispositivo, $user->id, $token_movil);
				
				if(empty($dispositivo)) {
					$this->logg->error("1045: Dispositivo No Autorizado al Usuario en registrodispositivo ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1045", "mensaje" => "Dispositivo No Autorizado.");
					$this->response($this->json($result), 200);
				} else {
					$this->tarjetasUsuario = Elemento::find_all_by_usuario_id_and_tipo($user->id, "tarjeta");
					if(empty($this->tarjetasUsuario)) {
						$result = array("estado" => "NOK", "codResp" => "1046", "mensaje" => "No Se Encontraton Tarjetas.");
						$this->response($this->json($result), 200);
					} else {
						$tarjetas = array();
						foreach($this->tarjetasUsuario as $tarjetasUsuario) {
							array_push($tarjetas, $tarjetasUsuario->to_json());
						}
						$result = array("estado" => "OK", "codResp" => "1047", "tarjetas" => $tarjetas);
						$this->response($this->json($result), 200);
					}
				}
			}
		}
		
		public function listacuentas() {
			$hash = $_GET['id'];
			$decrypted = $this->decrypt($hash);

			$datos = explode('|', $decrypted);

			$token_usuario		= $datos[0];
			$token_movil		= $datos[1];
			$idDispositivo 		= $datos[2];
			
			$user = Usuario::find_by_access_token_movil($token_usuario);

			if(empty($user)) {
				$this->logg->error("1048: Usuario no Autorizado en listacuentas ".$token_usuario. "con dispositivo ".$idDispositivo);
				$result = array("estado" => "NOK", "codResp" => "1048", "mensaje" => "Usuario No Autorizado.");
				$this->response($this->json($result), 200);
			} else {
				$dispositivo = Dispositivo::find_by_id_dispositivo_and_usuario_id_and_token($idDispositivo, $user->id, $token_movil);
				
				if(empty($dispositivo)) {
					$this->logg->error("1049: Dispositivo No Autorizado al Usuario en registrodispositivo ".$user->id. "con dispositivo ".$idDispositivo);
					$result = array("estado" => "NOK", "codResp" => "1049", "mensaje" => "Dispositivo No Autorizado.");
					$this->response($this->json($result), 200);
				} else {
					$this->cuentasUsuario = Elemento::find_all_by_usuario_id_and_tipo($user->id, "cuenta");
					if(empty($this->cuentasUsuario)) {
						$result = array("estado" => "NOK", "codResp" => "1050", "mensaje" => "No Se Encontraton Cuentas Bancarias.");
						$this->response($this->json($result), 200);
					} else {
						$cuentas = array();
						foreach($this->cuentasUsuario as $cuentasUsuario) {
							array_push($cuentas, $cuentasUsuario->to_json());
						}
						$result = array("estado" => "OK", "codResp" => "1051", "cuentas" => $cuentas);
						$this->response($this->json($result), 200);
					}
				}
			}
		}
		
		private function udate($format, $utimestamp = null) {
		  if (is_null($utimestamp))
		    $utimestamp = microtime(true);

		  $timestamp = floor($utimestamp);
		  $milliseconds = round(($utimestamp - $timestamp) * 1000000);

		  return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
		}
	}
?>