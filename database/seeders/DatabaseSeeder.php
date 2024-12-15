<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $data = [
            [
                'title' => 'Smart Home Automation System',
                'slug' => Str::slug('slugisworking'),
                'date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'category' => 'IT Project',
                'tags' => 'Raspberry Pi,Python,IoT,Zigbee',
                'description' => 'This project involves creating a comprehensive home automation system that can control and monitor home appliances using a Raspberry Pi and Python programming. It incorporates IoT technologies and Zigbee wireless networking for seamless connectivity.',
                'summary' => 'The smart home automation system effectively manages and controls home appliances, enhancing convenience and efficiency. It is also equipped with a user-friendly interface for easy operation and monitoring.',
                'link' => 'https://www.youtube.com/watch?v=your_video_id_here',
                'image' => ''
            ],
            [
                'title' => 'Smart Home Automation System 1',
                'slug' => Str::slug('slugisworking1'),
                'date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'category' => 'IT Project',
                'tags' => 'Raspberry Pi,Python,IoT,Zigbee',
                'description' => 'This project involves creating a comprehensive home automation system that can control and monitor home appliances using a Raspberry Pi and Python programming. It incorporates IoT technologies and Zigbee wireless networking for seamless connectivity.',
                'summary' => 'The smart home automation system effectively manages and controls home appliances, enhancing convenience and efficiency. It is also equipped with a user-friendly interface for easy operation and monitoring.',
                'link' => 'https://www.youtube.com/watch?v=your_video_id_here',
                'image' => ''
            ],
            [
                'title' => 'Smart Home Automation System 2',
                'slug' => Str::slug('slugisworking2'),
                'date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'category' => 'Software Devlopment',
                'tags' => 'Raspberry Pi,Python,IoT,Zigbee',
                'description' => 'This project involves creating a comprehensive home automation system that can control and monitor home appliances using a Raspberry Pi and Python programming. It incorporates IoT technologies and Zigbee wireless networking for seamless connectivity.',
                'summary' => 'The smart home automation system effectively manages and controls home appliances, enhancing convenience and efficiency. It is also equipped with a user-friendly interface for easy operation and monitoring.',
                'link' => 'https://www.youtube.com/watch?v=your_video_id_here',
                'image' => ''
            ],
        ];

        foreach ($data as $item) {
            DB::table('portfolio')->insert($item);
        }

        $this->call([
            ToolsTableSeeder::class,
        ]);
    }
}
