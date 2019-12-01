<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Services\NavigateService;
use Illuminate\Http\Request;

class NavigateController extends Controller
{
    protected $navigateService;

    public function __construct(NavigateService $navigateService)
    {
        $this->navigateService = $navigateService;
    }

    public function addCategory(Request $request)
    {
        return $this->navigateService->addCategory($request);
    }
}
