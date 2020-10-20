<?php


namespace Observatby\Mir24Quiz\Model;


use Observatby\Mir24Quiz\Dto\AnswerDto;
use Observatby\Mir24Quiz\Dto\QuestionDto;
use Observatby\Mir24Quiz\IdInterface;

class QuizQuestion
{
    private IdInterface $id;
    private string $text;
    private Image $image;
    /** @var QuizAnswer[] $answers */
    private array $answers;

    /**
     * QuizQuestion constructor.
     * @param IdInterface $id
     * @param string $text
     * @param Image $image
     * @param QuizAnswer[] $answers
     */
    public function __construct(IdInterface $id, string $text, Image $image, array $answers)
    {
        $this->id = $id;
        $this->text = $text;
        $this->image = $image;
        $this->answers = $answers;
    }

    public static function fromDto(QuestionDto $dto): self
    {
        return new self(
            Id::fromString($dto->id), # TODO
            $dto->text,
            new Image($dto->imageSrc),
            array_map(function (AnswerDto $answerDto) {
                return QuizAnswer::fromDto($answerDto);
            }, $dto->answers)
        );
    }

    public function getId(): IdInterface
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @return QuizAnswer[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function hasAnswer(QuizAnswer $searchedAnswer): bool
    {
        $searchedAnswerDbId = $searchedAnswer->getId()->toString();
        foreach ($this->answers as $answer) {
            if ($answer->getId()->toString() === $searchedAnswerDbId) {
                return true;
            }
        }

        return false;
    }
}
