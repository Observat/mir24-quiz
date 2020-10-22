<?php


namespace Observatby\Mir24Quiz\UseCase;


use Observatby\Mir24Quiz\Dto\ListOfQuizDto;
use Observatby\Mir24Quiz\Repository\ListOfQuizRepository;
use Observatby\Mir24Quiz\Repository\Persistence\QuizWithUuidPersistence;
use PDO;


class GetListOfQuiz
{
    private ListOfQuizRepository $repository;

    private function __construct(ListOfQuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function createWithPdo(PDO $pdo): self
    {
        return new self(new ListOfQuizRepository(new QuizWithUuidPersistence($pdo)));
    }

    public static function createWithRepository(ListOfQuizRepository $repository): self
    {
        return new self($repository);
    }

    public function handle(): ListOfQuizDto
    {
        return $this->repository->getListOfQuiz();
    }
}
