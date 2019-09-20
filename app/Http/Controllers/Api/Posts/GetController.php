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
        $query = Post::query();

        // If user iis logged in and his role is admin get the latest action related to the post
        // And get the user associated with each post
        if ($this->user && $this->user->role_key === 'admin') {
            $query->with(['action.user' => function ($query) {
                $query->select(['id', 'name', 'email']);
            }]);
        } else {
            $query->where('active', 1);
            if ($this->user) {
                $query->orWhere('user_id', $this->user->id)
                      ->with(['action' => function ($query) {
                            $query->where('user_id', $this->user->id);
                      }]);
            }
        }

        $query->orderBy('updated_at', 'DESC');

        return $this->response->statusOk(['posts' => $query->get()]);
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
        $query = Post::query();

        if ($this->user) {
            $query->with(['action' => function ($query) {
                if ($this->user->role_key === 'admin') {
                    $query->with(['user' => function ($query) {
                        $query->select(['id', 'name', 'email']);
                    }]);
                }
            }]);
        }

        $post = $query->withTrashed()->find($id);

        // If the Post ID is invalid or the post is not active and the user is not logged In or if the user is not
        // the owner of the post return Unauthorized message
        if ($post === null || ($this->user->role_key !== 'admin' && $this->user->id !== $post->user_id)) {
            return $this->response->unauthorized(['message' => 'You are not authorized to perform this action']);
        }

        return $this->response->statusOk(['post' => $post]);
    }


}