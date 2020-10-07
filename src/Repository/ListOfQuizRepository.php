<?php


namespace Observatby\Mir24Quiz\Repository;


use Observatby\Mir24Quiz\Dto\ListOfQuizDto;
use Observatby\Mir24Quiz\Dto\QuizMinForListOfQuizDto;

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
            $quizDto->id = $row['quiz_id'];
            $quizDto->title = $row['quiz_title'];

            $quizzes[] = $quizDto;
        }
        $listDto->quizzes = $quizzes;

        return $listDto;
    }
}
