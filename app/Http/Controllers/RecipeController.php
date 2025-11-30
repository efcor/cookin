<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateRequest;
use App\Models\ApiLog;
use App\Models\Interaction;
use Illuminate\Http\Request;
use OpenAI;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $recipe = Interaction::find($request->input('r', 999999999));

        return view('recipe-index', compact('recipe'));
    }

    public function generate(GenerateRequest $request)
    {
        // example input:
        // 'ingredients' // ['Ground beef', 'Salmon', 'Shrimp']
        // 'cuisine' // 'Mexican' // special value: 'Any'
        // 'diet' // 'Vegan' // special value: 'None'
        // 'servings' // 2
        // 'minutes' // 30 // special value: 0 (means any)

        // example model response (json):
        // {
        //     "title": "Cheesy Garlic Chicken Stir-Fry",
        //     "ingredients": [
        //         "2 chicken breasts, sliced into thin strips",
        //         "2 tablespoons butter",
        //         "1 cup cheddar cheese, shredded"
        //     ],
        //     "steps": [
        //         "Heat the oil and butter in a large skillet over medium heat.",
        //         "Add the sliced onion and minced garlic to the skillet. Sauté for 2-3 minutes until the onion is translucent."
        //     ],
        //     "timeMinutes": 30,
        //     "servings": 3,
        //     "cuisine": "Asian"
        // }

        $input = $request->validated();
        $ingredients = implode(', ', $request->input('ingredients'));

        $prompt = "Generate a recipe using some or all of the following ingredients: $ingredients.\n\n"
            . "Please omit any ingredients as needed to make the recipe be more cohesive or taste better, especially if there are many ingredients. "
            . "The recipe shouldn't require any ingredients that aren't in the list above, except for exceedingly "
            . "common on-hand ingredients like salt, pepper, garlic powder, milk, and oil."
            . ($input['cuisine'] === 'Any' ? '' : ('The recipe should be '.$input['cuisine'].' cuisine. '))
            . ($input['minutes'] === 0 ? '' : ('The recipe should take '.$input['minutes'].' minutes or less to make. '))
            . ($input['diet'] === 'None' ? '' : ('Make sure the recipe adheres to a '.$input['diet'].' diet. '))
            . ('Make the recipe instructions be such that it will yield about '.$input['servings'].' servings. ')
            . 'Thank you for your help!';

        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful recipe generator that outputs structured JSON.'],
            ['role' => 'user', 'content' => $prompt],
            ['role' => 'assistant', 'content' => 'The JSON structure must be: {"title": string, "ingredients": [string], "steps": [string], "timeMinutes": number, "servings": number, "cuisine": string}']
        ];

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => 'gpt-4o', // or 'gpt-4o-mini'
            'messages' => $messages,
        ]);

        $responseMessage = $response->choices[0]->message->content;

        ApiLog::create(['request_body' => json_encode($messages), 'response_message' => $responseMessage]);

        // The json that comes back from the model has ```json before the
        // acutal json and then ``` after, so remove those.
        $json = trim(trim($responseMessage, '```'), 'json');

        $interaction = Interaction::create(['username' => session('username'), 'input' => json_encode($input), 'output' => $json]);

        $payload = json_decode($json, true);

        $payload['id'] = $interaction->id;

        return response()->json($payload);
    }

    public function fakeGenerate()
    {
        $json = '
            {
            "title": "Cheesy Garlic Chicken Stir-Fry",
            "ingredients": [
                "2 chicken breasts, sliced into thin strips",
                "2 tablespoons butter",
                "1 cup cheddar cheese, shredded",
                "1 medium tomato, diced",
                "1 small onion, sliced",
                "3 cloves garlic, minced",
                "Salt, to taste",
                "Pepper, to taste",
                "1 tablespoon oil"
            ],
            "steps": [
                "Heat the oil and butter in a large skillet over medium heat.",
                "Add the sliced onion and minced garlic to the skillet. Sauté for 2-3 minutes until the onion is translucent.",
                "Increase the heat to medium-high and add the chicken strips. Season with salt and pepper. Stir-fry for about 5-7 minutes until the chicken is cooked through.",
                "Once the chicken is cooked, add the diced tomato to the skillet. Cook for an additional 2 minutes until the tomatoes start to soften.",
                "Reduce the heat to low and sprinkle the shredded cheddar cheese evenly over the chicken and vegetables. Cover the skillet and let the cheese melt, about 2-3 minutes.",
                "Once the cheese has melted, give it a good stir to combine everything.",
                "Serve hot as a main dish."
            ],
            "timeMinutes": 30,
            "servings": 3,
            "cuisine": "Asian"
            }
        ';

        return response()->json(json_decode($json, true));
    }

    public function seeAll()
    {
        $mine = Interaction::where('username', session('username'))->get();
        $others = Interaction::where('username', '!=', session('username'))->get();

        return view('recipe-see-all', compact('mine', 'others'));
    }
}
