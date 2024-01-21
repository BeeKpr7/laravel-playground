<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\View\View;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;

class PersonalBot extends Component
{
    public string $question = '';
    public string $prompt = '';
    public ?string $answer = null;
    public ?string $erro = null;

    private function createAndRunThread(): ThreadRunResponse
    {
        return OpenAI::threads()->createAndRun([
            'assistant_id' => 'asst_JaegvPJoPARwC4XilUPhGQ6x',
            'thread'       => [
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => $this->prompt,
                    ],
                ],
            ],
        ]);
    }

    public function loadAnswer()
    {
        $threadRun = $this->createAndRunThread();

        while (in_array($threadRun->status, ['queued', 'in_progress'])) {
            $threadRun = OpenAI::threads()->runs()->retrieve(
                threadId: $threadRun->threadId,
                runId: $threadRun->id,
            );
        }

        if ($threadRun->status !== 'completed') {
            $this->error = 'Request failed, please try again';
        }

        $messageList = OpenAI::threads()->messages()->list(
            threadId: $threadRun->threadId,
        );

        $this->answer = $messageList->data[0]->content[0]->text->value;

    }

    public function ask(): void
    {
        $this->prompt = $this->question;

        $this->question = "";

        // $this->loadAnswer($threadRun);
        $this->js('$wire.loadAnswer()');

    }
    public function render(): View
    {
        return view('livewire.personal-bot')->extends('partials.layout');
    }
}
