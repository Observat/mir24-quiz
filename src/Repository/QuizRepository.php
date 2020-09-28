<?php


namespace Observatby\Mir24Quiz\Repository;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;

class QuizRepository
{
    use CreateQuizTrait;

    private PersistenceInterface $persistence;

    public function __construct(PersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    public function findById(Id $id): Quiz
    {
        $row = $this->persistence->retrieve($id);

        return new Quiz(
            $id,
            $row['title'],
            [
                $this->createQuizQuestion_1(), # TODO
            ],
            null
        );
    }
}
