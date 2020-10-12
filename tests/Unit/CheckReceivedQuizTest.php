<?php
declare(strict_types=1);


namespace Observatby\Mir24Quiz\Tests\Unit;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Image;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Model\QuizQuestion;
use Observatby\Mir24Quiz\Model\ReceivedAnswer;
use Observatby\Mir24Quiz\Model\ReceivedQuiz;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use PHPUnit\Framework\TestCase;


class CheckReceivedQuizTest extends TestCase
{
    use CreateQuizTrait;

    public function testCountSuccessAnswer(): void
    {
        $answerYesTrue = $this->createQuizAnswer_yes_true();
        $answerYesFalse = $this->createQuizAnswer_yes_false();
        $answerNoTrue = $this->createQuizAnswer_no_true();
        $answerNoFalse = $this->createQuizAnswer_no_false();

        $question1 = $this->createQuizQuestion_1($answerYesTrue, $answerNoFalse);
        $question2 = $this->createQuizQuestion_1($answerYesFalse, $answerNoTrue);

        $quiz = new Quiz(
            Id::createNew(),
            'First quiz',
            [
                $question1,
                $question2,
            ]
        );

        $resQuizSuccess = new ReceivedQuiz(
            Id::createNew(),
            $quiz,
            [
                new ReceivedAnswer(
                    Id::createNew(),
                    $question2,
                    $answerNoTrue
                ),
                new ReceivedAnswer(
                    Id::createNew(),
                    $question1,
                    $answerYesTrue
                ),
            ]
        );

        $resQuizSecondFalse = new ReceivedQuiz(
            Id::createNew(),
            $quiz,
            [
                new ReceivedAnswer(
                    Id::createNew(),
                    $question1,
                    $answerYesTrue
                ),
                new ReceivedAnswer(
                    Id::createNew(),
                    $question2,
                    $answerYesFalse
                ),
            ]
        );

        $resQuizSecondWithOtherId = new ReceivedQuiz(
            Id::createNew(),
            $quiz,
            [
                new ReceivedAnswer(
                    Id::createNew(),
                    $question1,
                    $answerYesTrue
                ),
                new ReceivedAnswer(
                    Id::createNew(),
                    $question2,
                    $this->createQuizAnswer_no_true()
                ),
            ]
        );

        $answer3True = $this->createQuizAnswer_yes_true();
        $answer3False = $this->createQuizAnswer_no_false();
        $question3 = new QuizQuestion(
            Id::createNew(),
            'This is third question?',
            new Image(''),
            [
                $answer3True,
                $answer3False
            ]
        );
        $resQuizThreeSuccessAnswer = new ReceivedQuiz(
            Id::createNew(),
            $quiz,
            [
                new ReceivedAnswer(
                    Id::createNew(),
                    $question1,
                    $answerYesTrue
                ),
                new ReceivedAnswer(
                    Id::createNew(),
                    $question2,
                    $answerNoTrue
                ),
                new ReceivedAnswer(
                    Id::createNew(),
                    $question3,
                    $answer3True
                ),
            ]
        );

        $this->assertEquals(2, $resQuizSuccess->countSuccessAnswer());
        $this->assertEquals(1, $resQuizSecondFalse->countSuccessAnswer());
        $this->assertEquals(1, $resQuizSecondWithOtherId->countSuccessAnswer());
        $this->assertEquals(2, $resQuizThreeSuccessAnswer->countSuccessAnswer());
    }
}
