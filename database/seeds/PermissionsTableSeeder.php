<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id' => '1',
                'name' => 'user_management_access',
            ],
            [
                'id' => '2',
                'name' => 'permission_create',
            ],
            [
                'id' => '3',
                'name' => 'permission_edit',
            ],
            [
                'id' => '4',    
                'name' => 'permission_show',
            ],
            [
                'id' => '5',
                'name' => 'permission_delete',
            ],
            [
                'id' => '6',
                'name' => 'permission_access',
            ],
            [
                'id' => '7',
                'name' => 'role_create',
            ],
            [
                'id' => '8',
                'name' => 'role_edit',
            ],
            [
                'id' => '9',
                'name' => 'role_show',
            ],
            [
                'id' => '10',
                'name' => 'role_delete',
            ],
            [
                'id' => '11',
                'name' => 'role_access',
            ],
            [
                'id' => '12',
                'name' => 'user_create',
            ],
            [
                'id' => '13',
                'name' => 'user_edit',
            ],
            [
                'id' => '14',
                'name' => 'user_show',
            ],
            [
                'id' => '15',
                'name' => 'user_delete',
            ],
            [
                'id' => '16',
                'name' => 'user_access',
            ],
            [
                'id' => '17',
                'name' => 'status_create',
            ],
            [
                'id' => '18',
                'name' => 'status_edit',
            ],
            [
                'id' => '19',
                'name' => 'status_show',
            ],
            [
                'id' => '20',
                'name' => 'status_delete',
            ],
            [
                'id' => '21',
                'name' => 'status_access',
            ],
            [
                'id' => '22',
                'name' => 'priority_create',
            ],
            [
                'id' => '23',
                'name' => 'priority_edit',
            ],
            [
                'id' => '24',
                'name' => 'priority_show',
            ],
            [
                'id' => '25',
                'name' => 'priority_delete',
            ],
            [
                'id' => '26',
                'name' => 'priority_access',
            ],
            [
                'id' => '27',
                'name' => 'category_create',
            ],
            [
                'id' => '28',
                'name' => 'category_edit',
            ],
            [
                'id' => '29',
                'name' => 'category_show',
            ],
            [
                'id' => '30',
                'name' => 'category_delete',
            ],
            [
                'id' => '31',
                'name' => 'category_access',
            ],
            [
                'id' => '32',
                'name' => 'ticket_create',
            ],
            [
                'id' => '33',
                'name' => 'ticket_edit',
            ],
            [
                'id' => '34',
                'name' => 'ticket_show',
            ],
            [
                'id' => '35',
                'name' => 'ticket_delete',
            ],
            [
                'id' => '36',
                'name' => 'ticket_access',
            ],
            [
                'id' => '37',
                'name' => 'comment_create',
            ],
            [
                'id' => '38',
                'name' => 'comment_edit',
            ],
            [
                'id' => '39',
                'name' => 'comment_show',
            ],
            [
                'id' => '40',
                'name' => 'comment_delete',
            ],
            [
                'id' => '41',
                'name' => 'comment_access',
            ],
            [
                'id' => '42',
                'name' => 'audit_log_show',
            ],
            [
                'id' => '43',
                'name' => 'audit_log_access',
            ],
            [
                'id' => '44',
                'name' => 'dashboard_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
