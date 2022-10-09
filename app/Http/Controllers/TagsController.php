<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Repositories\TagsRepository;

class TagsController extends Controller
{
    private $blogCategoryRepository;

    public function __construct()
    {
        //parent::construct();
        $this->blogCategoryRepository = app(TagsRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        return $this->blogCategoryRepository->getAllWithPaginate(5);
    }

    /**
     * Update item
     *
     * @param TagUpdateRequest $request
     * @param TagsRepository   $tagsRepository
     *
     * @return bool|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(TagUpdateRequest $request, TagsRepository $tagsRepository)
    {
        $data = $request->validated();
        $result = $tagsRepository->update($data);

        if (!$result){
            return response("Invalid tag ID: {$data['id']}", 400);
        }

        return $result;
    }

    /**
     * Store new item
     *
     * @param TagCreateRequest $request
     * @param TagsRepository   $tagsRepository
     *
     * @return \App\Resources\TagResource
     */
    public function store(TagCreateRequest $request, TagsRepository $tagsRepository)
    : \App\Resources\TagResource
    {
        return $tagsRepository->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param                $id
     * @param TagsRepository $tagsRepository
     *
     * @return mixed
     */
    public function show($id, TagsRepository $tagsRepository)
    {
        $result = $tagsRepository->getEdit($id);

        if (empty($result)){
            return abort(404, 'Not Found');
        }

        return $result;
    }

    /**
     * Delete the model from the database.
     *
     * @param                $id
     * @param TagsRepository $tagsRepository
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id, TagsRepository $tagsRepository)
    {
        $tagsRepository->delete($id);

        return response('', 204);
    }
}
