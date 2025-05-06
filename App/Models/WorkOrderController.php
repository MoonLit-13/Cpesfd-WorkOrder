<?php

namespace App\Models;

use Core\Database;

class WorkOrderModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAllWorkOrders() {
        $stmt = $this->db->query("SELECT * FROM work_orders ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getWorkOrderById($id) {
        $stmt = $this->db->prepare("SELECT * FROM work_orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createWorkOrder($data) {
        $stmt = $this->db->prepare("
            INSERT INTO work_orders (company_name, street, city, zip, phone, fax, email, job_description, bill_name, bill_company, bill_address, bill_city, bill_phone, ship_name, ship_company, ship_address, ship_city, ship_phone, completed_date, signature, date)
            VALUES (:company_name, :street, :city, :zip, :phone, :fax, :email, :job_description, :bill_name, :bill_company, :bill_address, :bill_city, :bill_phone, :ship_name, :ship_company, :ship_address, :ship_city, :ship_phone, :completed_date, :signature, :date)
        ");
        $stmt->execute($data);
    }

    public function updateWorkOrder($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE work_orders SET company_name = :company_name, street = :street, city = :city, zip = :zip, phone = :phone, fax = :fax, email = :email, job_description = :job_description, bill_name = :bill_name, bill_company = :bill_company, bill_address = :bill_address, bill_city = :bill_city, bill_phone = :bill_phone, ship_name = :ship_name, ship_company = :ship_company, ship_address = :ship_address, ship_city = :ship_city, ship_phone = :ship_phone, completed_date = :completed_date, signature = :signature, date = :date WHERE id = :id
        ");
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function deleteWorkOrder($id) {
        $stmt = $this->db->prepare("DELETE FROM work_orders WHERE id = ?");
        $stmt->execute([$id]);
    }
}

