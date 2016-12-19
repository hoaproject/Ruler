//
// Hoa
//
//
// @license
//
// New BSD License
//
// Copyright © 2007-2016, Hoa community. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are met:
//     * Redistributions of source code must retain the above copyright
//       notice, this list of conditions and the following disclaimer.
//     * Redistributions in binary form must reproduce the above copyright
//       notice, this list of conditions and the following disclaimer in the
//       documentation and/or other materials provided with the distribution.
//     * Neither the name of the Hoa nor the names of its contributors may be
//       used to endorse or promote products derived from this software without
//       specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
// AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
// LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
// CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
// SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
// INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
// CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.
//
// Grammar \Hoa\Ruler\Grammar.
//
// Provide grammar for ruler.
//
// @copyright  Copyright © 2007-2016 Hoa community.
// @license    New BSD License
//


%skip   space         \s

// Scalars.
%token  true          (?i)true
%token  false         (?i)false
%token  null          (?i)null

// Logical operators
%token  not           (?i)not\b
%token  and           (?i)and\b
%token  or            (?i)or\b
%token  xor           (?i)xor\b

// Value
%token  string        ("|')(.*?)(?<!\\)\1
%token  float         \d+\.\d+
%token  integer       \d+
%token  parenthesis_  \(
%token _parenthesis   \)
%token  bracket_      \[
%token _bracket       \]
%token  comma          ,
%token  dot           \.

%token  identifier    (\d+[^\d\s\(\)\[\],\.]+|[^\s\(\)\[\],\.]+)

#expression:
    logical_operation()

logical_operation:
    operation()
    ( ( ::and:: #and | ::or:: #or | ::xor:: #xor ) logical_operation() )?

operation:
    operand() ( <identifier> logical_operation() #operation )?

operand:
    ::parenthesis_:: logical_operation() ::_parenthesis::
  | value()

value:
    ::not:: logical_operation() #not
  | <true> | <false> | <null> | <float> | <integer> | <string>
  | array_declaration()
  | chain()

chain:
    ( variable() | function_call() )
    ( ( array_access() | object_access() ) #variable_access )*

variable:
    <identifier>

#array_access:
    ::bracket_:: value() ::_bracket::

object_access:
    ::dot:: ( <identifier> #attribute_access | function_call() #method_access )

#array_declaration:
    ::bracket_:: value() ( ::comma:: value() )* ::_bracket::

#function_call:
    <identifier> ::parenthesis_::
    ( logical_operation() ( ::comma:: logical_operation() )* )?
    ::_parenthesis::
