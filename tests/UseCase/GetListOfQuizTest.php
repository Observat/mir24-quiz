<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\UseCase;

use Observatby\Mir24Quiz\Repository\ListOfQuizRepository;
use Observatby\Mir24Quiz\Repository\Persistence\DummyPersistence;
use Observatby\Mir24Quiz\UseCase\GetListOfQuiz;
use PHPUnit\Framework\TestCase;


class GetListOfQuizTest extends TestCase
{
    public function testEmptyHandle(): void
    {
        $listDto = GetListOfQuiz::createWithRepository(new ListOfQuizRepository(new DummyPersistence()))
            ->handle();

        $this->assertCount(0, $listDto->quizzes);
    }
}
