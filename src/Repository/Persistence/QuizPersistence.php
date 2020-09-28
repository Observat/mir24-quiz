<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;
use PDO;
use PDOException;

class QuizPersistence implements PersistenceInterface
{
    private PDO $pdo;
    private const QUERY = "SELECT title
                           FROM quiz
                           WHERE id = ?";

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

        $sth->execute([$id]);
        $res = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();

        return $res;
    }

    public function persist(array $data): void
    {
        // TODO: Implement persist() method.
    }

    public function delete(Id $id): void
    {
        // TODO: Implement delete() method.
    }
}
