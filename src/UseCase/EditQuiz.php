<?php


namespace Observatby\Mir24Quiz\UseCase;


use Exception;
use Observatby\Mir24Quiz\IdInterface;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use Observatby\Mir24Quiz\UseCase\Traits\CreateWithPdoTrait;
use Psr\Log\LoggerInterface;

class EditQuiz
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
     * @param array $data
     * @param LoggerInterface|null $logger
     * @return IdInterface
     * @throws QuizException
     */
    public function handle(array $data, ?LoggerInterface $logger = null): IdInterface
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
            return $this->repository->update($quizDto);
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
