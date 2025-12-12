<?php
require_once 'models/UserModel.php';
require_once 'libs/jwt.php';

class AuthApiController {
    private $userModel;
    private $view;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function getToken($request = null) {

        $auth_header = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
        
        if (!$auth_header && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        $auth_array = explode(' ', $auth_header);
        if (count($auth_array) != 2 || $auth_array[0] !== 'Basic') {
            $this->view->response("No se proporcionaron credenciales de autenticación", 401);
            return;
        }

        $user_pass = base64_decode($auth_array[1]);
        $user_pass_array = explode(':', $user_pass);
        
        if (count($user_pass_array) != 2) {
            $this->view->response("Formato de credenciales inválido", 401);
            return;
        }

        $email = $user_pass_array[0];
        $password = $user_pass_array[1];

        $userFromDB = $this->userModel->getUserByEmail($email);

        if ($userFromDB && password_verify($password, $userFromDB->password)) {
            // se generta el token
            $token_data = [
                'sub' => $userFromDB->id, // ID del usuario
                'email' => $userFromDB->email,
                'iat' => time(), // emitido ahora
                'exp' => time() + 3600 // expira en 1 hora
            ];
            
            $token = createJWT($token_data);
            
            // devuelvo el token
            $this->view->response(['token' => $token], 200);
        } else {
            $this->view->response("Usuario o contraseña incorrectos", 401);
        }
    }
}