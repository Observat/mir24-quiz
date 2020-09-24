<?php


namespace Observatby\Mir24Quiz\Model;


class ReceivedQuiz
{
    /** @var ReceivedAnswer[] $receivedAnswers */
    private array $receivedAnswers;

    /**
     * ReceivedQuiz constructor.
     * @param ReceivedAnswer[] $receivedAnswers
     */
    public function __construct(array $receivedAnswers)
    {
        $this->receivedAnswers = $receivedAnswers;
    }
}
