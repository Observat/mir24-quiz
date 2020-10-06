<?php


namespace Observatby\Mir24Quiz\UseCase;


use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\Repository\Persistence\QuizPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;

class CreateNewQuiz
{
    private QuizRepository $repository;

    private function __construct(QuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function createWithPdo(PDO $pdo): self
    {
        return new self(new QuizRepository(new QuizPersistence($pdo)));
    }

    public static function createWithRepository(QuizRepository $repository): self
    {
        return new self($repository);
    }

    public function handle(QuizDto $quizDto): void
    {
        $this->repository->create($quizDto);
    }
}
