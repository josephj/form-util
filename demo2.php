<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh-tw">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>YUI3 - Get Radio Value</title>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssreset/reset-min.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssfonts/fonts-min.css">
<style>
html, body {
    background: #000;
    color: #fff;
}
</style>

<script type="text/javascript" src="http://yui.yahooapis.com/3.2.0/build/yui/yui-min.js"></script> 
</head>
<body>
    <div id="foo">
    </div>
    <form>

        <textarea name="textarea">haha</textarea><br>
        <input type="text" name="input" value="1"><br>
        <input type="checkbox" name="checkbox" value="1">
        <input type="checkbox" name="checkbox" value="2">
        <input type="checkbox" name="checkbox" value="3" checked><br>
        <input type="radio" name="radio" value="1">
        <input type="radio" name="radio" value="2">
        <input type="radio" name="radio" value="3" checked><br>

        <select name="select-one">
            <option value="1" selected>one</option>
            <option value="2">two</option>
            <option value="3">three</option>
            <option value="4">four</option>
            <option value="5">five</option>
            <option value="6">six</option>
        </select><br>

        <select name="select-multiple" multiple="multiple">
            <option value="1" selected>one</option>
            <option value="2">two</option>
            <option value="3">three</option>
            <option value="4">four</option>
            <option value="5">five</option>
            <option value="6">six</option>
        </select><br>

        <input type="submit">

    </form>
    <script type="text/javascript" src="form-event.js"></script>
    <script type="text/javascript">
    YUI().use("node", "form-util", function (Y) {
        var form = Y.FormUtil.set("form");
        Y.log(form.item("textarea"));
        Y.log(form.item("input"));
        Y.log(form.item("checkbox"));
        Y.log(form.item("radio").val());
        Y.log(form.item("select-one"));
        Y.log(form.item("select-multiple").val());
        Y.log(form.item("select-one").val("4"));
        form.on("valueChange", function (e) {
            Y.log("valueChange(e) is executed.", "info");
            Y.log(e);
        });
    });
    </script>
</body>
</html>
