<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Services\LastParkedLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LastParkedLocationController extends Controller
{
    protected LastParkedLocationService $service;

    public function __construct()
    {
        $this->service = new LastParkedLocationService();
    }

    public function set(Request $request): JsonResponse
    {
        $data = $request->only(['longitude', 'latitude']);
        $this->service->setLocation($data);

        return new JsonResponse();
    }

    public function get(): JsonResponse
    {
        $coordinates = $this->service->getLocation();

        return new JsonResponse($coordinates);
    }
}
