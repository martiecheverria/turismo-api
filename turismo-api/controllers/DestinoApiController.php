<?php
require_once __DIR__ . '/../models/DestinoModel.php';
require_once __DIR__ . '/../models/RegionModel.php';
require_once __DIR__ . '/../views/ApiView.php';

class DestinoApiController
{
    private $modelDestino;
    private $modelRegion;
    private $view;

    public function __construct()
    {
        $this->modelDestino = new DestinoModel();
        $this->modelRegion = new RegionModel();
        $this->view = new ApiView();
    }

    function getAll($request, $response)
    {
        $page = isset($request->query->page) && (is_numeric($request->query->page)) && $request->query->page > 0 ? $request->query->page : null;
        $limit = isset($request->query->limit) && (is_numeric($request->query->limit)) ? $request->query->limit : null;
        $offset = ($page && $limit) ? ($page - 1) * $limit : null;


        $regionFilter = isset($request->query->region) && (is_numeric($request->query->region)) ? $request->query->region : null;

        if ($limit !== null && $limit <= 0) {
            return $this->view->response('ERROR: El limite no puede ser menor o igual a 0', 400);
        }

        $hasOrderBy = isset($request->query->orderby);
        $hasOrder = isset($request->query->order);

        if ($hasOrderBy && $hasOrder) {

            if ($this->checkParams($request->query->orderby, $request->query->order)) {
                $campo = $request->query->orderby;
                $order = $request->query->order;
            } else {
                return $this->view->response('Error: parámetros de orden inválidos', 400);
            }
        } else if (($hasOrderBy && !$hasOrder) || (!$hasOrderBy && $hasOrder)) {
            return $this->view->response('Debés enviar ambos parámetros: orderby y order', 400);
        } else {
 
            $campo = null;
            $order = null;
        }

        $destinos = $this->modelDestino->getAll($limit, $offset, $regionFilter, $campo, $order);
        return $this->view->response($destinos, 200);
    }


    private function checkParams($campo, $order)
    {
        $order = strtolower($order);
        if ($order !== 'asc' && $order !== 'desc') {
            return false;
        }

        $campo = strtolower($campo);
        $campos = $this->modelDestino->getColumnas(); 
        
        foreach ($campos as $c) {
            if ($campo === $c->COLUMN_NAME) {
                return true;
            }
        }
        return false;
    }


    function getDestino($request, $response)
    {
        $id = $request->params->id;

        $destino = $this->modelDestino->getDestinoById($id);
        if (!$destino) {
            return $this->view->response("No se encontró el destino con el id=$id", 404);
        }

        return $this->view->response($destino, 200);
    }

    function createDestino($request, $response)
    {
        if (!(isset($request->body->nombre) && isset($request->body->descripcion) && isset($request->body->id_region_fk))) {
            return $this->view->response('Faltan campos Obligatorios', 400);
        }

        $nombre = $request->body->nombre;
        $descripcion = $request->body->descripcion;
        $id_region = $request->body->id_region_fk;

        $img = isset($request->body->imagen_url) ? $request->body->imagen_url : null;

        if (!$this->modelRegion->getRegionById($id_region)) {
            return $this->view->response('La región indicada no existe', 404);
        }

        $id = $this->modelDestino->insertDestino($nombre, $descripcion, $img, $id_region);

        if ($id) {
            return $this->view->response('Destino Creado Correctamente', 201);
        } else {
            return $this->view->response('Error al crear el destino', 400);
        }
    }

    function updateDestino($request, $response)
    {
        $id = $request->params->id;

        if (!$this->modelDestino->getDestinoById($id)) {
            return $this->view->response('No existe el destino', 404);
        }

        if (!(isset($request->body->nombre) && isset($request->body->descripcion) && isset($request->body->id_region_fk))) {
            return $this->view->response('Faltan campos Obligatorios', 400);
        }

        $nombre = $request->body->nombre;
        $descripcion = $request->body->descripcion;
        $id_region = $request->body->id_region_fk;
        $img = isset($request->body->imagen_url) ? $request->body->imagen_url : null;

        if (strlen($nombre) > 255) {
            return $this->view->response('El nombre supera el límite de caracteres', 400);
        }

        if (!$this->modelRegion->getRegionById($id_region)) {
            return $this->view->response('No existe la región', 404);
        }

        $this->modelDestino->updateDestino($id, $nombre, $descripcion, $img, $id_region);

        return $this->view->response('Destino modificado con éxito!', 200);
    }

    function deleteDestino($request, $response)
    {
        $id = $request->params->id;

        $destino = $this->modelDestino->getDestinoById($id);

        if ($destino) {
            $this->modelDestino->deleteDestino($id);
            return $this->view->response("El destino con id=$id fue eliminado con éxito", 200);
        } else {
            return $this->view->response("El destino con id=$id no existe", 404);
        }
    }
}