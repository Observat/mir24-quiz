<?php


namespace Observatby\Mir24Quiz\Dto;


class QuestionDto
{
    public ?string $id = null;
    public ?string $text = null;
    public ?string $imageSrc = null;
    /** @var ?AnswerDto[] $answers */
    public ?array $answers = null;
}
