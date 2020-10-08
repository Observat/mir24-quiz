<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\UseCase;

use DateTimeImmutable;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\Persistence\InMemoryPersistence;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use Observatby\Mir24Quiz\UseCase\CreateNewQuiz;
use PHPUnit\Framework\TestCase;


class CreateNewQuizTest extends TestCase
{
    public function testEmptyData()
    {
        $repository = new QuizRepository(new InMemoryPersistence());
        $data = [];

        $this->expectException(QuizException::class);
        $this->expectExceptionMessage(QuizException::INPUT_ARRAY_AND_OUTPUT_DTO_MISMATCH);

        CreateNewQuiz::createWithRepository($repository)->handle($data);
    }

    public function testHandle()
    {
        $persistence = new InMemoryPersistence();
        $repository = new QuizRepository($persistence);
        $id = Id::createNew();

        $data = [
            'id' => $id->toDb(),
            'title' => 'New quiz',
            'questions' => [
                [
                    'id' => Id::createNew()->toDb(),
                    'text' => 'question_text',
                    'imageSrc' => 'question_image_src',
                    'answers' => [
                        [
                            'id' => Id::createNew()->toDb(),
                            'text' => 'answer_text1',
                            'correct' => true,
                        ],
                        [
                            'id' => Id::createNew()->toDb(),
                            'text' => 'answer_text2',
                            'correct' => false,
                        ],
                    ],
                ]
            ],
            'management' => [
                'enabled' => true,
                'beginDate' => (new DateTimeImmutable('now - 1 day')),
                'endDate' => (new DateTimeImmutable('now + 1 day')),
            ]
        ];

        CreateNewQuiz::createWithRepository($repository)->handle($data);

        $quizDtoSaved = $persistence->retrieve($id);
        $this->assertEquals("New quiz", $quizDtoSaved['quiz']['title']);
        $this->assertEquals("answer_text2", $quizDtoSaved['answers'][1]['text']);


//        $quizFounded = $repository->findById($id); TODO
//        $this->assertEquals("New quiz", $quizFounded->getTitle());
//        $this->assertEquals("answer_text2", $quizFounded->getQuestions()[0]->getAnswers()[1]->getText());
    }
}
