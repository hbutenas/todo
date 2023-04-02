<?php

namespace App\Http\Controllers\Api\V1\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Todo\CreateTodoRequest;
use App\Http\Requests\Api\V1\Todo\UpdateTodoRequest;
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

    /**
     * Get all the Todos of the currently authenticated user.
     *
     * This function calls the index() method of the injected TodoService instance to retrieve
     * all the Todos of the currently authenticated user. The retrieved Todos are returned
     * as a success response object with a 200 status code.
     *
     * @param TodoService $todoService An instance of the TodoService.
     *
     * @return object A success response object with the Todos of the authenticated user.
     */
    public function index(TodoService $todoService): object
    {
        return $todoService->index();
    }

    /**
     * Retrieve a Todo item by ID
     *
     * This function retrieves a Todo item from the database by its ID, as specified in the route parameters.
     * The user ID is retrieved from the Auth::user() function, and the Todo item is retrieved from the database
     * using the TodoService::show() method, which ensures that the Todo belongs to the authenticated user.
     * If the Todo is not found, a failed response is returned with a message indicating that the Todo could not be found.
     *
     * @param TodoService $todoService The TodoService instance to use for retrieving the Todo item.
     *
     * @return object A success response object with the retrieved Todo item, or a failed response object if the Todo is not found.
     */
    public function show(TodoService $todoService): object
    {
        return $todoService->show(request()->route('id'));
    }

    /**
     * Update an existing Todo item.
     *
     * This function updates an existing Todo item with the specified ID and ensures
     * that the Todo belongs to the currently authenticated user. The user ID is
     * retrieved from the Auth::user() function. If the Todo item does not exist or
     * does not belong to the user, a 404 error response is returned. Otherwise, the
     * Todo item is updated with the new information provided in the request and returned
     * in a success response object.
     *
     * @param UpdateTodoRequest $request The validated request object containing the new
     *                                   information for the Todo item.
     * @param TodoService $todoService The TodoService object used to update the Todo item.
     *
     * @return object A success response object containing the updated Todo item. If the
     *               Todo item does not exist or does not belong to the user, a 404 error
     *               response object is returned instead.
     */
    public function update(UpdateTodoRequest $request, TodoService $todoService): object
    {
        return $todoService->update(request()->route('id'), $request);
    }

    /**
     * Delete a Todo item by ID
     *
     * This function deletes a Todo item from the database by its ID, as specified in the route parameters.
     * The user ID is retrieved from the Auth::user() function, and the Todo item is retrieved from the database
     * using the TodoService::destroy() method, which ensures that the Todo belongs to the authenticated user.
     * If the Todo is not found, a failed response is returned with a message indicating that the Todo could not be found.
     *
     * @param TodoService $todoService The TodoService instance to use for deleting the Todo item.
     *
     * @return object A success response object with a message indicating that the Todo item has been deleted,
     * or a failed response object if the Todo item is not found.
     */
    public function destroy(TodoService $todoService): object
    {
        return $todoService->destroy(request()->route('id'));
    }
}
