<?php


namespace Observatby\Mir24Quiz\Model;


class ReceivedQuiz
{
    private Id $id;
    /** @var ReceivedAnswer[] $receivedAnswers */
    private array $receivedAnswers;

    /**
     * ReceivedQuiz constructor.
     * @param Id $id
     * @param ReceivedAnswer[] $receivedAnswers
     */
    public function __construct(Id $id, array $receivedAnswers)
    {
        $this->id = $id;
        $this->receivedAnswers = $receivedAnswers;
    }
}
