<?php


namespace Observatby\Mir24Quiz\Dto;


class QuizDto
{
    public string $id;
    public string $title;
    /** @var QuestionDto[] $questions */
    public array $questions;
    public ?PublishingManagementDto $management = null;
}
