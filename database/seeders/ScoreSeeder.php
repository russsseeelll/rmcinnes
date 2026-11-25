<?php

namespace Database\Seeders;

use App\Models\Score;
use Illuminate\Database\Seeder;

/** Joke leaderboard entries so the board isn't empty on day one. Remove or replace freely. */
class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            ['Jared Isaacman', 'NASA', 31],
            ['Elon Musk', 'Tesla', 27],
            ['Nicola Sturgeon', 'SNP', 24],
            ['Linus Torvalds', 'Linux Foundation', 22],
            ['Jensen Huang', 'NVIDIA', 19],
            ['Satya Nadella', 'Microsoft', 17],
            ['Gordon Ramsay', "Hell's Kitchen", 13],
            ['Andy Murray', 'Wimbledon', 11],
            ['Tim Cook', 'Apple', 9],
            ['Mark Zuckerberg', 'Meta', 6],
            ['Sundar Pichai', 'Google', 4],
            ['Nessie', 'Loch Ness', 2],
        ];

        Score::query()->delete();
        foreach ($entries as $i => [$name, $organisation, $score]) {
            Score::create(['name' => $name, 'organisation' => $organisation, 'score' => $score])
                ->forceFill(['created_at' => now()->subDays(30 - $i)])->save();
        }
    }
}
