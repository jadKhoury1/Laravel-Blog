<?php

namespace App\Http\Controllers\Api\Post;

use App\UserAction;
use App\Base\BaseController;

class DeleteController extends BaseController
{
    /**
     * Delete Post
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        if ($this->checkIfPostExists($id) === false ||
            $this->checkIfUserHasPendingAction(UserAction::ACTION_DELETE) === false
        ) {
            return $this->response->statusFail($this->errorMessage);
        }

        $this->createAction($id);

        return $this->response->statusOk(['message' => 'Post Deletion sent for approval']);
    }

    /**
     * Create Delete Action Record
     *
     * @param $postId
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function createAction($postId)
    {
        return UserAction::query()->create([
            'user_id'   => $this->user->id,
            'action'    => UserAction::ACTION_DELETE,
            'model'     => 'App\Post',
            'object_id' => $postId
        ]);
    }
}