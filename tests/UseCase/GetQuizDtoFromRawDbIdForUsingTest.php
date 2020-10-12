<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\UseCase;

use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use Observatby\Mir24Quiz\UseCase\GetQuizDtoFromRawDbIdForUsing;
use PHPUnit\Framework\TestCase;


class GetQuizDtoFromRawDbIdForUsingTest extends TestCase
{
    use CreateQuizTrait;

    public function testHandle(): void
    {
        $id = Id::createNew();

        $mockRepository = $this->createMock(QuizRepository::class);
        $mockRepository
            ->method('findById')
            ->willReturn(new Quiz(
                $id,
                'First quiz',
                [
                    $this->createQuizQuestion_1(),
                    $this->createQuizQuestion_2(),
                ]
            ));

        $quizDto = GetQuizDtoFromRawDbIdForUsing::createWithRepository($mockRepository)
            ->handle($id);

        $this->assertEquals("First quiz", $quizDto->title);
        $this->assertEquals('This no second question?', ($quizDto->questions)[1]->text);
        $this->assertEquals('No', (($quizDto->questions)[1]->answers)[1]->text);
        $this->assertTrue((($quizDto->questions)[1]->answers)[1]->correct);
        $this->assertNull($quizDto->management);
    }
}
