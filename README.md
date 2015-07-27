#PHP parse class
standard php parsing routines for manipulating html pages and extract the data you want <br>
it offer some useful methods to handle many important tasks for webbots and scrapers developers <br>
### splitString()
The simplest parsing method returns a string that contains everything <br>
before or after a delimiter term. This simple method can also be used to <br>
return the text between two terms.<br>
string splitStrin (string unparsed, string delimiter, BEFORE/AFTER, INCL/EXCL)<br>
Where<br>
<i><b>unparsed</i></b> is the string to parse<br>
<i><b>delimiter</i></b> defines boundary between substring you want and substring you don't want<br>
<i><b>BEFORE</i></b> indicates that you want what is before the delimiter<br>
<i><b>AFTER</i></b> indicates that you want what is after the delimiter<br>
<i><b>INCL</i></b> indicates that you want to include the delimiter in the parsed text<br>
<i><b>EXCL</i></b> indicates that you don't want to include the delimiter in the parsed text<br>

```php
include 'parse.class.php';
$parse = new Parse() ;
$string = "i made this is my cool class";
# Parse what's before the delimiter, including the delimiter
$parsed_text = $parse->splitString($string, "my", 'BEFORE', 'INCL');
// $parsed_text = "i made this is my"
# Parse what's after the delimiter, but don't include the delimiter
$parsed_text = $parse->splitString($string, "my", 'AFTER', 'EXCL');
// $parsed_text = "cool class"
```
### returnBetween()
This method uses a start delimiter and an end delimiter
to define a particular part of a string

string return_between (string unparsed, string start, string end, INCL/EXCL)
Where <br>
<i><b>unparsed</i></b> is the string to parse<br>
<i><b>start</i></b> identifies the starting delimiter<br>
<i><b>end</i></b> identifies the ending delimiter<br>
<i><b>INCL</i></b> indicates that you want to include the delimiters in the parsed text<br>
<i><b>EXCL</i></b> indicates that you don't want to include delimiters in the parsed text<br>
```php
include 'parse.class.php';
$parse = new Parse() ;
$string = "i made this is my cool class";
# Parse what's before the delimiter, including the delimiter
$parsed_text = $parse->returnBetween($string, "my", 'class', 'EXCL'); // will return cool
$parsed_text = $parse->returnBetween($string, "my", 'class', 'INCL'); // will return my cool class
```
### parseArray()
This method is usful for returning an array that contains <br>     
links, images, tables or any other data that appears more than once. <br>
array return_array (string unparsed, string beg, string end)
Where <br>
<i><b>unparsed </i></b>is the string to parse <br>
<i><b>beg </i></b> is a reoccurring beginning delimiter <br>
<i><b>end </i></b> is a reoccurring ending delimiter <br>
<i><b>array </i></b> contains every occurrence of what's found between beginning and end. <br>

