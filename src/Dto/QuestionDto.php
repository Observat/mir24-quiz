<?php


namespace Observatby\Mir24Quiz\Dto;


class QuestionDto
{
    public string $id;
    public string $text;
    public string $imageSrc;
    /** @var AnswerDto[] $answers */
    public array $answers;
}
