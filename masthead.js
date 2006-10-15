// Emulate the following CSS rules in JavaScript:
// div.department h3:after { content: ": " }
// div.department li:after { content: ", " }
// div.department li.last:after { content: "; " }
// div.department li.last-last:after { content: "." } 
function emulateAfter() {
    // XXX: Most browsers that support the CSS2 maxHeight also support the CSS2
    //      :after pseudo-element (I think), but it's not the best test ever...
    if (typeof document.body.style.maxHeight == "undefined") {
        var allDivs = document.getElementsByTagName("div");

        for (var i = 0; i < allDivs.length; i++) {
            var div = allDivs[i];
            if (div.className != "department") {
                continue;
            }

            var allH3s = div.getElementsByTagName("h3");
            for (var j = 0; j < allH3s.length; j++) {
                // Find the last child of the <h3> that's a text node
                var lastText = allH3s[j].lastChild;
                while (lastText &&
                       lastText.nodeType != 3 /* Node.TEXT_NODE */) {
                    lastText = lastText.previousSibling;
                }

                if (lastText) {
                    lastText.appendData(": ");
                }
            }


            var allLIs = div.getElementsByTagName("li");
            for (var j = 0; j < allLIs.length; j++) {
                var li = allLIs[j];

                // Find the last child of the <li> that's a text node
                var lastText = li.lastChild;
                while (lastText &&
                       lastText.nodeType != 3 /* Node.TEXT_NODE */) {
                    lastText = lastText.previousSibling;
                }

                if (lastText) {
                    // IE adds an extra space to <li>s for some reason
                    lastText.deleteData(lastText.length - 1, 1);

                    if (li.className == "last-last") {
                        lastText.appendData(".");
                    } else if (li.className == "last") {
                        lastText.appendData("; ");
                    } else {
                        lastText.appendData(", ");
                    }
                }
            }
        }
    }
}
