<?php
declare(strict_types=1);

namespace Observatby\Mir24Quiz\Tests\Unit;


use DateTimeImmutable;
use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\PublishingManagement;
use Observatby\Mir24Quiz\Model\Quiz;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Tests\CreateQuizTrait;
use PHPUnit\Framework\TestCase;


class PublishingManagementTest extends TestCase
{
    use CreateQuizTrait;

    public function testCreateQuizWithPublishingManagement(): void
    {
        $quiz = new Quiz(
            Id::createNew(),
            'First quiz',
            [
                $this->createQuizQuestion_1(),
                $this->createQuizQuestion_2(),
            ],
            new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week'))
        );

        $this->assertTrue($quiz->getPublishingManagement()->isActive());
    }

    public function testEnable(): void
    {
        $management = new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week'));
        $this->assertTrue($management->isActive());

        $management->disable();
        $this->assertFalse($management->isActive());

        $management->enable();
        $this->assertTrue($management->isActive());
    }

    public function testBeginDate(): void
    {
        $management = new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week'));
        $this->assertTrue($management->isActive());

        $management->setPublicationTimeRange(new DateTimeImmutable('now + 1 day'), $management->getEndedPublicationDate());
        $this->assertFalse($management->isActive());

        $management->setPublicationTimeRange(null, $management->getEndedPublicationDate());
        $this->assertFalse($management->isActive());
    }

    public function testEndDate(): void
    {
        $management = new PublishingManagement(true, new DateTimeImmutable('now - 1 week'), new DateTimeImmutable('now + 1 week'));
        $this->assertTrue($management->isActive());

        $management->setPublicationTimeRange($management->getDelayedPublicationDate(), new DateTimeImmutable('now - 1 day'));
        $this->assertFalse($management->isActive());

        $management->setPublicationTimeRange($management->getDelayedPublicationDate(), null);
        $this->assertTrue($management->isActive());
    }

    public function testIncorrectTimeRange(): void
    {
        $this->expectException(QuizException::class);

        new PublishingManagement(true, new DateTimeImmutable('now + 1 week'), new DateTimeImmutable('now - 1 week'));
    }
}
