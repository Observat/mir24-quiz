<?php


namespace Observatby\Mir24Quiz\Dto;


use DateTimeImmutable;

class PublishingManagementDto
{
    public bool $enabled;
    public ?DateTimeImmutable $beginDate;
    public ?DateTimeImmutable $endDate;
    # TODO public bool $activated;
}
