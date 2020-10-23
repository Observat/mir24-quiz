<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


use Exception;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\ListPersistenceInterface;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;

class QuizWithUuidPersistence implements PersistenceInterface, ListPersistenceInterface, QuizQueryConstantsInterface
{
    use QuizPersistenceTrait;

    private const QUERY_INSERT = "INSERT INTO quiz(id, title) values (?, ?) ON DUPLICATE KEY UPDATE title=?;";

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
                && $this->multiInsert('quiz_management', ['quiz_id', 'enable', 'beginDatetime', 'endDatetime'], [$data['management']])
                && $this->deleteNotInWithOneCascade('quiz_question', 'quiz_id', [$data['quiz']['id']], 'id', array_column($data['questions'], 'id'), 'quiz_answer', 'question_id')
                && $this->deleteNotIn('quiz_answer', 'question_id', array_column($data['questions'], 'id'), 'id', array_column($data['answers'], 'id'))
            ) {
                $dbh->commit();
            } else {
                $dbh->rollBack();
                throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE);
            }
        } catch (Exception $e) {
            $dbh->rollBack();
            throw new QuizException(QuizException::NOT_CREATED_IN_DATABASE, 0, $e);
        }
    }

    private function multiInsert(string $table, array $insertFields, array $data): bool
    {
        $insertPlaceholder = [];
        $insertValues = [];
        $updateFieldsWithPlaceholders = [];

        $i = 0;
        foreach ($data as $d) {
            $insertKeys = [];
            $uIndex = 0;
            foreach ($insertFields as $field) {
                $insertKey = ':i' . $i . $field;
                $insertKeys[] = $insertKey;
                $insertValues[$insertKey] = $d[$field];

                if ($uIndex !== 0) {
                    $updateFieldsWithPlaceholders[] = $field . '=values(' . $field . ')';
                }
                $uIndex++;
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
        return $stmt->execute(array_merge($insertValues));
    }
}
