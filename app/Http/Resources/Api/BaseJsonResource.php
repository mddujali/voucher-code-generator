<?php

namespace App\Http\Resources\Api;

use App\Support\Traits\Http\Templates\Resources\Api\ResponseJsonResourceTemplate;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseJsonResource extends JsonResource
{
    use ResponseJsonResourceTemplate;
}
