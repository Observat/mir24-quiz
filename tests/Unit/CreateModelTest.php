<?php
declare(strict_types=1);


namespace Observatby\Mir24Quiz\Tests\Unit;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Model\ReceivedAnswer;
use Observatby\Mir24Quiz\Model\ReceivedQuiz;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use PHPUnit\Framework\TestCase;


class CreateModelTest extends TestCase
{
    use CreateQuizTrait;

    public function testCreateQuiz()
    {
        $quiz = new Quiz(
            Id::createNew(),
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ]
        );

        $this->assertInstanceOf(Quiz::class, $quiz);
    }

    public function testCreateReceivedQuiz()
    {
        $resQuiz = new ReceivedQuiz(
            Id::createNew(),
            [
                new ReceivedAnswer(
                    Id::createNew(),
                    $this->createQuizQuestion_1(),
                    $this->createQuizAnswer_yes_1()
                ),
                new ReceivedAnswer(
                    Id::createNew(),
                    $this->createQuizQuestion_1(),
                    $this->createQuizAnswer_no_1()
                )
            ]
        );

        $this->assertInstanceOf(ReceivedQuiz::class, $resQuiz);
    }
}
