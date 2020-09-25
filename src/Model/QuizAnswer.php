<?php


namespace Observatby\Mir24Quiz\Model;


class QuizAnswer
{
    private Id $id;
    private bool $correct;
    private string $text;

    public function __construct(Id $id, bool $correct, string $text)
    {
        $this->id = $id;
        $this->correct = $correct;
        $this->text = $text;
    }

    public function getId(): Id
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
