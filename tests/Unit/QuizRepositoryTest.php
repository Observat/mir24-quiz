<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\Unit;

use DateTimeImmutable;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\PublishingManagement;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Repository\Persistence\DummyPersistence;
use Observatby\Mir24Quiz\Repository\Persistence\QuizPersistence;
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
            new DummyPersistence()
        );
        $this->assertInstanceOf(QuizRepository::class, $repository1);

        $mockPdo = $this->createMock(PDO::class);
        $repository2 = new QuizRepository(
            new QuizPersistence($mockPdo)
        );
        $this->assertInstanceOf(QuizRepository::class, $repository2);
    }

    public function testFindById(): void
    {
        $id = Id::createNew();
        $questionId = Id::createNew();

        $mockPersistence = $this->createMock(QuizPersistence::class);
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
                    'answer_id' => Id::createNew()->toDb(),
                    'answer_text' => 'answer_text1',
                    'answer_correct' => true,
                    'enabled' => 1,
                    'begin_date' => (new DateTimeImmutable('now - 1 day'))->format(\DateTimeImmutable::ISO8601),
                    'end_date' => (new DateTimeImmutable('now + 1 day'))->format(\DateTimeImmutable::ISO8601),
                ],
                [
                    'quiz_id' => $id->toDb(),
                    'quiz_title' => 'quiz_title',
                    'question_id' => $questionId->toDb(),
                    'question_text' => 'question_text',
                    'question_image_src' => 'question_image_src',
                    'answer_id' => Id::createNew()->toDb(),
                    'answer_text' => 'answer_text2',
                    'answer_correct' => false,
                    'enabled' => 1,
                    'begin_date' => (new DateTimeImmutable('now - 1 day'))->format(\DateTimeImmutable::ISO8601),
                    'end_date' => (new DateTimeImmutable('now + 1 day'))->format(\DateTimeImmutable::ISO8601),
                ],
            ]);

        /** @var QuizPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence);

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
            Id::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ],
            new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week')),
        );
        $quizDto = QuizToDto::transformForChange($quiz);
        $quizDto->id = null;

        $mockPersistence = $this->createMock(QuizPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('persist');

        /** @var QuizPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence);

        $createdId = $repository->create($quizDto);

        $this->assertInstanceOf(Id::class, $createdId);
    }

    public function testUpdate(): void
    {
        $id = Id::createNew();
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

        $mockPersistence = $this->createMock(QuizPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('persist');

        /** @var QuizPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence);

        $createdId = $repository->update($quizDto);

        $this->assertEquals($id->toString(), $createdId->toString());
    }

    public function testDelete(): void
    {
        $id = Id::createNew();

        $mockPersistence = $this->createMock(QuizPersistence::class);
        $mockPersistence
            ->expects($this->once())
            ->method('delete');

        /** @var QuizPersistence $mockPersistence */
        $repository = new QuizRepository($mockPersistence);

        $repository->delete($id);
    }
}
