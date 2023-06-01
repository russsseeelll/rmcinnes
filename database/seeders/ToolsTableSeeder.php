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
        'description' => 'A powerful PHP framework for building web applications with a clean and elegant syntax.',
        'image' => 'images/laravel.png',
    ],
    [
        'title' => 'Django',
        'description' => 'A high-level Python web framework that enables rapid development and encourages reusable and maintainable code.',
        'image' => 'images/django.png',
    ],
    [
        'title' => 'Bootstrap',
        'description' => 'A popular front-end framework that helps create responsive and visually appealing websites with pre-built CSS and JavaScript components.',
        'image' => 'images/bootstrap.jpg',
    ],
    [
        'title' => 'TailwindCSS',
        'description' => 'A utility-first CSS framework that allows for rapid UI development by providing a set of utility classes.',
        'image' => 'images/tailwindcss.png',
    ],
    [
        'title' => 'Vue.js',
        'description' => 'A progressive JavaScript framework for building user interfaces, known for its simplicity and seamless integration.',
        'image' => 'images/vuejs.png',
    ],
    [
        'title' => 'jQuery',
        'description' => 'A fast and concise JavaScript library that simplifies HTML document traversal, event handling, and animation.',
        'image' => 'images/jquery.jpg',
    ],
    [
        'title' => 'ReactNative',
        'description' => 'A JavaScript framework for building cross-platform mobile applications using React, allowing code reuse across iOS and Android.',
        'image' => 'images/reactnative.png',
    ],
    [
        'title' => 'MySQL',
        'description' => 'An open-source relational database management system that provides a reliable and scalable solution for storing and retrieving data efficiently.',
        'image' => 'images/mysql.jpg',
    ],
]);

    }
    
}
