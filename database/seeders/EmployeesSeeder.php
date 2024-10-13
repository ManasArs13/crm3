<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EmployeesSeeder extends Seeder
{
  public function run(): void
  {
    DB::table('employees')->insert(
      [
        array(
          'id' => 1,
          'created_at' => '2024-07-31 13:48:30',
          'updated_at' => '2024-08-01 18:50:25',
          'name' => 'Диспетчер 2',
          'firstName' => 'NULL',
          'middleName' => 'NULL',
          'lastName' => 'Диспетчер 2',
          'fullName' => 'Диспетчер 2',
          'shortFio' => 'Диспетчер 2',
          'position' => 'Менеджер',
          'email' => 'euroblock82@mail.ru',
          'phone' => 'NULL',
          'salary' => 0,
          'uid' => 'manager@euroblock82',
          'archived' => 0,
          'ms_id' => '199e0d70-dcff-11ec-0a80-08e200115889'
        ),
        array(
          'id' => 2,
          'created_at' => '2024-07-31 13:48:30',
          'updated_at' => '2024-08-01 18:50:25',
          'name' => 'Менеджер Ярослав',
          'firstName' => 'NULL',
          'middleName' => 'NULL',
          'lastName' => 'Менеджер Ярослав',
          'fullName' => 'Менеджер Ярослав',
          'shortFio' => 'Менеджер Ярослав',
          'position' => 'NULL',
          'email' => 'armx@yandex.ru',
          'phone' => 'NULL',
          'salary' => 0,
          'uid' => 'man2@euroblock82',
          'archived' => 0,
          'ms_id' => '365d78dd-32cd-11ef-0a80-0383000a3faf'
        ),
        array(
          'id' => 3,
          'created_at' => '2024-07-31 13:48:30',
          'updated_at' => '2024-08-01 18:50:25',
          'name' => 'Севак Х. Х.',
          'firstName' => 'Х',
          'middleName' => 'Х',
          'lastName' => 'Севак',
          'fullName' => 'Х Х Севак',
          'shortFio' => 'Севак Х. Х.',
          'position' => 'NULL',
          'email' => 'armx@yandex.ru',
          'phone' => 79782205008,
          'salary' => 0,
          'uid' => 'armx@euroblock82',
          'archived' => 0,
          'ms_id' => '71dc22de-ce56-11ea-0a80-04490010bd0e'
        ),
        array(
          'id' => 4,
          'created_at' => '2024-07-31 13:48:30',
          'updated_at' => '2024-08-01 18:50:25',
          'name' => 'Зайцева А. А.',
          'firstName' => 'Анастасия',
          'middleName' => 'Андреевна',
          'lastName' => 'Зайцева',
          'fullName' => 'Анастасия Андреевна Зайцева',
          'shortFio' => 'Зайцева А. А.',
          'position' => 'Бухгалтер',
          'email' => 'sigareva27@gmail.com',
          'phone' => 'NULL',
          'salary' => 0,
          'uid' => 'bukh@euroblock82',
          'archived' => 0,
          'ms_id' => '88cd1e30-f5ef-11ec-0a80-0c6600287c0e'
        ),
        array(
          'id' => 5,
          'created_at' => '2024-07-31 13:48:30',
          'updated_at' => '2024-08-01 18:50:25',
          'name' => 'Айк Х. Х.',
          'firstName' => 'Х',
          'middleName' => 'Х',
          'lastName' => 'Айк',
          'fullName' => 'Х Х Айк',
          'shortFio' => 'Айк Х. Х.',
          'position' => 'NULL',
          'email' => 'euroblock82@yandex.ru',
          'phone' => +79782205004,
          'salary' => 0,
          'uid' => 'admin@euroblock82',
          'archived' => 0,
          'ms_id' => '8feefcc4-7c10-11e7-7a6c-d2a9003ab423'
        ),
        array(
          'id' => 6,
          'created_at' => '2024-07-31 13:48:31',
          'updated_at' => '2024-08-01 18:50:26',
          'name' => 'crm',
          'firstName' => 'NULL',
          'middleName' => 'NULL',
          'lastName' => 'crm',
          'fullName' => 'crm',
          'shortFio' => 'crm',
          'position' => 'NULL',
          'email' => 'NULL',
          'phone' => 'NULL',
          'salary' => 0,
          'uid' => 'crm@euroblock82',
          'archived' => 0,
          'ms_id' => '91d44a79-87fd-11ec-0a80-0fbe002fdbc5'
        ),
        array(
          'id' => 7,
          'created_at' => '2024-07-31 13:48:31',
          'updated_at' => '2024-08-01 18:50:26',
          'name' => 'Менеджер Екатерина',
          'firstName' => 'NULL',
          'middleName' => 'NULL',
          'lastName' => 'Менеджер Екатерина',
          'fullName' => 'Менеджер Екатерина',
          'shortFio' => 'Менеджер Екатерина',
          'position' => 'NULL',
          'email' => 'armx2@mail.ru',
          'phone' => 'NULL',
          'salary' => 0,
          'uid' => 'm2@euroblock82',
          'archived' => 0,
          'ms_id' => 'aae22778-b1af-11ed-0a80-08f2000567fb'
        ),
        array(
          'id' => 8,
          'created_at' => '2024-07-31 13:48:31',
          'updated_at' => '2024-08-01 18:50:26',
          'name' => 'Диспетчер 1',
          'firstName' => 'NULL',
          'middleName' => 'NULL',
          'lastName' => 'Диспетчер 1',
          'fullName' => 'Диспетчер 1',
          'shortFio' => 'Диспетчер 1',
          'position' => 'NULL',
          'email' => 'armx2@mail.ru',
          'phone' => 'NULL',
          'salary' => 0,
          'uid' => 'nastya@euroblock82',
          'archived' => 0,
          'ms_id' => 'dbe37a22-caa1-11ec-0a80-06140041bd72'
        ),
        array(
          'id' => 9,
          'created_at' => '2024-07-31 13:48:31',
          'updated_at' => '2024-08-01 18:50:26',
          'name' => 'Гульмира Бухгалтер',
          'firstName' => 'NULL',
          'middleName' => 'NULL',
          'lastName' => 'Гульмира Бухгалтер',
          'fullName' => 'Гульмира Бухгалтер',
          'shortFio' => 'Гульмира Бухгалтер',
          'position' => 'NULL',
          'email' => 'armx@yandex.ru',
          'phone' => 'NULL',
          'salary' => 0,
          'uid' => 'lilya@euroblock82',
          'archived' => 0,
          'ms_id' => 'e3c41f07-f0b7-11ee-0a80-0ce300051d5b'
        )
      ]
    );
  }
}
