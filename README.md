# mir24-quiz

Initially used `"ramsey/uuid": "^4"`, but for laravel 6 is required `"ramsey/uuid": "^3"` and `"moontoast/math": "^1.2"`

## Use

`
$useCase = GetQuizDtoFromRawDbIdForEditing::createWithPdo($pdo);
$quizDtoForEditing = $useCase->handle($idFromDb);
`
