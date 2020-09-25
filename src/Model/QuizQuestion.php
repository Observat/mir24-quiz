<?php


namespace Observatby\Mir24Quiz\Model;


class QuizQuestion
{
    private Id $id;
    private string $text;
    private Image $image;
    /** @var QuizAnswer[] $answers */
    private array $answers;

    /**
     * QuizQuestion constructor.
     * @param Id $id
     * @param string $text
     * @param Image $image
     * @param QuizAnswer[] $answers
     */
    public function __construct(Id $id, string $text, Image $image, array $answers)
    {
        $this->id = $id;
        $this->text = $text;
        $this->image = $image;
        $this->answers = $answers;
    }

    public function getId(): Id
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
        $searchedAnswerDbId = $searchedAnswer->getId()->toDb();
        foreach ($this->answers as $answer) {
            if ($answer->getId()->toDb() === $searchedAnswerDbId) {
                return true;
            }
        }

        return false;
    }
}
