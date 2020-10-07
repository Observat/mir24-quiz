<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


use Exception;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\ListPersistenceInterface;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;
use PDO;
use PDOException;

class QuizPersistence implements PersistenceInterface, ListPersistenceInterface
{
    private PDO $pdo;
    private const QUERY_LIST = "SELECT
                               quiz.id as quiz_id,
                               quiz.title as quiz_title
                           FROM quiz";
    private const QUERY = "SELECT
                               quiz.id as quiz_id,
                               quiz.title as quiz_title,
                               quiz_question.id as question_id,
                               quiz_question.text as question_text,
                               quiz_question.image_src as question_image_src,
                               quiz_answer.id as answer_id,
                               quiz_answer.text as answer_text,
                               quiz_answer.correct as answer_correct,
                           FROM quiz
                           INNER JOIN quiz_question ON quiz_question.quiz_id = quiz.id
                           INNER JOIN quiz_answer ON quiz_answer.question_id = quiz_question.id
                           WHERE id = ?";
    private const QUERY_INSERT = "INSERT INTO quiz(id, title) values (?, ?);";

    public function __construct(PDO $pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo = $pdo;
    }

    /**
     * @param Id $id
     * @return array
     * @throws QuizException
     */
    public function retrieve(Id $id): array
    {
        try {
            $sth = $this->pdo->prepare(self::QUERY);
        } catch (PDOException $e) {
            throw new QuizException(QuizException::DATABASE_IS_NOT_PREPARED);
        }

        $sth->execute([$id->toDb()]);
        $res = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        return $res;
    }

    /**
     * @return array
     * @throws QuizException
     */
    public function retrieveList(): array
    {
        try {
            $sth = $this->pdo->prepare(self::QUERY_LIST);
        } catch (PDOException $e) {
            throw new QuizException(QuizException::DATABASE_IS_NOT_PREPARED);
        }

        $sth->execute();
        $res = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        return $res;
    }

    /**
     * TODO Add support for update
     *
     * @param array $data
     * @throws QuizException
     */
    public function persist(array $data): void
    {
        try {
            $dbh = $this->pdo;
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->beginTransaction();

            $this->persistQuiz($data);
            $this->persistQuestions($data);
            $this->persistAnswers($data);

            $dbh->commit();
        } catch (Exception $e) {
            $dbh->rollBack();
            throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE);
        }
    }

    public function delete(Id $id): void
    {
        // TODO: Implement delete() method.
    }


    private function persistQuiz(array $data): void
    {
        $sth = $this->pdo->prepare(self::QUERY_INSERT);

        $sth->execute([$data['id'], $data['title']]);
    }

    private function persistQuestions(array $data): void
    {
        // TODO
    }

    private function persistAnswers(array $data): void
    {
        // TODO
    }
}
