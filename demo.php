<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Form Value Change Event</title>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssreset/reset-min.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssfonts/fonts-min.css">
<link rel="stylesheet" type="text/css" href="demo.css">
</head>
</head>
<body>
    <h1>Form Value Change Event</h1>
    <p>A custom event which is triggered when form value changes. It's useful on form validation.</p>
    <xmp><script type="text/javascript" src="http://yui.yahooapis.com/3.3.0/build/yui/yui-min.js"></script> 
<script type="text/javascript" src="form-event.js"></script>
<script>
YUI().use("form-util", function (Y) {
    var form = Y.FormUtil.set("form");
    form.on("valueChange", function (e) {
        Y.all(".tip").removeClass("tip-active");
        Y.one(".tip-" + e.targetName).setContent("The value of field '<em>" + e.targetName + "</em>' 
                was changed from '<em>" + e.prevValue + "</em>' to '<em>" + e.newValue + "</em>'").addClass("tip-active");
    });
});
</script></xmp>
    <div id="message"></div>
    <form>
        <div class="row">
            <label>Textarea</label>
            <span class="cell">
                <textarea name="textarea">haha</textarea>
            </span>
            <span class="tip tip-textarea"></span>
        </div>
        <div class="row">
            <label>Text</label>
            <span class="cell">
                <input type="text" name="input" value="1" class="text">
            </span>
            <span class="tip tip-input"></span>
        </div>
        <div class"row">
            <label>Checkbox</label>
            <span class="cell">
                <input type="checkbox" name="checkbox" value="1">
                <input type="checkbox" name="checkbox" value="2">
                <input type="checkbox" name="checkbox" value="3" checked>
            </span>
            <span class="tip tip-checkbox"></span>
        </div>
        <div class="row">
            <label>Radio</label>
            <span class="cell">
                <input type="radio" name="radio" value="1">
                <input type="radio" name="radio" value="2">
                <input type="radio" name="radio" value="3" checked>
            </span>
            <span class="tip tip-radio"></span>
        </div>
        <div class="row"> 
            <label>Select One</label>
            <span class="cell">
                <select name="select-one">
                    <option value="1" selected>one</option>
                    <option value="2">two</option>
                    <option value="3">three</option>
                    <option value="4">four</option>
                    <option value="5">five</option>
                    <option value="6">six</option>
                </select>
            </span>
            <span class="tip tip-select-one"></span>
        </div>
        <div class="row">
            <label>Select Multiple</label>
            <span class="cell">
                <select name="select-multiple" multiple="multiple">
                    <option value="1" selected>one</option>
                    <option value="2">two</option>
                    <option value="3">three</option>
                    <option value="4">four</option>
                    <option value="5">five</option>
                    <option value="6">six</option>
                </select>
            </span>
            <span class="tip tip-select-multiple"></span>
        </div>
    </form>
    <script type="text/javascript" src="http://yui.yahooapis.com/3.2.0/build/yui/yui-min.js"></script> 
    <script type="text/javascript" src="form-event.js"></script>
    <script type="text/javascript">
    YUI().use("form-util", function (Y) {
        var form = Y.FormUtil.set("form");
        form.on("valueChange", function (e) {
            Y.all(".tip").removeClass("tip-active");
            Y.one(".tip-" + e.targetName).setContent("The value of field '<em>" + e.targetName + "</em>' was changed from '<em>" + e.prevValue + "</em>' to '<em>" + e.newValue + "</em>'").addClass("tip-active");
        });
    });
    </script>
</body>
</html>
