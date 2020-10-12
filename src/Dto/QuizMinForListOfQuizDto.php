<?php


namespace Observatby\Mir24Quiz\Dto;


use DateTimeImmutable;

class QuizMinForListOfQuizDto
{
    public ?string $id = null;
    public ?string $title = null;
    public ?bool $enabled = null;
    public ?DateTimeImmutable $beginDate = null;
    public ?DateTimeImmutable $endDate = null;
}
