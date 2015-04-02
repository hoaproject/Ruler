![Hoa](http://static.hoa-project.net/Image/Hoa_small.png)

Hoa is a **modular**, **extensible** and **structured** set of PHP libraries.
Moreover, Hoa aims at being a bridge between industrial and research worlds.

# Hoa\Ruler ![state](http://central.hoa-project.net/State/Ruler)
The documentation can be found on the [documentation website](http://hoa-project.net/En/Literature/Hack/Ruler.html).

This library allows to manipulate a rule engine. Rules can be written by using a
dedicated language, very close to SQL. Therefore, they can be written by a user
and saved in a database.

Such rules are useful, for example, for commercial solutions that need to
manipulate promotion or special offer rules written by a user. To quote
[Wikipedia](http://en.wikipedia.org/wiki/Business_rules_engine):

> A business rules engine is a software system that executes one or more
> business rules in a runtime production environment. The rules might come from
> legal regulation (“An employee can be fired for any reason or no reason but
> not for an illegal reason”), company policy (“All customers that spend more
> than $100 at one time will receive a 10% discount”), or other sources. A
> business rule system enables these company policies and other operational
> decisions to be defined, tested, executed and maintained separately from
> application code.

## Installation

With [Composer](http://getcomposer.org/), to include this library into your
dependencies, you need to require
[`hoa/ruler`](https://packagist.org/packages/hoa/ruler):

```json
{
    "require": {
        "hoa/ruler": "~1.0"
    }
}
```

Please, read the website to [get more informations about how to
install](http://hoa-project.net/Source.html).

## Quick usage

As a quick overview, we propose to see a very simple example that manipulates a
simple rule with a simple context. After, we will add a new operator in the
rule. And finally, we will see how to save a rule in a database.

### Three steps

So first, we create a context with two variables: `group` and `points`, and we
then assert a rule. A context holds values to concretize a rule. A value can
also be the result of a callable. Thus:

```php
$ruler = new Hoa\Ruler\Ruler();

// 1. Write a rule.
$rule  = 'group in ["customer", "guest"] and points > 30';

// 2. Create a context.
$context           = new Hoa\Ruler\Context();
$context['group']  = 'customer';
$context['points'] = function ( ) {

    return 42;
};

// 3. Assert!
var_dump(
    $ruler->assert($rule, $context)
);

/**
 * Will output:
 *     bool(true)
 */
```

In the next example, we have a `User` object and a context that is populated
dynamically (when the `user` variable is concretized, two new variables, `group`
and `points` are created). Moreover, we will create a new operator/function
called `logged`. There is no difference between an operator and a function
except that an operator has two operands (so arguments).

### Adding operators and functions

For now, we have the following operators/functions by default: `and`, `or`,
`xor`, `not`, `=` (`is` as an alias), `!=`, `>`, `>=`, `<`, `<=`, `in` and
`sum`. We can add our own by different way. The simplest and volatile one is
given in the following example. Thus:

```php
// The User object.
class User {

    const DISCONNECTED = 0;
    const CONNECTED    = 1;

    public $group      = 'customer';
    public $points     = 42;
    protected $_status = 1;

    public function getStatus ( ) {

        return $this->_status;
    }
}

$ruler = new Hoa\Ruler\Ruler();

// New rule.
$rule  = 'logged(user) and group in ["customer", "guest"] and points > 30';

// New context.
$context         = new Hoa\Ruler\Context();
$context['user'] = function ( ) use ( $context ) {

    $user              = new User();
    $context['group']  = $user->group;
    $context['points'] = $user->points;

    return $user;
};

// We add the logged() operator.
$ruler->getDefaultAsserter()->setOperator('logged', function ( User $user ) {

    return $user::CONNECTED === $user->getStatus();
});

// Finally, we assert the rule.
var_dump(
    $ruler->assert($rule, $context)
);

/**
 * Will output:
 *     bool(true)
 */
```

Also, if a variable in the context is an array, we can access to its values from
a rule with the same syntax as PHP. For example, if the `a` variable is an
array, we can write `a[0]` to access to the value associated to the `0` key. It
works as an hashmap (PHP array implementation), so we can have strings & co. as
keys. In the same way, if a variable is an object, we can call a method on it.
For example, if the `a` variable is an array where the value associated to the
first key is an object with a `foo` method, we can write: `a[0].foo(b)` where
`b` is another variable in the context. Also, we can access to the public
attributes of an object. Obviously, we can mixe array and object accesses.
Please, take a look at the grammar (`hoa://Library/Ruler/Grammar.pp`) to see all
the possible constructions.

### Saving a rule

Now, we have two options to save the rule, for example, in a database. Either we
save the rule as a string directly, or we will save the serialization of the
rule which will avoid further interpretations. In the next example, we see how
to serialize and unserialize a rule by using the `Hoa\Ruler\Ruler::interpret`
static method:

```php
$database->save(
    serialize(
        Hoa\Ruler\Ruler::interpret(
            'logged(user) and group in ["customer", "guest"] and points > 30'
        )
    )
);
```

And for next executions:

```php
$rule = unserialize($database->read());
var_dump(
    $ruler->assert($rule, $context)
);
```

When a rule is interpreted, its object model is created. We serialize and
unserialize this model. To see the PHP code needed to create such a model, we
can print the model itself (as an example). Thus:

```php
echo Hoa\Ruler\Ruler::interpret(
    'logged(user) and group in ["customer", "guest"] and points > 30'
);

/**
 * Will output:
 *     $model = new \Hoa\Ruler\Model();
 *     $model->expression =
 *         $model->and(
 *             $model->func(
 *                 'logged',
 *                 $model->variable('user')
 *             ),
 *             $model->and(
 *                 $model->in(
 *                     $model->variable('group'),
 *                     [
 *                         'customer',
 *                         'guest'
 *                     ]
 *                 ),
 *                 $model->{'>'}(
 *                     $model->variable('points'),
 *                     30
 *                 )
 *             )
 *         );
 */
```

Have fun!

## Documentation

Different documentations can be found on the website:
[http://hoa-project.net/](http://hoa-project.net/).

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](http://hoa-project.net/LICENSE).
