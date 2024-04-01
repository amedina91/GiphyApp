<?php

namespace App\Http\Controllers;

use App\Services\GiphyService;
use Illuminate\Http\Request;
use App\Services\ApiInteractionService;
use App\Models\Favorite;

/**
* @OA\Info(
*             title="Api Giphy Alvaro",
*             version="1.0",
*             description="Listados de URI'S de la API Giphy"
* )
*
* @OA\Server(url="http://127.0.0.1:8000")
*/

class GiphyController extends Controller
{
    private $giphyService;
    private $apiInteractionService;

    public function __construct(GiphyService $giphyService, ApiInteractionService $apiInteractionService)
    {
        $this->giphyService = $giphyService;
        $this->apiInteractionService = $apiInteractionService;
    }

    /**
    * @OA\Get(
    *     path="/giphy/search",
    *     tags={"Giphy"},
    *     summary="Search for gifs",
    *     @OA\Parameter(
    *         name="query",
    *         in="query",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="limit",
    *         in="query",
    *         required=false,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *         name="offset",
    *         in="query",
    *         required=false,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Invalid input",
    *     ),
    * )
    */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
            'limit' => 'nullable|numeric',
            'offset' => 'nullable|numeric'
        ]);

        $term = $request->input('query');
        $limit = $request->input('limit', 25);
        $offset = $request->input('offset', 0);

        $gifs = $this->giphyService->searchGifs($term, $limit, $offset);
        $response = response()->json($gifs);

        $this->apiInteractionService->logInteraction($request, $response);

        return $response;
    }

    /**
    * @OA\Get(
    *     path="/giphy/show/{id}",
    *     tags={"Giphy"},
    *     summary="Show gif by id",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Gif not found",
    *     ),
    * )
    */
    public function show(string $id)
    {
        $gif = $this->giphyService->getGifById($id);

        $response = response()->json($gif);

        $this->apiInteractionService->logInteraction(request(), $response);

        return $response;
    }

    /**
    * @OA\Post(
    *     path="/giphy/storeFavorite",
    *     tags={"Giphy"},
    *     summary="Store favorite gif",
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"gif_id","alias","user_id"},
    *             @OA\Property(property="gif_id", type="string"),
    *             @OA\Property(property="alias", type="string"),
    *             @OA\Property(property="user_id", type="integer"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Invalid input",
    *     ),
    * )
    */
    public function storeFavorite(Request $request)
    {
        $request->validate([
            'gif_id' => 'required|string',
            'alias' => 'required|string',
            'user_id' => 'required|numeric'
        ]);

        $favorite = Favorite::create([
            'gif_id' => $request->gif_id,
            'alias' => $request->alias,
            'user_id' => $request->user_id
        ]);

        $response = response()->json($favorite);

        $this->apiInteractionService->logInteraction($request, $response);

        return $response;
    }

}
