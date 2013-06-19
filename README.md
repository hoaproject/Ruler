Ruler
=====

Defining a rule is pretty simple, there is many libraries which offer it. But how do you set theses rules in a storage to re use them later ?
If you want to create a redeem which would be assigned to a cart if some conditions are met, you'll have to save this redeem in a storage.
Hoa\Ruler allows it, create your rules via object or string is easily and store them as string in your favorite storage.

A rule can be defined in 2 formats:

String
------

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

You have to define theses functions and give them to the ruler:

```php
$ruler = new \Rulez\Ruler();
$ruler->addFunction('DATE', function(array $arguments) {
    if (count($arguments) != 2) {
        throw new \LogicException('Date function accepts 2 arguments');
    }

    return date($arguments[0], $arguments[1]);
});

```

Object
------

1) Simple

```php
$rule = new Rulez\Comparator\Equal('foo', 'bar');
```

2) Logical operator

```php
$rule = new Rulez\LogicalOperator\LogicalAnd(
    Rulez\Comparator\Equal('foo', 'bar'),
    Rulez\Comparator\Equal('baz', 1)
    // ....
);
```

3) Nested logical operators

```php
$rule = new Rulez\LogicalOperator\LogicalAnd(
    Rulez\Comparator\Equal('foo', 'bar'),
    new Rulez\LogicalOperator\LogicalAnd(
        Rulez\Comparator\Equal('baz', 1),
        // ....
    )
);
```

Comparators
-----------

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

Logical operators
-----------------

- And
- NAnd
- NOr
- Or
- XNOr
- XOr

Assert rules
------------

```php
$context = new Rulez\Asserter\Context();
$context['customer.id'] = function() {
    // closure to fetch customer.id
};
$context['otherkey'] = 1234;

$ruler = new Rulez\Ruler();
$ruler->assert('customer.id IN [1, 2, 3] AND otherkey = 1234', $context);
```

Wishlist
========

1) Write many tests
