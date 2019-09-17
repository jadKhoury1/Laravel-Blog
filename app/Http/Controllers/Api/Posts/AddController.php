<?php

namespace App\Http\Controllers\Api\Posts;

use App\Post;
use App\UserAction;
use App\Base\BaseController;
use Illuminate\Support\Facades\DB;


class AddController extends BaseController
{
    /**
     * Stores newly created post
     *
     * @var Post
     */
    private $post;

    /**
     * Add new Post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add()
    {
        if ($this->makeValidation() === false ||
            $this->checkIfUserHasPendingAction(UserAction::ACTION_ADD) === false ||
            $this->create() === false
        ) {
            return $this->response->statusFail($this->errorMessage);
        }

        return $this->response->statusOk(['message' => 'Post Creation sent for approval', 'post' => $this->post]);

    }

    /**
     * Set Post creation validation rules
     *
     * @return array
     */
    protected function setValidationRules()
    {
        return [
            'title'       => 'required|string|min:6|max:191',
            'description' => 'required|string',
            'image'       => 'sometimes|string'
        ];
    }

    /**
     * Create all records for Post and UserAction
     *
     * @return bool
     */
    private function create()
    {
        DB::beginTransaction();

        try {
            $this->post = $this->createPost();
            $this->createAction($this->post->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Post Could not be created. Please try again later';
            return false;
        }

        return true;
    }

    /**
     * Create Post
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function createPost()
    {
        $data = $this->request->all();

        return Post::query()->create([
            'user_id'     => $this->user->id,
            'title'       => $data['title'],
            'description' => $data['description'],
            'image'       => isset($data['image']) ? $data['image'] : null
        ]);
    }

    /**
     * Create
     *
     * @param integer $postId
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function createAction($postId)
    {
        return UserAction::query()->create([
            'user_id'   => $this->user->id,
            'action'    => UserAction::ACTION_ADD,
            'item_id'   => $postId,
            'item_type' => 'posts'
        ]);
    }


}