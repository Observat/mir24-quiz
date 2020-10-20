<?php


namespace Observatby\Mir24Quiz\Model;


use Observatby\Mir24Quiz\Dto\QuestionDto;
use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\IdInterface;

class Quiz
{
    private IdInterface $id;
    private string $title; # TODO unique? length>0?
    /** @var QuizQuestion[] $questions */
    private array $questions;
    private ?PublishingManagement $publishingManagement;

    /**
     * Quiz constructor.
     * @param IdInterface $id
     * @param string $title
     * @param QuizQuestion[] $questions
     * @param PublishingManagement|null $publishingManagement
     */
    public function __construct(IdInterface $id, string $title, array $questions, ?PublishingManagement $publishingManagement = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->questions = $questions;
        $this->publishingManagement = $publishingManagement;
    }

    public static function fromDto(QuizDto $dto): self
    {
        return new self(
            Id::fromString($dto->id), # TODO
            $dto->title,
            array_map(
                function (QuestionDto $questionDto) {
                    return QuizQuestion::fromDto($questionDto);
                },
                $dto->questions
            ),
            $dto->management ? PublishingManagement::fromDto($dto->management) : null
        );
    }

    public function getId(): IdInterface
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
        $searchedQuestionDbId = $searchedQuestion->getId()->toString();
        foreach ($this->questions as $question) {
            if ($question->getId()->toString() === $searchedQuestionDbId) {
                return true;
            }
        }

        return false;
    }

}
