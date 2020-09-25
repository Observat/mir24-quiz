<?php


namespace Observatby\Mir24Quiz\Model;


class ReceivedQuiz
{
    private Id $id;
    private Quiz $quiz;
    /** @var ReceivedAnswer[] $receivedAnswers */
    private array $receivedAnswers;

    /**
     * ReceivedQuiz constructor.
     * @param Id $id
     * @param Quiz $quiz
     * @param ReceivedAnswer[] $receivedAnswers
     */
    public function __construct(Id $id, Quiz $quiz, array $receivedAnswers)
    {
        $this->id = $id;
        $this->quiz = $quiz;
        $this->receivedAnswers = $receivedAnswers;
    }

    public function countSuccessAnswer(): int
    {
        $cnt = 0;

        foreach ($this->receivedAnswers as $receivedAnswer) {
            if ($receivedAnswer->isCorrect($this->quiz)) {
                $cnt++;
            }
        }

        return $cnt;
    }
}
