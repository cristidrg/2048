<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\GameUpdated;

class GameController extends Controller
{
    public function restartGame(Game $game)
    {
        foreach ($game->blocks as $block) {
            $block->value = 0;
            $block->save();
        }

        $startingBlock = rand(0, 35);
        $game->blocks[$startingBlock]->value = 2;
        $game->blocks[$startingBlock]->save();

        GameUpdated::dispatch($game->blocks);
    }

    public function handleCommand(int $id)
    {   
        request()->validate([
            'command' => [
                'required',
                Rule::in(['restart','top','right','bottom','left']),
            ]
        ]);

        $game = Game::where('id', $id)->first();

        if (request('command') == 'restart') {
            $this->restartGame($game);
        }
    }
}
