<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\Unit;

use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Repository\Persistence\DummyPersistence;
use Observatby\Mir24Quiz\Repository\Persistence\QuizPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class QuizRepositoryTest extends TestCase
{
    public function testHasCreatedQuizRepository()
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

    public function testFindById()
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
        $this->assertNull($quiz->getPublishingManagement());
    }
}
