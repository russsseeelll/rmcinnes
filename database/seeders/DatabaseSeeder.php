<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
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
                'date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'category' => 'Home Automation',
                'tags' => 'Raspberry Pi,Python,IoT,Zigbee',
                'description' => 'This project involves creating a comprehensive home automation system that can control and monitor home appliances using a Raspberry Pi and Python programming. It incorporates IoT technologies and Zigbee wireless networking for seamless connectivity.',
                'summary' => 'The smart home automation system effectively manages and controls home appliances, enhancing convenience and efficiency. It is also equipped with a user-friendly interface for easy operation and monitoring.',
                'link' => 'https://www.youtube.com/watch?v=your_video_id_here',
                'image' => 'images/html.jpg'
            ],
            [
                'title' => 'Smart Home Automation System 1',
                'date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'category' => 'Home Automation',
                'tags' => 'Raspberry Pi,Python,IoT,Zigbee',
                'description' => 'This project involves creating a comprehensive home automation system that can control and monitor home appliances using a Raspberry Pi and Python programming. It incorporates IoT technologies and Zigbee wireless networking for seamless connectivity.',
                'summary' => 'The smart home automation system effectively manages and controls home appliances, enhancing convenience and efficiency. It is also equipped with a user-friendly interface for easy operation and monitoring.',
                'link' => 'https://www.youtube.com/watch?v=your_video_id_here',
                'image' => 'images/html.jpg'
            ],
            [
                'title' => 'Smart Home Automation System 2',
                'date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'category' => 'Home Automation',
                'tags' => 'Raspberry Pi,Python,IoT,Zigbee',
                'description' => 'This project involves creating a comprehensive home automation system that can control and monitor home appliances using a Raspberry Pi and Python programming. It incorporates IoT technologies and Zigbee wireless networking for seamless connectivity.',
                'summary' => 'The smart home automation system effectively manages and controls home appliances, enhancing convenience and efficiency. It is also equipped with a user-friendly interface for easy operation and monitoring.',
                'link' => 'https://www.youtube.com/watch?v=your_video_id_here',
                'image' => 'images/html.jpg'
            ],
            // Add more entries in the same format as above
        ];

        foreach ($data as $item) {
            DB::table('portfolio')->insert($item);
        }

        $this->call([
            ToolsTableSeeder::class,
        ]);
    }
}
