<?php


namespace Observatby\Mir24Quiz\UseCase;


use Exception;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\Persistence\QuizPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use PDO;
use Psr\Log\LoggerInterface;

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
     * @param LoggerInterface|null $logger
     * @return Id
     * @throws QuizException
     */
    public function handle(array $data, ?LoggerInterface $logger = null): Id
    {
        try {
            $quizDto = QuizToDto::transformFromArray($data);
        } catch (Exception $e) {
            if ($logger !== null) {
                $logger->error(sprintf("[%s] Exception: '%s'",
                    self::class,
                    $e->getMessage(),
                ));
            }
            throw new QuizException(QuizException::INPUT_ARRAY_AND_OUTPUT_DTO_MISMATCH);
        }

        try {
            return $this->repository->create($quizDto);
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
