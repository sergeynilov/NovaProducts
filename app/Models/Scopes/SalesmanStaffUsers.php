<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\ModelHasPermission;

class SalesmanStaffUsers implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        /*
CREATE TABLE `sppm_model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `sppm_model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `sppm_permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        if ( ! defined("ACCESS_PERMISSION_ADMIN")) {  // can do all
    define("ACCESS_PERMISSION_ADMIN", 1);  // Admin
}
if ( ! defined("ACCESS_PERMISSION_ADMIN_LABEL")) {
    define("ACCESS_PERMISSION_ADMIN_LABEL", 'Admin');
}


if ( ! defined("ACCESS_PERMISSION_MANAGER")) {  // Manager - can  edit pages
    define("ACCESS_PERMISSION_MANAGER", 2);  // Manager
}
if ( ! defined("ACCESS_PERMISSION_MANAGER_LABEL")) {
    define("ACCESS_PERMISSION_MANAGER_LABEL", 'Manager');
}


if ( ! defined("ACCESS_PERMISSION_SALESPERSON")) {  // SALESPERSON - can work with orders
    define("ACCESS_PERMISSION_SALESPERSON", 3); // Salesperson
}
if ( ! defined("ACCESS_PERMISSION_SALESPERSON_LABEL")) {
    define("ACCESS_PERMISSION_SALESPERSON_LABEL", 'Sales person');
}

          2024_02_09_133916_create_postponed_back_order_item_table ............................................................................ 2,078ms FAIL

   Illuminate\Database\QueryException

  SQLSTATE[HY093]: Invalid parameter number (Connection: mysql, SQL: select * from `users` where `id` = 3)

  at vendor/laravel/framework/src/Illuminate/Database/Connection.php:822
    818▕                     $this->getName(), $query, $this->prepareBindings($bindings), $e

         */
        $builder->whereIn('id', ModelHasPermission::getByPermissionId(ACCESS_PERMISSION_SALESPERSON)->get()->pluck('model_id'));

//        $builder->whereNotIn('id', ModelHasPermission::get()->pluck('model_id'));
    }

}
