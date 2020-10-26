<?php


namespace Observatby\Mir24Quiz\UseCase;


use Observatby\Mir24Quiz\IdInterface;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\UseCase\Traits\CreateWithPdoTrait;
use Psr\Log\LoggerInterface;

class DeleteQuiz
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

    /**
     * @param IdInterface $id
     * @param LoggerInterface|null $logger
     * @throws QuizException
     */
    public function handle(IdInterface $id, ?LoggerInterface $logger = null): void
    {
        try {
            $this->repository->delete($id);
        } catch (QuizException $e) {
            if ($logger !== null && $e->getPrevious() !== null) {
                $logger->error(sprintf("[%s] Exception: '%s'. Stack trace: %s",
                    self::class,
                    $e->getPrevious()->getMessage(),
                    $e->getPrevious()->getTraceAsString()
                ));
            }
            throw new QuizException($e->getMessage(), $e->getCode());
        }
    }
}
