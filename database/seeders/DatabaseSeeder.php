<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name'=>'admin' ,
            'first_name'=>'admin' ,
            'last_name'=>'admin' ,
            'email'=>'admin@admin.com' ,
            'password'=> Hash::make('12345678@!f') ,
            'level'=>'Admin'
        ]);


        //Action
        $actions = [
            ['id' => 1, 'name' => 'Create'],
            ['id' => 2, 'name' => 'Read'],
            ['id' => 3, 'name' => 'Update'],
            ['id' => 4, 'name' => 'Delete'],
        ];
        foreach ($actions as $action) {
            DB::table('actions')->insert($action);
        }
    }
}
