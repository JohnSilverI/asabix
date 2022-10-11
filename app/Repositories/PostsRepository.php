<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\Post as Model;
use App\Models\PostTags;
use App\Models\PostTranslation;
use App\Resources\PostResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class PostsRepository extends CoreRepository
{

    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     *
     * @param $perPage
     *
     * @return AnonymousResourceCollection
     */
    public function getAllWithPaginate($perPage = null)
    : AnonymousResourceCollection
    {
        $columns = implode (',',[
            'posts.id',
            'tags.name',
            'posts.created_at',
            'posts.updated_at',
            '(SELECT GROUP_CONCAT(title SEPARATOR " | ") AS `title` FROM post_translations  WHERE post_id = posts.id) AS title'
        ]);

        $result = $this->newConditions()->selectRaw($columns)
            ->join('post_tags', 'posts.id', 'post_tags.post_id')
            ->join('tags', 'tags.id', 'post_tags.tag_id')
            ->whereNull('posts.deleted_at')
            ->whereNull('tags.deleted_at')
            ->orderBy('posts.id')
            ->paginate($perPage);

        return PostResource::collection($result);
    }

    /**
     * Get item for edit
     *
     * @param $id
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEdit($id)
    : \Illuminate\Support\Collection
    {

        $result = $this->newConditions()
            ->select(
            'posts.id AS post_id',
            'post_tags.tag_id',
            'data_ua.title AS title_ua',
            'data_ua.description AS description_ua',
            'data_ua.content AS content_ua',
            'data_en.title AS title_en',
            'data_en.description AS description_en',
            'data_en.content AS content_en',
            'data_ru.title AS title_ru',
            'data_ru.description AS description_ru',
            'data_ru.content AS content_ru'
        )
            ->join('post_tags', 'post_tags.post_id', 'posts.id')
            ->join(DB::raw("( SELECT `post_id`, `title`, `description`, `content`
                    FROM `post_translations` pt
                        INNER JOIN `languages` ON languages.id = pt.language_id
                    WHERE locale = 'ukr'
                    ) data_ua"), 'data_ua.post_id', 'posts.id')
            ->join(DB::raw("( SELECT `post_id`, `title`, `description`, `content`
                    FROM `post_translations` pt
                        INNER JOIN `languages` ON languages.id = pt.language_id
                    WHERE locale = 'eng'
                    ) data_en"), 'data_ua.post_id', 'posts.id')
            ->join(DB::raw("( SELECT `post_id`, `title`, `description`, `content`
                    FROM `post_translations` pt
                        INNER JOIN `languages` ON languages.id = pt.language_id
                    WHERE locale = 'rus'
                    ) data_ru"), 'data_ua.post_id', 'posts.id')
            ->where('posts.id', $id)
            ->get();

        return collect($result[0]);
    }

    /**
     * @param $data
     *
     * @return array|bool|null[]
     */
    public function store($data)
    {
        return $this->postInsertUpdate($data);
    }

    /**
     * @param $data
     * @param $id
     *
     * @return false|\Illuminate\Support\Collection
     */
    public function update($data, $id)
    {
        return $this->postInsertUpdate($data, $id);
    }

    /**
     * @param $data
     * @param $post_id
     *
     * @return false|\Illuminate\Support\Collection
     */
    public function postInsertUpdate($data, $post_id = null)
    {
        try {
            DB::beginTransaction();
            $lang = Language::select('id', 'prefix')->get();

            if ($lang->count() <= 0){
                throw new \Exception('Empty Languages');
            }

            $langArr = [];
            $lang->map(function ($item) use (&$langArr) {
                return $langArr[$item->prefix] = $item->id;
            });
            $update = true;
            if (!$post_id){
                $update = false;
                $post = $this->newConditions()->create();
                $post_id = $post['id'];

                // Binding a post to a tag does not change
                PostTags::create(['post_id' => $post_id, 'tag_id' => $data['tag_id']]);
            }

            $result = collect($data);

            unset($data['tag_id']);

            $insertArr = [];
            foreach($data as $key => $value){
                $res = explode('_', $key);
                if (!empty($insertArr[$res[1]])){
                    continue;
                }
                $insertArr[$res[1]] = [
                    'post_id' => $post_id,
                    'language_id' => $langArr[$res[1]],
                    'title' => $data['title_' . $res[1]],
                    'description' => $data['description_' . $res[1]],
                    'content' => $data['content_' . $res[1]],
                ];
            }

            if ($update){
                foreach($insertArr as $key => $insertRow){
                    PostTranslation::where(['post_id' => $post_id, 'language_id' => $langArr[$key]])->update($insertRow);
                }
            }
            else{
                $insertArr = array_values($insertArr);
                PostTranslation::insert($insertArr);
            }
            DB::commit();
        }
        catch (\Throwable $e){
            DB::rollBack();
            report($e);

            return false;
        }

        return $result;
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
        $post = $this->newConditions()->find($id);

        if (empty($post)){
            return false;
        }

        return $post->delete();
    }
}
