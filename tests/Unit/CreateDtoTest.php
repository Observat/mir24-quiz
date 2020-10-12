<?php
declare(strict_types=1);


namespace Observatby\Mir24Quiz\Tests\Unit;


use Observatby\Mir24Quiz\Dto\AnswerDto;
use Observatby\Mir24Quiz\Dto\QuestionDto;
use Observatby\Mir24Quiz\Dto\QuizDto;
use PHPUnit\Framework\TestCase;


class CreateDtoTest extends TestCase
{
    public function testEmptyQuizDto(): void
    {
        $quizDto = new QuizDto();

        $this->assertInstanceOf(QuizDto::class, $quizDto);
        $this->assertNull($quizDto->id);
        $this->assertNull($quizDto->title);
        $this->assertNull($quizDto->questions);
        $this->assertNull($quizDto->management);
    }

    public function testEmptyQuestionDto(): void
    {
        $questionDto = new QuestionDto();

        $this->assertInstanceOf(QuestionDto::class, $questionDto);
        $this->assertNull($questionDto->id);
        $this->assertNull($questionDto->answers);
    }

    public function testEmptyAnswerDto(): void
    {
        $answerDto = new AnswerDto();

        $this->assertInstanceOf(AnswerDto::class, $answerDto);
        $this->assertNull($answerDto->id);
        $this->assertNull($answerDto->correct);
    }
}
