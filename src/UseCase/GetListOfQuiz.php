<?php


namespace Observatby\Mir24Quiz\UseCase;


use Observatby\Mir24Quiz\Dto\ListOfQuizDto;
use Observatby\Mir24Quiz\Enum\IdTypeEnum;
use Observatby\Mir24Quiz\Repository\ListOfQuizRepository;
use PDO;


class GetListOfQuiz
{
    private ListOfQuizRepository $repository;

    private function __construct(ListOfQuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function createWithPdo(PDO $pdo, IdTypeEnum $idTypeEnum): self
    {
        return new self(new ListOfQuizRepository($idTypeEnum->getListPersistence($pdo), $idTypeEnum));
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
