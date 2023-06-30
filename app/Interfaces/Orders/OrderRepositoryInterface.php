<?php

namespace App\Interfaces\Orders;

interface OrderRepositoryInterface
{
    public function index($request);
    public function store($data);
    public function update($request, $id);
    public function export($id);
    public function exportTransfer($id);
}
