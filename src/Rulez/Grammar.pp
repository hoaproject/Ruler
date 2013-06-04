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
%token  key           [a-zA-Z0-9-_\.]+
%token  number        \d+
%token  float         \d+\.\d+
%token  bracket_      \(
%token _bracket       \)
%token  hook_         \[
%token  _hook         \]
%token  comma          ,

%token  operator      [^\s]+

expression:
    condition()
    ( (::and:: #and | ::nand:: #nand | ::or:: #or | ::nor:: #nor | ::xor:: #xor | ::xnor:: #xnor) expression())?

condition:
    (::bracket_:: expression() ::_bracket::)
    | (value() operator() value()) #condition

string:
    <string>

key:
    <key>

number:
    <number>

float:
    <float>

operator:
    <operator> | <key> | <is> | <isNot>

#array:
    ::hook_:: value() ( ::comma:: value() )* ::_hook::

value:
    <true> | <false> | <null> | number() | float() | string() | key() | function() | array()

#function:
    <key> ::bracket_::
    ( value() ( ::comma:: value() )* )?
    ::_bracket::
