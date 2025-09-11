<?php
 
namespace App\Http\Resources\V1;
 

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Board;
use App\Models\Level;
use App\Models\Inventory;
use App\Models\CamelUnravelWinType;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'board'                 => Board::getBoardSettings(),
            'inventories'           => Inventory::getInventory(),
            'levels'                => Level::getLevels(),
            //'camel_unravel_win_type'=> CamelUnravelWinType::getCamelUnravelWinTypeByBoard(1)
        ];
    }
}