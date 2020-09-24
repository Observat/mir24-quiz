<?php


namespace Observatby\Mir24Quiz\Model;


class QuizQuestion
{
    private string $text;
    /** @var QuizAnswer[] $answers */
    private array $answers;

    /**
     * QuizQuestion constructor.
     * @param string $text
     * @param QuizAnswer[] $answers
     */
    public function __construct(string $text, array $answers)
    {
        $this->text = $text;
        $this->answers = $answers;
    }
}
