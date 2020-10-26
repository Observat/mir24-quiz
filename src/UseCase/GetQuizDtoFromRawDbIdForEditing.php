<?php


namespace Observatby\Mir24Quiz\UseCase;


use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\IdInterface;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use Observatby\Mir24Quiz\UseCase\Traits\CreateWithPdoTrait;

class GetQuizDtoFromRawDbIdForEditing
{
    use CreateWithPdoTrait;

    private QuizRepository $repository;

    private function __construct(QuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function createWithRepository(QuizRepository $repository): self
    {
        return new self($repository);
    }

    public function handle(IdInterface $id): QuizDto
    {
        $quiz = $this->repository->findById($id);

        return QuizToDto::transformForChange($quiz);
    }
}
