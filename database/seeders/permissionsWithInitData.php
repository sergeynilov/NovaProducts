<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use \App\Models\User;


class permissionsWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
    define("ACCESS_PERMISSION_ADMIN", 1);   // Admin - can do all
    define("ACCESS_PERMISSION_MANAGER", 2); // Manager - can  edit pages
    define("ACCESS_PERMISSION_SALESPERSON", 3); // Content Editor - can  edit news/cms
*/
        $appAdminPermission = Permission::create(['name' => ACCESS_PERMISSION_ADMIN_LABEL, 'guard_name' => 'web']);

        $appAdminUser= User::find(1);
        if ( $appAdminUser ) {
            $appAdminUser->givePermissionTo($appAdminPermission);
        }
        $appAdminUser= User::find(5);
        if ( $appAdminUser ) {
//            echo '<pre>$::-15</pre>';
            $appAdminUser->givePermissionTo($appAdminPermission);
        }
        //// $adminRole BLOCK END


        //// HOSTEL ROLE  BLOCK BEGIN
        $managerPermission = Permission::create(['name' => ACCESS_PERMISSION_MANAGER_LABEL, 'guard_name' => 'web']);


        $managerUser= User::find(2);
        if ( $managerUser ) {
//            echo '<pre>$::-1</pre>';
            $managerUser->givePermissionTo($managerPermission);
        }

        $managerUser= User::find(3);
        if ( $managerUser ) {
//            echo '<pre>$::-2</pre>';
            $managerUser->givePermissionTo($managerPermission);
        }




        //// CONTENT EDITOR ROLE BLOCK BEGIN
        $salespersonPermission = Permission::create(['name' => ACCESS_PERMISSION_SALESPERSON_LABEL, 'guard_name' => 'web']);


        $editPageUser= User::find(3);
        if ( $editPageUser ) {
//            echo '<pre>$::-2</pre>';
            $editPageUser->givePermissionTo($salespersonPermission);
        }

        $editPageUser= User::find(4);
        if ( $editPageUser ) {
//            echo '<pre>$::-3</pre>';
            $editPageUser->givePermissionTo($salespersonPermission);
        }
    }
}
