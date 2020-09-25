<?php


namespace Observatby\Mir24Quiz\Model;


class QuizQuestion
{
    private Id $id;
    private string $text;
    /** @var QuizAnswer[] $answers */
    private array $answers;

    /**
     * QuizQuestion constructor.
     * @param Id $id
     * @param string $text
     * @param QuizAnswer[] $answers
     */
    public function __construct(Id $id, string $text, array $answers)
    {
        $this->id = $id;
        $this->text = $text;
        $this->answers = $answers;
    }

    public function hasAnswer(QuizAnswer $searchedAnswer): bool
    {
        $searchedAnswerDbId = $searchedAnswer->getId()->toDb();
        foreach ($this->answers as $answer) {
            if ($answer->getId()->toDb() === $searchedAnswerDbId) {
                return true;
            }
        }

        return false;
    }
}
