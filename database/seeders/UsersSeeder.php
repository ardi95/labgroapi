<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
use App\Models\Role_user;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $role = new Role();
        $role->name = 'Admin APP';
        $role->save();

        $role2 = new Role();
        $role2->name = 'Admin Kasir';
        $role2->save();

        $admin = new User();
        $admin->name = 'Admin ARAN';
        $admin->email = 'admin_aran';
        $admin->password = bcrypt('ardi1995');
        $admin->active = 'Yes';
        $admin->save();

        $admin2 = new User();
        $admin2->name = 'Ardi';
        $admin2->email = 'ardi95';
        $admin2->password = bcrypt('ardi1995');
        $admin2->active = 'Yes';
        $admin2->save();

        $admin3 = new User();
        $admin3->name = 'Heliza';
        $admin3->email = 'heliza16';
        $admin3->password = bcrypt('ardi1995');
        $admin3->active = 'Yes';
        $admin3->save();

        $admin4 = new User();
        $admin4->name = 'Admin ARAN';
        $admin4->email = 'admin_aran4';
        $admin4->password = bcrypt('ardi1995');
        $admin4->active = 'Yes';
        $admin4->save();

        $admin5 = new User();
        $admin5->name = 'Admin ARAN';
        $admin5->email = 'admin_aran5';
        $admin5->password = bcrypt('ardi1995');
        $admin5->active = 'Yes';
        $admin5->save();

        $admin6 = new User();
        $admin6->name = 'Admin ARAN';
        $admin6->email = 'admin_aran6';
        $admin6->password = bcrypt('ardi1995');
        $admin6->active = 'Yes';
        $admin6->save();

        $admin7 = new User();
        $admin7->name = 'Admin ARAN';
        $admin7->email = 'admin_aran7';
        $admin7->password = bcrypt('ardi1995');
        $admin7->active = 'Yes';
        $admin7->save();

        $admin8 = new User();
        $admin8->name = 'Admin ARAN';
        $admin8->email = 'admin_aran8';
        $admin8->password = bcrypt('ardi1995');
        $admin8->active = 'Yes';
        $admin8->save();

        $admin9 = new User();
        $admin9->name = 'Admin ARAN';
        $admin9->email = 'admin_aran9';
        $admin9->password = bcrypt('ardi1995');
        $admin9->active = 'Yes';
        $admin9->save();

        $admin10 = new User();
        $admin10->name = 'Admin ARAN';
        $admin10->email = 'admin_aran10';
        $admin10->password = bcrypt('ardi1995');
        $admin10->active = 'Yes';
        $admin10->save();

        $admin11 = new User();
        $admin11->name = 'Admin ARAN';
        $admin11->email = 'admin_aran11';
        $admin11->password = bcrypt('ardi1995');
        $admin11->active = 'Yes';
        $admin11->save();

        $admin12 = new User();
        $admin12->name = 'Admin ARAN';
        $admin12->email = 'admin_aran12';
        $admin12->password = bcrypt('ardi1995');
        $admin12->active = 'Yes';
        $admin12->save();
        //$admin->attachRole($adminRole);

        $roleUser = new Role_user();
        $roleUser->role_id = $role->id;
        $roleUser->user_id = $admin->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role->id;
        $roleUser->user_id = $admin2->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin3->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin4->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin5->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin6->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin7->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin8->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin9->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin10->id;
        $roleUser->save();

        $roleUser = new Role_user();
        $roleUser->role_id = $role2->id;
        $roleUser->user_id = $admin11->id;
        $roleUser->save();
    }
}
