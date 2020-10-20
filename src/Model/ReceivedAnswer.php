<?php


namespace Observatby\Mir24Quiz\Model;


use Observatby\Mir24Quiz\IdInterface;

class ReceivedAnswer
{
    private IdInterface $id;
    private QuizQuestion $question;
    private QuizAnswer $answer;

    public function __construct(IdInterface $id, QuizQuestion $question, QuizAnswer $answer)
    {
        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;
    }


    public function isCorrect(Quiz $quiz): bool
    {
        return $quiz->hasQuestion($this->question)
            && $this->question->hasAnswer($this->answer)
            && $this->answer->isCorrect();
    }
}
