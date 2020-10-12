<?php
declare(strict_types=1);


namespace Observatby\Mir24Quiz\Tests\Unit;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use PHPUnit\Framework\TestCase;


class CreateModelFromDtoTest extends TestCase
{
    use CreateQuizTrait;

    public function testCreateQuizFromDto()
    {
        $quiz = new Quiz(
            Id::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ]
        );

        $dto = QuizToDto::transformForChange($quiz);

        $fromDto = Quiz::fromDto($dto);

        $this->assertJsonStringEqualsJsonString(json_encode($quiz), json_encode($fromDto));
    }
}
