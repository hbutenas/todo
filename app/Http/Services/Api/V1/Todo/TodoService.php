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
        // get user id
        $userId = Auth::user()->id;

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
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
        ]);

        return $this->successfullRequest($todo, 'Todo successfully created', 201);
    }
}
