<?php


namespace Observatby\Mir24Quiz;


use Exception;


class QuizException extends Exception
{
    public const INCORRECT_PUBLICATION_TIME_RANGE = 'INCORRECT_PUBLICATION_TIME_RANGE';
    public const DATABASE_IS_NOT_PREPARED = 'DATABASE_IS_NOT_PREPARED';
    public const NOT_CREATED_IN_DATABASE = 'NOT_CREATED_IN_DATABASE';
    public const INPUT_ARRAY_AND_OUTPUT_DTO_MISMATCH = 'INPUT_ARRAY_AND_OUTPUT_DTO_MISMATCH';
}
