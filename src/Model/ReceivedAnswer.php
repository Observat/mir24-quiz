<?php


namespace Observatby\Mir24Quiz\Model;


class ReceivedAnswer
{
    private QuizQuestion $question;
    private QuizAnswer $answer;

    /**
     * ReceivedAnswer constructor.
     * @param QuizQuestion $question
     * @param QuizAnswer $answer
     */
    public function __construct(QuizQuestion $question, QuizAnswer $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }
}
