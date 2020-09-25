<?php


namespace Observatby\Mir24Quiz\Model;


class ReceivedAnswer
{
    private Id $id;
    private QuizQuestion $question;
    private QuizAnswer $answer;

    /**
     * ReceivedAnswer constructor.
     * @param Id $id
     * @param QuizQuestion $question
     * @param QuizAnswer $answer
     */
    public function __construct(Id $id, QuizQuestion $question, QuizAnswer $answer)
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
