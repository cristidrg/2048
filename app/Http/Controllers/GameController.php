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
    private function restartGame(Game $game)
    {
        $setObstacles = 0;
        $blocks = $game->blocks;
        $obstacleCount = $game->obstacleCount;

        foreach ($blocks as $block) {
            $block->value = 0;
        }

        $blocks[rand(0, 35)]->value = 2;

        while ($setObstacles < $obstacleCount) {
            $obstacleIndex = rand(0, 35);
            if ($blocks[$obstacleIndex]->value == 0) {
                $blocks[$obstacleIndex]->value = -1;
                $setObstacles = $setObstacles + 1;
            }
        }

        DB::beginTransaction();
        foreach ($blocks as $block) {
            DB::table('blocks')
                ->where('id', '=', $block["id"])
                ->update(['value' => $block["value"]]);
        }
        DB::commit();

        GameUpdated::dispatch($blocks, $obstacleCount, $game->getPlayingState());

        return Response::json(array(
            'success' => true,
        )); 
    }

    private function handleMovement($game, $command)
    {
        if ($game->getPlayingState() != 'playing') {
            return Response::json(array(
                'error' => 'Game id ' . $game->id . ' has finished. Restart it!'
            ));
        }

        $grid = array_fill(0, 6, array_fill(0, 6,0));

        foreach ($game->blocks as $block) {
            $grid[$block->row][$block->column] = [
                "id" => $block->id,
                "canMerge" => true,
                "value" => $block->value,
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
        
        GameUpdated::dispatch($game->blocks, $game->obstacleCount, $game->getPlayingState());

        return Response::json(array(
            'success' => true,
        )); 
    }

    private function executeCommand($id, $command)
    {
        $game = Game::where('id', $id)->first();

        if ($game == null) {
            return Response::json(array(
                'error' => 'Game id ' . $id . ' is not existent'
            ));
        }

        switch($command) {
            case 'restart': return $this->restartGame($game);
            case 'left':
            case 'right':
            case 'bottom':
            case 'top': 
                return $this->handleMovement($game, $command);
        }

        return Response::json(array(
            'error' => 'Command ' . $command . ' is not available'
        )); 
    }

    public function setObstacles(int $id)
    {
        request()->validate([
            'numberOfObstacles'=>'required|min:1|max:4',
        ]);

        $game = Game::where('id', $id)->first();

        if ($game == null) {
            return Response::json(array(
                'error' => 'Game id ' . $id . ' is not existent'
            ));
        }

        $game->obstacleCount = request('numberOfObstacles');
        $game->save();

        return $this->restartGame($game);
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
