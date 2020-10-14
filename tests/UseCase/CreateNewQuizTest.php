<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\UseCase;

use DateTimeImmutable;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\Persistence\DummyPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\UseCase\CreateNewQuiz;
use PHPUnit\Framework\TestCase;


class CreateNewQuizTest extends TestCase
{
    public function testEmptyData(): void
    {
        $repository = new QuizRepository(new DummyPersistence());
        $data = [];

        $this->expectException(QuizException::class);
        $this->expectExceptionMessage(QuizException::INPUT_ARRAY_AND_OUTPUT_DTO_MISMATCH);

        CreateNewQuiz::createWithRepository($repository)->handle($data);
    }

    public function testHandle(): void
    {
        $id = Id::createNew();

        $data = [
            'id' => $id->toString(),
            'title' => 'New quiz',
            'questions' => [
                [
                    'id' => Id::createNew()->toString(),
                    'text' => 'question_text',
                    'imageSrc' => 'question_image_src',
                    'answers' => [
                        [
                            'id' => Id::createNew()->toString(),
                            'text' => 'answer_text1',
                            'correct' => true,
                        ],
                        [
                            'id' => Id::createNew()->toString(),
                            'text' => 'answer_text2',
                            'correct' => false,
                        ],
                    ],
                ]
            ],
            'management' => [
                'enabled' => true,
                'beginDate' => (new DateTimeImmutable('now - 1 day')),
                'endDate' => (new DateTimeImmutable('now + 1 day')),
            ]
        ];

        $mockRepository = $this->createMock(QuizRepository::class);
        $mockRepository
            ->expects($this->once())
            ->method('create');

        CreateNewQuiz::createWithRepository($mockRepository)->handle($data);
    }
}
