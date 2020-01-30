<?php

use Illuminate\Database\Seeder;

use App\Game;
use App\Block;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $game1 = Game::create();

        for ($i = 0; $i < 6; $i++) {
            for( $j = 0; $j < 6; $j++) {
                $block = Block::create([
                    'row' => $i,
                    'column' => $j,
                    'value' => 0
                ]);
                $game1->blocks()->save($block);
            }
        }
    }
}
