<?php
require_once 'models/DestinoModel.php';
require_once 'views/ApiView.php';

class DestinoApiController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new DestinoModel();
        $this->view = new ApiView();
    }

    public function getDestinos() {
        $destinos = $this->model->getAllDestinosConRegion();
        $this->view->response($destinos, 200);
    }

  
    public function getDestino($params = null) {
        $idDestino = $params[':ID'];
        $destino = $this->model->getDestinoById($idDestino);

        if ($destino) {
            $this->view->response($destino, 200);
        } else {
            $this->view->response("El destino con el id=$idDestino no existe", 404);
        }
    }
}