<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\UseCase;

use DateTimeImmutable;
use Observatby\Mir24Quiz\Model\Uuid;
use Observatby\Mir24Quiz\Model\PublishingManagement;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use Observatby\Mir24Quiz\UseCase\GetQuizDtoFromRawDbIdForEditing;
use PHPUnit\Framework\TestCase;


class GetQuizDtoFromRawDbIdForEditingTest extends TestCase
{
    use CreateQuizTrait;

    public function testHandle(): void
    {
        $id = Uuid::createNew();

        $mockRepository = $this->createMock(QuizRepository::class);
        $mockRepository
            ->method('findById')
            ->willReturn(new Quiz(
                $id,
                'First quiz',
                [
                    $this->createQuizQuestion_1(),
                    $this->createQuizQuestion_2(),
                ],
                new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week')),
            ));

        $quizDto = GetQuizDtoFromRawDbIdForEditing::createWithRepository($mockRepository)
            ->handle($id);

        $this->assertEquals("First quiz", $quizDto->title);
        $this->assertNotNull($quizDto->management);
    }
}
