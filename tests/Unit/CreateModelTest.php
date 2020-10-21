<?php
declare(strict_types=1);


namespace Observatby\Mir24Quiz\Tests\Unit;


use Observatby\Mir24Quiz\Model\Uuid;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Model\ReceivedAnswer;
use Observatby\Mir24Quiz\Model\ReceivedQuiz;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use PHPUnit\Framework\TestCase;


class CreateModelTest extends TestCase
{
    use CreateQuizTrait;

    public function testCreateQuiz(): void
    {
        $quiz = new Quiz(
            Uuid::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ]
        );

        $this->assertInstanceOf(Quiz::class, $quiz);
    }

    public function testCreateReceivedQuiz(): void
    {
        $quiz = new Quiz(
            Uuid::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ]
        );

        $resQuiz = new ReceivedQuiz(
            Uuid::createNew(),
            $quiz,
            [
                new ReceivedAnswer(
                    Uuid::createNew(),
                    $this->createQuizQuestion_1(),
                    $this->createQuizAnswer_yes_true()
                ),
                new ReceivedAnswer(
                    Uuid::createNew(),
                    $this->createQuizQuestion_1(),
                    $this->createQuizAnswer_no_true()
                )
            ]
        );

        $this->assertInstanceOf(ReceivedQuiz::class, $resQuiz);
    }
}
