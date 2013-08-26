<html>
<head>
<title>JS-RichEdit and PHP / MySQL</title>
<style> <!--
BODY { margin: 10px; }
H3 { color: #D00000; }
BODY, P, SPAN, DIV, TABLE, TD, TH, UL, OL, LI {
  font-family: Arial, Helvetica;
  font-size: 14px;
  color: black;
}
.content {
  width: 96%;
  margin-top: 10px;
  margin-bottom: 10px;
  padding: 10px;
  background-color: #F6FAFE;
  border: 1px solid #A0C0E0;
  overflow: auto;
}
.code {
  font-family: Courier New, Monospace;
  font-size: 12px;
  margin: 10px;
  padding: 0px;
  color: blue;
}
--> </style>
</head>
<body>
<h3>JS-RichEdit and PHP / MySQL</h3>
<b>Example</b>
<br/><br/>
This is an example how you can use the richtext editor with PHP. Type something into
the editor, and hit the submit button. The submitted content will appear below and in
the editor.
<br/><br/>
<?
  // if register_globals is off:
  if(isset($_REQUEST['richEdit0'])) $richEdit0 = $_REQUEST['richEdit0'];

  if($richEdit0) {
    if(get_magic_quotes_gpc()) $richEdit0 = stripslashes($richEdit0);
?>
    Submitted content:
    <div class="content">
    <? echo $richEdit0; ?>
    </div>
<?
  }
?>
<script type="text/javascript" src="richedit.js"></script>
<form name="f1" action="example.php" method="post">
<script type="text/javascript"> <!--
var editor = new EDITOR();
<?
  $richEdit0 = preg_replace("/\r|\n/", '', $richEdit0);
  if(!get_magic_quotes_runtime()) $richEdit0 = addslashes($richEdit0);
?>
editor.create("<? echo $richEdit0; ?>");
//--> </script>
<br/>
<input type="submit" value="Submit" onClick="rtoStore()">
</form>
<br/>
<b>Usage</b>
<br/><br/>
You can access the submitted contents with PHP simply by using their corresponding
field names:
<pre class="code">
$richEdit0, $richEdit1, $richEdit2, ...
</pre>
If your PHP configuration does not register global variables (register_globals = Off),
then you can access them like this (PHP 4.1.0 or higher):
<pre class="code">
$_REQUEST['richEdit0'], $_REQUEST['richEdit1'], $_REQUEST['richEdit2'], ...
</pre>
BTW, the default field name <i>richEdit</i> can be changed in the configuration section
of <b>richedit.js</b> or when creating the editor to anything you like.
<br/><br/>
Usually PHP adds slashes automatically to GPC data, so if you want to display the
contents you should strip all slashes first:
<pre class="code">
if(get_magic_quotes_gpc()) $richEdit0 = stripslashes($richEdit0);
</pre>
However, when you store the contents into a database, slashes must not be stripped; instead,
if your PHP configuration does not add slashes to GPC data automatically, you should add them
yourself or you might get a MySQL error:
<pre class="code">
if(!get_magic_quotes_gpc()) {
  $richEdit0 = addslashes($richEdit0);
  $richEdit1 = addslashes($richEdit1);
}
mysql_query("INSERT INTO table_name (field_name1, field_name2) VALUES ('$richEdit0', '$richEdit1')");
</pre>
If you want to put content into the richtext editor with PHP, e.g. from a database, first
you have to remove all CR and NL characters and add slashes. If you don't, you'll probably
get a JavaScript error:
<pre class="code">
$content = preg_replace("/\r|\n/", '', $content);
if(!get_magic_quotes_runtime()) $content = addslashes($content);
</pre>
Finally, it's a good idea to have a look at the source code of this page. ;-)
</body>
</html>
