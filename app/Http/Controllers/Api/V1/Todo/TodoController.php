<?php

namespace App\Http\Controllers\Api\V1\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Todo\CreateTodoRequest;
use App\Http\Services\Api\V1\Todo\TodoService;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /*
     * This function receives a CreateTodoRequest object containing the data for the new
     * Todo item, and a TodoService object that handles the creation of the Todo. The
     * TodoService::create() function is called with the request object to create the
     * new Todo item, and the resulting response object is returned from the function.
     *
     * @param CreateTodoRequest $request The request object containing the Todo data.
     * @param TodoService $todoService The TodoService object used to create the Todo.
     *
     * @return object The response object returned from the TodoService::create() function.
     */
    public function store(CreateTodoRequest $request, TodoService $todoService): object
    {
        return $todoService->store($request);
    }
}
