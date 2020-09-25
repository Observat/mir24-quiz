<?php

namespace Observatby\Mir24Quiz\Tests;

use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Model\QuizAnswer;
use Observatby\Mir24Quiz\Model\QuizQuestion;

trait CreateQuizTrait
{
    private function createQuizQuestion_1(): QuizQuestion
    {
        return new QuizQuestion(
            Id::createNew(),
            'This first question?',
            [
                $this->createQuizAnswer_yes_1(),
                $this->createQuizAnswer_no_0()
            ]
        );
    }

    private function createQuizQuestion_2(): QuizQuestion
    {
        return new QuizQuestion(
            Id::createNew(),
            'This no second question?',
            [
                $this->createQuizAnswer_yes_0(),
                $this->createQuizAnswer_no_1()
            ]
        );

    }

    private function createQuizAnswer_yes_1(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            true,
            'Yes'
        );
    }

    private function createQuizAnswer_yes_0(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            false,
            'Yes'
        );
    }

    private function createQuizAnswer_no_0(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            false,
            'No'
        );
    }

    private function createQuizAnswer_no_1(): QuizAnswer
    {
        return new QuizAnswer(
            Id::createNew(),
            true,
            'No'
        );
    }
}
