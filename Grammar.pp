%skip   space         \s
// Scalars.
%token  true          true|TRUE
%token  false         false|FALSE
%token  null          null|NULL
// Comparator
%token  isNot         (is not|IS NOT)
%token  is            (is|IS)
// Logical operators
%token  not           NOT
%token  and           AND
%token  or            OR
%token  xor           XOR
// Value
%token  string        ("|')(.*?)(?<!\\)\1
%token  number        \d+
%token  float         \d+\.\d+
%token  key           [a-zA-Z0-9-_\.]+
%token  bracket_      \(
%token _bracket       \)
%token  comma          ,

%token  operator      [^\s]+

expression:
    unary_expression()
    ( (::and:: #and | ::or:: #or | ::xor:: #xor) expression())?

unary_expression:
    (::not:: #not) ? condition()

condition:
    (::bracket_:: expression() ::_bracket::)
    | (value() operator() value()) #condition
    | value()

operator:
     <key> | <is> | <isNot> | <operator>

#array:
    ::bracket_:: value() ( ::comma:: value() )* ::_bracket::

value:
    (::not:: #not) ?
    (<true> | <false> | <null> | <number> | <float> | <string> | <key> | function() | array())

#function:
    <key> ::bracket_::
    ( value() ( ::comma:: value() )* )?
    ::_bracket::
