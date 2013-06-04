Ruler
=====

Some rules:

```
// nested brackets
("a.toto" IS "toto" AND "b" = true AND ("c" IS NOT NULL and "e" = 1)) OR "d" = "pouet"
// functions
DATE('Y-m-d', contextKey) > "2013-02-25"
// Arrays
customer.id IN [1, 2, 3]
```

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
