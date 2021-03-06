<?php

namespace Observatby\Mir24Quiz\Tests\Unit;

use DateTimeImmutable;
use Observatby\Mir24Quiz\Model\Uuid;
use Observatby\Mir24Quiz\Model\PublishingManagement;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;
use PHPUnit\Framework\TestCase;

class TransformToDtoTest extends TestCase
{
    use CreateQuizTrait;

    public function testTransformQuizForUse(): void
    {
        $quiz = new Quiz(
            Uuid::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ],
            new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week')),
        );

        $dto = QuizToDto::transformForUse($quiz);

        $this->assertIsString($dto->id);
        $this->assertGreaterThan(1, strlen($dto->id));
        $this->assertEquals('First quiz', $dto->title);
        $this->assertEquals('This no second question?', ($dto->questions)[1]->text);
        $this->assertEquals('No', (($dto->questions)[1]->answers)[1]->text);
        $this->assertTrue((($dto->questions)[1]->answers)[1]->correct);

        $this->assertNull($dto->management);
    }

    public function testTransformQuizForManagement(): void
    {
        $quiz = new Quiz(
            Uuid::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ],
            new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week')),
        );

        $dto = QuizToDto::transformForChange($quiz);

        $this->assertIsString($dto->id);
        $this->assertMatchesRegularExpression('/[\w\d]{8}\-[\w\d]{4}\-[\w\d]{4}\-[\w\d]{4}\-[\w\d]{12}/', $dto->id);
        $this->assertEquals('First quiz', $dto->title);
        $this->assertEquals('This no second question?', ($dto->questions)[1]->text);
        $this->assertEquals('No', (($dto->questions)[1]->answers)[1]->text);
        $this->assertTrue((($dto->questions)[1]->answers)[1]->correct);

        $this->assertTrue($dto->management->enabled);
    }

    public function testTransformQuizFromArray(): void
    {
        $data = [
            'id' => Uuid::createNew()->toString(),
            'title' => 'quiz_title',
            'questions' => [
                [
                    'id' => Uuid::createNew()->toString(),
                    'text' => 'question_text',
                    'imageSrc' => 'question_image_src',
                    'answers' => [
                        [
                            'id' => Uuid::createNew()->toString(),
                            'text' => 'answer_text1',
                            'correct' => true,
                        ],
                        [
                            'id' => Uuid::createNew()->toString(),
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

        $dto = QuizToDto::transformFromArray($data);

        $this->assertIsString($dto->id);
        $this->assertEquals('quiz_title', $dto->title);
        $this->assertEquals('question_text', ($dto->questions)[0]->text);
        $this->assertFalse((($dto->questions)[0]->answers)[1]->correct);
        $this->assertTrue($dto->management->enabled);
        $this->assertInstanceOf(DateTimeImmutable::class, $dto->management->beginDate);
    }

    public function testTransformQuizFromArrayWithEmptyDate(): void
    {
        $data = [
            'id' => Uuid::createNew()->toString(),
            'title' => 'quiz_title',
            'questions' => [
                [
                    'id' => Uuid::createNew()->toString(),
                    'text' => 'question_text',
                    'imageSrc' => 'question_image_src',
                    'answers' => [
                        [
                            'id' => Uuid::createNew()->toString(),
                            'text' => 'answer_text1',
                            'correct' => true,
                        ],
                        [
                            'id' => Uuid::createNew()->toString(),
                            'text' => 'answer_text2',
                            'correct' => false,
                        ],
                    ],
                ]
            ],
            'management' => [
                'enabled' => true,
                'beginDate' => null,
                'endDate' => null,
            ]
        ];

        $dto = QuizToDto::transformFromArray($data);

        $this->assertIsString($dto->id);
        $this->assertEquals('quiz_title', $dto->title);
        $this->assertEquals('question_text', ($dto->questions)[0]->text);
        $this->assertFalse((($dto->questions)[0]->answers)[1]->correct);
        $this->assertTrue($dto->management->enabled);
        $this->assertNull($dto->management->beginDate);
    }

    public function testTransformQuizFromArrayWithEmptyManagement(): void
    {
        $data = [
            'id' => Uuid::createNew()->toString(),
            'title' => 'quiz_title',
            'questions' => [
                [
                    'id' => Uuid::createNew()->toString(),
                    'text' => 'question_text',
                    'imageSrc' => function ($questionId) {
                        return 'question_image_src_' . $questionId;
                    },
                    'answers' => [
                        [
                            'id' => Uuid::createNew()->toString(),
                            'text' => 'answer_text1',
                            'correct' => true,
                        ],
                        [
                            'id' => Uuid::createNew()->toString(),
                            'text' => 'answer_text2',
                            'correct' => false,
                        ],
                    ],
                ]
            ],
            'management' => null
        ];

        $dto = QuizToDto::transformFromArray($data);

        $this->assertIsString($dto->id);
        $this->assertEquals('quiz_title', $dto->title);
        $this->assertEquals('question_text', ($dto->questions)[0]->text);
        $this->assertStringStartsWith('question_image_src_', ($dto->questions)[0]->imageSrc);
        $this->assertGreaterThan(strlen('question_image_src_'), strlen(($dto->questions)[0]->imageSrc));
        $this->assertFalse((($dto->questions)[0]->answers)[1]->correct);
        $this->assertNull($dto->management);
    }
}
