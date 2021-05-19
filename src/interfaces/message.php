<?php
namespace App\Plugins\zero\src\interfaces;

interface message{
    public function register(object $data,array $value);
    public function boot();
}