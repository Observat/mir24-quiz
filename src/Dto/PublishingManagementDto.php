<?php


namespace Observatby\Mir24Quiz\Dto;


use DateTimeImmutable;

class PublishingManagementDto
{
    public ?bool $enabled = null;
    public ?DateTimeImmutable $beginDate = null;
    public ?DateTimeImmutable $endDate = null;
    # TODO public bool $activated;
}
