<?php
namespace App\Contracts;

interface EntityInterface
{
    public function import(array $rows);
}
