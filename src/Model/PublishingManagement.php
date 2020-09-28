<?php


namespace Observatby\Mir24Quiz\Model;


use DateTimeImmutable;
use Observatby\Mir24Quiz\QuizException;


class PublishingManagement
{
    private bool $enabled;
    private ?DateTimeImmutable $delayedPublicationDate;
    private ?DateTimeImmutable $endedPublicationDate;

    public function __construct(bool $enabled, ?DateTimeImmutable $delayedPublicationDate, ?DateTimeImmutable $endedPublicationDate)
    {
        $this->enabled = $enabled;
        $this->delayedPublicationDate = $delayedPublicationDate;
        $this->endedPublicationDate = $endedPublicationDate;
    }

    public function enable(): self
    {
        $this->enabled = true;
        return $this;
    }

    public function disable(): self
    {
        $this->enabled = false;
        return $this;
    }

    public function getDelayedPublicationDate(): ?DateTimeImmutable
    {
        return $this->delayedPublicationDate;
    }

    public function getEndedPublicationDate(): ?DateTimeImmutable
    {
        return $this->endedPublicationDate;
    }

    /**
     * @param DateTimeImmutable|null $startDate
     * @param DateTimeImmutable|null $endDate
     * @return $this
     * @throws QuizException
     */
    public function setPublicationTimeRange(?DateTimeImmutable $startDate, ?DateTimeImmutable $endDate): self
    {
        if ($this->isCorrectPublicationTimeRange($startDate, $endDate)) {
            $this->delayedPublicationDate = $startDate;
            $this->endedPublicationDate = $endDate;
        } else {
            throw new QuizException(QuizException::INCORRECT_PUBLICATION_TIME_RANGE);
        }

        return $this;
    }

    private function isCorrectPublicationTimeRange(?DateTimeImmutable $startDate, ?DateTimeImmutable $endDate): bool
    {
        if ($startDate !== null && $endDate !== null && $startDate > $endDate) {
            return false;
        }

        return true;
    }
}
