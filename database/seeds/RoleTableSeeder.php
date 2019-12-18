<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'superadmin';
        $role->description = 'Superadmin';
        $role->save();

        $role = new Role();
        $role->name = 'eventManager';
        $role->description = 'Event Manager';
        $role->save();
    }
}
