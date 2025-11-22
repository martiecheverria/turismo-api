<?php
require_once 'models/RegionModel.php';
require_once 'views/ApiView.php';

class RegionApiController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new RegionModel();
        $this->view = new ApiView();
    }


    public function getRegiones() {
        $regiones = $this->model->getAllRegiones();
        $this->view->response($regiones, 200);
    }

  
    public function getRegion($params = null) {
        $id = $params[':ID'];
        $region = $this->model->getRegionById($id);

        if ($region) {
            $this->view->response($region, 200);
        } else {
            $this->view->response("La región con el id=$id no existe", 404);
        }
    }
}