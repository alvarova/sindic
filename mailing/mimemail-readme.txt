===============================================================================================
INTRODUCTION
===============================================================================================
What do you need this script for? Basically, if you just want to send a simple message with
plain text and don't want to attach any files to it, you don't need this script. Just use the
PHP mail-function.

However, if you want to send HTML mails including images and stylesheets, or want to attach
files - this script is for you. Images and files will be included into the mail body and will
be sent to the recipient(s). MIME mails (Multipurpose Internet Mail Extensions) can consist of
more than just plain text.

What's the benefit? Once the recipients got your e-mail, they don't need to be online to see
the images or load the stylesheets, since they are included in the mail body. So one benefit
is that your e-mails can be viewed offline at any time, and the second is that all the included
images and stylesheets will not be loaded from your server every time somebody opens your
e-mail, like they would if you just linked them. This means less traffic for your server, and
no more missing files.

This script should work with PHP4+. Mail delivery was tested with Windows XP and Outlook
Express. If you use another system or e-mail client and this script doesn't work for you,
please let me know!

===============================================================================================
LICENSE
===============================================================================================
This script is freeware for non-commercial use. If you like it, please feel free to make a
donation! However, if you intend to use the script in a commercial project, please donate at
least EUR 6.
You can make a donation on my website: http://www.gerd-tentler.de/tools/mimemail/

===============================================================================================
USAGE
===============================================================================================
Variables:

  o type         => e-mail type ("HTML" or "Text")
  o senderName   => sender name
  o senderMail   => sender e-mail address
  o cc           => cc (e-mail address)
  o bcc          => bcc (e-mail address)
  o replyTo	 => reply-to (e-mail address)
  o subject      => subject line
  o priority     => priority ("high", "normal", "low");
  o body         => message (plain text or HTML) OR path to existing HTML file
  o attachments  => attachment(s) (array; path to file)

  o documentRoot => document root (path to images, stylesheets, etc.)
  o saveDir      => save e-mail to this directory instead of sending it => just for testing :)
  o charSet      => character set (ISO)
  o useQueue     => use mail queue (true = yes, false = no) => does not work with PHP
                    versions < 4.0.5, or with versions >= 4.2.3 in Safe Mode, or with
                    MTAs other than sendmail!

Include the MIMEMAIL class into your PHP script and create a new instance:

  include("mimemail.inc.php");
  $mail = new MIMEMAIL("HTML");

Then set senderName, senderMail, subject, and optionally cc, bcc, replyTo, and priority:

  $mail->senderName = "sender name";
  $mail->senderMail = "sender@email";
  $mail->subject = "This is the subject line";

The mail body either contains your message (plain text or HTML):

  $mail->body = "Hello! This is a message for you.";

Or, if you want to send an already existing HTML file instead, just put its path into the
mail body:

  $mail->body = "path/to/file";

The HTML text will be parsed for images, scripts and stylesheets, and they will be included
into your e-mail automatically.

You can also attach files to your e-mail:

  $mail->attachments[] = "path/to/file1";
  $mail->attachments[] = "path/to/file2";
  $mail->attachments[] = "path/to/file3";
  ...

When all settings are done, create the MIME mail:

  $mail->create();

And finally send it to each recipient:

  // recipient list can be an array...
  $recipients = array('recipient1@email', 'recipient2@email', 'Recipient3 <recipient3@email>');
  if(!$mail->send($recipients)) echo $mail->error;

  // ...or a string with comma-separated values
  $recipients = 'recipient4@email, recipient5@email, Recipient6 <recipient6@email>';
  if(!$mail->send($recipients)) echo $mail->error;

NOTES:

If you include attachments and PHP can not locate the files e.g. because of a wrong path,
they won't be sent. Same goes of course for images and stylesheets inside your mail body.
So before starting a mailing you should first send the e-mail to an address of yours, or
save the e-mail using the saveDir option, and check if everything works like it should.

If you are using sendmail as MTA, you can put the e-mails into its mail queue instead of
sending them immediately by using the useQueue option. This is useful if you are sending
lots of e-mails, because by using the queue PHP doesn't have to wait for each e-mail until
it is actually sent, thus making the script much faster. The drawback is that by using the
mail queue you leave it to the MTA when your e-mails are being sent. This can take minutes
or even hours, depending on server load and queue size.

Maybe you ask yourself why you have to call create() explicitely before sending your e-mail
with send(). Well, imagine this: You want to send the same e-mail to several recipients, but
you also want to personalize it, e.g. by starting with "Dear Peter", "Dear Paul", etc. If
you had to create the entire e-mail with all attachments etc. for each recipient again only
because the name has changed, this would be a big waste of time and ressources. Instead, you
create it only once with a variable [NAME] and then replace [NAME] with each recipient's
name before sending. Example:

  ...
  $mail->subject = "Hello [NAME]!";
  $mail->body = "Dear [NAME], this is a personal message for you.";

  // create the MIME mail once
  $mail->create();

  $recipients = array('Peter' => 'peter@somewhere.com',
                      'Paul'  => 'paul@somewhere.com',
                      'Mary'  => 'mary@somewhere.com');

  // for each recipient...
  foreach($recipients as $name => $address) {

      // replace variables in subject line and body text
      $mail->subject = str_replace('[NAME]', $name, $mail->subject);
      $mail->body = str_replace('[NAME]', $name, $mail->body);

      // send e-mail
      if(!$mail->send("$name <$address>")) echo $mail->error;
  }

This works because the MIMEMAIL class saves the original subject line and body text when the
MIME mail is created and restores it each time after sending. Please note that this variable
replacement only works with the subject line and the body text, not with any other contents.

===============================================================================================
Source code available at http://www.gerd-tentler.de/tools/mimemail/
===============================================================================================
