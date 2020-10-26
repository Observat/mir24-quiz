<?php


namespace Observatby\Mir24Quiz\Repository;


use DateTimeImmutable;
use Observatby\Mir24Quiz\Dto\ListOfQuizDto;
use Observatby\Mir24Quiz\Dto\QuizMinForListOfQuizDto;
use Observatby\Mir24Quiz\Enum\IdTypeEnum;
use Observatby\Mir24Quiz\IdInterface;


class ListOfQuizRepository
{
    private ListPersistenceInterface $persistence;
    private IdInterface $idInterface;

    public function __construct(ListPersistenceInterface $persistence, IdTypeEnum $idTypeEnum)
    {
        $this->persistence = $persistence;
        $this->idInterface = $idTypeEnum->getIdInterface();
    }

    public function getListOfQuiz(): ListOfQuizDto
    {
        $rows = $this->persistence->retrieveList();

        $listDto = new ListOfQuizDto();

        $quizzes = [];
        foreach ($rows as $row) {
            $quizDto = new QuizMinForListOfQuizDto();
            $quizDto->id = ($this->idInterface)::fromDb($row['quiz_id'])->toString();
            $quizDto->title = $row['quiz_title'];
            $quizDto->enabled = $row['enabled'];
            $quizDto->beginDate = $row['begin_date'] !== null ? new DateTimeImmutable($row['begin_date']) : null;
            $quizDto->endDate = $row['end_date'] !== null ? new DateTimeImmutable($row['end_date']) : null;

            $quizzes[] = $quizDto;
        }
        $listDto->quizzes = $quizzes;

        return $listDto;
    }
}
