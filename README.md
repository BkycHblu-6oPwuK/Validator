# Валидатор

Эта библиотека предоставляет собой простую реализацию валидатора.

Установка:

```bash
composer require beeralex/validator:dev-main
```

## Как использовать

Для использования валидатора создайте класс, наследующийся от `\Validator\Validator`, и реализуйте метод `rules`, описывающий правила валидации. Каждое правило разделяется символом '|'.

```php
protected function rules() : array
{
    return [
        'name' => 'required|string|min:2|max:30',
        'email' => 'email',
        'age' => 'nullable|integer',
    ];
}
```

## Пользовательские сообщения об ошибках

Вы также можете создать пользовательские сообщения об ошибках для каждого правила с помощью метода `messages`:

```php
protected function messages(): array
{
    return [
        'name.required' => 'Имя обязательный параметр',
        'name.min' => 'Имя должно быть не короче 2 символов',
        'email.required' => 'Email обязательный параметр',
        'email.email' => 'Email должен быть валидным',
    ];
}
```

## Стандартные правила

- `array` - Проверяет, является ли значение массивом.
- `boolean` - Проверяет, является ли значение логическим значением.
- `email` - Проверяет, является ли значение валидным email.
- `float` - Проверяет, является ли значение числом с плавающей точкой (целое число вернет false).
- `in` - Ищет значение в массиве. Если значение не массив, то вернет false.
- `integer` - Проверяет, является ли значение целым числом (числовая строка или число с плавающей точкой вернут false).
- `max:{param}` - Проверяет, является ли длина строки больше или равна переданному параметру. Если значение не строка, то вернется false.
- `min:{param}` - Проверяет, является ли длина строки меньше или равна переданному параметру. Если значение не строка, то вернется false.
- `nullable` - Значение может быть необязательным.
- `numeric` - Проверяет, является ли значение числом или числовой строкой.
- `required` - Значение обязательно. При этом если поле и required и nullable, то nullable будет проигнорировано.
- `string` - Проверяет, является ли значение строкой (пустая строка также вернет true).

## Пользовательские правила

Пользовательские правила должны наследоваться от класса Validator\Rules\Rule, а добавить их можно с помощью метода `userRules` который нужно переопределить в вашем класса валидатора, этот метод должен вернуть объект класса `Validator\RegistryCustomRules`. Используйте метод `addRule` который параметром принимает полное имя класса.

```php
protected function userRules() : ?RegistryUserRules
{
    return (new RegistryUserRules)->addRule(TestRule::class);
}
```
Имя класса правила должно заканчиваться на `Rule` (TestRule), таким образом имя правила будет сформировано из названия класса и получиться `test`, слово `Rule` в названии обрезается. Или же переопределите статический метод `getRuleName`, которое должно вернуть имя этого правила.

Имя пользовательского правила должно быть уникальным и не совпадать со стандартными правилами. Теперь имя, возвращаемое методом `getRuleName`, можно использовать при формировании правила в методе `rules`. Если передаете параметр через двоеточие, то самостоятельно реализуйте конструктор в своем классе правила, значение будет передано.

```php
namespace Validator\Test;

class TestRule extends \Validator\Rules\Rule
{
    public function validate($value) : bool
    {
        return false;
    }
    public function getMessage(): string
    {
        return 'test сustom rule message';
    }
    public static function getRuleName() : string
    {
        return 'newTestRuleName';
    }
}
```

## Пример использования

Для валидации массива создайте экземпляр класса валидатора. Конструктор принимает массив, значения которого будут проверяться.

```php
$validator = new UserValidator([
    'name' => 'имя',
    'email' => 'email@email.com',
    'age' => 33,
]);
```

Для валидации используйте метод `validate`, который возвращает логическое значение:

```php
$validator->validate();
```

Для получения сообщений об ошибках используйте метод `errors`:

```php
$validator->errors();
```

Для получения массива отвалидированных значений используйте метод `validated`:

```php
$validator->validated();
```

```php
class UserValidator extends \Validator\Validator
{
    protected function rules() : array
    {
        return [
            'name' => 'required|string|min:2|max:30',
            'email' => 'email|newTestRuleName',
            'age' => 'nullable|integer',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Имя обязательный параметр',
            'name.min' => 'Имя должно быть не короче 2 символов',
            'email.required' => 'Email обязательный параметр',
            'email.email' => 'Email должен быть валидным',
            'email.newTestRuleName' => 'Новое сообщение для newTestRuleName'
        ];
    }

    protected function userRules() : ? \Validator\RegistryUserRules
    {
        return (new \Validator\RegistryUserRules)->addRule(TestRule::class);
    }
}
```