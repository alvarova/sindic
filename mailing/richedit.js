/*
 +-------------------------------------------------------------------+
 |                 J S - R I C H E D I T   (v1.20)                   |
 |                                                                   |
 | Copyright Gerd Tentler               www.gerd-tentler.de/tools    |
 | Created: Jun. 2, 2003                Last modified: Jun. 11, 2009 |
 +-------------------------------------------------------------------+
 | This program may be used and hosted free of charge by anyone for  |
 | personal purpose as long as this copyright notice remains intact. |
 |                                                                   |
 | Obtain permission before selling the code for this program or     |
 | hosting this software on a commercial website or redistributing   |
 | this software over the Internet or in any other medium. In all    |
 | cases copyright must remain intact.                               |
 +-------------------------------------------------------------------+

===========================================================================================================
 This script was tested with the following systems and browsers:

 - Windows XP: IE 8, Opera 9, Firefox 3

 If you use another browser or system, this script may not work for you - sorry.

 Generally, richtext editing should work on Windows with Internet Explorer 4+ and with browsers using the
 Mozilla 1.3+ engine, i.e. all browsers that support "designMode".

 NOTE: The following browsers have been tested and do NOT support richtext editing: NN 7.0 and Opera 7.0
 on Windows, IE 5.2 and Safari 1.0 on Mac OS. However, the script works with them, too - a simple textarea
 will replace the richtext editor.
===========================================================================================================
*/

var OP = (window.opera || navigator.userAgent.indexOf('Opera') != -1);
var IE = (navigator.userAgent.indexOf('MSIE') != -1 && !OP);
var GK = (navigator.userAgent.indexOf('Gecko') != -1 || OP);
var DM = (document.designMode && document.execCommand);

var rto = new Array();
var mouseX, mouseY, winX, winY, scrLeft, scrTop;

//---------------------------------------------------------------------------------------------------------
// Language settings
//---------------------------------------------------------------------------------------------------------
  var txtParagraph       = "Paragraph";
  var txtNormal          = "Normal";
  var txtHeading         = "Heading";
  var txtClearFormatting = "Clear Formatting";
  var txtJustifyLeft     = "Justify Left";
  var txtJustifyCenter   = "Justify Center";
  var txtJustifyRight    = "Justify Right";
  var txtJustifyFull     = "Justify Full";
  var txtOrderedList     = "Ordered List";
  var txtUnorderedList   = "Unordered List";
  var txtOutdent         = "Outdent";
  var txtIndent          = "Indent";
  var txtInsertHR        = "Insert Horizontal Rule";
  var txtInsertTable     = "Insert Table";
  var txtInsertGraph     = "Insert Graph";
  var txtInsertBullet    = "Insert Bullet Point";
  var txtInsertImage     = "Insert Image";
  var txtInsertText      = "Insert text here";
  var txtFont            = "Font";
  var txtSize            = "Size";
  var txtBold            = "Bold";
  var txtItalic          = "Italic";
  var txtUnderline       = "Underline";
  var txtFontColor       = "Font Color";
  var txtBGColor         = "Background Color";
  var txtHyperlink       = "Hyperlink";
  var txtCut             = "Cut";
  var txtCopy            = "Copy";
  var txtPaste           = "Paste";
  var txtUndo            = "Undo";
  var txtRedo            = "Redo";
  var txtBorder          = "Border";
  var txtBorderColor     = "Border Color";
  var txtCellColor       = "Cell Color";
  var txtCellSpacing     = "Cell Spacing";
  var txtCellPadding     = "Cell Padding";
  var txtColumns         = "Columns";
  var txtRows            = "Rows";
  var txtCreate          = "Create";
  var txtCancel          = "Cancel";
  var txtValues          = "Values";
  var txtLabels          = "Labels";
  var txtBarColor        = "Bar Color";
  var txtLabelColor      = "Label Color";
  var txtViewValues      = "View Values";
  var txtLegend          = "Legend";
  var txtViewSource      = "View Source";
  var txtViewEditor      = "View Editor";
  var txtNoRichEdit      = "Sorry, your browser does not support richtext editing!";
  var txtCreateError     = "Could not create editor!";

function EDITOR() {
//---------------------------------------------------------------------------------------------------------
// Configuration
//---------------------------------------------------------------------------------------------------------
  this.editorBGColor = "#E0E0E0";               // editor background color
  this.editorBorder = "2px groove #FFFFFF";     // editor border (CSS-spec: "size style color")

  this.textWidth = 460;                         // text field width (pixels)
  this.textHeight = 120;                        // text field height (pixels)
  this.textBGColor = "#FFFFFF";                 // text field background color
  this.textBorder = "2px inset #FFFFFF";        // text field border (CSS-spec: "size style color")
  this.textFont = "Verdana, Arial, Helvetica";  // text field font family (CSS-spec)
  this.textFontSize = 12;                       // text field font size (pixels)

  this.setFocus = false;                        // focus text field on load (true or false)
  this.fieldName = "richEdit";                  // default field name
  this.iconPath = "icons";                      // path to icons
  this.bulletpoint = "bp.gif";                  // bullet point image (full path)

//---------------------------------------------------------------------------------------------------------
// Functions
//---------------------------------------------------------------------------------------------------------
  this.editor = 0;
  this.id = 0;
  this.curSelection = 0;
  this.field = '';
  this.curFontColor = '#000000';
  this.curBGColor = '#FFFF00';
  this.source = false;

  this.getEditor = function() {
    var e = false;
    if(GK) e = document.getElementById('rtoIFrame' + this.id).contentWindow;
    else if(IE) e = document.frames('rtoIFrame' + this.id);
    if(e && !DM) e = false;
    return e;
  }

  this.wordWrap = function(string, col, prefix) {
    if(col == null) col = 100;
    if(prefix == null) prefix = '';
    var text = line = newline = word = '';
    var row = col - prefix.length;
    var i, j, cnt;
    var words = new Array();
    var lines = new Array();
    lines = string.split('\n');

    if(row > 0) {
      for(i = 0; i < lines.length; i++) {
        line = lines[i];

        if(line.length > row) {
          newline = '';
          words = line.split(' ');

          for(j = 0; j < words.length; j++) {
            word = words[j];

            if(word.length > row) {
              if(newline) {
                text += prefix + newline + '\n';
                newline = '';
              }
              text += prefix + word + '\n';
            }
            else if(newline.length + word.length > row) {
              newline.replace(/ +$/, '');
              text += prefix + newline + '\n';
              newline = word + ' ';
            }
            else newline += word + ' ';
          }
          newline.replace(/ +$/, '');
          text += prefix + newline + '\n';
        }
        else {
          line.replace(/ +$/, '');
          text += prefix + line + '\n';
        }
      }
    }
    return text.replace(/\n$/, '');
  }

  this.initEditor = function(content) {
    if(this.editor = this.getEditor()) {
      var html = '<html><head><style> ' +
                 'BODY { ' +
                 'margin: 4px; ' +
                 'background-color: ' + this.textBGColor + '; ' +
                 '} ' +
                 'BODY, TD, TH { ' +
                 'color: #000000; ' +
                 'font-family: ' + this.textFont + '; ' +
                 'font-size: ' + this.textFontSize + 'px; ' +
                 '} ' +
                 'TD { border: 1px dashed #C0C0C0; } ' +
                 'P { margin: 0px; } ' +
                 '</style></head>' +
                 '<body>' +
                 content.replace(/<STYLE>[^<]+<\/STYLE>(\r?\n)*/gi, '') +
                 '</body></html>';
      this.editor.document.designMode = 'on';
      if(GK) this.editor.document.execCommand('useCSS', false, false);
      this.editor.document.open();
      this.editor.document.write(this.wordWrap(html));
      this.editor.document.close();
      if(this.setFocus) this.editor.focus();

      for(var i = document.forms.length - 1; i > 0 && !this.field; i--) {
        if(document.forms[i].elements[this.fieldName + this.id]) {
          this.field = document.forms[i].elements[this.fieldName + this.id];
        }
      }
      rtoSetUnselectable(rtoGetObj('rtoEditor' + this.id));
    }
    else alert(txtNoRichEdit);
  }

  this.setButtonStyle = function(name, cls) {
    var obj = rtoGetObj(name + this.id);
    if(obj) obj.className = cls + this.id;
    obj = rtoGetObj(name + 'Arrow' + this.id);
    if(obj) obj.className = cls + this.id;
  }

  this.pickColor = function(color, mode) {
    var obj = rtoGetObj('dlg' + mode);
    if(obj) obj.style.visibility = 'hidden';
    obj = rtoGetObj('cur' + mode + this.id);
    if(obj) {
      obj.style.backgroundColor = color;
      if(mode == 'FontColor') this.curFontColor = color;
      else this.curBGColor = color;
    }
    this.setColor(mode);
  }

  this.setColor = function(mode) {
    if(mode == 'FontColor') this.fnExec('foreColor', this.curFontColor);
    else this.fnExec((GK ? 'hiliteColor' : 'backColor'), this.curBGColor);
  }

  this.changeColor = function(mode) {
    document.forms['f' + mode].id.value = this.id;
    this.viewDialog(mode);
  }

  this.viewDialog = function(mode) {
    var obj = rtoGetObj('dlg' + mode);
    if(obj) {
      if(IE) {
        this.editor.focus();
        this.curSelection = this.editor.document.selection.createRange();
      }
      if(obj.style.visibility == 'visible') obj.style.visibility = 'hidden';
      else {
        var obj2 = rtoGetObj('dlgBGColor');
        obj2.style.visibility = 'hidden';
        obj2 = rtoGetObj('dlgFontColor');
        obj2.style.visibility = 'hidden';
        obj2 = rtoGetObj('dlgImage');
        obj2.style.visibility = 'hidden';
        obj2 = rtoGetObj('dlgTable');
        obj2.style.visibility = 'hidden';
        obj2 = rtoGetObj('dlgGraph');
        obj2.style.visibility = 'hidden';

        var wdth = hght = 0;
        var top = mouseY;
        var left = mouseX;

        if(document.getElementById) {
          wdth = obj.offsetWidth;
          hght = obj.offsetHeight;
        }
        else if(IE) {
          wdth = obj.style.pixelWidth;
          hght = obj.style.pixelHeight;
        }

        rtoGetWinXY();
        if(left + wdth - scrLeft > winX) left = winX + scrLeft - wdth;
        if(top + hght - scrTop > winY) top = winY + scrTop - hght - 20;

        obj.style.left = (left - 50) + 'px';
        obj.style.top = top + 'px';
        obj.style.visibility = 'visible';

        if(mode.indexOf('Color') == -1) {
          document.forms['f' + mode].elements[1].focus();
          if(mode == 'Image') document.fImage.URL.value = 'http://';
        }
      }
    }
  }

  this.fnExec = function(command, option) {
    if(this.editor) {
      if(option == 'removeFormat') {
        command = option;
        option = null;
      }
      try {
        this.editor.document.execCommand(command, false, option);
      }
      catch(e) {
        alert(command + ": not supported");
      }
      this.editor.focus();
    }
  }

  this.doCmd = function(cmd, opt) {
    if(IE && !this.curSelection) {
      this.editor.focus();
      this.curSelection = this.editor.document.selection.createRange();
    }
    if(cmd && opt) {
      if(cmd == 'insertHTML' && IE) this.curSelection.pasteHTML(opt);
      else this.fnExec(cmd, opt);
    }
    else if(cmd) this.fnExec(cmd);
    if(IE) this.curSelection = 0;
  }

  this.insertLink = function() {
    if(IE) this.doCmd('createLink');
    else {
      var url = prompt('URL:', 'http://');
      if(url && url != 'http://') this.doCmd('createLink', url);
    }
  }

  this.insertImage = function() {
    document.fImage.id.value = this.id;
    this.viewDialog('Image');
  }

  this.insertBullet = function() {
    var html = '<table cellspacing=0 cellpadding=0><tr><td valign=top><img src="' + this.bulletpoint + '"></td><td>' + txtInsertText + '</td></tr></table>';
    this.doCmd('insertHTML', html);
  }

  this.insertTable = function() {
    document.fTable.id.value = this.id;
    this.viewDialog('Table');
  }

  this.insertGraph = function() {
    document.fGraph.id.value = this.id;
    this.viewDialog('Graph');
  }

  this.createImage = function() {
    var obj = rtoGetObj('dlgImage');
    if(obj) obj.style.visibility = 'hidden';
    var f = document.fImage;
    if(f.URL.value && f.URL.value != 'http://') {
      if(this.curSelection) this.doCmd('insertHTML', '<IMG src="' + f.URL.value + '">');
      else this.doCmd('insertImage', f.URL.value);
    }
  }

  this.createTable = function() {
    var obj = rtoGetObj('dlgTable');
    if(obj) obj.style.visibility = 'hidden';
    var f = document.fTable;
    if(f.Cols.value && f.Rows.value) {
      var border = f.Border.options[f.Border.selectedIndex].value;
      var html = '<table border=' + border;
      if(f.Spacing.value) html += ' cellspacing=' + f.Spacing.value;
      if(f.Padding.value) html += ' cellpadding=' + f.Padding.value;
      if(f.BorderColor.value && border > 0) html += ' bordercolor=' + f.BorderColor.value;
      html += '>';

      for(var i = j = 0; i < f.Rows.value; i++) {
        html += '<tr' + (f.CellColor.value ? ' bgcolor=' + f.CellColor.value : '') + '>';
        for(j = 0; j < f.Cols.value; j++) html += '<td>' + txtInsertText + '</td>';
        html += '</tr>';
      }
      html += '</table>';
      this.doCmd('insertHTML', html);
    }
  }

  this.createGraph = function() {
    var obj = rtoGetObj('dlgGraph');
    if(obj) obj.style.visibility = 'hidden';
    var f = document.fGraph;
    if(f.Values.value) {
      var html = this.barGraph(f.Values.value, f.Labels.value, f.BarColor.value, f.LabelColor.value,
                               f.ViewValues.options[f.ViewValues.selectedIndex].value, f.Legend.value);
      this.doCmd('insertHTML', html);
    }
  }

  this.toggleSource = function() {
    var s = rtoGetObj(this.fieldName + this.id);
    var r = rtoGetObj('rtoIFrame' + this.id);
    var t = rtoGetObj('rtoToolBar' + this.id);
    var b = rtoGetObj('rtoButton' + this.id);
    this.store(true);
    if(this.source) {
      this.source = false;
      s.style.visibility = 'hidden';
      t.style.visibility = 'visible';
      r.style.width = this.textWidth + 'px';
      r.style.visibility = 'visible';
      this.editor.focus();
      b.value = txtViewSource;
    }
    else {
      this.source = true;
      t.style.visibility = 'hidden';
      r.style.visibility = 'hidden';
      r.style.width = '0px';
      s.style.visibility = 'visible';
      s.focus();
      b.value = txtViewEditor;
    }
  }

  this.store = function(view) {
    if(this.field) {
      if(this.source) this.editor.document.body.innerHTML = this.field.value;
      else {
        var content = this.editor.document.body.innerHTML;
        if(content && IE && DM && !view) content = '<style> P { margin: 0px; } </style>\n' + content;
        this.field.value = content;
      }
    }
  }

  this.buildEditor = function() {
    document.writeln('<style> ' +
                     '#rtoEditor' + this.id + ' { ' +
                     'position: relative; ' +
                     'background-color: ' + this.editorBGColor + '; ' +
                     'width: ' + (this.textWidth + (IE ? 20 : 12)) + 'px; ' +
                     'margin: 0px; ' +
                     'padding: 4px; ' +
                     'border: ' + this.editorBorder + '; ' +
                     'text-align: left; ' +
                     '} ' +
                     '.cssIFrame' + this.id + ' { ' +
                     'margin: 2px; ' +
                     'padding: 0px; ' +
                     'width: ' + this.textWidth + 'px; ' +
                     'height: ' + this.textHeight + 'px; ' +
                     'border: ' + this.textBorder + '; ' +
                     '} ' +
                     '.cssToolBar' + this.id + ' { ' +
                     'background-color: ' + this.editorBGColor + '; ' +
                     'border: 1px solid ' + this.editorBGColor + '; ' +
                     'padding: 2px; ' +
                     '} ' +
                     '.cssRaised' + this.id + ' { ' +
                     'border-top: 1px solid buttonhighlight; ' +
                     'border-left: 1px solid buttonhighlight; ' +
                     'border-bottom: 1px solid buttonshadow; ' +
                     'border-right: 1px solid buttonshadow; ' +
                     'background-color: ' + this.editorBGColor + '; ' +
                     'padding: 2px; ' +
                     '} ' +
                     '.cssPressed' + this.id + ' { ' +
                     'border-top: 1px solid buttonshadow; ' +
                     'border-left: 1px solid buttonshadow; ' +
                     'border-bottom: 1px solid buttonhighlight; ' +
                     'border-right: 1px solid buttonhighlight; ' +
                     'background-color: ' + this.editorBGColor + '; ' +
                     'padding-left: 3px; ' +
                     'padding-top: 3px; ' +
                     'padding-bottom: 1px; ' +
                     'padding-right: 1px; ' +
                     '} ' +
                     '.cssSource' + this.id + ' { ' +
                     'position: absolute; ' +
                     'top: 0px; ' +
                     'left: 0px; ' +
                     'margin: 6px; ' +
                     'width: ' + (this.textWidth + 4) + 'px; ' +
                     'height: ' + (this.textHeight + 56) + 'px; ' +
                     'font-family: Courier New, Courier, Monospace; ' +
                     'font-size: 12px; ' +
                     'background-color: ' + this.textBGColor + '; ' +
                     'border: ' + this.textBorder + '; ' +
                     'visibility: hidden; ' +
                     '} ' +
                     '#rtoButton' + this.id + ' { ' +
                     'font-family: Verdana, Arial, Helvetica; ' +
                     'font-size: 11px; ' +
                     'font-weight: bold; ' +
                     'background-color: ' + this.editorBGColor + '; ' +
                     '} ' +
                     '#curBGColor' + this.id + ' { ' +
                     'width: 16px; ' +
                     'height: 4px; ' +
                     'font-size: 1px; ' +
                     'background-color: #FFFF00; ' +
                     '} ' +
                     '#curFontColor' + this.id + ' { ' +
                     'width: 16px; ' +
                     'height: 4px; ' +
                     'font-size: 1px; ' +
                     'background-color: #000000; ' +
                     '} ' +
                     '</style>');

    document.writeln('<div id="rtoEditor' + this.id + '">');

    document.writeln('<div id="rtoToolBar' + this.id + '">');

    document.writeln('<table border=0 cellspacing=1 cellpadding=0><tr align=center>' +
                     '<td><select class="cssFormField" onChange="rto[' + this.id + '].doCmd(\'formatBlock\', this[this.selectedIndex].value); this.selectedIndex=0">' +
                     '<option style="color:#C0C0C0">' + txtParagraph + ':' +
                     '<option value="<P>">' + txtNormal + ' &lt;P&gt;' +
                     '<option value="<H1>">' + txtHeading + ' 1 &lt;H1&gt;' +
                     '<option value="<H2>">' + txtHeading + ' 2 &lt;H2&gt;' +
                     '<option value="<H3>">' + txtHeading + ' 3 &lt;H3&gt;' +
                     '<option value="<H4>">' + txtHeading + ' 4 &lt;H4&gt;' +
                     '<option value="<H5>">' + txtHeading + ' 5 &lt;H5&gt;' +
                     '<option value="<H6>">' + txtHeading + ' 6 &lt;H6&gt;' +
                     '<option value="removeFormat">' + txtClearFormatting +
                     '</select></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'justifyLeft\')" title="' + txtJustifyLeft + '"><img src="' + this.iconPath + '/justify_left.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'justifyCenter\')" title="' + txtJustifyCenter + '"><img src="' + this.iconPath + '/justify_center.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'justifyRight\')" title="' + txtJustifyRight + '"><img src="' + this.iconPath + '/justify_right.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'justifyFull\')" title="' + txtJustifyFull + '"><img src="' + this.iconPath + '/justify_full.gif" width=16 height=16></td>');
    document.writeln('<td><div style="width:0px; height:15px; border:1px inset #FFFFFF"></div></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'insertOrderedList\')" title="' + txtOrderedList + '"><img src="' + this.iconPath + '/ol.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'insertUnorderedList\')" title="' + txtUnorderedList + '"><img src="' + this.iconPath + '/ul.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'outdent\')" title="' + txtOutdent + '"><img src="' + this.iconPath + '/outdent.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'indent\')" title="' + txtIndent + '"><img src="' + this.iconPath + '/indent.gif" width=16 height=16></td>');
    document.writeln('<td><div style="width:0px; height:15px; border:1px inset #FFFFFF"></div></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'insertHorizontalRule\')" title="' + txtInsertHR + '"><img src="' + this.iconPath + '/hrule.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].insertBullet()" title="' + txtInsertBullet + '"><img src="' + this.iconPath + '/bulletpoint.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].insertImage()" title="' + txtInsertImage + '"><img src="' + this.iconPath + '/image.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].insertTable()" title="' + txtInsertTable + '"><img src="' + this.iconPath + '/table.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].insertGraph()" title="' + txtInsertGraph + '"><img src="' + this.iconPath + '/graph.gif" width=16 height=16></td>');
    document.writeln('</tr></table>');

    document.writeln('<div style="border:1px inset #FFFFFF"></div>');

    document.writeln('<table border=0 cellspacing=2 cellpadding=0><tr align=center>' +
                     '<td><select class="cssFormField" onChange="rto[' + this.id + '].doCmd(\'fontName\', this[this.selectedIndex].value); this.selectedIndex=0">' +
                     '<option style="color:#C0C0C0">' + txtFont + ':' +
                     '<option value="Arial, Helvetica">Arial' +
                     '<option value="Verdana, Arial, Helvetica">Verdana' +
                     '<option value="Times New Roman, Times, Serif">Times' +
                     '<option value="Comic Sans MS">Comic' +
                     '<option value="MS Sans Serif, sans-serif">Sans-Serif' +
                     '<option value="Courier New, Courier, Monospace">Courier' +
                     '<option value="Trebuchet MS, Arial, Helvetica">Trebuchet' +
                     '</select></td>');
    document.writeln('<td><select class="cssFormField" onChange="rto[' + this.id + '].doCmd(\'fontSize\', this[this.selectedIndex].text); this.selectedIndex=0">' +
                     '<option style="color:#C0C0C0">' + txtSize + ':' +
                     '<option value="1">1' +
                     '<option value="2">2' +
                     '<option value="3">3' +
                     '<option value="4">4' +
                     '<option value="5">5' +
                     '<option value="6">6' +
                     '<option value="7">7' +
                     '</select></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'bold\')" title="' + txtBold + '"><img src="' + this.iconPath + '/bold.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'italic\')" title="' + txtItalic + '"><img src="' + this.iconPath + '/italic.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'underline\')" title="' + txtUnderline + '"><img src="' + this.iconPath + '/underline.gif" width=16 height=16></td>');
    document.writeln('<td width=25 height=20 title="' + txtBGColor + '"><table border=0 cellspacing=0 cellpadding=0><tr>');
    document.writeln('<td id="btnBGColor' + this.id + '" class="cssToolBar' + this.id + '" onMouseOver="rto[' + this.id + '].setButtonStyle(\'btnBGColor\', \'cssRaised\')" onMouseOut="rto[' + this.id + '].setButtonStyle(\'btnBGColor\', \'cssToolBar\')" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].setColor(\'BGColor\')"><img src="' + this.iconPath + '/bgcolor.gif" width=16 height=12><div id="curBGColor' + this.id + '"></div></td>');
    document.writeln('<td id="btnBGColorArrow' + this.id + '" class="cssToolBar' + this.id + '" onMouseOver="rto[' + this.id + '].setButtonStyle(\'btnBGColor\', \'cssRaised\')" onMouseOut="rto[' + this.id + '].setButtonStyle(\'btnBGColor\', \'cssToolBar\')" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].changeColor(\'BGColor\')"><img src="' + this.iconPath + '/arrow.gif" width=5 height=16></td>');
    document.writeln('</tr></table></td>');
    document.writeln('<td width=25 height=20 title="' + txtFontColor + '"><table border=0 cellspacing=0 cellpadding=0><tr>');
    document.writeln('<td id="btnFontColor' + this.id + '" class="cssToolBar' + this.id + '" onMouseOver="rto[' + this.id + '].setButtonStyle(\'btnFontColor\', \'cssRaised\')" onMouseOut="rto[' + this.id + '].setButtonStyle(\'btnFontColor\', \'cssToolBar\')" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].setColor(\'FontColor\')"><img src="' + this.iconPath + '/color.gif" width=16 height=12><div id="curFontColor' + this.id + '"></div></td>');
    document.writeln('<td id="btnFontColorArrow' + this.id + '" class="cssToolBar' + this.id + '" onMouseOver="rto[' + this.id + '].setButtonStyle(\'btnFontColor\', \'cssRaised\')" onMouseOut="rto[' + this.id + '].setButtonStyle(\'btnFontColor\', \'cssToolBar\')" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].changeColor(\'FontColor\')"><img src="' + this.iconPath + '/arrow.gif" width=5 height=16></td>');
    document.writeln('</tr></table></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].insertLink()" title="' + txtHyperlink + '"><img src="' + this.iconPath + '/link.gif" width=16 height=16></td>');
    document.writeln('<td><div style="width:0px; height:15px; border:1px inset #FFFFFF"></div></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'cut\')" title="' + txtCut + '"><img src="' + this.iconPath + '/cut.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'copy\')" title="' + txtCopy + '"><img src="' + this.iconPath + '/copy.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'paste\')" title="' + txtPaste + '"><img src="' + this.iconPath + '/paste.gif" width=16 height=16></td>');
    document.writeln('<td><div style="width:0px; height:15px; border:1px inset #FFFFFF"></div></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'undo\')" title="' + txtUndo + '"><img src="' + this.iconPath + '/undo.gif" width=16 height=16></td>');
    document.writeln('<td class="cssToolBar' + this.id + '" width=20 height=20 onMouseOver="this.className=\'cssRaised' + this.id + '\'" onMouseOut="this.className=\'cssToolBar' + this.id + '\'" onMouseDown="this.className=\'cssPressed' + this.id + '\'" onMouseUp="this.className=\'cssRaised' + this.id + '\'" onClick="rto[' + this.id + '].doCmd(\'redo\')" title="' + txtRedo + '"><img src="' + this.iconPath + '/redo.gif" width=16 height=16></td>');
    document.writeln('</tr></table>');

    document.writeln('</div>');

    document.writeln('<iframe id="rtoIFrame' + this.id + '" frameborder=0 class="cssIFrame' + this.id + '"></iframe>');

    document.writeln('<textarea name="' + this.fieldName + this.id + '" id="' + this.fieldName + this.id + '" class="cssSource' + this.id + '" wrap=virtual></textarea>');

    document.writeln('<center><input type=button id="rtoButton' + this.id + '" value="' + txtViewSource + '" onClick="rto[' + this.id + '].toggleSource()"></center>');

    document.writeln('</div>');
  }

  this.create = function(content) {
    if(content == null) content = '';
    this.id = rto.length;
    if(rto[this.id] = this) {
      if((IE || GK) && DM) {
        this.buildEditor();
        this.initEditor(content);
      }
      else {
        var cols = Math.round(this.textWidth / 10);
        var rows = Math.round(this.textHeight / 20);
        document.write('<textarea name="' + this.fieldName + this.id + '" style="' +
                       'margin-bottom: 4px; ' +
                       'padding: 4px; ' +
                       'background-color: ' + this.textBGColor + '; ' +
                       'border: ' + this.textBorder +
                       '" cols=' + cols + ' rows=' + rows + ' wrap=virtual>' + content +
                       '</textarea>');
      }
    }
    else alert(txtCreateError);
  }

  this.barGraph = function(values, labels, bColor, lColor, showVal, legend, bSize, bBorder, bLen) {
    showVal = parseInt(showVal);
    var colors = new Array('#0000FF', '#FF0000', '#00E000', '#A0A0FF', '#FFA0A0', '#00A000');
    var d = (typeof(values) == 'string') ? this.makeArray(values) : values;
    if(labels) var r = (typeof(labels) == 'string') ? this.makeArray(labels) : labels;
    else var r = new Array();
    var label = graph = '';
    var percent = 0;
    if(bColor) var drf = (typeof(bColor) == 'string') ? this.makeArray(bColor) : bColor;
    else var drf = new Array();
    var drw, val = new Array();
    var bc = new Array();
    if(lColor) {
      if(lColor.indexOf(',') != -1) var lc = lColor.split(',');
      else {
        lColor = lColor.replace(/\s+/g, ' ');
        var lc = lColor.split(' ');
      }
    }
    else var lc = new Array();
    if(lc[0]) lc[0] = lc[0].replace(/\s+/, '');
    else lc[0] = '#C0E0FF';
    if(lc[1]) lc[1] = lc[1].replace(/\s+/, '');
    var bars = (d.length > r.length) ? d.length : r.length;

    if(legend) graph += '<table border=0 cellspacing=0 cellpadding=0><tr valign=top><td>';

    var sum = max = max_neg = max_dec = ccnt = lcnt = 0;

    for(var i = 0; i < bars; i++) {
      if(typeof(d[i]) == 'string') drw = d[i].split(';');
      else {
        drw = new Array();
        drw[0] = d[i];
      }
      val[lcnt] = new Array();

      for(var j = v = 0; j < drw.length; j++) {
        val[lcnt][j] = v = drw[j] ? parseFloat(drw[j]) : 0;

        if(v > max) max = v;
        else if(v < max_neg) max_neg = v;

        if(v < 0) v *= -1;
        sum += v;

        v = v.toString();
        if(v.indexOf('.') != -1) {
          v = v.substr(v.indexOf('.') + 1);
          dec = v.length;
          if(dec > max_dec) max_dec = dec;
        }

        if(!bc[j]) {
          if(ccnt >= colors.length) ccnt = 0;
          bc[j] = (!drf[j] || drf[j].length < 3) ? colors[ccnt++] : drf[j];
        }
      }
      lcnt++;
    }

    if(!showVal) showVal = 0;
    if(!bBorder) bBorder = '2px outset #FFFFFF';
    if(!bSize) bSize = 15;
    if(!bLen) bLen = 1.0;
    else if(bLen < 0.1) bLen = 0.1;
    else if(bLen > 2.9) bLen = 2.9;

    var border = parseInt(bBorder);
    var mPerc = sum ? Math.round(max * 100 / sum) : 0;
    var mul = mPerc ? 100 / mPerc : 1;
    mul *= bLen;
    if(showVal < 2) var valSpace = 25;
    else var valSpace = 0;
    var spacer = maxSize = Math.round(mPerc * mul + valSpace + border * 2);

    if(max_neg) {
      var mPerc_neg = sum ? Math.round(-max_neg * 100 / sum) : 0;
      var spacer_neg = Math.round(mPerc_neg * mul + valSpace + border * 2);
      maxSize += spacer_neg;
    }

    graph += '<table border=0 cellspacing=2 cellpadding=0>';

    for(i = 0; i < val.length; i++) {
      label = (i < r.length) ? r[i] : i+1;
      graph += '<tr><td rowspan=' + val[i].length + ' bgcolor=' + lc[0] + ' align=center style="font:10px Verdana,Arial,Helvetica">';
      graph += '&nbsp;' + label + '&nbsp;</td>';

      for(j = 0; j < val[i].length; j++) {
        percent = sum ? val[i][j] * 100 / sum : 0;

        if(showVal == 1 || showVal == 2) {
          graph += this.showValue(val[i][j], max_dec, lc[0], 0, 'right');
        }

        if(percent < 0) {
          percent *= -1;
          graph += '<td bgcolor=' + lc[0] + ' align=right nowrap>';
          if(showVal < 2) graph += '<div style="float:left; font:10px Verdana,Arial,Helvetica">' + Math.round(percent) + '%&nbsp;</div>';
          graph += '<div style="float:left; border:' + bBorder + '; background-color:' + bc[j] + '">';
          graph += '<img width=0 height=' + bSize + '><img height=0 width=' + Math.round(percent * mul) + '></div>';
          graph += '</td><td' + (lc[1] ? ' bgcolor=' + lc[1] : '') + '>&nbsp;</td>';
        }
        else {
          if(max_neg) graph += '<td style="background-color:' + lc[0] + '">&nbsp;</td>';
          graph += '<td' + (lc[1] ? ' bgcolor=' + lc[1] : '') + ' nowrap style="font:10px Verdana,Arial,Helvetica">';
          if(percent) {
            graph += '<div style="float:left; border:' + bBorder + '; background-color:' + bc[j] + '">';
            graph += '<img height=0 width=' + Math.round(percent * mul) + '><img width=0 height=' + bSize + '></div>';
          }
          else graph += '<div style="float:left; border-width:' + border + 'px"><img width=0 height=' + bSize + '></div>';
          if(showVal < 2) graph += '<div style="float:left; font:10px Verdana,Arial,Helvetica">&nbsp;' + Math.round(percent) + '%</div>';
          graph += '</td>';
        }
        graph += '</tr>';
      }
    }
    graph += '</table>';

    if(legend) {
      graph += '</td><td width=10>&nbsp;</td><td><div style="padding:4px; background-color:#F0F0F0; border:1px solid #808080">';
      var l = (typeof(legend) == 'string') ? this.makeArray(legend) : legend;

      for(i = 0; i < bc.length; i++) {
        graph += '<span style="font:8px Arial,Helvetica; background-color:' + bc[i] + '; border:' + bBorder + '; height:15px"><img width=10 height=0></span>';
        graph += '&nbsp;<span style="height:15px; font:10px Verdana,Arial,Helvetica">' + (l[i] ? l[i] : '') + '</span><br>';
      }
      graph += '</div></td></tr></table>';
    }
    return graph;
  }

  this.makeArray = function(str) {
    var arr = new Array();
    if(str.indexOf(',') != -1) {
      arr = str.split(',');
      for(var i = 0; i < arr.length; i++) {
        arr[i] = arr[i].replace(/^\s+/, '');
        arr[i] = arr[i].replace(/\s+$/, '');
      }
    }
    else {
      str = str.replace(/\s+/g, ' ');
      arr = str.split(' ');
    }
    return arr;
  }

  this.formatValue = function(val, dec) {
    if(val < 0) {
      var neg = true;
      val *= -1;
    }
    else var neg = false;
    var v = (Math.round(val * Math.pow(10, dec))).toString();
    if(v.length <= dec) for(var i = 0; i < dec - v.length + 1; i++) v = '0' + v;
    v = v.substr(0, v.length - dec) + '.' + v.substr(v.length - dec);
    if(v.substr(0, 1) == '.') v = '0' + v;
    if(neg) v = '-' + v;
    return v;
  }

  this.showValue = function(val, max_dec, color, sum, align) {
    val = max_dec ? this.formatValue(val, max_dec) : val;
    if(sum) sum = max_dec ? this.formatValue(sum, max_dec) : sum;
    value = '<td bgcolor=' + color;
    if(align) value += ' align=' + align;
    value += ' nowrap style="font:10px Verdana,Arial,Helvetica">';
    value += '&nbsp;' + val + (sum ? ' / ' + sum : '') + '&nbsp;</td>';
    return value;
  }
}

//---------------------------------------------------------------------------------------------------------
// Global functions
//---------------------------------------------------------------------------------------------------------
function buildColorChart(mode) {
  var c = new Array();
  // red
  c[0] = new Array('FFEEEE', 'FFCCCC', 'FFAAAA', 'FF8888', 'FF6666', 'FF4444', 'FF2222', 'FF0000',
                   'EE0000', 'CC0000', 'AA0000', '880000', '770000', '660000', '550000', '440000', '330000');
  // green
  c[1] = new Array('EEFFEE', 'CCFFCC', 'AAFFAA', '88FF88', '66FF66', '44FF44', '22FF22', '00FF00',
                   '00EE00', '00CC00', '00AA00', '008800', '007700', '006600', '005500', '004400', '003300');
  // blue
  c[2] = new Array('EEEEFF', 'CCCCFF', 'AAAAFF', '8888FF', '6666FF', '4444FF', '2222FF', '0000FF',
                   '0000EE', '0000CC', '0000AA', '000088', '000077', '000066', '000055', '000044', '000033');
  // yellow
  c[3] = new Array('FFFFEE', 'FFFFCC', 'FFFFAA', 'FFFF88', 'FFFF66', 'FFFF44', 'FFFF22', 'FFFF00',
                   'EEEE00', 'CCCC00', 'AAAA00', '888800', '777700', '666600', '555500', '444400', '333300');
  // pink
  c[4] = new Array('FFEEFF', 'FFCCFF', 'FFAAFF', 'FF88FF', 'FF66FF', 'FF44FF', 'FF22FF', 'FF00FF',
                   'EE00EE', 'CC00CC', 'AA00AA', '880088', '770077', '660066', '550055', '440044', '330033');
  // brown
  c[5] = new Array('FFF0D0', 'FFEECC', 'FFEEBB', 'FFDDAA', 'FFCC99', 'FFC090', 'EEBB88', 'DDAA77',
                   'CC9966', 'BB8855', 'AA7744', '886633', '775522', '664411', '553300', '442200', '331100');
  // cyan
  c[6] = new Array('EEFFFF', 'CCFFFF', 'AAFFFF', '88FFFF', '66FFFF', '44FFFF', '22FFFF', '00FFFF',
                   '00EEEE', '00CCCC', '00AAAA', '008888', '007777', '006666', '005555', '004444', '003333');
  // grey
  c[7] = new Array('FFFFFF', 'EEEEEE', 'DDDDDD', 'CCCCCC', 'BBBBBB', 'AAAAAA', 'A0A0A0', '999999',
                   '888888', '777777', '666666', '555555', '444444', '333333', '222222', '111111', '000000');

  var html = '<table border=0 cellspacing=1 cellpadding=0 bgcolor=#808080>';
  var style, i, j;

  for(i = 0; i < c.length; i++) {
    html += '<tr>';

    for(j = 0; j < c[i].length; j++) {
      style = 'width:14px; height:14px; font-size:1px; cursor:hand; background-color:#' + c[i][j];
      html += '<td width=14 height=14 bgcolor=#' + c[i][j] + '>' +
              '<a href="javascript:rto[document.f' + mode + '.id.value].pickColor(\'#' + c[i][j] + '\', \'' + mode + '\')" ' +
              'title="#' + c[i][j] + '"><div style="' + style + '"></div></a></td>';
    }
    html += '</tr>';
  }
  html += '</table>';
  return html;
}

function rtoGetObj(id) {
  var obj = false;
  if(document.getElementById) obj = document.getElementById(id);
  else if(document.all) obj = document.all[id];
  return obj;
}

function rtoSetUnselectable(elm) {
  if(document.getElementsByTagName) {
    if(elm && typeof(elm.tagName) != 'undefined') {
      if(elm.tagName != 'INPUT' && elm.tagName != 'TEXTAREA' && elm.tagName != 'IFRAME') {
        if(elm.hasChildNodes()) {
          for(var i = 0; i < elm.childNodes.length; i++) {
            rtoSetUnselectable(elm.childNodes[i]);
          }
        }
        elm.unselectable = true;
      }
    }
  }
}

function rtoStore() {
  for(var i = 0; i < rto.length; i++) rto[i].store(false);
}

function rtoGetScrollLeft() {
  var scrLeft = 0;
  if(window.pageXOffset) scrLeft = window.pageXOffset;
  else if(document.documentElement && document.documentElement.scrollLeft)
    scrLeft = document.documentElement.scrollLeft;
  else if(document.body && document.body.scrollLeft)
    scrLeft = document.body.scrollLeft;
  return scrLeft;
}

function rtoGetScrollTop() {
  var scrTop = 0;
  if(window.pageYOffset) scrTop = window.pageYOffset;
  else if(document.documentElement && document.documentElement.scrollTop)
    scrTop = document.documentElement.scrollTop;
  else if(document.body && document.body.scrollTop)
    scrTop = document.body.scrollTop;
  return scrTop;
}

function rtoGetWinXY() {
  if(window.innerWidth) {
    winX = window.innerWidth;
    winY = window.innerHeight;
  }
  else if(document.documentElement && document.documentElement.clientWidth) {
    winX = document.documentElement.clientWidth;
    winY = document.documentElement.clientHeight;
  }
  else if(document.body && document.body.clientWidth) {
    winX = document.body.clientWidth;
    winY = document.body.clientHeight;
  }
  else {
    winX = screen.width;
    winY = screen.height;
  }
  scrLeft = rtoGetScrollLeft();
  scrTop = rtoGetScrollTop();
}

function rtoGetMouse(e) {
  if(e && e.pageX != null) {
    mouseX = e.pageX;
    mouseY = e.pageY;
  }
  else if(event && event.clientX != null) {
    mouseX = event.clientX + rtoGetScrollLeft();
    mouseY = event.clientY + rtoGetScrollTop();
  }
}

document.onmousedown = rtoGetMouse;

//---------------------------------------------------------------------------------------------------------
// Global styles / dialog boxes
//---------------------------------------------------------------------------------------------------------
if((IE || GK) && DM) {
  document.writeln('<style> ' +
                   '.cssForm { ' +
                   'margin: 0px; ' +
                   'padding: 4px; ' +
                   'border: 2px groove #FFFFFF; ' +
                   '} ' +
                   '.cssDialog { ' +
                   'position: absolute; ' +
                   'padding: 4px; ' +
                   'z-index: 69; ' +
                   'background-color: #E0E0E0; ' +
                   'border: 2px groove #FFFFFF; ' +
                   'text-align: center; ' +
                   'visibility: hidden; ' +
                   '} ' +
                   '.cssFont1 { ' +
                   'font-family: Verdana, Arial, Helvetica; ' +
                   'font-size: 12px; ' +
                   'font-weight: bold; ' +
                   'margin-top: 0px; ' +
                   'margin-bottom: 4px; ' +
                   'padding: 2px; ' +
                   'background-color: #F0F0F0; ' +
                   'border: 2px groove #FFFFFF; ' +
                   'white-space: nowrap; ' +
                   '} ' +
                   '.cssFont2 { ' +
                   'font-family: Verdana, Arial, Helvetica; ' +
                   'font-size: 11px; ' +
                   '} ' +
                   '.cssFormField { ' +
                   'font: menu; ' +
                   'font-size: 12px; ' +
                   'border: 2px groove #FFFFFF; ' +
                   '} ' +
                   '.cssButton { ' +
                   'font-family: Verdana, Arial, Helvetica; ' +
                   'font-size: 11px; ' +
                   'font-weight: bold; ' +
                   'width: 100px; ' +
                   'margin-top: 4px; ' +
                   'background-color: #D0D0D0; ' +
                   '} ' +
                   '</style>');

  document.writeln('<div id="dlgBGColor" class="cssDialog"><center>' +
                   '<p class="cssFont1">' + txtBGColor + '</p>' +
                   '<form name="fBGColor" class="cssForm">' +
                   '<input type=hidden name="id" value="">' +
                   buildColorChart('BGColor') +
                   '<input type=button value="' + txtCancel + '" class="cssButton" onClick="rto[document.fBGColor.id.value].viewDialog(\'BGColor\')">' +
                   '</form>' +
                   '</center></div>');

  document.writeln('<div id="dlgFontColor" class="cssDialog"><center>' +
                   '<p class="cssFont1">' + txtFontColor + '</p>' +
                   '<form name="fFontColor" class="cssForm">' +
                   '<input type=hidden name="id" value="">' +
                   buildColorChart('FontColor') +
                   '<input type=button value="' + txtCancel + '" class="cssButton" onClick="rto[document.fFontColor.id.value].viewDialog(\'FontColor\')">' +
                   '</form>' +
                   '</center></div>');

  document.writeln('<div id="dlgImage" class="cssDialog"><center>' +
                   '<p class="cssFont1">' + txtInsertImage + '</p>' +
                   '<form name="fImage" class="cssForm" action="javascript:rto[document.fImage.id.value].createImage()">' +
                   '<input type=hidden name="id" value="">' +
                   '<table border=0 cellspacing=0>');
  document.writeln('<tr><td class="cssFont2" nowrap>URL:</td><td>&nbsp;<input type=text name="URL" size=30 maxlength=100 class="cssFormField"></td></tr>');
  document.writeln('</table>' +
                   '<table border=0 cellspacing=0 cellpadding=0 width=230><tr>' +
                   '<td><input type=button value="' + txtCancel + '" class="cssButton" onClick="rto[document.fImage.id.value].viewDialog(\'Image\')"></td>' +
                   '<td align=right><input type=submit value="OK" class="cssButton"></td>' +
                   '</tr></table>' +
                   '</form>' +
                   '</center></div>');

  document.writeln('<div id="dlgTable" class="cssDialog"><center>' +
                   '<p class="cssFont1">' + txtInsertTable + '</p>' +
                   '<form name="fTable" class="cssForm" action="javascript:rto[document.fTable.id.value].createTable(true)">' +
                   '<input type=hidden name="id" value="">' +
                   '<table border=0 cellspacing=0>');
  document.writeln('<tr align=left><td class="cssFont2" nowrap>' + txtColumns + ':</td><td>&nbsp;<input type=text name="Cols" size=2 maxlength=2 class="cssFormField"></td><td>&nbsp;</td><td class="cssFont2" nowrap>' + txtCellSpacing + ':</td><td>&nbsp;<input type=text name="Spacing" size=2 maxlength=2 class="cssFormField" value="2"></td></tr>');
  document.writeln('<tr align=left><td class="cssFont2" nowrap>' + txtRows + ':</td><td>&nbsp;<input type=text name="Rows" size=2 maxlength=2 class="cssFormField"></td><td>&nbsp;</td><td class="cssFont2" nowrap>' + txtCellPadding + ':</td><td>&nbsp;<input type=text name="Padding" size=2 maxlength=2 class="cssFormField" value="2"></td></tr>');
  document.writeln('<tr align=left><td class="cssFont2" nowrap>' + txtBorder + ':</td><td>&nbsp;<select name="Border" class="cssFormField"><option value="0">0<option value="1" selected>1<option value="2">2<option value="3">3<option value="4">4<option value="5">5</select></td><td>&nbsp;</td><td class="cssFont2" nowrap>' + txtBorderColor + ':</td><td>&nbsp;<input type=text name="BorderColor" size=10 maxlength=10 class="cssFormField" value="#000000"></td></tr>');
  document.writeln('<tr align=left><td colspan=3></td><td class="cssFont2" nowrap>' + txtCellColor + ':</td><td>&nbsp;<input type=text name="CellColor" size=10 maxlength=10 class="cssFormField"></td></tr>');
  document.writeln('</table>' +
                   '<table border=0 cellspacing=0 cellpadding=0 width=230><tr>' +
                   '<td><input type=button value="' + txtCancel + '" class="cssButton" onClick="rto[document.fTable.id.value].viewDialog(\'Table\')"></td>' +
                   '<td align=right><input type=submit value="' + txtCreate + '" class="cssButton"></td>' +
                   '</tr></table>' +
                   '</form>' +
                   '</center></div>');

  document.writeln('<div id="dlgGraph" class="cssDialog"><center>' +
                   '<p class="cssFont1">' + txtInsertGraph + '</p>' +
                   '<form name="fGraph" class="cssForm" action="javascript:rto[document.fGraph.id.value].createGraph(true)">' +
                   '<input type=hidden name="id" value="">' +
                   '<table border=0 cellspacing=0>');
  document.writeln('<tr align=left><td class="cssFont2" nowrap>' + txtValues + ':</td><td>&nbsp;<input name="Values" type=text size=10 class="cssFormField" title="123[;...], 123[;...], ..."></td><td>&nbsp;</td><td class="cssFont2" nowrap>' + txtBarColor + ':</td><td>&nbsp;<input type=text name="BarColor" size=10 class="cssFormField"></td></tr>');
  document.writeln('<tr align=left><td class="cssFont2" nowrap>' + txtLabels + ':</td><td>&nbsp;<input type=text name="Labels" size=10 class="cssFormField" title="abc, abc, ..."></td><td>&nbsp;</td><td class="cssFont2" nowrap>' + txtLabelColor + ':</td><td>&nbsp;<input type=text name="LabelColor" size=10 class="cssFormField"></td></tr>');
  document.writeln('<tr align=left><td class="cssFont2" nowrap>' + txtLegend + ':</td><td>&nbsp;<input type=text name="Legend" size=10 class="cssFormField" title="abc, abc, ..."></td><td>&nbsp;</td><td class="cssFont2" nowrap>' + txtViewValues + ':</td><td>&nbsp;<select name="ViewValues" class="cssFormField"><option value="">%<option value="1">abs. + %<option value="2">abs.<option value="3">-</select></td></tr>');
  document.writeln('</table>' +
                   '<table border=0 cellspacing=0 cellpadding=0 width=230><tr>' +
                   '<td><input type=button value="' + txtCancel + '" class="cssButton" onClick="rto[document.fGraph.id.value].viewDialog(\'Graph\')"></td>' +
                   '<td align=right><input type=submit value="' + txtCreate + '" class="cssButton"></td>' +
                   '</tr></table>' +
                   '</form>' +
                   '</center></div>');

  rtoSetUnselectable(rtoGetObj('dlgBGColor'));
  rtoSetUnselectable(rtoGetObj('dlgFontColor'));
  rtoSetUnselectable(rtoGetObj('dlgImage'));
  rtoSetUnselectable(rtoGetObj('dlgTable'));
  rtoSetUnselectable(rtoGetObj('dlgGraph'));
}

//---------------------------------------------------------------------------------------------------------
