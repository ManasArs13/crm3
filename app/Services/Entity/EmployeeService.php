<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Models\Employee;

class EmployeeService implements EntityInterface
{
    private Option $options;

    public MoySkladService $service;

    public function __construct(Option $options, MoySkladService $service)
    {
        $this->service = $service;
        $this->options = $options;
    }

    /**
     * @param array $rows
     * @return void
     */
    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {

            $entity = Employee::query()->firstOrNew(['ms_id' => $row["id"]]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->firstName = array_key_exists('firstName', $row) ? $row['firstName'] : null;
            $entity->middleName = array_key_exists('middleName', $row) ? $row['middleName'] : null;
            $entity->lastName = array_key_exists('lastName', $row) ? $row['lastName'] : null;
            $entity->fullName = array_key_exists('fullName', $row) ? $row['fullName'] : null;
            $entity->shortFio = array_key_exists('shortFio', $row) ? $row['shortFio'] : null;
            $entity->position = array_key_exists('position', $row) ? $row['position'] : null;
            $entity->email = array_key_exists('email', $row) ? $row['email'] : null;
            $entity->phone = array_key_exists('phone', $row) ? $row['phone'] : null;
            $entity->salary = array_key_exists('salary', $row) ? $row['salary']['value'] : null;
            $entity->uid = array_key_exists('uid', $row) ? $row['uid'] : null;
            $entity->archived = array_key_exists('archived', $row) ? $row['archived'] : false;

            $entity->save();
        }
    }
}
