<?php


namespace Observatby\Mir24Quiz\Model;


class Quiz
{
    private Id $id;
    private string $title;
    /** @var QuizQuestion[] $questions */
    private array $questions;

    /**
     * Quiz constructor.
     * @param Id $id
     * @param string $title
     * @param QuizQuestion[] $questions
     */
    public function __construct(Id $id, string $title, array $questions)
    {
        $this->id = $id;
        $this->title = $title;
        $this->questions = $questions;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return QuizQuestion[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    public function hasQuestion(QuizQuestion $searchedQuestion): bool
    {
        $searchedQuestionDbId = $searchedQuestion->getId()->toDb();
        foreach ($this->questions as $question) {
            if ($question->getId()->toDb() === $searchedQuestionDbId) {
                return true;
            }
        }

        return false;
    }

}
