<?php

namespace Observatby\Mir24Quiz\Tests;

use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\Image;
use Observatby\Mir24Quiz\Model\QuizAnswer;
use Observatby\Mir24Quiz\Model\QuizQuestion;

trait CreateQuizTrait
{
    private function createQuizQuestion_1(?QuizAnswer $answer1 = null, ?QuizAnswer $answer2 = null): QuizQuestion
    {
        return new QuizQuestion(
            Id::createNew(),
            'This first question?',
            new Image(''),
            [
                $answer1 ?? $this->createQuizAnswer_yes_true(),
                $answer2 ?? $this->createQuizAnswer_no_false()
            ]
        );
    }

    private function createQuizQuestion_2(?QuizAnswer $answer1 = null, ?QuizAnswer $answer2 = null): QuizQuestion
    {
        return new QuizQuestion(
            Id::createNew(),
            'This no second question?',
            new Image(''),
            [
                $answer1 ?? $this->createQuizAnswer_yes_false(),
                $answer2 ?? $this->createQuizAnswer_no_true()
            ]
        );

    }

    private function createQuizAnswer_yes_true(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            true,
            'Yes'
        );
    }

    private function createQuizAnswer_yes_false(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            false,
            'Yes'
        );
    }

    private function createQuizAnswer_no_false(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            false,
            'No'
        );
    }

    private function createQuizAnswer_no_true(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            true,
            'No'
        );
    }
}
