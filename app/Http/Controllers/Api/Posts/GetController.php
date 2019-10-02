<?php

namespace App\Http\Controllers\Api\Posts;

use App\Post;
use App\Base\BaseController;
use phpDocumentor\Reflection\Types\Integer;

class GetController extends BaseController
{
    /**
     * Fetch all posts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $posts =  Post::query()
            ->getAll($this->user)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return $this->response->statusOk(['posts' => $posts]);
    }

    /**
     * Get Post Details
     *
     * @param Integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails($id)
    {
        $post = Post::query()->getDetails($this->user)->withTrashed()->find($id);

        // If the Post ID is invalid or the post is not active and the user is not logged In or if the user is not
        // the owner of the post return Unauthorized message
        if ($post === null || ($this->user->role_key !== 'admin' && $this->user->id !== $post->user_id)) {
            return $this->response->unauthorized(['message' => 'You are not authorized to perform this action']);
        }

        return $this->response->statusOk(['post' => $post]);
    }


}