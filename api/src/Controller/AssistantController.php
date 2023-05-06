<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route('/api')]
class AssistantController extends AbstractController
{
    #[Route('/assistant', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        // prepare openAI client
        $apiKey = "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";
        $client = \OpenAI::client($apiKey);

        // get request body
        $body = json_decode($request->getContent());

        // map the properties to variables
        $language = $body->language;
        $level = $body->level;
        $userPrompt = $body->prompt;

        // build the final prompt
        $prompt = 'You are Fluentify Assistant AI, start a simple conversation with me as a ' . $level . ' ' . $language . 'speaker.\n' . $userPrompt;

        // request openAi API
        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => array(['role' => 'user', 'content' => $prompt]),
        ]);

        // return the first result
        return $this->json([
            'message' => $result['choices'][0]['message']["content"],
        ]);
    }
}
