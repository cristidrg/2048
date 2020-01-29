<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
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

        return Response::json(array(
            'success' => true,
        )); 
    }

    public function switchLeft($startingGrid)
    {
        $grid = $startingGrid;
        for ($row = 0; $row < 6; $row++) {
            for ($column = 0; $column < 6; $column++) {
                if ($grid[$row][$column]["value"] != 0) {
                    $columnCopy = $column - 1;
                    $count = 0;
                    while ($columnCopy > -1) {
                        if ($grid[$row][$columnCopy]["value"] == 0) {
                            $temp = $grid[$row][$columnCopy + 1];
                            $grid[$row][$columnCopy + 1] = $grid[$row][$columnCopy];
                            $grid[$row][$columnCopy] = $temp;
                        } else {

                        }
                        $columnCopy = $columnCopy - 1;
                        $count = $count + 1;
                    }
                }
            }
        }

        return $grid;
    }

    public function handleMovement($game, $command)
    {
        $grid = array_fill(0, 6, array_fill(0, 6,0));

        foreach ($game->blocks as $block) {
            $grid[$block->row][$block->column] = [
                "value" => $block->value,
                "id" => $block->id
            ];
        }

        switch($command) {
            case 'left': {$grid = $this->switchLeft($grid); break;}
        }
        
        DB::beginTransaction();
            for ($row = 0; $row < 6; $row++) {
                for ($column = 0; $column < 6; $column++) {
                    $block = $grid[$row][$column];
                    DB::table('blocks')
                        ->where('id', '=', $block["id"])
                        ->update([
                            'value' => $block["value"],
                            'row' => $row,
                            'column' =>$column
                        ]);
                }
            }
        DB::commit();
        
        GameUpdated::dispatch($game->blocks);

        return Response::json(array(
            'success' => true,
        )); 
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

        switch(request('command')) {
            case 'restart': return $this->restartGame($game);
            case 'left': return $this->handleMovement($game, request('command'));
        }
    }
}
