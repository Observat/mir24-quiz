<?php


namespace Observatby\Mir24Quiz\Model;


class QuizAnswer
{
    private bool $correct;
    private string $text;

    /**
     * QuizAnswer constructor.
     * @param bool $correct
     * @param string $text
     */
    public function __construct(bool $correct, string $text)
    {
        $this->correct = $correct;
        $this->text = $text;
    }
}
