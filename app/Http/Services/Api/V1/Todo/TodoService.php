<?php

namespace App\Http\Services\Api\V1\Todo;

use App\Models\Todo;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class TodoService
{
    use HttpResponses;

    /*
     * Create a new Todo item
     *
     * This function creates a new Todo item based on the data provided in the $request object.
     * The user ID is retrieved from the Auth::user() function, and the title is capitalized
     * using the ucfirst() function. The description is split into sentences using a regular
     * expression, and each sentence is modified to capitalize the first letter of the first
     * word using a foreach loop. The modified sentences are then joined back into a string.
     * The Todo item is then created in the database using the Todo::create() function, and
     * a success response is returned with the created Todo item and a message indicating
     * that the Todo was successfully created.
     *
     * @param object $request The request object containing the Todo data.
     *
     * @return object A success response object with the created Todo item and a success message.
     * */
    public function store(object $request): object
    {
        // make sure that title starts capitalized
        $title = ucfirst($request->title);

        // split the description into sentences using a regular expression
        $sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $request->description);

        // loop through each sentence and modify it
        foreach ($sentences as &$sentence) {
            // Split the sentence into words
            $words = explode(' ', $sentence);

            // Capitalize the first letter of the first word
            $words[0] = ucfirst($words[0]);

            // Join the modified words back into a sentence
            $sentence = implode(' ', $words);
        }

        // Join the modified sentences back into a string
        $description = implode(' ', $sentences);

        // create todo
        $todo = Todo::create([
            'user_id' => Auth::user()->id,
            'title' => $title,
            'description' => $description,
        ]);

        return $this->successfullRequest($todo, 'Todo successfully created', 201);
    }

    /**
     * Retrieve all Todo items for the authenticated user
     *
     * This function retrieves all the Todo items for the authenticated user based on their user ID.
     * The user ID is retrieved from the Auth::user() function. The todos are then queried from the database
     * using the Todo::where() function, which filters the results based on the user ID. The retrieved todos are
     * returned in a success response object along with a message indicating if the request was successful and if any
     * todos were retrieved. If no todos are retrieved, the message indicates that the user does not have any todos.
     *
     * @return object A success response object with the retrieved Todo items and a message indicating
     * that the request was successful or that the user does not have any todos.
     */
    public function index(): object
    {
        // get todos
        $todos = Todo::where('user_id', Auth::user()->id)->get();

        $message = $todos->count() > 0 ? 'Request successful' : 'User does not have any todos';

        return $this->successfullRequest($todos, $message, 200);
    }

    /**
     * Retrieve a single Todo item by ID.
     *
     * This function retrieves a single Todo item with the specified ID and ensures
     * that the Todo belongs to the currently authenticated user. The user ID is
     * retrieved from the Auth::user() function. If the Todo item does not exist or
     * does not belong to the user, a 404 error response is returned. Otherwise, the
     * Todo item is returned in a success response object.
     *
     * @param int $todoId The ID of the Todo item to retrieve.
     *
     * @return object A success response object containing the retrieved Todo item.
     *               If the Todo item does not exist or does not belong to the user,
     *               a 404 error response object is returned instead.
     */
    public function show(int $todoId): object
    {
        // get todo by todo id and ensure that todo belongs to user
        $todo = Todo::where('id', $todoId)->where('user_id', Auth::user()->id)->first();

        // todo not found
        if (!$todo) {
            return $this->failedRequest('', "Todo with id $todoId does not found", 404);
        }

        return $todo;
    }

    /**
     * Update a single Todo item by ID.
     *
     * This function updates a single Todo item with the specified ID and ensures
     * that the Todo belongs to the currently authenticated user. The user ID is
     * retrieved from the `Auth::user()` function. If the Todo item does not exist or
     * does not belong to the user, a 404 error response is returned. Otherwise, the
     * Todo item is updated and returned in a success response object.
     *
     * @param int $todoId The ID of the Todo item to update.
     * @param object $request The request object containing the new values for the Todo item.
     *
     * @return object A success response object containing the updated Todo item.
     *                If the Todo item does not exist or does not belong to the user,
     *                a 404 error response object is returned instead.
     */
    public function update(int $todoId, object $request): object
    {
        // get todo by todo id and ensure that todo belongs to user
        $todo = Todo::where('id', $todoId)->where('user_id', Auth::user()->id)->first();

        // todo not found
        if (!$todo) {
            return $this->failedRequest('', "Todo with id $todoId does not found", 404);
        }

        // update todo
        Todo::where('id', $todoId)->where('user_id', Auth::user()->id)->update([
            'title' => $request->title ? $request->title : $todo->title,
            'description' => $request->description ? $request->description : $todo->description,
            'status' => $request->status ? $request->status : $todo->status
        ]);

        return $this->successfullRequest(Todo::find($todoId), 'Todo successfully updated', 200);
    }

    /**
     *
     * Delete a Todo item by ID.
     * This function retrieves a Todo item by its ID and ensures that it belongs to the currently authenticated user.
     * If the Todo item is not found or does not belong to the user, a 404 error response is returned.
     * Otherwise, the Todo item is deleted from the database and a success response object is returned.
     *
     * @param int $todoId The ID of the Todo item to be deleted.
     *
     * @return object A success response object indicating that the Todo item has been deleted,
     * or a failed response object with an error message and status code.
     */
    public function destroy(int $todoId): object
    {
        // get todo by todo id and ensure that todo belongs to user
        $todo = Todo::where('id', $todoId)->where('user_id', Auth::user()->id)->first();

        // todo not found
        if (!$todo) {
            return $this->failedRequest('', "Todo with id $todoId does not found", 404);
        }

        // delete todo
        Todo::where('id', $todoId)->where('user_id', Auth::user()->id)->delete();

        return $this->successfullRequest('', 'Todo successfully deleted', 200);
    }
}
