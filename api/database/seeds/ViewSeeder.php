<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
                 // 建立获取角色的视图
         DB::statement("CREATE VIEW `v_admin_roles` AS select `admins`.`id` AS `id`,`admins`.`nickname` AS `nickname`,`admins`.`email` AS `email`,`admins`.`phone` AS `phone`,`roles`.`desc` AS `desc`,`roles`.`name` AS `name`,`admin_roles`.`admin_id` AS `admin_id`,`admin_roles`.`role_id` AS `role_id` from ((`admins` join `admin_roles` on((`admin_roles`.`admin_id` = `admins`.`id`))) join `roles` on((`admin_roles`.`role_id` = `roles`.`id`)))");
         // 建立获取权限的视图
         DB::statement("CREATE VIEW `v_admin_permissions` AS select `admins`.`id` AS `id`,`admins`.`nickname` AS `nickname`,`admins`.`email` AS `email`,`admins`.`phone` AS `phone`,`admin_roles`.`admin_id` AS `admin_id`,`admin_roles`.`role_id` AS `role_id`,`roles`.`desc` AS `roles_desc`,`roles`.`name` AS `roles_name`,`role_permissions`.`permission_id` AS `permission_id`,`permissions`.`name` AS `permissions_name`,`permissions`.`desc` AS `permissions_desc`,`modules`.`name` AS `modules_name`,`permissions`.`module_id` AS `module_id`,concat(`modules`.`name`,'.',`permissions`.`name`) AS `full_permissions` from (((((`admins` join `admin_roles` on((`admin_roles`.`admin_id` = `admins`.`id`))) join `roles` on((`admin_roles`.`role_id` = `roles`.`id`))) join `role_permissions` on((`role_permissions`.`role_id` = `roles`.`id`))) join `permissions` on((`role_permissions`.`permission_id` = `permissions`.`id`))) join `modules` on((`permissions`.`module_id` = `modules`.`id`)))");
    }
}
