<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [
                'name' => 'Длина забора',
                'code' => 'fence_length',
                'value' => 8,
                'module' => 'calc'
            ],
            [
                'name' => 'Количество столбов',
                'code' => 'number_of_columns',
                'value' => 2,
                'module' => 'calc'
            ],
            [
                'name' => 'Цвет по умолчанию',
                'code' => 'color_id',
                'value' => 'f42821f7-6c62-11eb-0a80-047c001ecb72',
                'module' => 'calc'
            ],
            [
                'name' => 'Основное: Логин для МойСклад',
                'code' => 'ms_login',
                'value' => 'crm@euroblock82',
                'module' => 'ms'
            ],
            [
                'name' => 'Основное: Пароль для МойСклад',
                'code' => 'ms_password',
                'value' => 'crm123456',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Адрес, с которого будем забирать товары',
                'code' => 'ms_product_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/assortment/',
                'module' => 'ms'
            ],
            [
                'name' => 'Группа: Адрес, с которого будем забирать группы товаров',
                'code' => 'ms_productfolder_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/productfolder/',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Адрес, с которого будем забирать метаданные',
                'code' => 'ms_attributes_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes/',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Адрес заказа в мс',
                'code' => 'ms_orders_ms_url',
                'value' => 'https://api.moysklad.ru/app/#customerorder/edit?id=',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Адрес, с которого будем забирать заказы',
                'code' => 'ms_orders_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/',
                'module' => 'ms'
            ],
            [
                'name' => 'Основное: Дата конца последней выгрузки',
                'code' => 'ms_date_last_change',
                'value' => '2023-06-01 10:52:08',
                'module' => 'ms'
            ],
            [
                'name' => 'Основное: Максимальное число записей загружаемых из МойСклад за 1 шаг.',
                'code' => 'ms_limit',
                'value' => 5,
                'module' => 'ms'
            ],
            [
                'name' => 'Статусы заказа: Адрес, с которого будем брать статусы заказа',
                'code' => 'ms_orders_status_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/',
                'module' => 'ms'
            ],
            [
                'name' => 'Статусы заказа: Адрес, с которого будем брать нужные статусы заказа (для фильтра нужных заказов)',
                'code' => 'ms_orders_need_status_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/',
                'module' => 'ms'
            ],
            [
                'name' => 'Основное: Количество позиции на одной странице',
                'code' => 'count_page',
                'value' => 1000,
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Адрес аттрибутов',
                'code' => 'ms_attributes_order_date_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Гуид аттрибута "Вероятная дата готовности" МС',
                'code' => 'ms_order_date_fact_guid',
                'value' => '5f6f96d1-2c17-11ec-0a80-08720022e099',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Гуид аттрибута "Долг" МС" МС',
                'code' => 'ms_order_debt_guid',
                'value' => '7f5a6fa9-833b-11ec-0a80-05af0053b26c',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Гуид аттрибута "Штук в паллете"',
                'code' => 'ms_count_pallets_guid',
                'value' => '0aae9e10-7779-11ec-0a80-0067002d23ed',
                'module' => 'ms'
            ],
            [
                'name' => 'Перевозчик: Адрес, с которого будем забирать справочник "Перевозчик"',
                'code' => 'ms_carriers_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customentity/d6afeece-5ad3-11ea-0a80-05280010f6ec/',
                'module' => 'ms'
            ],
            [
                'name' => 'Доставка: Адрес, с которого будем забирать справочник "Доставка"',
                'code' => 'ms_delivery_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customentity/8b306150-5c8b-11ea-0a80-02ed000aa214/',
                'module' => 'ms'
            ],
            [
                'name' => 'Транспорт: Адрес, с которого будем забирать справочник "Транспорт"',
                'code' => 'ms_transport_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customentity/45afdcd7-88cb-11ec-0a80-0e2e000bdce1/',
                'module' => 'ms'
            ],
            [
                'name' => 'Категория ТС: Адрес, с которого будем забирать справочник "Категория ТС"',
                'code' => 'ms_vehicle_type_url',
                'value' => 'https://online.moysklad.ru/api/remap/1.2/entity/customentity/86e0e802-8d7e-11ec-0a80-05e6002ff525/',
                'module' => 'ms'
            ],
            [
                'name' => 'Основное: Дата начала последней выгрузки',
                'code' => 'ms_date_begin_change',
                'value' => '2023-11-23 16:00:39',
                'module' => 'ms'
            ],
            [
                'name' => 'Контрагент: Адрес контграгента в мс',
                'code' => 'ms_counterparty_ms_url',
                'value' => 'https://api.moysklad.ru/#company/edit?id=',
                'module' => 'ms'
            ],
            [
                'name' => 'Контрагент: Адрес, с которого будем брать контрагентов',
                'code' => 'ms_counterparty_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/',
                'module' => 'ms'
            ],
            [
                'name' => 'Цена: Адрес, с которого будем брать типы цен ',
                'code' => 'ms_prices_type_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/context/companysettings/pricetype/',
                'module' => 'ms'
            ],
            [
                'name' => "Техкарта",
                'code' => 'ms_tech_chart_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/processingplanfolder',
                'module' => 'ms'
            ],
            [
                'name' => "Техоперации",
                'code' => 'ms_processings_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/processing',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Адрес, с которого будем брать товары для заказа',
                'code' => 'ms_product_for_order_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/product/',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Гуид аттрибута "Паллет МС',
                'code' => 'ms_order_pallet_guid',
                'value' => '6da20bbc-88bf-11ec-0a80-0fc30009470e',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Гуид аттрибута "Вес заказа МС',
                'code' => 'ms_order_weight_guid',
                'value' => '6da208f6-88bf-11ec-0a80-0fc30009470d',
                'module' => 'ms'
            ],
            [
                'name' => 'Организация: Адрес, с которого будем забирать организации',
                'code' => 'ms_organization_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/organization/',
                'module' => 'ms'
            ],
            [
                'name' => 'Организация: Гуид организации ООО "Евроблок"',
                'code' => 'ms_organization_guid',
                'value' => '8ff8467d-7c10-11e7-7a6c-d2a9003ab44a',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Гуид аттрибута "Доставка" МС',
                'code' => 'ms_order_delivery_guid',
                'value' => 'ebd3862f-5c92-11ea-0a80-0535000bb626',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Гуид аттрибута "Транспорт" МС',
                'code' => 'ms_order_transport_guid',
                'value' => 'e7011b28-a05a-11ec-0a80-0fae000aba81',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Гуид аттрибута "Краткое название" МС',
                'code' => 'ms_product_nameShort_guid',
                'value' => '10b19505-d6a7-11ec-0a80-029b002346f5',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Гуид аттрибута "Продвигаемые слова"',
                'code' => 'ms_product_keywords_guid',
                'value' => '48715267-d1d0-11eb-0a80-00a50020c61f',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Гуид аттрибута "Название страницы"',
                'code' => 'ms_product_title_guid',
                'value' => 'ab56b9d3-d009-11eb-0a80-08fd0006649b',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Гуид аттрибута "Товар"',
                'code' => 'ms_product_product_guid',
                'value' => '392ed4ab-2904-11ed-0a80-0f28001c8f53',
                'module' => 'ms'
            ],
            [
                'name' => 'Адрес, с которого будем забирать справочники',
                'code' => 'ms_customentity_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customentity/',
                'module' => 'ms'
            ],
            [
                'name' => 'Товар: Гуид аттрибута "Цвет"',
                'code' => 'ms_product_color_guid',
                'value' => '1628c018-6c6d-11eb-0a80-06e5001fdb92',
                'module' => 'ms'
            ],
            [
                'name' => 'Цвет: Адрес, с которого будем забирать справочник "Цвет"',
                'code' => 'ms_color_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customentity/ffc72cef-6c60-11eb-0a80-0761001f02a3',
                'module' => 'ms'
            ],
            [
                'name' => 'Тип ТС по умолчанию',
                'code' => 'vehicle_type_id',
                'value' => 'a4ee16dd-8d7e-11ec-0a80-0f9b002ff027',
                'module' => 'calc'
            ],
            [
                'name' => 'Контрагент: Адрес, с которого будем брать показатели контргагентов',
                'code' => 'ms_counterparty_report_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/report/counterparty/',
                'module' => 'ms'
            ],
            [
                'name' => 'Статусы заказа: Адрес, с которого будем брать статусы заказа',
                'code' => 'ms_orders_status_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/',
                'module' => 'main'
            ],
            [
                'name' => 'Контрагент: Гуид аттрибута "Ссылка на контакт в Amo" МС',
                'code' => 'ms_counterparty_amo_link_guid',
                'value' => 'bb95261f-972b-11ed-0a80-0e9300807fe0',
                'module' => 'ms'
            ],
            [
                'name' => 'Контрагент: Гуид аттрибута "Id контакта в Amo" МС',
                'code' => 'ms_counterparty_amo_id_contact_guid',
                'value' => 'bb952939-972b-11ed-0a80-0e9300807fe1',
                'module' => 'ms'
            ],
            [
                'name' => 'Основное: Округление числа паллет',
                'code' => 'round_number',
                'value' => '0.2',
                'module' => 'ms'
            ],
            [
                'name' => 'Базовый домен',
                'code' => 'base_domain',
                'value' => 'euroblock.amocrm.ru',
                'module' => 'amo'
            ],
            [
                'name' => 'Виджет: Ид интеграции',
                'code' => 'amo_widget_client_id',
                'value' => 'db9a7088-061e-4531-af59-958a37072fb9',
                'module' => 'amo'
            ],
            [
                'name' => 'Секретный ключ',
                'code' => 'client_secret',
                'value' => 'BaZUHQqB5IQuBoVTlFbrKoUGlSxnCN5I5uUXJX1yvkmGc9Ae3oRCFWmu0nZHfvQ4',
                'module' => 'amo'
            ],
            [
                'name' => 'Ид интеграции',
                'code' => 'client_id',
                'value' => '1a7d3bf6-c713-4221-b1c2-dabd66102fee',
                'module' => 'amo'
            ],
            [
                'name' => 'Код авторизации',
                'code' => 'code',
                'value' => 'def50200a2b82d1c6739172263d2f33d16d6300165b99b68cb96f7ff6ac716c9cc7e93d312441e108e34aa9b3a08d17ed103b0da9a575c348f90cbffdbbe1525f8b281f8f16c4f42e251102a381c1b1f7833f57c9e7ca137d408da185758656fc097ec0eadef323a8fb944fd7da1a94dafd20b18fc8714d50ecc4906ccf5dd7952d3babbdc07ac8986f62600d056910d5cb0db37fd48ab42d80ff609b45e34d7fad40d28678b8fee11217c72327099f38ef72b2a60d4f0e06aa3f5b2c84b1e59102a220e41dd28b9f778c41c57fcd69c65c856bf6d270ee7821ae9c6a5249aef0ec46d797d8d06953bc09a4376f0675bfe1acc8c17c906d8c0142796f1635bc6bf510f88ef589c1b79e92f6692e740fb287501bb7e7533c14989e9f83585275563c9e8bc0f03c55e734f8359496baac9dd02bd35ab5c4f89c589d7e7d65621a68ecea49e5d760a571616b27d0308f82eafb48bb7e1d87268a3da11d90e1e23ea920a174747c2b629c17a0e22dde726287238114c36fd900c0dce5a72ea63b79c1126c28411e591d06d1959fa21c03c01eb9067730e15b60c14aa87874adf1a392a553a4c1691e0221850e3d2978a5491968fe5687e49f21806d4bc8cb7b12a9b94cc5b5173864bfa090a60cd6f580f0741257661a01d9aa10219a54588a4de50c77c0c0663b094e4eee2490b304a70bb960cd1cb30b161366befa9c3601a',
                'module' => 'amo'
            ],
            [
                'name' => 'Сделки: Адрес, с которого будем забирать сделки',
                'code' => 'leads_url',
                'value' => '/api/v4/leads/',
                'module' => 'amo'
            ],
            [
                'name' => 'Компании: Адрес, с которого будем забирать компании',
                'code' => 'companies_url',
                'value' => '/api/v4/companies/',
                'module' => 'amo'
            ],
            [
                'name' => 'Базовый домен',
                'code' => 'base_domain',
                'value' => 'euroblock.amocrm.ru',
                'module' => 'amo'
            ],
            [
                'name' => 'Редирект',
                'code' => 'redirect_uri',
                'value' => 'https://euroblock82.ru/admin/amo/get_token/',
                'module' => 'amo'
            ],
            [
                'name' => 'Ид воронки',
                'code' => 'pipeline_id',
                'value' => '5477380',
                'module' => 'amo'
            ],
            [
                'name' => 'Воронки: Адрес, с которого будем забирать воронки',
                'code' => 'pipelines_url',
                'value' => '/api/v4/leads/pipelines/',
                'module' => 'amo'
            ],
            [
                'name' => 'Дата, с которой загружать c амо',
                'code' => 'last_date',
                'value' => '2023-11-26 16:00:03',
                'module' => 'amo'
            ],
            [
                'name' => 'Регистрация с помощью кода авторизации',
                'code' => 'registration_url',
                'value' => 'https://euroblock82.ru/admin/amo/get_access_token/',
                'module' => 'amo'
            ],
            [
                'name' => 'Контакт: Адрес контакта в мс',
                'code' => 'amo_contact_url',
                'value' => 'https://euroblock.amoru/contacts/detail/',
                'module' => 'amo'
            ],
            [
                'name' => 'Виджет: Секретный ключ',
                'code' => 'amo_widget_client_secret',
                'value' => 'BaZUHQqB5IQuBoVTlFbrKoUGlSxnCN5I5uUXJX1yvkmGc9Ae3oRCFWmu0nZHfvQ4',
                'module' => 'amo'
            ],
            [
                'name' => 'Виджет: Ид интеграции',
                'code' => 'amo_widget_client_id',
                'value' => 'db9a7088-061e-4531-af59-958a37072fb9',
                'module' => 'amo'
            ],
            [
                'name' => 'Виджет: Код авторизации',
                'code' => 'amo_widget_code',
                'value' => 'def502007e671ab0eb0d2f886c34395147bd48961431674e8f1ca9114579d2b5951b3a87277a1fd01bf26534a76636d94d7a11ba38b72007e646cc77d0f996161f87320fe9d8bbc5632f7b39b36a9e86176e8dd242e3693813fd4fee8995ee7c51218f8428a7cbc489a0c51da407f2bc01dcc90705012363d5c56587981456965a44aa456489dcdecfdce1517e147bf4ea18175626c5cdbf911ea9b3f25d0e0c79649a4a71817ddacdabaa8245ee2cc96bbb36dd622a1dacdd4464541116abf58705cdda2d960b21bf0bc7aa74b274a847202f8fc65ea06f2e32e2b4d77b9bdadef35ccad9ae8c7b2a9768a85042a39e59962f9e955842bcfcc94b2ed22986bdca28f45653bea43a2a2c239fc67ad4cf53739127bd3e953b6354ac75c95ae145f6d2db221035ccf7469c6d390b0645d8123ac91da3b52481dd6d278321df3869561ee568ef6fe4b3a94d1169a889f7c94590dba12ea5681d0f1aab0e43aeba833f788742610f59183186040029c16de63abdf6c1093c6e69a7900e959f3775875a7f37138a5d00e5cfba75c1c1a0259d3e59fea286673b5ce8155b601bfd8bacb8eba0a8a24395c652741e42390917eed13858da86981fed9c37639a093fba95d055',
                'module' => 'amo'
            ],
            [
                'name' => 'Виджет: Код виджета',
                'code' => 'amo_widget_auth_code',
                'value' => 'dpzr3s0sp33iuesvoqqywnyx56bgoafx1tfhvmch',
                'module' => 'amo'
            ],
            [
                'name' => 'Виджет: Файл подтверждения',
                'code' => 'amo_widget_token_file',
                'value' => 'token_amocrm_widget.json',
                'module' => 'amo'
            ],
            [
                'name' => 'Синхронизация заказ',
                'code' => 'updated_at_sync',
                'value' => '2023-07-18 16:20:36',
                'module' => 'ms'
            ],
            [
                'name' => 'Синхронизация заказ контакт',
                'code' => 'updated_at_sync_contact',
                'value' => '2023-11-26 18:00:09',
                'module' => 'ms'
            ],
            [
                'name' => 'отгрузки',
                'code' => 'ms_url_demand',
                'value' => 'https://online.moysklad.ru/api/remap/1.2/entity/demand/',
                'module' => 'ms'
            ],
            [
                'name' => 'Срок резерва ',
                'code' => 'reserve_period',
                'value' => '10',
                'module' => 'ms'
            ],
            [
                'name' => 'Запрос на получение текущих остатков по складам с указанием типа остатка.',
                'code' => 'product_residual_url',
                'value' => 'https://online.moysklad.ru/api/remap/1.2/report/stock/bystore/current?filter=assortmentId=',
                'module' => 'ms'
            ],

        ]);
    }
}
