<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


interface QuizQueryConstantsInterface
{
    public const QUERY_LIST = "SELECT
                               quiz.id as quiz_id,
                               quiz.title as quiz_title,
                               quiz_management.enable as enabled,
                               quiz_management.beginDatetime as begin_date,
                               quiz_management.endDatetime as end_date
                           FROM quiz
                           LEFT JOIN quiz_management ON quiz.id = quiz_management.quiz_id";
    public const QUERY = "SELECT
                               quiz.id as quiz_id,
                               quiz.title as quiz_title,
                               quiz_question.id as question_id,
                               quiz_question.text as question_text,
                               quiz_question.image_src as question_image_src,
                               quiz_answer.id as answer_id,
                               quiz_answer.text as answer_text,
                               quiz_answer.correct as answer_correct,
                               quiz_management.enable as enabled,
                               quiz_management.beginDatetime as begin_date,
                               quiz_management.endDatetime as end_date
                           FROM quiz
                           INNER JOIN quiz_question ON quiz_question.quiz_id = quiz.id
                           INNER JOIN quiz_answer ON quiz_answer.question_id = quiz_question.id
                           LEFT JOIN quiz_management ON quiz.id = quiz_management.quiz_id
                           WHERE quiz.id = ?";
    public const QUERY_ONLY_ID = "SELECT
                               quiz.id as quiz_id,
                               quiz_question.id as question_id,
                               quiz_answer.id as answer_id
                           FROM quiz
                           LEFT JOIN quiz_question ON quiz_question.quiz_id = quiz.id
                           LEFT JOIN quiz_answer ON quiz_answer.question_id = quiz_question.id
                           WHERE quiz.id = ?";
}
