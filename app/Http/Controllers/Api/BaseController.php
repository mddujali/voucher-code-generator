<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Traits\Http\Templates\Requests\Api\ResponseTemplate;

abstract class BaseController extends Controller
{
    use ResponseTemplate;
}
