![Hoa](http://static.hoa-project.net/Image/Hoa_small.png)

Hoa is a **modular**, **extensible** and **structured** set of PHP libraries.
Moreover, Hoa aims at being a bridge between industrial and research worlds.

# Hoa\Ruler

This library allows to manipulate a rule engine. Rules can be written by using a
dedicated language, very close to SQL. Therefore, they can be written by a user
and saved in a database.

Such rules are useful, for example, for commercial solutions that need to
manipulate promotion or special offer rules written by a user.

## Quick usage

As a quick overview, we propose to see a very simple example that manipulates a
simple rule with a simple context. After, we will add a new operator in the
rule. And finally, we will see how to save a rule in a database.

So first, we create a context with two variables: `group` and `points`, and we
then assert a rule. A context holds values to concretize a rule. A value can
also be the result of a callable. Thus:

    $ruler = new Hoa\Ruler();

    // 1. Write a rule.
    $rule  = 'group in ("customer", "guest") and points > 30';

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

In the next example, we have a `User` object and a context that is populated
dynamically (when the `user` variable is concretized, two new variables, `group`
and `points` are created). Moreover, we will create a new operator/function
called `logged`. There is no difference between an operator and a function
except that an operator has two operands (so arguments).

For now, we have the following operators/functions by default: `and`, `or`,
`xor`, `not`, `=` (`is` as an alias), `!=`, `>`, `>=`, `<`, `<=`, `in` and
`sum`. We can add our own by different way. The simplest and volatile one is
given in the following example. Thus:

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

    $ruler = new Hoa\Ruler();

    // New rule.
    $rule  = 'logged(user) and group in ("customer", "guest") and points > 30';

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

Now, we have two options to save the rule, for example, in a database. Either we
save the rule as a string directly, or we will save the serialization of the
rule which will avoid further interpretations. In the next example, we see how
to serialize and unserialize a rule by using the `Hoa\Ruler::interprete` static
method:

    $database->save(
        serialize(
            Hoa\Ruler::interprete(
                'logged(user) and group in ("customer", "guest") and points > 30'
            )
        )
    );

And for next executions:

    $rule = unserialize($database->read());
    var_dump(
        $ruler->assert($rule, $context)
    );

When a rule is interpreted, its object model is created. We serialize and
unserialize this model. To see the PHP code needed to create such a model, we
can print the model itself (as an example). Thus:

    echo Hoa\Ruler::interprete(
        'logged(user) and group in ("customer", "guest") and points > 30'
    );

    /**
     * Will output:
     *     $model = new \Hoa\Ruler\Model();
     *     $model->expression =
     *         $model->and(
     *             $model->logged(
     *                 $model->variable('user')
     *             ),
     *             $model->and(
     *                 $model->in(
     *                     $model->variable('group'),
     *                     array(
     *                         'customer',
     *                         'guest'
     *                     )
     *                 ),
     *                 $model->{'>'}(
     *                     $model->variable('points'),
     *                     30
     *                 )
     *             )
     *         );
     */

Have fun!

## Documentation

Different documentations can be found on the website:
[http://hoa-project.net/](http://hoa-project.net/).

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](http://hoa-project.net/LICENSE).
