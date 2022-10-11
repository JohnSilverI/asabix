<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Repositories\PostsRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param PostsRepository $postsRepository
     *
     * @return AnonymousResourceCollection
     */
    public function index(PostsRepository $postsRepository)
    : AnonymousResourceCollection
    {
        return $postsRepository->getAllWithPaginate(5);
    }
    /**
     * Display the specified resource.
     *
     * @param                $id
     * @param PostsRepository $postsRepository
     *
     * @return Collection
     */
    public function show($id, PostsRepository $postsRepository)
    : Collection
    {
        $result = $postsRepository->getEdit($id);

        if (empty($result)){
            return abort(404, "Not Found. Post Id: {$id}");
        }

        return $result;
    }

    /**
     * @param                   $id
     * @param PostCreateRequest $request
     * @param PostsRepository   $postsRepository
     *
     * @return Application|ResponseFactory|Response|Collection
     */
    public function update($id, PostCreateRequest $request, PostsRepository $postsRepository)
    {
        $data = $request->validated();
        $result = $postsRepository->update($data, $id);

        if (!$result){
            return response("Error Update. Post ID: {$id}", 400);
        }

        return $result;
    }

    /**
     * Store new item
     *
     * @param PostCreateRequest $request
     * @param PostsRepository   $postsRepository
     *
     * @return Application|ResponseFactory|Response|Collection
     */
    public function store(PostCreateRequest $request, PostsRepository $postsRepository)
    {
        $data = $request->validated();
        $result = $postsRepository->store($data);

        if (!$result){
            return response("Error Create Post", 400);
        }

        return $result;
    }

    /**
     * Delete the model from the database.
     *
     * @param                $id
     * @param PostsRepository $postsRepository
     *
     * @return Application|Response|ResponseFactory
     */
    public function destroy($id, PostsRepository $postsRepository)
    {
        $result = $postsRepository->delete($id);

        if(!$result){
            return response('Delete Post error. ID:'. $id, 400);
        }

        return response('', 204);
    }
}
