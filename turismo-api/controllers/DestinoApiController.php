<?php
require_once 'models/DestinoModel.php';
require_once 'models/RegionModel.php';
require_once 'models/UserModel.php';
require_once 'libs/jwt/jwt.middleware.php';


class DestinoApiController {
    private $modelDestino;
    private $modelRegion;
    private $authHelper;

    public function __construct() {
        $this->modelDestino = new DestinoModel();
        $this->modelRegion = new RegionModel();
        $this->authHelper = new JwtMiddleware();
    }

    //MÉTODOS PÚBLICOS (GET)

    public function getAll($request, $response) {
        // 1. Paginación
        $page = isset($request->query->page) && (is_numeric($request->query->page)) && $request->query->page > 0 ? $request->query->page : null;
        $limit = isset($request->query->limit) && (is_numeric($request->query->limit)) ? $request->query->limit : null;
        $offset = ($page && $limit) ? ($page - 1) * $limit : null;

        // 2. Filtro
        $regionFilter = isset($request->query->region) && (is_numeric($request->query->region)) ? $request->query->region : null;

        if ($limit !== null && $limit <= 0) {
            return $response->json('ERROR: El limite no puede ser menor o igual a 0', 400);
        }

        // 3. Ordenamiento
        $hasOrderBy = isset($request->query->orderby);
        $hasOrder = isset($request->query->order);
        $campo = null;
        $order = null;

        if ($hasOrderBy && $hasOrder) {
            if ($this->checkParams($request->query->orderby, $request->query->order)) {
                $campo = $request->query->orderby;
                $order = $request->query->order;
            } else {
                return $response->json('Error: parámetros de orden inválidos', 400);
            }
        } else if (($hasOrderBy && !$hasOrder) || (!$hasOrderBy && $hasOrder)) {
            return $response->json('Debés enviar ambos parámetros: orderby y order', 400);
        }

        $destinos = $this->modelDestino->getAll($limit, $offset, $regionFilter, $campo, $order);
        return $response->json($destinos, 200);
    }

    public function getDestino($request, $response) {
        $id = $request->params->ID;
        $destino = $this->modelDestino->getDestinoById($id);

        if ($destino) {
            return $response->json($destino, 200);
        } else {
            return $response->json("El destino con el id=$id no existe", 404);
        }
    }

    //MÉTODOS PROTEGIDOS (POST, PUT, DELETE)

    public function deleteDestino($request, $response) {
     

        $id = $request->params->ID;
        $destino = $this->modelDestino->getDestinoById($id);

        if ($destino) {
            $this->modelDestino->deleteDestino($id);
            return $response->json("El destino con id=$id fue eliminado con éxito", 200);
        } else {
            return $response->json("El destino con id=$id no existe", 404);
        }
    }

    public function createDestino($request, $response) {
        // Validación de campos (usando $request->body)
        if (!isset($request->body->nombre) || !isset($request->body->descripcion) || !isset($request->body->id_region_fk)) {
            return $response->json('Faltan campos obligatorios', 400);
        }

        $nombre = $request->body->nombre;
        $descripcion = $request->body->descripcion;
        $id_region = $request->body->id_region_fk;
        $img = isset($request->body->imagen_url) ? $request->body->imagen_url : null;

        // Validación FK
        if (!$this->modelRegion->getRegionById($id_region)) {
            return $response->json('La región indicada no existe', 404);
        }

        $id = $this->modelDestino->insertDestino($nombre, $descripcion, $img, $id_region);

        if ($id) {
            $nuevoDestino = $this->modelDestino->getDestinoById($id);
            return $response->json($nuevoDestino, 201);
        } else {
            return $response->json('Error al crear el destino', 500);
        }
    }

    public function updateDestino($request, $response) {
        $id = $request->params->ID;

        // Verificar existencia
        $destinoActual = $this->modelDestino->getDestinoById($id);
        if (!$destinoActual) {
            return $response->json("No existe el destino con id=$id", 404);
        }

        // Validación campos
        if (!isset($request->body->nombre) || !isset($request->body->descripcion) || !isset($request->body->id_region_fk)) {
            return $response->json('Faltan campos obligatorios', 400);
        }

        $nombre = $request->body->nombre;
        $descripcion = $request->body->descripcion;
        $id_region = $request->body->id_region_fk;
        $img = isset($request->body->imagen_url) ? $request->body->imagen_url : $destinoActual->imagen_url;

        // Validación FK
        if (!$this->modelRegion->getRegionById($id_region)) {
            return $response->json('La región indicada no existe', 404);
        }

        $this->modelDestino->updateDestino($id, $nombre, $descripcion, $img, $id_region);

        return $response->json('Destino modificado con éxito', 200);
    }

    //MÉTODOS PRIVADOS

    private function checkParams($campo, $order) {
        $order = strtolower($order);
        if ($order !== 'asc' && $order !== 'desc') {
            return false;
        }

        $campo = strtolower($campo);
        $camposPermitidos = $this->modelDestino->getColumnas(); 
        
        foreach ($camposPermitidos as $c) {
            if ($campo === strtolower($c->COLUMN_NAME)) { 
                return true;
            }
        }
        return false;
    }
}