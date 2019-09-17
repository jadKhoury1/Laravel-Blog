<?php

namespace App\Http\Controllers\Api\Posts;

use App\Post;
use App\Base\BaseController;
use Illuminate\Support\Facades\Auth;
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
        $query = Post::query()
            ->where('active', 1);

        // This will fetch the inactive posts of the authenticated user
        if ($this->user) {
            $query->orWhere('user_id', $this->user->id);
        }

        $query->orderBy('updated_at', 'DESC');

        return $this->response->statusOk(['posts' => $query->get(), 'user' => $this->user]);
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
        $post = Post::query()->find($id);

        // If the Post ID is invalid or the post is not active and the user is not logged In or if the user is not
        // the owner of the post return Unauthorized message
        if ($post === null || ($post->active == 0 && ($this->user === null || $this->user->id != $post->user_id))) {
            return $this->response->unauthorized(['message' => 'You are not authorized to perform this action']);
        }
        return $this->response->statusOk(['post' => $post]);
    }

}