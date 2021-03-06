<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\Unit;

use DateTimeImmutable;
use Observatby\Mir24Quiz\Enum\IdTypeEnum;
use Observatby\Mir24Quiz\Model\IntId;
use Observatby\Mir24Quiz\Model\PublishingManagement;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Model\Uuid;
use Observatby\Mir24Quiz\Repository\Persistence\DummyPersistence;
use Observatby\Mir24Quiz\Repository\Persistence\QuizWithUuidPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use PDO;
use PHPUnit\Framework\TestCase;

class QuizRepositoryTest extends TestCase
{
    use CreateQuizTrait;

    public function testHasCreatedQuizRepository(): void
    {
        $repository1 = new QuizRepository(
            new DummyPersistence(),
            IdTypeEnum::BINARY_UUID()
        );
        $this->assertInstanceOf(QuizRepository::class, $repository1);

        $mockPdo = $this->createMock(PDO::class);
        $repository2 = new QuizRepository(
            new QuizWithUuidPersistence($mockPdo),
            IdTypeEnum::BINARY_UUID()
        );
        $this->assertInstanceOf(QuizRepository::class, $repository2);
    }

    public function testFindByIdWithUuid(): void
    {
        $id = Uuid::createNew();
        $questionId = Uuid::createNew();

        $mockPersistence = $this->createMock(QuizWithUuidPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('retrieve')
            ->willReturn([
                [
                    'quiz_id' => $id->toDb(),
                    'quiz_title' => 'quiz_title',
                    'question_id' => $questionId->toDb(),
                    'question_text' => 'question_text',
                    'question_image_src' => 'question_image_src',
                    'answer_id' => Uuid::createNew()->toDb(),
                    'answer_text' => 'answer_text1',
                    'answer_correct' => true,
                    'enabled' => 1,
                    'begin_date' => (new DateTimeImmutable('now - 1 day'))->format(DateTimeImmutable::ISO8601),
                    'end_date' => (new DateTimeImmutable('now + 1 day'))->format(DateTimeImmutable::ISO8601),
                ],
                [
                    'quiz_id' => $id->toDb(),
                    'quiz_title' => 'quiz_title',
                    'question_id' => $questionId->toDb(),
                    'question_text' => 'question_text',
                    'question_image_src' => 'question_image_src',
                    'answer_id' => Uuid::createNew()->toDb(),
                    'answer_text' => 'answer_text2',
                    'answer_correct' => false,
                    'enabled' => 1,
                    'begin_date' => (new DateTimeImmutable('now - 1 day'))->format(DateTimeImmutable::ISO8601),
                    'end_date' => (new DateTimeImmutable('now + 1 day'))->format(DateTimeImmutable::ISO8601),
                ],
            ]);

        /** @var QuizWithUuidPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence, IdTypeEnum::BINARY_UUID());

        $quiz = $repository->findById($id);

        $this->assertEquals('quiz_title', $quiz->getTitle());
        $this->assertCount(1, $quiz->getQuestions());
        $this->assertEquals('question_text', $quiz->getQuestions()[0]->getText());
        $this->assertCount(2, $quiz->getQuestions()[0]->getAnswers());
        $this->assertTrue($quiz->getQuestions()[0]->getAnswers()[0]->isCorrect());
        $this->assertFalse($quiz->getQuestions()[0]->getAnswers()[1]->isCorrect());
        $this->assertEquals('answer_text2', $quiz->getQuestions()[0]->getAnswers()[1]->getText());
        $this->assertNotNull($quiz->getPublishingManagement());
        $this->assertTrue($quiz->getPublishingManagement()->isEnabled());
    }

    public function testFindByIdWithIntId(): void
    {
        $id = IntId::createNew();
        $questionId = IntId::createNew();

        $mockPersistence = $this->createMock(QuizWithUuidPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('retrieve')
            ->willReturn([
                [
                    'quiz_id' => $id->toDb(),
                    'quiz_title' => 'quiz_title',
                    'question_id' => $questionId->toDb(),
                    'question_text' => 'question_text',
                    'question_image_src' => 'question_image_src',
                    'answer_id' => Uuid::createNew()->toDb(),
                    'answer_text' => 'answer_text1',
                    'answer_correct' => true,
                    'enabled' => 1,
                    'begin_date' => (new DateTimeImmutable('now - 1 day'))->format(DateTimeImmutable::ISO8601),
                    'end_date' => (new DateTimeImmutable('now + 1 day'))->format(DateTimeImmutable::ISO8601),
                ],
                [
                    'quiz_id' => $id->toDb(),
                    'quiz_title' => 'quiz_title',
                    'question_id' => $questionId->toDb(),
                    'question_text' => 'question_text',
                    'question_image_src' => 'question_image_src',
                    'answer_id' => Uuid::createNew()->toDb(),
                    'answer_text' => 'answer_text2',
                    'answer_correct' => false,
                    'enabled' => 1,
                    'begin_date' => (new DateTimeImmutable('now - 1 day'))->format(DateTimeImmutable::ISO8601),
                    'end_date' => (new DateTimeImmutable('now + 1 day'))->format(DateTimeImmutable::ISO8601),
                ],
            ]);

        /** @var QuizWithUuidPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence, IdTypeEnum::AUTOINCREMENT_INTEGER());

        $quiz = $repository->findById($id);

        $this->assertEquals('quiz_title', $quiz->getTitle());
        $this->assertCount(1, $quiz->getQuestions());
        $this->assertEquals('question_text', $quiz->getQuestions()[0]->getText());
        $this->assertCount(2, $quiz->getQuestions()[0]->getAnswers());
        $this->assertTrue($quiz->getQuestions()[0]->getAnswers()[0]->isCorrect());
        $this->assertFalse($quiz->getQuestions()[0]->getAnswers()[1]->isCorrect());
        $this->assertEquals('answer_text2', $quiz->getQuestions()[0]->getAnswers()[1]->getText());
        $this->assertNotNull($quiz->getPublishingManagement());
        $this->assertTrue($quiz->getPublishingManagement()->isEnabled());
    }

    public function testCreate(): void
    {
        $quiz = new Quiz(
            Uuid::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ],
            new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week')),
        );
        $quizDto = QuizToDto::transformForChange($quiz);
        $quizDto->id = null;

        $mockPersistence = $this->createMock(QuizWithUuidPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('persist');

        /** @var QuizWithUuidPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence, IdTypeEnum::BINARY_UUID());

        $createdId = $repository->create($quizDto);

        $this->assertInstanceOf(Uuid::class, $createdId);
    }

    public function testUpdate(): void
    {
        $id = Uuid::createNew();
        $quiz = new Quiz(
            $id,
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ],
            new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week')),
        );
        $quizDto = QuizToDto::transformForChange($quiz);

        $mockPersistence = $this->createMock(QuizWithUuidPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('persist');

        /** @var QuizWithUuidPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence, IdTypeEnum::BINARY_UUID());

        $createdId = $repository->update($quizDto);

        $this->assertEquals($id->toString(), $createdId->toString());
    }

    public function testDelete(): void
    {
        $id = Uuid::createNew();

        $mockPersistence = $this->createMock(QuizWithUuidPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('delete');

        /** @var QuizWithUuidPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence, IdTypeEnum::BINARY_UUID());

        $repository->delete($id);
    }
}
