<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


use Exception;
use Observatby\Mir24Quiz\IdInterface;
use Observatby\Mir24Quiz\QuizException;
use PDO;
use PDOException;

trait QuizPersistenceTrait
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo = $pdo;
    }

    /**
     * @param IdInterface $id
     * @return array
     * @throws QuizException
     */
    public function retrieve(IdInterface $id): array
    {
        try {
            $sth = $this->pdo->prepare(self::QUERY);
        } catch (PDOException $e) {
            throw new QuizException(QuizException::DATABASE_IS_NOT_PREPARED, 0, $e);
        }

        $sth->execute([$id->toDb()]);
        $res = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        if ($res === false || count($res) === 0) {
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
            throw new QuizException(QuizException::DATABASE_IS_NOT_PREPARED, 0, $e);
        }

        $sth->execute();
        $res = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        return $res;
    }

    /**
     * @param IdInterface $id
     * @throws QuizException
     */
    public function delete(IdInterface $id): void
    {
        $dbh = $this->pdo;
        try {
            $sth = $dbh->prepare(self::QUERY_ONLY_ID);
        } catch (PDOException $e) {
            throw new QuizException(QuizException::DATABASE_IS_NOT_PREPARED, 0, $e);
        }

        $sth->execute([$id->toDb()]);
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        if ($rows === false) {
            throw new QuizException(QuizException::NOT_FOUND_QUIZ_IN_DATABASE);
        }

        try {
            $dbh->beginTransaction();
            if ($this->deleteIn('quiz_answer', 'id', array_column($rows, 'answer_id'))
                && $this->deleteIn('quiz_question', 'id', array_column($rows, 'question_id'))
                && $this->deleteIn('quiz_management', 'quiz_id', [$id->toDb()])
                && $this->deleteIn('quiz', 'id', [$id->toDb()])
            ) {
                $dbh->commit();
            } else {
                $dbh->rollBack();
                throw new QuizException(QuizException::NOT_DELETED_FROM_DATABASE);
            }
        } catch (Exception $e) {
            $dbh->rollBack();
            throw new QuizException(QuizException::NOT_DELETED_FROM_DATABASE, 0, $e);
        }
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

    private function deleteNotInWithOneCascade(string $table, string $includedField, array $included, string $excludedField, array $excluded, string $childTable, string $childField): bool
    {
        $querySelect = sprintf('SELECT %s FROM %s WHERE %s IN (%s) AND %s NOT IN (%s);',
            $excludedField,
            $table,
            $includedField,
            implode(',', array_fill(0, count($included), '?')),
            $excludedField,
            implode(',', array_fill(0, count($excluded), '?'))
        );
        $sthSelect = $this->pdo->prepare($querySelect);
        if (!$sthSelect->execute(array_merge($included, $excluded))) {
            return false;
        }
        $idsParent = $sthSelect->fetchAll(PDO::FETCH_COLUMN, 0);
        if (count($idsParent) === 0) {
            return true;
        }

        return $this->deleteIn($childTable, $childField, $idsParent)
            && $this->deleteIn($table, $excludedField, $idsParent);
    }

    private function deleteIn(string $table, string $includedField, array $included): bool
    {
        if (empty($included)) {
            return true;
        }

        $queryDelete = sprintf(
            'DELETE FROM %s WHERE %s IN (%s)',
            $table,
            $includedField,
            implode(',', array_fill(0, count($included), '?')),
        );

        return $this->pdo
            ->prepare($queryDelete)
            ->execute($included);
    }
}
