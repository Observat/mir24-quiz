<?php


namespace Observatby\Mir24Quiz\UseCase;


use Exception;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\Persistence\QuizPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;

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

    /**
     * @param array $data
     * @throws QuizException
     */
    public function handle(array $data): void
    {
        try {
            $quizDto = QuizToDto::transformFromArray($data);
        } catch (Exception $e) {
            throw new QuizException(QuizException::INPUT_ARRAY_AND_OUTPUT_DTO_MISMATCH);
        }

        $this->repository->create($quizDto);
    }
}
