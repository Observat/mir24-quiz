<?php


namespace Observatby\Mir24Quiz\Repository;


use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Image;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Model\QuizAnswer;
use Observatby\Mir24Quiz\Model\QuizQuestion;

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
        $quizArr = [
            'id' => $quizDto->id ? Id::fromString($quizDto->id)->toDb() : Id::createNew()->toDb(),
            'title' => $quizDto->title
        ];

        $questionsArr = [];
        $answersArr = [];
        foreach ($quizDto->questions as $questionDto) {
            $question = [
                'id' => $questionDto->id ? Id::fromString($questionDto->id)->toDb() : Id::createNew()->toDb(),
                'text' => $questionDto->text,
                'image_src' => $questionDto->imageSrc,
                'quiz_id' => $quizArr['id'],
            ];

            foreach ($questionDto->answers as $answerDto) {
                $answersArr[] = [
                    'id' => $answerDto->id ? Id::fromString($answerDto->id)->toDb() : Id::createNew()->toDb(),
                    'text' => $answerDto->text,
                    'correct' => $answerDto->correct ? 1 : 0,
                    'question_id' => $question['id'],
                ];
            }
            $questionsArr[] = $question;
        }

        # TODO management

        $this->persistence->persist([
            'quiz' => $quizArr,
            'questions' => $questionsArr,
            'answers' => $answersArr,
        ]);
    }

    public function update(QuizDto $quizDto): void
    {
        $this->create($quizDto);
    }
}
