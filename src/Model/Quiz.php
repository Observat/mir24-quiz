<?php


namespace Observatby\Mir24Quiz\Model;


class Quiz
{
    /** @var QuizQuestion[] $questions */
    private array $questions;

    /**
     * Quiz constructor.
     * @param QuizQuestion[] $questions
     */
    public function __construct(array $questions)
    {
        $this->questions = $questions;
    }
}
