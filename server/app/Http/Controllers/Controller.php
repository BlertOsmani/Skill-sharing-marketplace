<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 *      @OA\Info(
 *          version="1.0",
 *          title="Skill-sharing marketplace app",
 *          description="This is the Skill-sharing marketplace application API documentation."
 *      ),
 *      @OA\Server(
 *          url="http://localhost/api",
 *          description="Local server"
 *      )
 * )
 */
/**
 * @OA\Info(
 *    title="Swagger with Laravel",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )

 */

abstract class Controller
{
    
}
