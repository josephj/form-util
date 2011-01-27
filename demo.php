<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh-tw">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>YUI3 - Get Radio Value</title>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssreset/reset-min.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssfonts/fonts-min.css">
<script type="text/javascript" src="http://yui.yahooapis.com/3.2.0/build/yui/yui-min.js"></script> 
<style type="text/css">
</style>
</head>
<body>
    <form>
        <!-- Textarea -->
        <textarea name="ite123123">haha</textarea>
        <br>

        <input type="text" name="item1" value="1">
        <br>

        <!-- Check box -->
        <input type="checkbox" name="item2" value="1">
        <input type="checkbox" name="item2" value="2">
        <input type="checkbox" name="item2" value="3" checked>
        <br>

        <!-- Check box -->
        <input type="radio" name="item3" value="1">
        <input type="radio" name="item3" value="2">
        <input type="radio" name="item3" value="3" checked>
        <br>

        <!-- Select box -->
        <select name="item4">
            <option value="1231" selected>111231hhhhhhh3</option>
            <option value="1231a">111231hhhhhhh3</option>
            <option value="1231b">111231hhhhhhh3</option>
            <option value="1231c">111231hhhhhhh3</option>
            <option value="1231d">111231hhhhhhh3</option>
            <option value="1231e">111231hhhhhhh3</option>
        </select>
        <br>

        <!-- Select box -->
        <select name="item7" multiple="multiple">
            <option value="1231" selected>111231hhhhhhh3</option>
            <option value="1231a">111231hhhhhhh3</option>
            <option value="1231b">111231hhhhhhh3</option>
            <option value="1231c" selected>111231hhhhhhh3</option>
            <option value="1231d">111231hhhhhhh3</option>
            <option value="1231e">111231hhhhhhh3</option>
        </select>
        <br>

        <!-- Submit -->
        <input type="submit">

    </form>
<script type="text/javascript">
YUI.add("form-event", function (Y) {

    var MODULE_ID = "FormEvent";

    var getItems = function (formNode, fieldName) {
        var fieldEl,
            fieldType,
            items;

        fieldEl   = formNode._node.elements[fieldName];
        items     = [];

        formNode.all("[name=" + fieldName + "]").each(function(node) {
            items.push({
                "active": node.get("checked"),
                "value" : node.get("value")
            });
        }); 

        switch (fieldType) {
            case "radio":
            case "checkbox":
                break;
            default:
                items = null;
                break;
        }
        return items;
    };

    var getValue = function (formNode, fieldName) {
        var fieldEl   = formNode._node.elements[fieldName];
        var fieldType = fieldEl.type;
        var value = "";
        switch (fieldType) {
            case "radio":
                value = formNode.one("[name=" + fieldName + "]:checked").get("value");
                break;
            case "checkbox":
                value = formNode.all("[name=" + fieldName + "]:checked").get("value").join(",");
                break;
            break;
                value = formNode.one("[name=" + fieldName + "]").get("value");
                break;
            case "select-multiple":
                value = formNode.one("[name=" + fieldName + "]").all("option[selected]").get("value").join(",");
                break;
            case "textarea":
                value = formNode.one("[name=" + fieldName + "]").get("value");
                break;
            default:
                value = formNode.one("[name=" + fieldName + "]").get("value");
                break;
        }
        return value;
    };

    var getState = function (formEl) {
        var attrs,
            fieldNames,
            formEl;
        attrs      = [];
        fieldNames = [];
        for (i in formEl.elements) {
            fieldEl   = formEl.elements[i];
            fieldName = fieldEl.name;
            fieldType = fieldEl.type;
            if (Y.Array.indexOf(fieldNames, fieldName) !== -1 || !fieldName || !fieldType) {
                continue;
            }
            fieldNames.push(fieldName);
            attrs.push(fieldName + "=" + getValue(Y.one(formEl), fieldName)); 
        }
        return attrs.join("&");
    };

    var getStateData = function (formEl) {
        var attrs,
            fieldNames;

        attrs      = {};
        fieldNames = [];

        for (i in formEl.elements) {
            fieldEl   = formEl.elements[i];
            fieldName = fieldEl.name;
            fieldType = fieldEl.type;
            if (Y.Array.indexOf(fieldNames, fieldName) !== -1 || !fieldName || !fieldType) {
                continue;
            }
            fieldNames.push(fieldName);
            items = (fieldType === "radio" || fieldType === "checkbox") ? getItems(Y.one(formEl), fieldName) : null;
            attrs[fieldName] = {
                "name" : fieldName,
                "type" : fieldType,
                "value": getValue(Y.one(formEl), fieldName),
                "items": items
            };
        }

        return attrs;
    };

    var setForm = function (node) {
        var formNode,
            initState,
            initStateData;

        previousState = getState(document.forms[0]);
        previousStateData = getStateData(document.forms[0]);

        Y.one(document.forms[0]).on("click", function (e) {
            var state = getState(this._node);
            Y.log("Diff:\n" + previousState + "\n" + state);
            if (state !== previousState) {
/*
                previousState = state;
                previousStateData = getStateData(document.forms[0]);
*/
            } 
        });
        
    };

    Y.FormEvent = {
        setForm: setForm
    };

}, "3.2.0", {"requires": ["selector-css3"]});
YUI().use("node", "form-event", function (Y) {

    Y.FormEvent.setForm(Y.one("form"));

    var getRadioValue;

    getRadioValue = function (name) {
        var formEl = document.forms[0],
            formNode = Y.one(formEl.elements[name]);
    }
    Y.all("[name=item]").on("focus", function (e) {
        Y.log(e.target.get("id") + "," + "focus");
    });
    Y.all("[name=item]").on("click", function (e) {
        Y.log(e.target.get("id") + "," + "click");
    });
    Y.all("[name=item]").on("change", function (e) {
        Y.log(e.target.get("id") + "," + "change");
    });
    Y.all("[name=item]").on("blur", function (e) {
        Y.log(e.target.get("id") + "," + "blur");
    });

    Y.one("form").on("submit", function (e) {
        e.preventDefault();
        getRadioValue("item");
        //alert(Y.one("[name=item]:checked").get("value"));
    });

});
</script>
</body>
</html>
