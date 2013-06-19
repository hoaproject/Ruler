%skip   space         \s
// Scalars.
%token  true          true|TRUE
%token  false         false|FALSE
%token  null          null|NULL
// Comparator
%token  isNot         (is not|IS NOT)
%token  is            (is|IS)
// Logical operators
%token  and           AND
%token  nand          NAND
%token  or            OR
%token  nor           NOR
%token  xor           XOR
%token  xnor          XNOR
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
    condition()
    ( (::and:: #and | ::nand:: #nand | ::or:: #or | ::nor:: #nor | ::xor:: #xor | ::xnor:: #xnor) expression())?

condition:
    (::bracket_:: expression() ::_bracket::)
    | (value() operator() value()) #condition

operator:
    <operator> | <key> | <is> | <isNot>

#array:
    ::bracket_:: value() ( ::comma:: value() )* ::_bracket::

value:
    <true> | <false> | <null> | <number> | <float> | <string> | <key> | function() | array()

#function:
    <key> ::bracket_::
    ( value() ( ::comma:: value() )* )?
    ::_bracket::
