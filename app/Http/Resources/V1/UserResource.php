<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Board;
use App\Models\Level;
use App\Models\Inventory;
use App\Models\Dice;
use App\Models\MysteryCard;
use App\Models\TradingPost;
use App\Models\TradingQuest;
use App\Models\UserBuilding;
use App\Models\Building;
use App\Models\UserResource as UsrResource;
use App\Models\CamelUnravelGameplayOption;
use App\Models\CamelUnravelWinType;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        $createdAt      = new \DateTime($this->created_at);
        $updatedAt      = new \DateTime($this->updated_at);
        $lastLoginAt    = new \DateTime($this->last_login_date);
        return [
            'id'                => $this->id,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'email'             => $this->email,
            'status'            => $this->status == 1 ? 1 : 0,
            'created_ip'        => $this->created_ip,
            'last_login_ip'     => $this->last_login_ip,
            'last_login_date'   => $lastLoginAt->format('Y-m-d H:i:s'),
            'created_at'        => $createdAt->format('Y-m-d H:i:s'),
            'updated_at'        => $updatedAt->format('Y-m-d H:i:s'),
            'device_token'      => $this->userDeviceToken,
        ];
    }
}
