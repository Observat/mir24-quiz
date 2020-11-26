<?php

namespace Observatby\Mir24Quiz\Repository\Persistence;

use Exception;
use Observatby\Mir24Quiz\Dto\QuizDto;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\ListPersistenceInterface;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;

class QuizWithIncrementIdPersistence implements PersistenceInterface, ListPersistenceInterface, QuizQueryConstantsInterface
{
    use QuizPersistenceTrait;

    private const QUERY_INSERT = "INSERT INTO quiz(title) values (?);";

    /**
     * @param QuizDto $quizDto
     * @return int
     * @throws QuizException
     */
    public function insertIncrementally(QuizDto $quizDto): int
    {
        # TODO not implemented
//        $idQuiz = $this->insertQuiz($quizDto);
//        foreach ($quizDto->questions as $questionDto) {
//            $questionId = $this->insertQuestion([
//                'id' => null,
//                'text' => $questionDto->text,
//                'image_src' => $questionDto->imageSrc,
//                'quiz_id' => $idQuiz,
//            ]);
//
//            # TODO Answers
//        }
//
        return 0;
    }

    /**
     * @param array $data
     * @return int
     * @throws QuizException
     */
    public function persist(array $data): int
    {
        $dbh = $this->pdo;
        try {
            # TODO not implemented
            throw new QuizException("TODO Not implemented");
//            $dbh->beginTransaction();
//            $idQuiz = ($data['quiz']['id'] === null)
//                ? $this->insertQuiz($data)
//                : $this->updateQuiz($data);
//
//            $questions = $data['questions'];
//            foreach ($questions as $question) {
//                $questionId = $this->insertQuestion($question);
//                # TODO Answers
//            }
//
//            $dbh->commit();
//            return $idQuiz;
        } catch (Exception $e) {
            $dbh->rollBack();
            throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE, 0, $e);
        }
    }

//    /**
//     * @param QuizDto $quizDto
//     * @return int
//     * @throws QuizException
//     */
//    private function insertQuiz(QuizDto $quizDto): int
//    {
//        $sth = $this->pdo->prepare(self::QUERY_INSERT);
//        if ($sth->execute([$quizDto->title])) {
//            return $this->pdo->lastInsertId();
//        }
//
//        throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE);
//    }
//
//    /**
//     * @param array $data
//     * @return int
//     * @throws QuizException
//     */
//    private function updateQuiz(array $data): int
//    {
//        # TODO updateQuiz
//
//        throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE);
//    }
//
//    /**
//     * @param array $data
//     * @return int
//     * @throws QuizException
//     */
//    private function insertQuestion(array $data): int
//    {
//        $sth = $this->pdo->prepare(self::QUERY_INSERT_QUESTION);
//        if ($sth->execute(["TODO"])) { # TODO
//            return $this->pdo->lastInsertId();
//        }
//
//        throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE);
//    }
}
