<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ToolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run()
    {

        DB::table('tools')->insert([
            [
                'title' => 'Laravel',
                'description' => 'Laravel description...',
                'image' => 'images/laravel.png',
            ],
            [
                'title' => 'Django',
                'description' => 'Django description...',
                'image' => 'images/django.png',
            ],
            [
                'title' => 'Bootstrap',
                'description' => 'Bootstrap description...',
                'image' => 'images/bootstrap.jpg',
            ],
            [
                'title' => 'TailwindCSS',
                'description' => 'TailwindCSS description...',
                'image' => 'images/tailwindcss.png',
            ],
            [
                'title' => 'Vue.js',
                'description' => 'Vue.js description...',
                'image' => 'images/vuejs.png',
            ],
            [
                'title' => 'Jquery',
                'description' => 'Jquery description...',
                'image' => 'images/jquery.jpg',
            ],
            [
                'title' => 'ReactNative',
                'description' => 'ReactNative description...',
                'image' => 'images/reactnative.png',
            ],
            [
                'title' => 'MySQL',
                'description' => 'MySQL description...',
                'image' => 'images/mysql.jpg',
            ],
        ]);
    }
    
}
