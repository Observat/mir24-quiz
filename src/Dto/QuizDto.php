<?php


namespace Observatby\Mir24Quiz\Dto;


class QuizDto
{
    public ?string $id = null;
    public ?string $title = null;
    /** @var ?QuestionDto[] $questions */
    public ?array $questions = null;
    public ?PublishingManagementDto $management = null;
}
