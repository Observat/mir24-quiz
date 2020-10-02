<?php


namespace Observatby\Mir24Quiz\UseCase;


use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Repository\Persistence\QuizPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use PDO;

class GetQuizDtoFromRawDbIdForUsing
{
    private QuizRepository $repository;

    private function __construct(QuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function createWithPdo(PDO $pdo): GetQuizDtoFromRawDbIdForUsing
    {
        return new GetQuizDtoFromRawDbIdForUsing(new QuizRepository(new QuizPersistence($pdo)));
    }

    public static function createWithRepository(QuizRepository $repository): GetQuizDtoFromRawDbIdForUsing
    {
        return new GetQuizDtoFromRawDbIdForUsing($repository);
    }

    public function handle(string $rawDbId): QuizDto
    {
        $id = Id::fromDb($rawDbId);

        $quiz = $this->repository->findById($id);

        return QuizToDto::transformForUse($quiz);
    }
}
