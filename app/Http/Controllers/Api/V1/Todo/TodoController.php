<?php

namespace App\Http\Controllers\Api\V1\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Todo\CreateTodoRequest;
use App\Http\Services\Api\V1\Todo\TodoService;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function create(CreateTodoRequest $request, TodoService $todoService): object
    {
        return $todoService->create($request);
    }
}
