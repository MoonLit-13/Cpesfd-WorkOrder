<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\WorkOrderModel;

class WorkOrderController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new WorkOrderModel();
    }

    public function index() {
        $workOrders = $this->model->getAllWorkOrders();
        $this->loadView('dashboard', ['workOrders' => $workOrders]);
    }

    public function create() {
        $this->loadView('form');
    }

    public function store() {
        $this->model->createWorkOrder($_POST);
        header("Location: /");
    }

    public function edit($id) {
        $workOrder = $this->model->getWorkOrderById($id);
        $this->loadView('update', ['workOrder' => $workOrder]);
    }

    public function update($id) {
        $this->model->updateWorkOrder($id, $_POST);
        header("Location: /");
    }

    public function delete($id) {
        $this->model->deleteWorkOrder($id);
        header("Location: /");
    }
}

