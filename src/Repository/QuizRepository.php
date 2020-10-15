<?php


namespace Observatby\Mir24Quiz\Repository;


use DateTimeImmutable;
use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Image;
use Observatby\Mir24Quiz\Model\PublishingManagement;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\Model\QuizAnswer;
use Observatby\Mir24Quiz\Model\QuizQuestion;
use Observatby\Mir24Quiz\QuizException;

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

        $management = null;
        if ($rows[0]['enabled'] !== null) {
            $management = new PublishingManagement(
                $rows[0]['enabled'],
                $rows[0]['begin_date'] !== null ? new DateTimeImmutable($rows[0]['begin_date']) : null,
                $rows[0]['end_date'] !== null ? new DateTimeImmutable($rows[0]['end_date']) : null,
            );
        }

        return new Quiz(
            $id,
            $rows[0]['quiz_title'],
            $questions,
            $management
        );
    }

    /**
     * @param QuizDto $quizDto
     * @return Id
     * @throws QuizException
     */
    public function create(QuizDto $quizDto): Id
    {
        $quizId = $quizDto->id ? Id::fromString($quizDto->id) : Id::createNew();
        $quizArr = [
            'id' => $quizId->toDb(),
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

        $management = [
            'quiz_id' => $quizArr['id'],
            'enable' => 0,
            'beginDatetime' => null,
            'endDatetime' => null
        ];
        $managementDto = $quizDto->management;
        if ($managementDto !== null) {
            $management['enable'] = $managementDto->enabled ? 1 : 0;
            $management['beginDatetime'] = $managementDto->beginDate ? $managementDto->beginDate->format("Y-m-d H:i:s") : null;
            $management['endDatetime'] = $managementDto->endDate ? $managementDto->endDate->format("Y-m-d H:i:s") : null;
        }

        $this->persistence->persist([
            'quiz' => $quizArr,
            'questions' => $questionsArr,
            'answers' => $answersArr,
            'management' => $management,
        ]);

        return $quizId;
    }

    /**
     * @param QuizDto $quizDto
     * @return Id
     * @throws QuizException
     */
    public function update(QuizDto $quizDto): Id
    {
        return $this->create($quizDto);
    }
}
