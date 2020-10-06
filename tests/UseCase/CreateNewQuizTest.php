<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\UseCase;

use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Repository\Persistence\InMemoryPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use Observatby\Mir24Quiz\UseCase\CreateNewQuiz;
use PHPUnit\Framework\TestCase;


class CreateNewQuizTest extends TestCase
{
    use CreateQuizTrait;

    public function testHandle()
    {
        $persistence = new InMemoryPersistence();
        $repository = new QuizRepository($persistence);
        $id = Id::createNew();

        $quizDto = new QuizDto();
        $quizDto->id = $id->toDb();
        $quizDto->title = "New quiz";

        CreateNewQuiz::createWithRepository($repository)->handle($quizDto);

        $quizDtoSaved = $persistence->retrieve($id);
        $this->assertEquals("New quiz", $quizDtoSaved['title']);

// TODO        $quizFounded = $repository->findById($id);
//        $this->assertEquals("New quiz", $quizFounded->getTitle());
    }
}
