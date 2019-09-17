<?php

namespace App\Http\Controllers\Api\Actions;

use App\UserAction;
use App\Base\BaseController;

class GetController extends BaseController
{
    /**
     * Get All actions done by the user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $actions = UserAction::query()->with(['item' => function ($query) {
            $query->withTrashed();
        }])->get();
        return $this->response->statusOk(['actions' => $actions]);
    }

    /**
     * get Action details
     *
     * @param integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails($id)
    {
        $action = UserAction::query()->with(['item' => function ($query) {
            $query->withTrashed();
        }])->find($id);

        if ($action === null) {
            return $this->response->statusFail(['message' => 'Invalid Action ID']);
        }
        return $this->response->statusOk(['action' => $action]);
    }

}