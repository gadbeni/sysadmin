<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'avatar' => 'users/default.png',
                'ci' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$10$piw47ZDCJq3ieZXMVap/2eo1j71yvn4g2LNCxJu7b/7JEgq/8Q4oG',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2021-06-01 21:05:11',
                'updated_at' => '2021-06-01 21:05:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'role_id' => 2,
                'name' => 'Responsable de sección caja',
                'email' => 'responsable_caja@admin.com',
                'avatar' => 'users/default.png',
                'ci' => '0000000',
                'email_verified_at' => NULL,
                'password' => '$2y$10$0fuqo2tu8l98KBIQqp9z8Oho9fjgm4NLK9V.Q3BH.ome7xHyxc78y',
                'remember_token' => NULL,
                'settings' => '{"locale":"es"}',
                'created_at' => '2021-08-26 12:16:12',
                'updated_at' => '2021-08-26 12:16:12',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'role_id' => 3,
                'name' => 'Cajero',
                'email' => 'cajero@admin.com',
                'avatar' => 'users/default.png',
                'ci' => '0000000',
                'email_verified_at' => NULL,
                'password' => '$2y$10$rYHOoYCy2Q.x8JSrL0ufNe.BxzOWUy8j5YIQop6IB3XcmCGH3ZypS',
                'remember_token' => NULL,
                'settings' => '{"locale":"es"}',
                'created_at' => '2021-08-26 12:16:56',
                'updated_at' => '2021-08-26 12:17:09',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}