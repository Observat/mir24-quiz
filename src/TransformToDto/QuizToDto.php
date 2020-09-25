<?php


namespace Observatby\Mir24Quiz\TransformToDto;


use Observatby\Mir24Quiz\Dto\AnswerDto;
use Observatby\Mir24Quiz\Dto\QuestionDto;
use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\Model\Quiz;

class QuizToDto
{
    public static function transform(Quiz $quiz): QuizDto
    {
        $quizDto = new QuizDto();
        $quizDto->id = $quiz->getId()->toDb();
        $quizDto->title = $quiz->getTitle();

        $questionsDto = [];
        foreach ($quiz->getQuestions() as $question) {
            $questionDto = new QuestionDto();
            $questionDto->id = $question->getId()->toDb();
            $questionDto->text = $question->getText();
            $questionDto->imageSrc = $question->getImage()->getSrc();

            $answersDto = [];
            foreach ($question->getAnswers() as $answer) {
                $answerDto = new AnswerDto();
                $answerDto->id = $answer->getId()->toDb();
                $answerDto->text = $answer->getText();
                $answerDto->correct = $answer->isCorrect();

                $answersDto[] = $answerDto;
            }
            $questionDto->answers = $answersDto;

            $questionsDto[] = $questionDto;
        }
        $quizDto->questions = $questionsDto;

        return $quizDto;
    }
}
