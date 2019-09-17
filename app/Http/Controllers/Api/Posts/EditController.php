<?php

namespace App\Http\Controllers\Api\Posts;

use App\UserAction;
use App\Base\BaseController;

class EditController extends BaseController
{
    /**
     * Edit Post
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        if ($this->checkIfPostExists($id) === false || $this->makeValidation() === false ||
            $this->checkIfUserHasPendingAction(UserAction::ACTION_EDIT) === false
        ) {
            return $this->response->statusFail($this->errorMessage);
        }
        $this->createAction($id);

        return $this->response->statusOk(['message' => 'Post Edit sent for approval', 'id' => $id]);
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
     * Create Edit Action
     *
     * @param $postId
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function createAction($postId)
    {
        return UserAction::query()->create([
            'user_id'   => $this->user->id,
            'action'    => UserAction::ACTION_EDIT,
            'item_id'   => $postId,
            'item_type' => 'posts',
            'data'      => json_encode($this->request->only(['title', 'description', 'image']))
        ]);
    }
}