<?php

namespace Observatby\Mir24Quiz\Tests\Unit;

use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use PHPUnit\Framework\TestCase;

class TransformToDtoTest extends TestCase
{
    use CreateQuizTrait;

    public function testTransformQuiz()
    {
        $quiz = new Quiz(
            Id::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ]
        );

        $dto = QuizToDto::transform($quiz);

        $this->assertIsString($dto->id);
        $this->assertGreaterThan(1, strlen($dto->id));
        $this->assertEquals('First quiz', $dto->title);
        $this->assertEquals('This no second question?', ($dto->questions)[1]->text);
        $this->assertEquals('No', (($dto->questions)[1]->answers)[1]->text);
        $this->assertTrue((($dto->questions)[1]->answers)[1]->correct);
    }
}
