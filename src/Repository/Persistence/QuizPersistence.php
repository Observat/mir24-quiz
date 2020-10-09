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
                               quiz_answer.correct as answer_correct
                           FROM quiz
                           INNER JOIN quiz_question ON quiz_question.quiz_id = quiz.id
                           INNER JOIN quiz_answer ON quiz_answer.question_id = quiz_question.id
                           WHERE quiz.id = ?";
    private const QUERY_INSERT = "INSERT INTO quiz(id, title) values (?, ?) ON DUPLICATE KEY UPDATE title=?;";

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
        $res = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        if ($res === false) {
            throw new QuizException(QuizException::NOT_FOUND_QUIZ_IN_DATABASE);
        }

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
        $res = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        return $res;
    }

    /**
     * @param array $data
     * @throws QuizException
     */
    public function persist(array $data): void
    {
        $dbh = $this->pdo;
        try {
            $dbh->beginTransaction();
            $sth = $this->pdo->prepare(self::QUERY_INSERT);
            if ($sth->execute([$data['quiz']['id'], $data['quiz']['title'], $data['quiz']['title']])
                && $this->multiInsert('quiz_question', ['id', 'text', 'image_src', 'quiz_id'], $data['questions'])
                && $this->multiInsert('quiz_answer', ['id', 'text', 'correct', 'question_id'], $data['answers'])
// TODO               && $this->deleteNotIn('quiz_question', 'quiz_id', [$data['quiz']['id']], 'id', array_column($data['questions'], 'id'))
// TODO               && $this->deleteNotIn('quiz_answer', 'question_id', array_column($data['questions'], 'id'), 'id', array_column($data['answers'], 'id'))
            ) {
                $dbh->commit();
            } else {
                $dbh->rollBack();
                throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE);
            }
        } catch (Exception $e) {
            $dbh->rollBack();
            # TODO LoggerInterface::error($e->getMessage());
            throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE);
        }
    }

    public function delete(Id $id): void
    {
        // TODO: Implement delete() method.
    }

    private function multiInsert(string $table, array $insertFields, array $data): bool
    {
        $insertPlaceholder = [];
        $insertValues = [];
        $updateValues = [];
        $updateFieldsWithPlaceholders = [];

        $i = 0;
        foreach ($data as $d) {
            $insertKeys = [];
            foreach ($insertFields as $field) {
                $insertKey = ':i' . $i . $field;
                $insertKeys[] = $insertKey;
                $insertValues[$insertKey] = $d[$field];

                $updateKey = ':u' . $i . $field;
                $updateValues[$updateKey] = $d[$field];
                $updateFieldsWithPlaceholders[] = $field . '=' . $updateKey;
            }
            $insertPlaceholder[] = '(' . implode(',', $insertKeys) . ')';
            $i++;
        }
        $sthText = sprintf(
            'INSERT INTO %s (%s) VALUES %s ON DUPLICATE KEY UPDATE %s',
            $table,
            implode(',', $insertFields),
            implode(',', $insertPlaceholder),
            implode(',', $updateFieldsWithPlaceholders),
        );

        $stmt = $this->pdo->prepare($sthText);
        return $stmt->execute(array_merge($insertValues, $updateValues));
    }

    private function deleteNotIn(string $table, string $includedField, array $included, string $excludedField, array $excluded): bool
    {
        # TODO soft delete: update set active=0
        # TODO on delete cascade
        $sthText = sprintf(
            'DELETE FROM %s WHERE %s IN (%s) AND %s NOT IN (%s);',
            $table,
            $includedField,
            implode(',', array_fill(0, count($included), '?')),
            $excludedField,
            implode(',', array_fill(0, count($excluded), '?'))
        );

        $stmt = $this->pdo->prepare($sthText);
        return $stmt->execute(array_merge($included, $excluded));
    }
}
