<?php

namespace App\Http\Controllers;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/levels/get",
 *     summary="Retrieves all levels",
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of all levels",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID of the level"),
 *                 @OA\Property(property="name", type="string", description="Name of the level"),
 *                 @OA\Property(property="description", type="string", description="Description of the level, if available")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message detailing an internal error")
 *         )
 *     )
 * )
 */


    public function getLevels()
    {
        $levels = Level::all();
        return response()->json($levels);
    }
}
