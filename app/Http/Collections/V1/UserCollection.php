<?php
 
namespace App\Http\Collections\V1;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
 
class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function($users) {
            $createdAt      = new \DateTime($users->created_at);
            $updatedAt      = new \DateTime($users->updated_at);
            $lastLoginAt    = new \DateTime($users->last_login_date);
            return [
                'id'                => $users->id,
                'first_name'        => $users->first_name,
                'last_name'         => $users->last_name,
                'email'             => $users->email,
                'status'            => $users->status == 1 ? 1 : 0,
                'created_ip'        => $users->created_ip,
                'last_login_ip'     => $users->last_login_ip,
                'last_login_date'   => $lastLoginAt->format('Y-m-d H:i:s'),
                'created_at'        => $createdAt->format('Y-m-d H:i:s'),
                'updated_at'        => $updatedAt->format('Y-m-d H:i:s'),
                'device_token'      => $users->userDeviceToken,
            ];
        })->toArray();
    }
}