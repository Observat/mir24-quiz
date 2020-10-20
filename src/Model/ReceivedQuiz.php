<?php


namespace Observatby\Mir24Quiz\Model;


use Observatby\Mir24Quiz\IdInterface;

class ReceivedQuiz
{
    private IdInterface $id;
    private Quiz $quiz;
    /** @var ReceivedAnswer[] $receivedAnswers */
    private array $receivedAnswers;

    /**
     * ReceivedQuiz constructor.
     * @param IdInterface $id
     * @param Quiz $quiz
     * @param ReceivedAnswer[] $receivedAnswers
     */
    public function __construct(IdInterface $id, Quiz $quiz, array $receivedAnswers)
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
