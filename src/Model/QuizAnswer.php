<?php


namespace Observatby\Mir24Quiz\Model;


use Observatby\Mir24Quiz\Dto\AnswerDto;
use Observatby\Mir24Quiz\IdInterface;

class QuizAnswer
{
    private IdInterface $id;
    private bool $correct;
    private string $text;

    public function __construct(IdInterface $id, bool $correct, string $text)
    {
        $this->id = $id;
        $this->correct = $correct;
        $this->text = $text;
    }

    public static function fromDto(AnswerDto $dto): self
    {
        return new self(
            Uuid::fromString($dto->id), # TODO
            $dto->correct,
            $dto->text);
    }

    public function getId(): IdInterface
    {
        return $this->id;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
