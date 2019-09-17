<?php

namespace App\Policies;

use App\User;
use App\Post;
use phpDocumentor\Reflection\Types\Integer;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Defines a callback that is run before all other authorization checks
     *
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        return $user->role_key === 'admin' ? true : null;
    }
    
    /**
     * Determine whether the user can view any posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\User  $user
     * @param  integer $id
     * @return mixed
     */
    public function view(User $user, $id)
    {
        $post = Post::query()->find($id);

        if ($post === null || ($post->active == 0 && ($user->id !== $post->user_id))) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\User  $user
     * @param  Integer $id
     * @return mixed
     */
    public function update(User $user, $id)
    {
        return $this->checkAuthorization($user, $id);
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\User  $user
     * @param  Integer  $id
     * @return mixed
     */
    public function delete(User $user, $id)
    {
        return $this->checkAuthorization($user, $id);
    }

    /**
     * Determine whether the user can approve post action
     *
     * @param User $user
     * @return bool
     */
    public function approve(User $user)
    {
        return $user->role->key === 'admin' ? true : false;
    }

    /**
     * Determine whether the user can restore the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function restore(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function forceDelete(User $user, Post $post)
    {
        //
    }

    private function checkAuthorization(User $user, $id)
    {
        $post = Post::query()->find($id);

        if ($post === null || $post->active == 0) {
            return false;
        }
        return $user->id === $post->user_id;
    }

}
