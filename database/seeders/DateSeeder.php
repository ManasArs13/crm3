<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateSeeder extends Seeder
{
    public function run(): void
    {
        $sql = <<<SQL

            DROP PROCEDURE IF EXISTS filldates;

            CREATE PROCEDURE filldates(dateStart DATE, dateEnd DATE)

            BEGIN

            DECLARE adate date;

                WHILE dateStart <= dateEnd DO

                    SET adate = (SELECT date FROM dates WHERE date = dateStart);

                    IF adate IS NULL THEN BEGIN

                        INSERT INTO dates (date, is_active) VALUES (dateStart, 1);

                    END; END IF;

                    SET dateStart = date_add(dateStart, INTERVAL 1 DAY);

                END WHILE;

            END;

            CALL filldates('2024-09-06','2024-12-31');

        SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}
