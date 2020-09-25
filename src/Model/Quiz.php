<?php


namespace Observatby\Mir24Quiz\Model;


class Quiz
{
    private Id $id;
    /** @var QuizQuestion[] $questions */
    private array $questions;

    /**
     * Quiz constructor.
     * @param Id $id
     * @param QuizQuestion[] $questions
     */
    public function __construct(Id $id, array $questions)
    {
        $this->id = $id;
        $this->questions = $questions;
    }
}
