<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\UserFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UsersResource;
use App\Models\User;

class UsersController extends ApiController
{
    /**
     * index
     *
     * <example description>
     *
     * @group Users
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function index(UserFilter $filter)
    {
        return UsersResource::collection(User::filter($filter)->paginate());
    }

    /**
     * store
     *
     * <example description>
     *
     * @group Users
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * show
     *
     * <example description>
     *
     * @group Users
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function show(User $user)
    {
        if ($this->include('tickets'))
        {
            $user->load('tickets');
        }

        return new UsersResource($user);
    }

    /**
     * update
     *
     * <example description>
     *
     * @group Users
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * destroy
     *
     * <example description>
     *
     * @group Users
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function destroy(User $user)
    {
        //
    }
}
