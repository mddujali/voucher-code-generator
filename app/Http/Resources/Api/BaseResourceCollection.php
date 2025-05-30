<?php

namespace App\Http\Resources\Api;

use App\Support\Traits\Http\Templates\Resources\Api\ResponseResourceCollectionTemplate;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class BaseResourceCollection extends ResourceCollection
{
    use ResponseResourceCollectionTemplate;
}
