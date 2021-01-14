# mir24-quiz

Initially used `"ramsey/uuid": "^4"`, but for laravel 6 is required `"ramsey/uuid": "^3"` and `"moontoast/math": "^1.2"`

## Use

```
use Observatby\Mir24Quiz\UseCase\GetQuizDtoFromRawDbIdForEditing;

$useCase = GetQuizDtoFromRawDbIdForEditing::createWithPdo($pdo);

$quizDtoForEditing = $useCase->handle($idFromDb);
```

## Caution

Из-за внешних обстоятельств библиотека не завершена,
отсутствует поддержка автоинкрементных ключей.
Но версия 0.0.2 работоспособна, хотя поддерживает только uuid
