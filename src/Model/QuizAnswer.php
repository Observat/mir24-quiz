<?php


namespace Observatby\Mir24Quiz\Model;


class QuizAnswer
{
    private Id $id;
    private bool $correct;
    private string $text;

    /**
     * QuizAnswer constructor.
     * @param Id $id
     * @param bool $correct
     * @param string $text
     */
    public function __construct(Id $id, bool $correct, string $text)
    {
        $this->id = $id;
        $this->correct = $correct;
        $this->text = $text;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }
}
