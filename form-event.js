YUI.add("form-util", function (Y) {

    var MODULE_ID = "FormUtil",
        //=====================
        // Private Variables
        //=====================
        _state,
        _stateAttr,       
        //=====================
        // Private Events
        //=====================
        _formEventHandler,
        //=====================
        // Private Methods
        //=====================
        _arrayDiff,
        //=====================
        // Public Methods
        //=====================
        getItems,
        getValue,
        getState,
        set,
        setValue;

    /*
     * Handles form events including click, keyup, paste....
     *
     * @private _formEventHandler
     * @param {Y.Event} Event instance.
     */
    _formEventHandler = function (e) {
        var state = getState(this),
            affectedNodes = [],
            name,
            i;

        if (state === _state) {
            return;
        }
        stateAttr = getState(this, true);
        for (i in stateAttr) {

            // Stop when this field doesn't get updated.
            if (stateAttr[i].value === _stateAttr[i].value) {
                continue;
            }

            // Find out affected items (remove/add, select).
            if (stateAttr[i].item && stateAttr[i].items.activeNodes) {
                diff = _arrayDiff(_stateAttr[i].items["activeNodes"], stateAttr[i].items["activeNodes"]);
                if (diff.added.length) {
                    affectedNodes.push(Y.one("#" + diff.added[0]));
                }
                if (diff.removed.length) {
                    affectedNodes.push(Y.one("#" + diff.removed[0]));
                }
                affectedNodes = new Y.NodeList(affectedNodes);
            } else {
                affectedNodes = this.one("[name=" + stateAttr[i].name + "]");
            }
            name = stateAttr[i].name;

            Y.fire("formUtil:valueChange", {
                "form" : this,
                "name" : name,
                "items": affectedNodes
            });
            if (this.publish) {
                this.fire("valueChange", {
                    "type"      : "valueChange",
                    "target"    : affectedNodes,
                    "prevValue" : _stateAttr[i].value,
                    "newValue"  : stateAttr[i].value,
                    "targetName": name
                });
            }
            break;
        }
        _state = state;
        _stateAttr = stateAttr;
    };

    /*
     * Get field items by name.
     * By providing form and field name, you can get all field items.
     *
     * @public getItems
     * @param {Mixed} node Form node, can be HTML element, Y.Node or CSS ID selector.
     * @param {String} name Field name.
     * @return {Object} It contains "ids" and "activeNodes" 
     */
    getItems = function (node, name) {
        var type  = Y.Node.getDOMNode(node.one("[name=" + name + "]")).type;
            items = {};

        node   = Y.one(node);
        switch (type) {
            case "radio":
            case "checkbox":
                items = {
                    "ids"        : node.all("[name=" + name + "]").get("id"),
                    "activeNodes": node.all("[name=" + name + "]:checked").get("id")
                }
                break;
            default:
                try {
                    item = Y.one("[name=" + name + "]");
                } catch (e) { 
                    Y.log(name + ":" + e.message); 
                    return;
                }
            break;
        }
        return items;
    };

    /*
     * Set field value.
     * By providing form, field name, and target value, you can set field value.
     * 
     * @public getValue
     * @param {Mixed} node Form node, can be Y.Node or CSS ID selector.
     * @param {String} name Field name. 
     * @return {Boolean} true if set value successfully.
     */
    setValue = function (node, name, value) {
        node = Y.one(node);
        var el,
            type,
            result,
            hasMatch;

        try { 
            type  = Y.Node.getDOMNode(node.one("[name=" + name + "]")).type;
        } catch (e) {
            Y.log("setValue() The name you provided does not exists.", "error", MODULE_ID);
            return;
        }
        hasMatch = false;

        switch (type) {
            case "checkbox":
            case "radio":
                node.all("[name=" + name + "]").each(function (node) {
                    if (node.get(value) === value) {
                        node.set("checked", true);
                        hasMatch = true;
                    } else {
                        node.set("checked", false); 
                    }
                });
            break;
            case "select-one":
            case "select-multiple":
                result = [];
                node.one("[name=" + name + "]").all("option").each(function (node) {
                    if (node.get("value") == value) {
                        node.set("selected", true);
                        hasMatch = true;
                    } else {
                        node.set("selected", false);
                    }
                });
                result = result.join(",");
            break;
            default:
                value = node.one("[name=" + name + "]").set("value", value);
        }
        return hasMatch;
    };

    /*
     * Get field value.
     * By providing form and field name, you can get selected field values.
     * 
     * @public getValue
     * @param {Mixed} node Form node, can be Y.Node or CSS ID selector.
     * @param {String} name Field name.
     * @return {String} Selected value.
     */
    getValue = function (node, name) {
        node = Y.one(node);
        var el,
            type,
            value;

        try { 
            type  = Y.Node.getDOMNode(node.one("[name=" + name + "]")).type;
        } catch (e) {
            Y.log("getValue() The name you provided does not exists.", "error", MODULE_ID);
            return;
        }
        value = "";

        switch (type) {
            case "checkbox":
            case "radio":
                value = node.all("[name=" + name + "]:checked").get("value").join(",");
            break;
            case "select-multiple":
                value = [];
                node.one("[name=" + name + "]").all("option").each(function (node) {
                    if (node.get("selected")) {
                        value.push(node.get("value"));                
                    }
                });
                value = value.join(",");
            break;
            default:
                value = node.one("[name=" + name + "]").get("value");
        }
        return value;
    };

    /*
     * Get form values as query string or object.
     * It's useful when you want to check if form has modified by user.
     * 
     * @public getState
     * @param {Y.Node} node Form node.
     * @param {Boolean} isObject true if you want return type is object literal.
     * @return {Mixed} query string or object literal.
     */
    getState = function (node, isObject) {
        var attrs = [],
            el    = Y.Node.getDOMNode(node),
            ele   = null,
            i,
            names = [];

        for (i = 0, j = el.elements.length; i < j; i++) {
            ele = el.elements[i]
            if (typeof ele.name === "undefined" || !ele.name) {
                continue;
            }
            if (typeof ele.type === "undefined" || !ele.type) {
                continue;
            }
            if (Y.Array.indexOf(names, ele.name) !== -1) {
                continue;
            }
            names.push(ele.name);
            if (isObject) {
                attrs[ele.name] = {
                    "name" : ele.name,
                    "type" : ele.type,
                    "value": getValue(node, ele.name),
                    "items": getItems(node, ele.name)  
                };
            } else {
                attrs.push(ele.name + "=" + getValue(el, ele.name)); 
                attrs.join("&");
            }
        }
        return attrs;
    }

    /*
     * Compare two arrays to see which items are added, removed, or not changed.
     * 
     * @private _arrayDiff
     * @param {Array} theirs Array before modification.
     * @param {Array} mines  Array after modification.
     * @return {Object} It contains "added", "removed" and "same" items.
     */
    _arrayDiff = function (theirs, mines) {
        var same    = [],
            added   = [],
            removed = [],
            i,
            j,
            mine,
            their,
            isMatch;

        for (i in theirs) {
            their = theirs[i];
            isMatch = false;
            for (j in mines) {
                mine = mines[j] 
                if (their === mine) {
                    isMatch = true;
                    mines.splice(j, 1);
                    break;
                }
            }
            if (isMatch) {
                same.push(theirs[i]);                    
            } else {
                removed.push(theirs[i]);
            }
        }

        return {
            "added"  : mines,
            "removed": removed,
            "same"   : same 
        };
    };
    

    /*
     * We want to fix different event behavior in different browsers.
     * After using setForm(), you can subscribe "form:change" custom event.
     *
     * @public setForm
     * @param {Y.Node} Form node.
     * @return void
     */
    set = function (node) {
        var node   = Y.one(node);

        // Set IDs
        var el = node._node;
        for (var i = 0, j = el.elements.length; i < j; i++) {
            if (
                el.elements[i].nodeName.toLowerCase() !== "textarea" && 
                el.elements[i].nodeName.toLowerCase() !== "input" && 
                el.elements[i].nodeName.toLowerCase() !== "button" && 
                el.elements[i].nodeName.toLowerCase() !== "select"
            ) { 
                continue;
            }
            if (!el.elements[i].getAttribute("id")) {
                el.elements[i].setAttribute("id", Y.guid());
            }
        }

        _state     = getState(node);
        _stateAttr = getState(node, true);

        node.getValue = function (name) {
            return getValue.call(this, node, name);
        };
        node.getItems = function (name) {
            return setValue.call(this, node, name);
        };
        node.setValue = function (name, value) {
            return setValue.call(this, node, name, value);
        };
        node.getState = function () {
            return getState.call(this, node);
        };

        function Item(name){
            if (!(this instanceof Item)) {
                return new Item(name);
            }
            this.name = name;
            this.toString = function () {
                return getValue.call(this, node, name);
            };
            this.val = function (value) {
                if (arguments.length) {    
                    return setValue.call(this, node, this.name, value);
                } else {
                    return getValue.call(this, node, this.name);
                }
            };
        }

        node.item = Item; 

        Y.augment(node, Y.EventTarget); 
        node.publish("valueChange");

        // Bind all events which might change form values.
        node.on("click", _formEventHandler);
        node.on("keyup", _formEventHandler);
        node.on("keypress", _formEventHandler);
        node.on("paste", _formEventHandler);
        node.on("focus", _formEventHandler);
        node.on("blur", _formEventHandler);
        Y.later(1000, node, _formEventHandler, null, true);
        return node;
    };

    Y.FormUtil = {
        "set": set,
        "getItems": getItems,
        "getState": getState,
        "getValue": getValue,
        "setValue": setValue
    };

}, "3.2.0", {"requires": ["node", "selector-css3"]});

