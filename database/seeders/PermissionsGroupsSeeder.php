<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\PermissionGroup;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsGroupsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        $actions = ['view'];

        $groups = [
            'Dashboard|Dashboard',
        ];


        //$super_admin_role = Role::create(['name' => 'Super Admin']);

        $admin_role = Role::create(['name' => 'Admin']);


        foreach ($groups as $group) {
            $data = explode('|',$group);

            
            $add = PermissionGroup::create(['name' => $data[1],'module' => $data[0]]);

            foreach ($actions as $key => $action) {
                $permission_name = str_replace(' ', '-',strtolower($data[0])).'-'.str_replace(' ', '-',strtolower($data[1])).'-'.$action;

                Permission::create(['name' => $permission_name,'permission_group_id'=>$add->id]);

                $admin_role->givePermissionTo($permission_name);
            }

        }

        
        

    }
}
