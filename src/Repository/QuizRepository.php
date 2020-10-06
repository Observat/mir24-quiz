<?php


namespace Observatby\Mir24Quiz\Repository;


use Observatby\Mir24Quiz\Dto\AnswerDto;
use Observatby\Mir24Quiz\Dto\QuestionDto;
use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Image;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Model\QuizAnswer;
use Observatby\Mir24Quiz\Model\QuizQuestion;
use Observatby\Mir24Quiz\Repository\Persistence\InMemoryPersistence;
use Observatby\Mir24Quiz\TransformToDto\QuizToDto;

class QuizRepository
{
    private PersistenceInterface $persistence;

    public function __construct(PersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    public function findById(Id $id): Quiz
    {
        $rows = $this->persistence->retrieve($id);

        # InMemoryPersistence and QuizPersistence has diff in returned array
        if ($this->persistence instanceof InMemoryPersistence) {
            return Quiz::fromDto(QuizToDto::transformFromArray($rows));
        }

        $answers = [];
        $questionRows = [];
        foreach ($rows as $row) {
            if (!key_exists($row['question_id'], $questionRows)) {
                $questionRows[$row['question_id']] = [
                    'text' => $row['question_text'],
                    'imageSrc' => $row['question_image_src'],
                ];
                $answers[$row['question_id']] = [];
            }

            $answers[$row['question_id']][] = new QuizAnswer(
                Id::fromDb($row['answer_id']),
                $row['answer_correct'],
                $row['answer_text']
            );
        }

        $questions = [];
        foreach ($questionRows as $questionId => $questionRow) {
            $questions[] = new QuizQuestion(
                Id::fromDb($questionId),
                $questionRow['text'],
                new Image($questionRow['imageSrc']),
                $answers[$questionId]
            );
        }

        return new Quiz(
            $id,
            $rows[0]['quiz_title'],
            $questions,
            null # TODO
        );
    }

    public function create(QuizDto $quizDto): void
    {
        $this->persistence->persist([
            'id' => $quizDto->id ?? Id::createNew()->toDb(),
            'title' => $quizDto->title,
            'questions' => array_map(function (QuestionDto $questionDto) {
                return [
                    'id' => $questionDto->id ?? Id::createNew()->toDb(),
                    'text' => $questionDto->text,
                    'imageSrc' => $questionDto->imageSrc,
                    'answers' => array_map(function (AnswerDto $answerDto) {
                        return [
                            'id' => $answerDto->id ?? Id::createNew()->toDb(),
                            'text' => $answerDto->text,
                            'correct' => $answerDto->correct
                        ];
                    }, $questionDto->answers),
                ];
            }, $quizDto->questions),
            # TODO management
        ]);
    }

    public function update(): void
    {
        // TODO
    }
}
