<?php 

	require 'Model.php';	

	class Controller {
		private $model;

		public function __construct() {
			$this->model = new Model;
		}

		// Inicio funciones de SESION DE USUARIO
		public function login() {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

				$email = $_POST['email'];
				$password = $_POST['password'];

				$user_existe = $this->model->traerPorEmail($email);

				if ($user_existe) {
					$user_rol = $user_existe->email;
					$user_pass = $user_existe->clave;

					if ($password == $user_pass) {

						$this->crearSesion($user_existe);

					} else {
					  // Error! Contraseña incorrecta."
			    	header("Location: index.php?err=1");
					}

				} else {
					// Error! Usuario no registrado."
			    header("Location: index.php?err=2");
				}

			}
		}

		public function crearSesion($user) {
			$_SESSION['user_id'] = $user->idusuarios;
			$_SESSION['user_usuario'] = $user->usuario;
			$_SESSION['user_rol'] = $user->rol;
			$_SESSION['user_nombre'] = $user->nombre_apellido;
			$_SESSION['user_email'] = $user->email;


			if ($user->rol == 1) {
				header('location: ' . URLROOT . '/views/admin/');
			}

			if ($user->rol == 2) {
				header('location: ' . URLROOT .  '/views/vendedor/');
			}
		}

		public function logout() {
			unset($_SESSION['user_id']);
			unset($_SESSION['user_usuario']);
			unset($_SESSION['user_rol']);
			unset($_SESSION['user_nombre']);
			unset($_SESSION['user_email']);
			session_destroy();
			header("Location: " . URLROOT);
		}

		public function readRooms() {
			return  $this->model->readRooms();
		}
		// fin funciones de SESION DE USUARIO


		// inicio funciones para tabla usuarios
		public function createUsuario($nombre, $email, $usuario) {
			$created = $this->model->createUsuario($nombre, $email, $usuario);
			return $created;
		}

		public function readTodosUsuarios() {
			$usuarios = $this->model->readTodosUsuarios();
			return $usuarios;
		}
		// fin funciones para tabla usuarios



	}
?>