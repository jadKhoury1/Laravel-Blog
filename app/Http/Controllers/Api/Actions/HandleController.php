<?php

namespace App\Http\Controllers\Api\Actions;

use App\UserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Base\BaseController;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

class HandleController extends BaseController
{
    /**
     * Stores Success Message
     *
     * @var string
     */
    private $successMessage = 'Action completed successfully';

    /**
     * Stores Failure Message
     *
     * @var string
     */
    private $failureMessage = 'Action could not be done';

    /**
     * Handle User actions
     *
     * @return JsonResponse
     */
    public function handle()
    {
        if ($this->makeValidation() === false) {
            return $this->response->statusFail(['message' => $this->errorMessage]);
        }

        return $this->execute($this->request->all());
    }

    /**
     * Set action validation rules
     *
     * @return array
     */
    protected function setValidationRules()
    {
        return [
            'id'          => 'required|integer|exists:user_actions',
            'action_type' => "required|in:approve,reject"
        ];
    }

    /**
     * Execute Approval and Rejection logic
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    private function execute($data)
    {
        $action = UserAction::query()->with('item')->find($data['id']);

        if ($action->status != UserAction::STATUS_PENDING) {
            return $this->response->statusFail('You already took an action on this item');
        }

        switch ($data['action_type']) {
            case  'approve':
                return $this->approve($action);
            case  'reject':
                return $this->reject($action);
            default:
                return $this->response->statusFail(['message' => $this->failureMessage]);
        }
    }


    /**
     * Handles approval action
     *
     * @param $action \Illuminate\Database\Eloquent\Model
     *
     * @return JsonResponse
     */
    private function approve($action)
    {
        $method = $this->getMethod($action);
        $model  = $this->getModel($action);

        DB::beginTransaction();
        if ($this->{$method}($action, $model) === false||
            $this->changeActionStatus($action, UserAction::STATUS_APPROVED) === false) {
            return $this->response->statusFail(['message' => $this->failureMessage]);
        }
        DB::commit();

        return $this->response->statusOk(['message' => $this->successMessage]);
    }

    /**
     * handles Rejection action
     *
     * @param $action \Illuminate\Database\Eloquent\Model
     *
     * @return JsonResponse
     */
    private function reject($action)
    {
        DB::beginTransaction();
        $model  = $this->getModel($action);

        if (($action->action === UserAction::ACTION_ADD && $this->delete($action, $model) === false ) ||
            $this->changeActionStatus($action, UserAction::STATUS_REJECTED) === false
        ) {
            DB::rollBack();
            return $this->response->statusFail(['message' => $this->failureMessage]);
        }
        DB::commit();

        return $this->response->statusOk(['message' => $this->successMessage]);

    }

    /**
     * Get Method name
     *
     * @param $action \Illuminate\Database\Eloquent\Model
     *
     * @return string
     */
    private function getMethod($action)
    {
        return strtolower($action->action);

    }

    /**
     * Set Item active field to 1
     *
     * @param $action \Illuminate\Database\Eloquent\Model
     * @param $model \Illuminate\Database\Eloquent\Builder
     *
     * @return bool
     */
    protected function add($action, $model)
    {
        try {
            $model->where('id', $action->item_id)->update(['active' => 1]);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Set Item active field to 1
     *
     * @param $action \Illuminate\Database\Eloquent\Model
     * @param $model \Illuminate\Database\Eloquent\Builder
     *
     * @return bool
     */
    protected function edit($action, $model)
    {
        $data = json_decode($action->data, true);
        try {
            $model->where('id', $action->item_id)->update($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete Item
     *
     * @param $action \Illuminate\Database\Eloquent\Model
     * @param $model \Illuminate\Database\Eloquent\Builder
     *
     * @return bool
     */
    protected function delete($action, $model)
    {
        try {
            $model->where('id', $action->item_id)->delete();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Get action model name dynamically
     *
     * @param $action
     *
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    private function getModel($action)
    {
         // Lets say item_type = posts his related model will be App\Post
         $model =  'App\\' . Str::singular(ucfirst($action->item_type));
         return $model::query();

    }

    /**
     * Update the status of the action
     *
     * @param \Illuminate\Database\Eloquent\Model $action
     * @param Integer
     * @return bool
     */
    private function changeActionStatus($action, $status)
    {
        try {
            $action->update(['status' => $status]);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }




}