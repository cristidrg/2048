<?php

namespace App\Http\Controllers;

use App\Game;
use App\Message;
use App\GridHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Events\GameUpdated;
use App\Events\BroadcastMessageCreation;

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
            case 'left': {$grid = GridHelper::switchLeft($grid); break;}
            case 'right': {$grid = GridHelper::switchRight($grid); break;}
            case 'bottom': {$grid = GridHelper::switchBottom($grid); break;}
            case 'top': {$grid = GridHelper::switchTop($grid); break;}
        }

        $newState = GridHelper::addNewBlock($grid);
        
        $grid = $newState['grid'];

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
        
        GameUpdated::dispatch($game->blocks, $newState['gridIsFull']);

        return Response::json(array(
            'success' => true,
        )); 
    }

    public function executeCommand($id, $command)
    {
        $game = Game::where('id', $id)->first();

        switch($command) {
            case 'restart': return $this->restartGame($game);
            case 'left':
            case 'right':
            case 'bottom':
            case 'top': 
                return $this->handleMovement($game, $command);
        }

        return Response::json(array(
            'success' => false,
            'error' => 'Command ' . $command . ' is not available'
        )); 
    }

    public function receiveMessage(int $id)
    {
        request()->validate([
            'content'=>'required',
        ]);

        $message = Message::create(request(['content']));
        
        BroadcastMessageCreation::dispatch($message);
        return $this->executeCommand($id, request('content'));
    }

    public function handleCommand(int $id)
    {   
        request()->validate([
            'command' => [
                'required',
                Rule::in(['restart','top','right','bottom','left']),
            ]
        ]);

        return $this->executeCommand($id, request('command'));
    }
}
