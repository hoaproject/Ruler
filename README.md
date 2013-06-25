Ruler
=====

## About

The ruler library aims to help you creating rules with an abstract language, very close to SQL.

So, this way you can easily write a simple & readable one line string rule and ruler will manage the rule for you in the background.
It ensures compilation, serialization & de-serialization of the rule from string to objects (or what you want, feel free to implement your own serializers).

## Use cases

- My boss asked me a complex system of dynamic rules to attribute promo codes into our e-commerce website.
- I've written a piece of software that needs to take realtime decisions depending on some context and I don't want to write spaghetti code.
- ...

## Ok, How can I write rules ?

### With simple strings


Ruler can parse the string and create an object from it.

1) Simple

If we have only one assertion, the format is simple: `left comparator right`

`foo = "bar"`

2) Logical operator

`foo = "bar" AND baz = 1`

3) Parenthesis

You can (as on php/sql or other languages) define many levels of conditions

`foo = "bar" AND (baz = 1 or baz = 2)`
`foo = "bar" AND (baz = 1 or (baz = 2 AND toto != "1"))`

4) Left and right accepts theses formats:

Strings:  `foo = "bar"` or `foo = 'bar'` or `foo = 'Let\'s go'`

Context Key: `foo = "1"` (here `foo` is a context key)

Boolean: `foo = true` or `foo = false`

Integer: `foo = 2`

Float: `foo = 3.14`

Null: `foo is NULL`

Array: `(1, "string", true)`

Function: `DATE('y-m-d', key)`,

Not: `NOT foo = 3.14` or `NOT (foo = 1 and bar = 2)` or `NOT foo`,

You have to define theses functions and give them to the ruler:

```php
from('Hoa')->import('Ruler.Ruler');

$ruler = new \Hoa\Ruler\Ruler();
$ruler->addFunction('DATE', function(array $arguments) {
    if (count($arguments) != 2) {
        throw new \LogicException('Date function accepts 2 arguments');
    }

    return date($arguments[0]->getValue(), $arguments[1]->getValue());
});

```

### The Object way


1) Simple

```php
from('Hoa')->import('Ruler.Comparator.Equal');
$rule = new Hoa\Ruler\Comparator\Equal('foo', 'bar');
```

2) Logical operator

```php
from('Hoa')
    ->import('Ruler.LogicalOperator.LogicalAnd')
    ->import('Ruler.Comparator.*');

$rule = new Hoa\Ruler\LogicalOperator\LogicalAnd(
    Hoa\Ruler\Comparator\Equal('foo', 'bar'),
    Hoa\Ruler\Comparator\Equal('baz', 1)
    // ....
);
```

3) Nested logical operators

```php
from('Hoa')
    ->import('Ruler.LogicalOperator.LogicalAnd')
    ->import('Ruler.Comparator.*');

$rule = new Hoa\Ruler\LogicalOperator\LogicalAnd(
    Hoa\Ruler\Comparator\Equal('foo', 'bar'),
    new Hoa\Ruler\LogicalOperator\LogicalAnd(
        Hoa\Ruler\Comparator\Equal('baz', 1),
        // ....
    )
);
```

## Toolkit

### Comparators


Accepts at this moment:

- equal (=)
- greater than (>)
- greater than equal (>=)
- in (IN), assert than a key exists in an array `key IN (1, 2, 3)`
- is (IS)  `foo IS NULL`
- is not (IS NOT) `foo IS NOT NULL`
- less than (<)
- less than equal (<=)
- not equal (!=)

### Logical operators

Unary:

- Not

Binary:

- And
- NAnd
- NOr
- Or
- XNOr
- XOr

### Transformers


Object as string:

```php
from('Hoa')
    ->import('Ruler.LogicalOperator.LogicalAnd')
    ->import('Ruler.Comparator.*');

$rule = new Hoa\Ruler\LogicalOperator\LogicalAnd(
    Hoa\Ruler\Comparator\Equal('foo', 'bar'),
    new Hoa\Ruler\LogicalOperator\LogicalNot(
        Hoa\Ruler\Comparator\Equal('baz', 1),
    )
);

echo (string) $rule; // 'foo' = 'bar' AND NOT ('baz' = 1)
```

String as object

```php
from('Hoa')->import('Ruler.Ruler');

$rule   = "foo = 'bar' AND NOT (baz = 1)";

$ruler  = new \Hoa\Ruler\Ruler();
$object = $ruler->decode($rule);

// =
new Hoa\Ruler\LogicalOperator\LogicalAnd(
    new Hoa\Ruler\Comparator\Equal(
        new new Hoa\Ruler\Asserter\Bag\ContextBag('foo'),
        new Hoa\Ruler\Asserter\Bag\ScalarBag('bar')
    ),
    new Hoa\Ruler\LogicalOperator\LogicalNot(
        new Hoa\Ruler\Comparator\Equal(
            new Hoa\Ruler\Asserter\Bag\ContextBag('baz'),
            new Hoa\Ruler\Asserter\Bag\ScalarBag('1')
        )
    )
);

```

### Assert rules

```php
from('Hoa')
    ->import('Ruler.Asserter.Context')
    ->import('Ruler.Ruler')
    ;

$context = new Hoa\Ruler\Asserter\Context();
$context['customer.id'] = function() {
    // closure to fetch customer.id
};
$context['otherkey'] = 1234;

$ruler = new Hoa\Ruler\Ruler();
$ruler->assert('customer.id IN (1, 2, 3) AND otherkey = 1234', $context);
```
