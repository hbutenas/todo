<?php

namespace App\Http\Services\Api\V1\Todo;

use App\Models\Todo;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class TodoService
{
    use HttpResponses;

    public function create(object $request): object
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
