<?php


namespace Observatby\Mir24Quiz\Model;


use Observatby\Mir24Quiz\Dto\QuestionDto;
use Observatby\Mir24Quiz\Dto\QuizDto;

class Quiz
{
    private Id $id;
    private string $title;
    /** @var QuizQuestion[] $questions */
    private array $questions;
    private ?PublishingManagement $publishingManagement;

    /**
     * Quiz constructor.
     * @param Id $id
     * @param string $title
     * @param QuizQuestion[] $questions
     * @param PublishingManagement|null $publishingManagement
     */
    public function __construct(Id $id, string $title, array $questions, ?PublishingManagement $publishingManagement = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->questions = $questions;
        $this->publishingManagement = $publishingManagement;
    }

    public static function fromDto(QuizDto $dto): self
    {
        return new self(
            Id::fromDb($dto->id),
            $dto->title,
            array_map(
                function (QuestionDto $questionDto) {
                    return QuizQuestion::fromDto($questionDto);
                },
                $dto->questions
            ),
            null # TODO
        );
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

    public function getPublishingManagement(): ?PublishingManagement
    {
        return $this->publishingManagement;
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
