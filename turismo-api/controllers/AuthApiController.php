<?php
require_once 'models/UserModel.php';
require_once 'libs/jwt/jwt.php';

class AuthApiController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function getToken($request, $response) {
        // Leer el ecabezado Authorization
        $authorization = $request->authorization;

        // Chequear que el encabezado sea 'Basic base64(user:pass)'
        $auth = explode(' ', $authorization);
        if (count($auth) != 2 || $auth[0] !== 'Basic') {
            // Si no es correcto, devolver error 401 Unauthorized
            header("WWW-Authenticate: Basic realm='Get a token'");
            return $response->json("Autenticación no valida", 401);
        }

        $auth = base64_decode($auth[1]); // "user:pass"
        $user_pass = explode(":", $auth);
        if (count($user_pass) != 2) {
            // Si no es correcto, devolver error 401 Unauthorized
            return $response->json("Autenticación no valida", 401);
        }

        $user = $user_pass[0];
        $password = $user_pass[1];
        // Buscar el usuario en la DB
        $userFromDB = $this->userModel->getUserByEmail($user);
        
        // verificar la contreaseña con la de la base (hasheada)
        if(!$userFromDB || !password_verify($password, $userFromDB->password)) {
            return $response->json("Usuario o contraseña incorrecta", 401);
        }


        $roles = [];
        
        if ($userFromDB->rol) {
            $roles[] = $userFromDB->rol;
        }


        // Si el usuario no tiene roles, le damos uno por defecto para que no rompa
        if (empty($roles)) {
            $roles[] = 'USER'; 
        }

        // Si todo está bien, devolvemos un token
        $payload = [
            'sub' => $userFromDB->id_usuario,
            'email' => $userFromDB->email, 
            'roles' => $roles,            
            'exp' => time() + 3600
        ];

        return $response->json(createJWT($payload));
    }
}