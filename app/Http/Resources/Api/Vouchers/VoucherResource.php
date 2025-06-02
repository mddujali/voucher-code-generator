<?php

namespace App\Http\Resources\Api\Vouchers;

use App\Http\Resources\Api\BaseJsonResource;
use App\Http\Resources\Api\Users\UserResource;
use Illuminate\Http\Request;

class VoucherResource extends BaseJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'code' => $this->code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
