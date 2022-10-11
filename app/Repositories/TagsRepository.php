<?php

namespace App\Repositories;
use App\Models\Tag as Model;
use App\Resources\TagResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagsRepository extends CoreRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param $perPage
     *
     * @return AnonymousResourceCollection
     */
    public function getAllWithPaginate($perPage = null)
    : AnonymousResourceCollection
    {
        $columns = ['id', 'name', 'created_at', 'updated_at'];

        return TagResource::collection($this->newConditions()->select($columns)->whereNull('deleted_at')->paginate($perPage));
    }

    public function getAllForSelectbox()
    {
        $columns = ['id', 'name'];
        return $this->newConditions()->select($columns)->toBase()->get();
    }

    /**
     * Get item for edit
     *
     * @param $id
     *
     * @return mixed
     */
    public function getEdit($id)
    {
        return new TagResource($this->newConditions()->find($id));
    }

    /**
     * Store new item
     *
     * @param $data
     *
     * @return TagResource
     */
    public function store($data)
    : TagResource
    {
        $tag = $this->newConditions()->create($data);

        return new TagResource($tag);
    }

    /**
     * Update item
     *
     * @param $data
     *
     * @return TagResource|bool
     */
    public function update($data)
    : TagResource
    {
        $tag = $this->newConditions()->find($data['id']);

        if (empty($tag)){
            return false;
        }

        $tag->name = $data['name'];

        $tag->save();

        return new TagResource($tag);
    }

    /**
     * Deleting from model
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    : bool
    {
        $tag = $this->newConditions()->find($id);
        if (empty($tag)){
            return false;
        }
        $tag->delete();

        return true;
    }

}
