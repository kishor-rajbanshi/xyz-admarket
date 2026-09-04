function PopWindowUnder(url,width,height)
{
    var main_window  = (top != self && typeof(top.document.location.toString()) === 'string') ? top : self;

    var useragent = function() {
        var agentname = navigator.userAgent.toLowerCase();
        var agentbrowser = {
            webkit: /webkit/.test(agentname),
            mozilla: (/mozilla/.test(agentname)) && (!/(compatible|webkit)/.test(agentname)),
            chrome: /chrome/.test(agentname),
            msie: (/msie/.test(agentname)) && (!/opera/.test(agentname)),
            firefox: /firefox/.test(agentname),
            safari: (/safari/.test(agentname) && !(/chrome/.test(agentname))),
            opera: /opera/.test(agentname)
        };
        agentbrowser.version = (agentbrowser.safari) ? (agentname.match(/.+(?:ri)[\/: ]([\d.]+)/) || [])[1] : (agentname.match(/.+(?:ox|me|ra|ie)[\/: ]([\d.]+)/) || [])[1];
        return agentbrowser;
    }();

    function PopWindow(url,width,height)
    {
        var window_data = 'toolbar=no,scrollbars=yes,location=yes,statusbar=yes,menubar=no,resizable=1,width='+width+',height='+height;

            pop_object = main_window.window.open(url,'',window_data);
            if(pop_object)
            {
            	try {
                    pop_object.blur();
                    pop_object.opener.window.focus();
                    window.self.window.focus();
                    window.focus();


                    //if (useragent.firefox) closewindow();
                    if (useragent.webkit) closetab();
                    else
                    {
                        setTimeout(function() {
                            pop_object.blur();
                            pop_object.opener.window.focus();
                            window.self.window.focus();
                            window.focus();
                        }, 1000);
                    }


                } catch (e) {}
            }
    }


    function closewindow() {
        var newwindow = window.open('about:blank');
        newwindow.focus();
        newwindow.close();
    }

    function closetab()
    {
        var temp = '';
        var newtab = document.createElement("a");
        newtab.href   = "data:text/html,<scr"+temp+"ipt>window.close();</scr"+temp+"ipt>";
        document.getElementsByTagName("body")[0].appendChild(newtab);

        var clickevent = document.createEvent("MouseEvents");
        clickevent.initMouseEvent("click", false, true, window, 0, 0, 0, 0, 0, true, false, false, true, 0, null);
        newtab.dispatchEvent(clickevent);

        newtab.parentNode.removeChild(newtab);


    	window.open(newtab.href).close();
    }

    PopWindow(url,width,height);
}
