<?php


namespace Observatby\Mir24Quiz\Repository;


use DateTimeImmutable;
use Observatby\Mir24Quiz\Dto\ListOfQuizDto;
use Observatby\Mir24Quiz\Dto\QuizMinForListOfQuizDto;
use Observatby\Mir24Quiz\Model\Id;


class ListOfQuizRepository
{
    private ListPersistenceInterface $persistence;

    public function __construct(ListPersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    public function getListOfQuiz(): ListOfQuizDto
    {
        $rows = $this->persistence->retrieveList();

        $listDto = new ListOfQuizDto();

        $quizzes = [];
        foreach ($rows as $row) {
            $quizDto = new QuizMinForListOfQuizDto();
            $quizDto->id = Id::fromDb($row['quiz_id'])->toString();
            $quizDto->title = $row['quiz_title'];
            $quizDto->enabled = $row['enabled'];
            $quizDto->beginDate = new DateTimeImmutable($row['begin_date']);
            $quizDto->endDate = new DateTimeImmutable($row['end_date']);

            $quizzes[] = $quizDto;
        }
        $listDto->quizzes = $quizzes;

        return $listDto;
    }
}
