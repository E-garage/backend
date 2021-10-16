<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="E-garage Api",
 *    version="0.0.1",
 * )
 */

/**
 * @OA\Get(
 *     path="/docs/v1",
 *     tags={"documentation"},
 *     summary="E-garage api",
 *     @OA\Response(response="200", description="success"),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
