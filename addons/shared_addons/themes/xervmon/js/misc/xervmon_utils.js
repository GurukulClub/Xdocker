;
!(function ($) {
    "use strict";
    $.fn.serializeObject = function () {
        /*http://stackoverflow.com/a/1186309/937891/*/
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    $.reduce = function (arr, valueInitial, fnReduce) {
        $.each(arr, function (i, value) {
            valueInitial = fnReduce.apply(value, [valueInitial, i, value]);
        });
        return valueInitial;
    };
    $.fn.scrollToThis = function (time) {
        return this.each(function () {
            $('html,body').animate({
                scrollTop: $(this).offset().top - 50
            }, time || 100);
        });
    };

    $.ajaxQ = (function () {
        // http://stackoverflow.com/a/11612641/937891
        var id = 0,
            Q = {};

        $(document).ajaxSend(function (e, jqx) {
            jqx._id = ++id;
            Q[jqx._id] = jqx;
        });
        $(document).ajaxComplete(function (e, jqx) {
            delete Q[jqx._id];
        });

        return {
            abortAll: function () {
                var r = [];
                $.each(Q, function (i, jqx) {
                    r.push(jqx._id);
                    jqx.abort();
                });
                return r;
            }
        };

    })();

    window.customTypeAheadEngine = function () {
        this.compile = function (template) {
            this.template = template;
            return this;
        };
        this.render = function (context) {
            var template = this.template;
            return template.replace(/\%\%\%([^\%]*)\%\%\%/ig, function (match, key) {
                return context[key];
            });
        };
    };

})(jQuery);
window.getEnableDisableToggle = function (val) {
    var ret = '';
    switch (val) {
    case 1:
        ret = '<span class="badge badge-success">✓</span>';
        break;
    case 0:
        ret = '<span class="badge badge-important">✘</span>';
        break;
    }
    return ret;
};
window.getCloudProviderIcon = function (cloudProvider) {
    var icon = '';
    switch (cloudProvider) {
    case 'Rackspace Cloud':
        icon = BASE_URL + '/addons/shared_addons/themes/xervmon/img/rackspace-big.jpg';
        break;
    case 'Amazon AWS':
        icon = BASE_URL + '/addons/shared_addons/themes/xervmon/img/aws-big.jpg';
        break;
    case 'HP Cloud':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/hpcloud_logo.png';
        break;
    case 'OpenStack':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/openstack-small.png';
        break;
    case 'DigitalOcean':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/digitalocean.png';
        break;
    case 'vCloud':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/vcloud.png';
        break;
    case 'CloudStack':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/cloudstack.png';
        break;

    case 'Softlayer':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/softlayer.png';
        break;
    case 'GCE':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/gce.png';
        break;
    case 'Windows Azure':
        icon = BASE_URL + 'addons/shared_addons/themes/xervmon/img/providers/azure.png';
        break;


    }
    return icon;
};

function getDefaultUserIcon() {
    return BASE_URL + 'addons/shared_addons/themes/xervmon/img/icons/user.png';
};



function getDplannerIcon() {
    return BASE_URL + 'addons/shared_addons/themes/xervmon/img/icons/dplanner.png';
};

window.getCurrencySymbol = function (region) {
    var symbol = '';
    switch (region) {
    case 'US':
        symbol = ' $ ';
        break;
    case 'UK':
        symbol = ' £ ';
        break;
    default:
        symbol = '$';
        break;
    }
    return symbol;
};
window.getCurrencySymbolForRegionCode = function (region) {
    var symbol = '';
    switch (region) {
    case 'LON':
        symbol = ' £ ';
        break;
    case 'DFW':
    case 'ORD':
    case 'SYD':
    default:
        symbol = ' $ ';
        break
    }
    return symbol;
};

function wordwrap(str, int_width, str_break, cut) {
    // From: http://phpjs.org/functions
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Nick Callen
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Sakimori
    // +   bugfixed by: Michael Grier
    // *     example 1: wordwrap('Kevin van Zonneveld', 6, '|', true);
    // *     returns 1: 'Kevin |van |Zonnev|eld'
    // *     example 2: wordwrap('The quick brown fox jumped over the lazy dog.', 20, '<br />\n');
    // *     returns 2: 'The quick brown fox <br />\njumped over the lazy<br />\n dog.'
    // *     example 3: wordwrap('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');
    // *     returns 3: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod \ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim \nveniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea \ncommodo consequat.'
    // PHP Defaults
    var m = ((arguments.length >= 2) ? arguments[1] : 75);
    var b = ((arguments.length >= 3) ? arguments[2] : "\n");
    var c = ((arguments.length >= 4) ? arguments[3] : false);

    var i, j, l, s, r;

    str += '';

    if (m < 1) {
        return str;
    }

    for (i = -1, l = (r = str.split(/\r\n|\n|\r/)).length; ++i < l; r[i] += s) {
        for (s = r[i], r[i] = ""; s.length > m; r[i] += s.slice(0, j) + ((s = s.slice(j)).length ? b : "")) {
            j = c == 2 || (j = s.slice(0, m + 1).match(/\S*(\s)?$/))[1] ? m : j.input.length - j[0].length || c == 1 && m || j.input.length + (j = s.slice(m).match(/^\S*/)).input.length;
        }
    }

    return r.join("\n");
};
window.ucfirst = function (str) {
    str += '';
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1);
};

window.getLabel = function (type, str) {
    if (!str)
        return '';
    var ret = '';
    switch (ucfirst(type)) {
        default:
    case 'Info':
        ret = '<span class="label label-info">' + str + '</span>';
        break;
    case 'Default':
        ret = '<span class="label">' + str + '</span>';
        break;
    case 'Success':
        ret = '<span class="label label-success">' + str + '</span>';
        break;
    case 'Warning':
        ret = '<span class="label label-warning">' + str + '</span>';
        break;
    case 'Important':
        ret = '<span class="label label-important">' + str + '</span>';
        break;
    case 'Inverse':
        ret = '<span class="label label-inverse">' + str + '</span>';
        break;
    }
    return ret;
};
window.getBadge = function (type, str) {
    if (!str)
        return '';
    var ret = '';
    switch (ucfirst(type)) {
        default:
    case 'Info':
        ret = '<span class="badge badge-info">' + str + '</span>';
        break;
    case 'Default':
        ret = '<span class="badge">' + str + '</span>';
        break;
    case 'Success':
        ret = '<span class="badge badge-success">' + str + '</span>';
        break;
    case 'Warning':
        ret = '<span class="badge badge-warning">' + str + '</span>';
        break;
    case 'Important':
        ret = '<span class="badge badge-important">' + str + '</span>';
        break;
    case 'Inverse':
        ret = '<span class="badge badge-inverse">' + str + '</span>';
        break;
    }
    return ret;
};


window.getBadgeByStr = function (str) {
    var ret = '';
    switch (str) {
        default:
    case 'new':
    case 'New':
        ret = '<span class="badge badge-info">' + ucfirst(str) + '</span>';
        break;

    case 'ACTIVE':
    case 'Active':
    case 'running':
    case 'Running':
    case 'Success':
    case 'available':
    case 'Available':
        ret = '<span class="badge badge-success">' + ucfirst(str) + '</span>';
        break;
    case 'Warning':
        ret = '<span class="badge badge-warning">' + ucfirst(str) + '</span>';
        break;
    case 'error':
    case 'Error':
        ret = '<span class="badge badge-danger">' + ucfirst(str) + '</span>';
        break;

    case 'Processing':
    case 'processing':
        ret = '<span class="badge badge-important">' + ucfirst(str) + '</span>';
        break;
    case 'Inverse':
        ret = '<span class="badge badge-inverse">' + str + '</span>';
        break;
    }
    return ret;
};
window.getDevelopmentMode = function (type) {
    if (!type)
        return '';
    var ret = '';
    switch (ucfirst(type)) {
    case 'Development':
        ret = '<span class="statusNew"></span>' + type;
        break;
    case 'QA':
        ret = '<span class="badge">' + type + '</span>';
        break;
    case 'Production':
        ret = '<span class="badge badge-success">' + type + '</span>';
        break;
    case 'Test':
        ret = '<span class="statusNew yellowFlag"></span>' + type;
        break;
    case 'Staging':
        ret = '<span class="statusNew redFlag"></span>' + type;
        break;
    default:
    case 'POC':
        ret = '<span class="badge badge-inverse">' + type + '</span>';
        break;
    }
    return ret;
};

window.IsJsonString = function(str) 
{
    try 
    {
		JSON.parse(str);
	} catch (e) 
	{
		return false;
	}
	return true;
};


function date(format, timestamp) {
    // http://kevin.vanzonneveld.net
    // +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +      parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: MeEtc (http://yass.meetcweb.com)
    // +   improved by: Brad Touesnard
    // +   improved by: Tim Wiel
    // +   improved by: Bryan Elliott
    //
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: David Randall
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +  derived from: gettimeofday
    // +      input by: majak
    // +   bugfixed by: majak
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Alex
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +   improved by: Thomas Beaucourt (http://www.webapp.fr)
    // +   improved by: JT
    // +   improved by: Theriault
    // +   improved by: Rafał Kukawski (http://blog.kukawski.pl)
    // +   bugfixed by: omid (http://phpjs.org/functions/380:380#comment_137122)
    // +      input by: Martin
    // +      input by: Alex Wilson
    // +   bugfixed by: Chris (http://www.devotis.nl/)
    // %        note 1: Uses global: php_js to store the default timezone
    // %        note 2: Although the function potentially allows timezone info (see notes), it currently does not set
    // %        note 2: per a timezone specified by date_default_timezone_set(). Implementers might use
    // %        note 2: this.php_js.currentTimezoneOffset and this.php_js.currentTimezoneDST set by that function
    // %        note 2: in order to adjust the dates in this function (or our other date functions!) accordingly
    // *     example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
    // *     returns 1: '09:09:40 m is month'
    // *     example 2: date('F j, Y, g:i a', 1062462400);
    // *     returns 2: 'September 2, 2003, 2:26 am'
    // *     example 3: date('Y W o', 1062462400);
    // *     returns 3: '2003 36 2003'
    // *     example 4: x = date('Y m d', (new Date()).getTime()/1000);
    // *     example 4: (x+'').length == 10 // 2009 01 09
    // *     returns 4: true
    // *     example 5: date('W', 1104534000);
    // *     returns 5: '53'
    // *     example 6: date('B t', 1104534000);
    // *     returns 6: '999 31'
    // *     example 7: date('W U', 1293750000.82); // 2010-12-31
    // *     returns 7: '52 1293750000'
    // *     example 8: date('W', 1293836400); // 2011-01-01
    // *     returns 8: '52'
    // *     example 9: date('W Y-m-d', 1293974054); // 2011-01-02
    // *     returns 9: '52 2011-01-02'
    var that = this,
        jsdate, f, formatChr = /\\?([a-z])/gi,
        formatChrCb,
        // Keep this here (works, but for code commented-out
        // below for file size reasons)
        //, tal= [],
        _pad = function (n, c) {
            n = n.toString();
            return n.length < c ? _pad('0' + n, c, '0') : n;
        },
        txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    formatChrCb = function (t, s) {
        return f[t] ? f[t]() : s;
    };
    f = {
        // Day
        d: function () { // Day of month w/leading 0; 01..31
            return _pad(f.j(), 2);
        },
        D: function () { // Shorthand day name; Mon...Sun
            return f.l().slice(0, 3);
        },
        j: function () { // Day of month; 1..31
            return jsdate.getDate();
        },
        l: function () { // Full day name; Monday...Sunday
            return txt_words[f.w()] + 'day';
        },
        N: function () { // ISO-8601 day of week; 1[Mon]..7[Sun]
            return f.w() || 7;
        },
        S: function () { // Ordinal suffix for day of month; st, nd, rd, th
            var j = f.j()
            i = j % 10;
            if (i <= 3 && parseInt((j % 100) / 10) == 1)
                i = 0;
            return ['st', 'nd', 'rd'][i - 1] || 'th';
        },
        w: function () { // Day of week; 0[Sun]..6[Sat]
            return jsdate.getDay();
        },
        z: function () { // Day of year; 0..365
            var a = new Date(f.Y(), f.n() - 1, f.j()),
                b = new Date(f.Y(), 0, 1);
            return Math.round((a - b) / 864e5);
        },

        // Week
        W: function () { // ISO-8601 week number
            var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
                b = new Date(a.getFullYear(), 0, 4);
            return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
        },

        // Month
        F: function () { // Full month name; January...December
            return txt_words[6 + f.n()];
        },
        m: function () { // Month w/leading 0; 01...12
            return _pad(f.n(), 2);
        },
        M: function () { // Shorthand month name; Jan...Dec
            return f.F().slice(0, 3);
        },
        n: function () { // Month; 1...12
            return jsdate.getMonth() + 1;
        },
        t: function () { // Days in month; 28...31
            return (new Date(f.Y(), f.n(), 0)).getDate();
        },

        // Year
        L: function () { // Is leap year?; 0 or 1
            var j = f.Y();
            return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
        },
        o: function () { // ISO-8601 year
            var n = f.n(),
                W = f.W(),
                Y = f.Y();
            return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
        },
        Y: function () { // Full year; e.g. 1980...2010
            return jsdate.getFullYear();
        },
        y: function () { // Last two digits of year; 00...99
            return f.Y().toString().slice(-2);
        },

        // Time
        a: function () { // am or pm
            return jsdate.getHours() > 11 ? "pm" : "am";
        },
        A: function () { // AM or PM
            return f.a().toUpperCase();
        },
        B: function () { // Swatch Internet time; 000..999
            var H = jsdate.getUTCHours() * 36e2,
                // Hours
                i = jsdate.getUTCMinutes() * 60,
                // Minutes
                s = jsdate.getUTCSeconds();
            // Seconds
            return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
        },
        g: function () { // 12-Hours; 1..12
            return f.G() % 12 || 12;
        },
        G: function () { // 24-Hours; 0..23
            return jsdate.getHours();
        },
        h: function () { // 12-Hours w/leading 0; 01..12
            return _pad(f.g(), 2);
        },
        H: function () { // 24-Hours w/leading 0; 00..23
            return _pad(f.G(), 2);
        },
        i: function () { // Minutes w/leading 0; 00..59
            return _pad(jsdate.getMinutes(), 2);
        },
        s: function () { // Seconds w/leading 0; 00..59
            return _pad(jsdate.getSeconds(), 2);
        },
        u: function () { // Microseconds; 000000-999000
            return _pad(jsdate.getMilliseconds() * 1000, 6);
        },

        // Timezone
        e: function () { // Timezone identifier; e.g. Atlantic/Azores, ...
            // The following works, but requires inclusion of the very large
            // timezone_abbreviations_list() function.
            /*              return that.date_default_timezone_get();
             */
            throw 'Not supported (see source code of date() for timezone on how to add support)';
        },
        I: function () { // DST observed?; 0 or 1
            // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
            // If they are not equal, then DST is observed.
            var a = new Date(f.Y(), 0),
                // Jan 1
                c = Date.UTC(f.Y(), 0),
                // Jan 1 UTC
                b = new Date(f.Y(), 6),
                // Jul 1
                d = Date.UTC(f.Y(), 6);
            // Jul 1 UTC
            return ((a - c) !== (b - d)) ? 1 : 0;
        },
        O: function () { // Difference to GMT in hour format; e.g. +0200
            var tzo = jsdate.getTimezoneOffset(),
                a = Math.abs(tzo);
            return (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
        },
        P: function () { // Difference to GMT w/colon; e.g. +02:00
            var O = f.O();
            return (O.substr(0, 3) + ":" + O.substr(3, 2));
        },
        T: function () { // Timezone abbreviation; e.g. EST, MDT, ...
            // The following works, but requires inclusion of the very
            // large timezone_abbreviations_list() function.
            /*              var abbr = '', i = 0, os = 0, default = 0;
             if (!tal.length) {
             tal = that.timezone_abbreviations_list();
             }
             if (that.php_js && that.php_js.default_timezone) {
             default = that.php_js.default_timezone;
             for (abbr in tal) {
             for (i=0; i < tal[abbr].length; i++) {
             if (tal[abbr][i].timezone_id === default) {
             return abbr.toUpperCase();
             }
             }
             }
             }
             for (abbr in tal) {
             for (i = 0; i < tal[abbr].length; i++) {
             os = -jsdate.getTimezoneOffset() * 60;
             if (tal[abbr][i].offset === os) {
             return abbr.toUpperCase();
             }
             }
             }
             */
            return 'UTC';
        },
        Z: function () { // Timezone offset in seconds (-43200...50400)
            return -jsdate.getTimezoneOffset() * 60;
        },

        // Full Date/Time
        c: function () { // ISO-8601 date.
            return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
        },
        r: function () { // RFC 2822
            return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
        },
        U: function () { // Seconds since UNIX epoch
            return jsdate / 1000 | 0;
        }
    };
    this.date = function (format, timestamp) {
        that = this;
        jsdate = (timestamp === undefined ? new Date() : // Not provided
            (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
            new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
        );
        return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
};

window.getDPSummary = function (data) {
    var ret = '';
    if (data.summary) {
        var summary = JSON.parse(data.summary);
        ret = '<div class="priceTime"><label class="time">Hourly:</label><label class="price">' + getCurrencySymbol(data.region) + summary.hourly + '</label></div>';
        ret += '<div class="priceTime"><label class="time">Monthly:</label><label class="price">' + getCurrencySymbol(data.region) + summary.monthly + '</label></div>';
    }
    return ret;
};

window.getDPStatus = function (type) {
    if (!type)
        return '';
    var ret = '';
    switch (type) {
    case 'in progress':
    case 'In Progress':
    case 'in-use':
        ret = '<span class="label label-warning">' + type + '</span>';
        break;
    case 'completed':
    case 'COMPLETED':
    case 'running':
    case 'active':
    case 'ACTIVE':
    case 'available':
        ret = '<span class="label label-success">' + type + '</span>';
        break;
    case 'draft':
        ret = '<span class="label label-info">' + type + '</span>';
        break;
    case 'stopped':
        ret = '<span class="label label-important">' + type + '</span>';
        break;
    case 'error':
    case 'ERROR':
        ret = '<span class="label label-danger">' + type + '</span>';
        break;
    default:
        ret = '<span class="label label-inverse">' + type + '</span>';
        break;
    }
    return ret;
};

function call_user_func(cb) {
    // http://kevin.vanzonneveld.net
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Diplom@t (http://difane.com/)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: call_user_func('isNaN', 'a');
    // *     returns 1: true
    var func;

    if (typeof cb === 'string') {
        func = (typeof this[cb] === 'function') ? this[cb] : func = (new Function(null, 'return ' + cb))();
    } else if (Object.prototype.toString.call(cb) === '[object Array]') {
        func = (typeof cb[0] == 'string') ? eval(cb[0] + "['" + cb[1] + "']") : func = cb[0][cb[1]];
    } else if (typeof cb === 'function') {
        func = cb;
    }

    if (typeof func !== 'function') {
        throw new Error(func + ' is not a valid function');
    }

    var parameters = Array.prototype.slice.call(arguments, 1);
    return (typeof cb[0] === 'string') ? func.apply(eval(cb[0]), parameters) : (typeof cb[0] !== 'object') ? func.apply(null, parameters) : func.apply(cb[0], parameters);
};

function call_user_func_array(cb, parameters) {
    // http://kevin.vanzonneveld.net
    // +   original by: Thiago Mata (http://thiagomata.blog.com)
    // +   revised  by: Jon Hohle
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Diplom@t (http://difane.com/)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: call_user_func_array('isNaN', ['a']);
    // *     returns 1: true
    // *     example 2: call_user_func_array('isNaN', [1]);
    // *     returns 2: false
    var func;

    if (typeof cb === 'string') {
        func = (typeof this[cb] === 'function') ? this[cb] : func = (new Function(null, 'return ' + cb))();
    } else if (Object.prototype.toString.call(cb) === '[object Array]') {
        func = (typeof cb[0] == 'string') ? eval(cb[0] + "['" + cb[1] + "']") : func = cb[0][cb[1]];
    } else if (typeof cb === 'function') {
        func = cb;
    }

    if (typeof func !== 'function') {
        throw new Error(func + ' is not a valid function');
    }

    return (typeof cb[0] === 'string') ? func.apply(eval(cb[0]), parameters) : (typeof cb[0] !== 'object') ? func.apply(null, parameters) : func.apply(cb[0], parameters);
};

window.buildTableFromArray = function (data, excludeKeys, headings, nameChanges, classForKeyLabels, classForKeyItems, customKeyConverter) {
    "use strict";
    // Generate the markup for a table from the given data
    var i, j, len, len2, current, markup, fieldName;


    window.Object = window.Object || {};
    window.Object.keys = window.Object.keys || (function (obj) {
        return $.map(obj, function (v, i) {
            return i;
        })
    });

    data = $.makeArray(data);

    if (!$.isArray(excludeKeys)) {
        excludeKeys = [];
    }
    if (!$.isPlainObject(nameChanges)) {
        nameChanges = {};
    }
    if (!$.isArray(headings)) {
        headings = data.length > 0 ? window.Object.keys(data[0]) : [];
    }
    if (!$.isPlainObject(classForKeyLabels)) {
        classForKeyLabels = {};
    }
    if (!$.isPlainObject(classForKeyItems)) {
        classForKeyItems = {};
    }

    markup = '<table id="exportTableid" class="table table-striped table-bordered">';

    markup += '<thead><tr>';
    for (i = 0, len = headings.length; i < len; i++) {
        fieldName = headings[i];
        if ($.inArray(fieldName, excludeKeys) !== -1) {
            continue;
        }
        markup += '<th class="sorting_asc ' + (classForKeyLabels[fieldName] || "") + '">' + ucfirst(nameChanges[fieldName] || fieldName) + '</th>';
    }
    markup += '</tr></thead>';

    markup += '<tbody>';
    for (i = 0, len = data.length; i < len; i++) {
        current = data[i] || {};
        markup += '<tr>';
        for (j = 0, len2 = headings.length; j < len2; j++) {
            fieldName = headings[j];
            if ($.inArray(fieldName, excludeKeys) !== -1) {
                continue;
            }
            var fieldMarkup = (current[fieldName] || "");
            if ($.isPlainObject(current[fieldName])) {
                fieldMarkup = '';
                for (var key in current[fieldName]) {
                    if (!current[fieldName].hasOwnProperty(key))
                        continue;
                    fieldMarkup += '<p><b>' + current[fieldName] + ':</b> <span>' + current[fieldName][key] + '</span></p>';
                }
            } else if ($.isArray(current[fieldName])) {
                fieldMarkup = JSON.stringify(current[fieldName]);
                //[{"Key":"Name","Value":"test1"}]
                //Key":"Name","Value":"test1"

                fieldMarkup = fieldMarkup.replace('[{"Key":"', ' ');
                fieldMarkup = fieldMarkup.replace('","Value":"', ' ');
                fieldMarkup = '<b>' + fieldMarkup.replace('"}]', ' ') + '</b>';
            }

            if (customKeyConverter && $.isFunction(customKeyConverter)) {
                fieldMarkup = customKeyConverter(fieldMarkup, current[fieldName], fieldName, current);
            }
            markup += '<td class="' + (classForKeyItems[fieldName] || "") + '" data-title="' + fieldName + '">' + fieldMarkup + '</td>';
        }
        markup += '</tr>';
    }
    markup += '</tbody></table>';
    return markup;
};

window.buildHP_Table = function (data, excludeKeys, headings, nameChanges, classForKeyLabels, classForKeyItems) {
    "use strict";

    excludeKeys = $.makeArray(excludeKeys);
    // Generate the markup for a table from the given data
    var i, j, len, len2, current, markup, fieldName;

    window.Object = window.Object || {};
    window.Object.keys = window.Object.keys || (function (obj) {
        return $.map(obj, function (v, i) {
            return i;
        })
    });

    data = $.makeArray(data);

    if (!$.isArray(excludeKeys)) {
        excludeKeys = [];
    }
    if (!$.isPlainObject(nameChanges)) {
        nameChanges = {};
    }
    if (!$.isArray(headings)) {
        headings = data.length > 0 ? window.Object.keys(data[0]) : [];
    }
    if (!$.isPlainObject(classForKeyLabels)) {
        classForKeyLabels = {};
    }
    if (!$.isPlainObject(classForKeyItems)) {
        classForKeyItems = {};
    }

    markup = '<table class="table table-striped table-bordered">';

    markup += '<thead><tr>';
    for (i = 0, len = headings.length; i < len; i++) {
        fieldName = headings[i];
        if ($.inArray(fieldName, excludeKeys) !== -1) {
            continue;
        }
        markup += '<th class="sorting_asc' + (classForKeyLabels[fieldName] || "") + '">' + ucfirst(nameChanges[fieldName] || fieldName) + '</th>';
    }
    markup += '</tr></thead>';

    markup += '<tbody>';
    for (i = 0, len = data.length; i < len; i++) {
        current = data[i] || {};
        markup += '<tr>';
        for (j = 0, len2 = headings.length; j < len2; j++) {
            fieldName = headings[j];
            if ($.inArray(fieldName, excludeKeys) !== -1) {
                continue;
            }
            markup += '<td class="' + (classForKeyItems[fieldName] || "") + '" data-title="' + fieldName + '">' + (current[fieldName] || "") + '</td>';
        }
        markup += '</tr>';
    }
    markup += '</tbody></table>';
    return markup;
};

function setupToggleBoxes() {
    // Event handlers for toggling boxes
    jQuery(function ($) {
        "use strict";

        function getSpanNumber(selector) {
            var $this = $(selector);
            for (var i = -1; i <= 12; i++) {
                if ($this.hasClass("span" + i))
                    return i;
            }
            return 0;
        }

        function toggleBoxesHandler(e) {
            e.preventDefault();
            // Toggle content
            var $this = $(this),
                $wrapper = $this.closest(".toggle-box-wrapper"),
                $content = $wrapper.find(".toggle-box-content");
            var spanNumber = getSpanNumber($wrapper),
                $nextSibling = $($wrapper.siblings().get(0)),
                nextSiblingSpanNumber = getSpanNumber($nextSibling);
            if ($content.is(":visible")) {
                $content.fadeOut("normal", function () {
                    $wrapper.removeClass("span" + spanNumber).addClass("span0").data("original-span-number", spanNumber);
                    $nextSibling.addClass("span" + (nextSiblingSpanNumber + spanNumber - 1)).removeClass("span" + nextSiblingSpanNumber).data("original-span-number", nextSiblingSpanNumber);
                });
            } else {
                $wrapper.removeClass("span0").addClass("span" + ($wrapper.data("original-span-number")));
                $nextSibling.removeClass("span" + nextSiblingSpanNumber).addClass("span" + ($nextSibling.data("original-span-number")))
                $content.fadeIn();
            }
            // Toggle icon
            var $icon = $this.find("i");
            if ($icon.hasClass("icon-chevron-left")) {
                $icon.removeClass("icon-chevron-left").addClass("icon-chevron-right");
            } else {
                $icon.removeClass("icon-chevron-right").addClass("icon-chevron-left");
            }
            return false;
        }


        $(".toggle-box-left, .toggle-box-right").on("click", toggleBoxesHandler);
        $(document).on("click", ".toggle-box-left, .toggle-box-right", toggleBoxesHandler);
    });
};

function makeSafeForCSS(name) {
    // http://stackoverflow.com/a/7627603/937891
    return name.replace(/[^a-z0-9]/g, function (s) {
        var c = s.charCodeAt(0);
        if (c == 32)
            return '-';
        if (c >= 65 && c <= 90)
            return '_' + s.toLowerCase();
        return '__' + ('000' + c.toString(16)).slice(-4);
    });
};

function getParameterByName(name) {
    // http://stackoverflow.com/a/901144/937891
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};

function createOverlayWithProgressbar(selector, options) {
    var $container, template, defaults, overlayID, containerOffset;

    defaults = {
        title: "Please wait, while we do some stuff.",
        progressbar: {
            enable: true,
            initialProgress: "100"
        },
        button: {
            enable: false,
            url: "",
            text: "Go Back"
        },
        css: {
            "position": "absolute",
            "height": 0,
            "width": 0,
            "overflow": "hidden",
            "background-color": "rgba(245, 245, 245, 0.85)",
            "vertical-align": "middle",
            "text-align": "center",
            "z-index": 1000
        },
        contentCSS: {
            "max-width": "480px",
            "display": "inline-block",
            "margin": "20px auto",
            "padding": "20px"
        },
        attachTo: "body"
    };
    options = $.extend(true, {}, defaults, options || {});

    $container = $(selector || 'body');
    containerOffset = options.attachTo === "body" ? $container.offset() : {
        top: 0,
        left: 0
    };
    overlayID = "customOverlay_" + ($container.attr("id") || Math.round(Math.random() * 10000));

    $overlay = $("#" + $container.data("custom-overlay-id"));

    if ($overlay.length === 0) {
        template = '<div class="custom-overlay hide" id="' + overlayID + '">';
        template += '<div class="custom-overlay-content img-polaroid">';
        template += '<h4 class="custom-overlay-title" style="margin:auto">' + options.title + '</h4><br/>';
        if (options.progressbar.enable) {
            template += '<div class="progress progress-striped active custom-overlay-progress">';
            template += '<div class="bar custom-overlay-progress-bar" style="width: ' + options.progressbar.initialProgress + '%;"></div>';
            template += '</div>';
        }
        if (options.button.enable) {
            template += '<a href="' + options.button.url + '" class="btn btn-primary custom-overlay-button"> ' + options.button.text + ' </a>';
        }
        template += '</div></div>';

        $overlay = $(template).prependTo(options.attachTo || $container);
        $overlay.find(".custom-overlay-content").css(options.contentCSS || {});
        $overlay.css(options.css || {});
        // Prevent relativistic effects
        $overlay.wrap("<div style='position:" + (options.attachTo !== "body" ? "relative" : "static") + "'/>");
        $container.data("custom-overlay-id", overlayID);
    }

    containerOffset = containerOffset || {
        top: 0,
        left: 0
    };
    $overlay.css({
        top: containerOffset.top,
        left: containerOffset.left,
        height: $container.height(),
        width: $container.width()
    }).fadeIn();

    $(window).on("resize orientationchange", function () {
        containerOffset = options.attachTo === "body" ? $container.offset() : {
            top: 0,
            left: 0
        };
        containerOffset = containerOffset || {
            top: 0,
            left: 0
        };
        $overlay.css({
            top: containerOffset.top,
            left: containerOffset.left,
            height: $container.height(),
            width: $container.width()
        });
    }).trigger("resize");

    return $overlay;
};

function setupTableSorterChecked(selector, displayTotalCount, pageSize, customFooterMarkup, hidePaginationToggleButton, themeOptions, sorterOptions, pagerOptions) {
    // Setup table sorter over the given element
    //  master table generator/sorter
    // Author: Sathvik, Doers' Guild

    var $this = $($(selector || this).get(0));

    setTimeout(function () {
        // Wait until it's inserted into the DOM
        if (!$this.is("table")) {
            $this = $($this.find("table").get(0));
        }
        if (!$this.is("table")) {
            $this = $this.closest("table");
        }
        if (!$this.is("table")) {
            return $this;
        }

        $this = $($this);

        var id = $this.attr("id") || "setupTableSorterChecked_table_" + (Math.round(Math.random() * 100000));
        $this.attr("id", id);

        $this.wrap('<div />');
        var $parent = $this.parent().addClass("dataTables_wrapper").attr("id", id + '_wrapper');

        themeOptions = $.isPlainObject(themeOptions) ? themeOptions : {};

        $.extend($.tablesorter.themes.bootstrap, {
            // these classes are added to the table. To see other table classes available,
            // look here: http://twitter.github.com/bootstrap/base-css.html#tables
            table: 'table table-bordered',
            header: 'bootstrap-header', // give the header a gradient background
            footerRow: '',
            footerCells: '',
            icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
            sortNone: 'bootstrap-icon-unsorted',
            sortAsc: 'icon-chevron-up',
            sortDesc: 'icon-chevron-down',
            active: '', // applied when column is sorted
            hover: '', // use custom css here - bootstrap class may not override it
            filterRow: '', // filter row class
            even: '', // odd row zebra striping
            odd: '' // even row zebra striping
        }, themeOptions);

        // Insert pagination controls
        var $pager = $this.find(".tfoot-pager");
        if ($pager.length === 0) {
            var $tfoot = $this.find("tfoot");
            if ($tfoot.length === 0) {
                $tfoot = $("<tfoot>").appendTo($this);
            }
            if (customFooterMarkup) {
                $('<tr><th class="tfoot-pager text-center" colspan="' + ($this.find("tr").first().children().length + 5) + '">' + customFooterMarkup + '</th></tr>').appendTo($tfoot);
            }

            // Insert pagination

            $tfoot = $('<tr><th class="tfoot-pager text-center" colspan="' + ($this.find("tr").first().children().length + 5) + '"></th></tr>').appendTo($tfoot);
            var $pager = $tfoot.find("th.tfoot-pager");

            var pager = '';

            /*if (!hidePaginationToggleButton) {
             pager += '<div class="clearfix showmoreBtn-wrapper"><div class="showmoreBtn"></div></div>';
             }*/

            pager += '<input type="hidden" class="pagesize" value="' + (parseInt(pageSize, 10) || 5) + '" />';

            pager += '<div class="nav nav-tabs" style="border:none;">';
            if (displayTotalCount == true) {
                pager += ('<div class="totalAmnt pull-left totalCost"><i class="cashicon    icons"></i>Total Cost of visible items:<span class="total-visible-amount">0</span></div>');
            }
            pager += ('<div class="dataTables_paginate paging_full_numbers" id="' + id + '_paginate"><button type="button" class="first paginate_button" id="' + id + '_first"></button>');
            pager += ('<button type="button" class="prev previous paginate_button" id="' + id + '_previous" ></button>');
            pager += ('<span class="pagedisplay" ></span>');
            pager += ('<button type="button" class="next paginate_button" id="' + id + '_next"></button>');
            pager += ('<button type="button" class="last paginate_button" id="' + id + '_last"></button></div>');
            pager += '</div>';

            $pager.html(pager);

        }

        try {

            sorterOptions = $.isPlainObject(sorterOptions) ? sorterOptions : {};
            pagerOptions = $.isPlainObject(pagerOptions) ? pagerOptions : {};

            $this.tablesorter($.extend(true, {
                // this will apply the bootstrap theme if "uitheme" widget is included
                // the widgetOptions.uitheme is no longer required to be set
                theme: "bootstrap",

                widthFixed: true,

                headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

                // widget code contained in the jquery.tablesorter.widgets.js file
                // use the zebra stripe widget if you plan on hiding any rows (filter widget)
                widgets: ["uitheme", "filter", "zebra"],

                widgetOptions: {
                    // using the default zebra striping class name, so it actually isn't included in the theme variable above
                    // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
                    zebra: ["even", "odd"],

                    // reset filters button
                    filter_reset: ".reset"

                    // set the uitheme widget to use the bootstrap theme class names
                    // this is no longer required, if theme is set
                    // ,uitheme : "bootstrap"

                }
            }, sorterOptions)).tablesorterPager($.extend(true, {

                // target the pager markup - see the HTML block below
                container: $pager,

                // target the pager page select dropdown - choose a page
                cssGoto: ".pagenum",

                // remove rows from the table to speed up the sort of large tables.
                // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
                removeRows: false,

                // output string - default is '{page}/{totalPages}';
                // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
                output: '{startRow} - {endRow} / {totalRows} ({filteredRows})',

                size: parseInt(pageSize, 10) || 5
            }, pagerOptions));
        } catch (err) {
            console.warn(err.message, err.stack);
        }

        $parent.find('.showmoreBtn').toggle(function () {
            // show
            // console.log("Expand table", this);
            $this.find(".pagesize").val(100000).trigger("change");
            $(this).addClass('hideBtn');
        }, function () {
            // hide
            // console.log("Collapse table", this);
            $this.find(".pagesize").val(parseInt(pageSize, 10) || 5).trigger("change");
            $(this).removeClass('hideBtn');
        });

    }, 100);

    return $this;
};

window.setupTableSorter = function (selector) {
    return (setupTableSorterChecked(selector, true));
};


function setupTableSorterCheckedSimple(options) {
    // Simpler parameter passing
    var paramKeys = ['selector', 'displayTotalCount', 'pageSize', 'customFooterMarkup', 'hidePaginationToggleButton', 'themeOptions', 'sorterOptions', 'pagerOptions'];
    var paramData = [];
    for (var i = 0, l = paramKeys.length; i < l; i++) {
        paramData.push(options[paramKeys[i]]);
    }
    //    console.log('setupTableSorterCheckedSimple', options, paramData);
    return setupTableSorterChecked.apply(this, paramData);
};

window.setupHPInvoiceTableSorter = function (selector) {
    var pager = '<ul class="nav nav-tabs" style="border:none;">';
    pager += '</ul>';
    return (setupTableSorterChecked(selector, true, 10, pager));
};

//Method created for dplanner pagination by Anupama
function setupTableSorterCheckedDplanner(selector, displayTotalCount) {

    var pager = ' <div>';
    pager += '<div class="nav nav-tabs" style="border:none;">';
    if (displayTotalCount == true) {
        pager += ('<div class="totalAmnt pull-left totalCost"><i class="cashicon icons"></i>Total Cost of visible items:<span class="total-visible-amount">0</span></div>');
    }
    pager += '</div></div>';

    return (setupTableSorterChecked(selector, true, 10, pager));
};

window.setupTableSorterDplanner = function (selector) {
    return (setupTableSorterCheckedDplanner(selector, false));
};

window.enableDateRangePicker = function (selector, callback, options) {
    // Date-Range-Picker
    if (!$.isFunction(callback)) {
        callback = function () {
            //console.log("Date-Range-Picker Callback", this, arguments);
        }
    }
    if (!$.isPlainObject(options)) {
        options = {};
    }

    try {
        var $dateRangeSelector = $(selector || this).daterangepicker($.extend({
            opens: 'left',
            showTime: true,
            ranges: {
                'Yesterday': [Date.today().setTimeToNow().add({
                    days: -1
                }), "now"],
                '1 week': [Date.today().setTimeToNow().add({
                    weeks: -1
                }), "now"],
                '1 month': [Date.today().setTimeToNow().add({
                    months: -1
                }), "now"],
                '2 months': [Date.today().setTimeToNow().add({
                    months: -2
                }), "now"],
                '3 months': [Date.today().setTimeToNow().add({
                    months: -3
                }), "now"]
            }
        }, options), callback);

        ($dateRangeSelector.length > 0) && callback.call();
    } catch (err) {
        console.error("DateRangePicker error", err.message, err.stack);
    }
};

(function ($) {
    "use strict";
    // jQuery.bootstrapTabManager
    // v1.1.0
    // Author: Sathvik, Doers' Guild
    function bootstrapTabManager(selector, options) {
        // Constructor
        var self, $container;
        self = this;
        if (!$.isPlainObject(options)) {
            options = {};
        }
        self.options = $.extend(true, {
            tabs: [],
            container: selector,
            navClass: "nav-pills",
            debug: false,
            addTabButtonEnabled: false,
            keepMarkup: false
        }, options);

        $container = $(self.options.container);
        if ($container.length === 0) {
            $container = $("body");
        }

        function generateRandomID() {
            return Math.round(Math.random() * 1000000000);
        }


        self.addTab = function (tab, index) {
            // Add a new tab
            tab = $.extend(true, {
                title: '',
                content: '',
                id: 'bootstrapTabManager_tab_' + index + generateRandomID(),
                enableCloseButton: true,
                activate: false
            }, tab);
            self.options.debug && console.log("bootstrapTabManager - Adding tab: ", tab);
            while ($("#" + tab.id).length > 0) {
                // Prevent duplicate tab IDs
                tab.id = tab.id + index + generateRandomID()
            }
            var navMarkup = '<li data-id="' + tab.id + '">';
            if (tab.enableCloseButton) {
                navMarkup += '<button class="close" >&times;</button>';
            }
            navMarkup += '<a href="#' + tab.id + '">' + tab.title + '</a>';
            navMarkup += '</li>';
            self.$tabNav.append(navMarkup);
            var $content = $('<div class="tab-pane" id="' + tab.id + '" />').appendTo(self.$tabContent);
            $content.append(tab.content);
            if (tab.activate) {
                self.activateTab(tab.id);
            }
            return tab.id;
        };

        self.addTabs = function (tabs) {
            // Add multiple new tabs
            $.map(tabs, self.addTab);
            self.options.debug && console.log("bootstrapTabManager - Adding tabs: ", tabs);
        };

        self.activateTab = function (id) {
            self.$tabNav.find('li[data-id="' + id + '"]').trigger("click.bootstrapTabManager");
        };

        self.updateTab = function (tab) {
            self.removeTab(tab.id);
            self.addTab(tab, 0);
            self.activateTab(tab.id);
        };

        self.removeTab = function (id) {
            // Remove tab with given ID
            self.$tabNav.children('[data-id="' + id + '"]').remove();
            self.$tabContent.find('#' + id).remove();
            // Go to previous tab
            var $prevTab = $(self.previousTab);
            if ($prevTab.length === 0 || !$prevTab.is(":visible")) {
                $prevTab = self.$tabNav.children().not(".helper-tab").first();
            }
            $prevTab.trigger("click.bootstrapTabManager");
            self.options.debug && console.log("bootstrapTabManager - Removing tab: ", id);
        };

        self.destroy = function () {
            self.options.debug && console.log("bootstrapTabManager - Destroy: ", this);
            $container.html("");
        };

        self.checkNavArrows = function () {
            // SHow/hide arrows based on position
            var scrollPos = self.$tabNav.scrollLeft(),
                containerWidth = self.$tabNav.width(),
                innerWidth = $.reduce($.map(self.$tabNav.children(), function (item) {
                    // @TODO avoid doing this every single time
                    return $(item).outerWidth();
                }), 0, function (a, i, b) {
                    return a + b;
                });
            if (scrollPos === innerWidth - containerWidth) {
                self.$tabNavArrowWrapper.find('.js-tab-nav-scroll-right').fadeOut('slow');
            } else {
                self.$tabNavArrowWrapper.find('.js-tab-nav-scroll-right').fadeIn('fast');
            }
            if (scrollPos <= 0) {
                self.$tabNavArrowWrapper.find('.js-tab-nav-scroll-left').fadeOut('slow');
            } else {
                self.$tabNavArrowWrapper.find('.js-tab-nav-scroll-left').fadeIn('fast');
            }
        };

        self.redraw = function () {
            // Constructor
            self.options.debug && console.log("bootstrapTabManager - Re-Draw: ", this);
            if (!self.options.keepMarkup) {
                $container.empty();
                self.$tabNav = $('<ul class="nav ' + self.options.navClass + '"/>').appendTo($container);
                self.$tabContent = $('<div class="tab-content"></div>').appendTo($container);
            } else {
                self.$tabNav = $container.find("ul.nav");
                self.$tabContent = $container.find(".tab-content");
            }
            self.$tabNavArrowWrapper = self.$tabNav.parent();
            if (!self.$tabNavArrowWrapper.is('.nav-arrow-wrapper')) {
                self.$tabNav.wrap('<div class="nav-arrow-wrapper" />');
                self.$tabNavArrowWrapper = self.$tabNav.parent();
                self.$tabNavArrowWrapper.prepend('<button type="button" class="btn btn-medium pull-left js-tab-nav-scroll-left"><i class="icon icon-arrow-left"></i></button>');
                self.$tabNavArrowWrapper.prepend('<button type="button" class="btn btn-medium pull-right js-tab-nav-scroll-right"><i class="icon icon-arrow-right"></i></button>');
            }
            if (self.options.addTabButtonEnabled) {
                var navMarkup = '<li title="Add New Tab" class="helper-tab">';
                navMarkup += '<a><i class="icon-plus"></i></a>';
                navMarkup += '</li>';
                var $plusBtn = $(navMarkup).prependTo(self.$tabNav);
                $plusBtn.add($plusBtn.find("a")).on("click", function (e) {
                    e.preventDefault();
                    $container.trigger("newTab.bootstrapTabManager");
                    return false;
                });
            }
            // Add tabs that the user selected
            self.addTabs(self.options.tabs);
            // Setup events
            self.$tabNavArrowWrapper.on('click.bootstrapTabManager', '.js-tab-nav-scroll-right', function () {
                self.$tabNav.scrollLeft(self.$tabNav.scrollLeft() + 120);
                self.checkNavArrows();
            });
            self.$tabNavArrowWrapper.on('click.bootstrapTabManager', '.js-tab-nav-scroll-left', function () {
                self.$tabNav.scrollLeft(self.$tabNav.scrollLeft() - 120);
                self.checkNavArrows();
            });
            self.$tabNav.on("click.bootstrapTabManager", "li, a", function (e) {
                e.preventDefault();
                self.options.debug && console.log("bootstrapTabManager - Showing tab: ", this);
                var $this = $(this);
                if ($this.is("li")) {
                    $this = $this.find("a");
                }
                self.previousTab = $this;
                $this.tab('show').addClass("active").closest("li").addClass("active");
            });
            self.$tabNav.on("click.bootstrapTabManager", ".close", function (e) {
                var evt = jQuery.Event("closeTab.bootstrapTabManager"),
                    $li = $(this).closest("li"),
                    tabID = $li.attr("data-id");
                $container.trigger(evt, tabID, $li.text());
                if (!evt.isDefaultPrevented()) {
                    e.preventDefault();
                    self.options.debug && console.log("bootstrapTabManager - Removing tab: ", this);
                    self.removeTab(tabID);
                }
            });
            // Activate 1st tab
            self.$tabNav.children().not(".helper-tab").first().find("a").trigger("click.bootstrapTabManager");
            // Setup scroll arrows
            self.checkNavArrows();
        };

        self.redraw();

        return self;
    };


    $.fn.bootstrapTabManager = function (method, options) {
        var isMethod, globalArgs;

        // Check if the first parameter is a method
        isMethod = $.type(method) === "string";

        globalArgs = arguments;

        // Support for chaining
        return this.each(function () {
            var $this, bootstrapTabManagerObject

            // Convert to jQuery object
            $this = $(this);

            // Get the bootstrapTabManager object if already created
            bootstrapTabManagerObject = $this.data("bootstrapTabManager");

            if (isMethod) {
                // The first argument is a string => Assume to be a method name
                if (!bootstrapTabManagerObject) {
                    throw new Error("bootstrapTabManager: Cannot call " + method + " before the tab-manager is created");
                } else {
                    switch (method) {
                    case "destroy":
                        // Destroy the slider
                        bootstrapTabManagerObject = null;
                        // Remove events
                        $this.off(".bootstrapTabManager");
                    default:
                        // Call a public method of the bootstrapTabManager object
                        if (bootstrapTabManagerObject.hasOwnProperty(method)) {
                            // Call the selected method with the given parameters
                            bootstrapTabManagerObject[method].apply(this, Array.prototype.slice.call(globalArgs, 1));
                        } else {
                            // Throw an error
                            throw new Error("bootstrapTabManager: Unknown method - " + method);
                        }
                        break;
                    }
                }
            } else {
                // Assume the first parameter to be the options if it isn't a method
                options = method || {};
                // Create a new bootstrapTabManager object
                bootstrapTabManagerObject = new bootstrapTabManager($this, options);
                $this.data("bootstrapTabManager", bootstrapTabManagerObject);
            }

            //Update the object
            $this.data("bootstrapTabManager", bootstrapTabManagerObject);
        });
    };

})(jQuery);

function generatePieChart(data, options) {
    if (!$.isArray(data)) {
        if (!$.isPlainObject(data)) {
            data = $.makeArray(data);
        } else {
            try {
                data = $.parseJSON(data);
            } catch (err) {
                console.error(data, "generatePieChart error", err.message, err.stack);
                data = [];
            }
        }
    }
    var chart1;

    if (!$.isPlainObject(options)) {
        options = {};
    }
    options = $.extend(true, {
        width: 500,
        height: 300,
        container: "#svgChart",
        title: "",
        titleStyle: {
            x: 100,
            y: 40,
            fontFamily: "sans-serif",
            fontSize: "20px",
            fill: "black"
        },
        titleKey: 'title',
        countKey: 'total',
        colorKey: 'color',
        legendLimit: 8,
        showLegend: true,
        showLabels: true,
        forcePercent: false
    }, options);

    nv.addGraph(function () {
        var colorIndex = 0;
        chart1 = nv.models.pieChart().x(function (d) {
            return d[options.titleKey] || d.code || "";
        }).y(function (d) {
            return d[options.countKey] || d.fixedCost || 0;
        }).color(function (d) {
            return d[options.colorKey] || nv.utils.defaultColor()({}, colorIndex++);
        }).values(function (d) {
            return d;
        }).showLabels(options.showLabels).labelType(options.forcePercent || data.length <= options.legendLimit ? 'percent' : 'key').showLegend(options.showLegend && data.length <= options.legendLimit);
        if (options.width) {
            chart1 = chart1.width(options.width);
        }
        if (options.height) {
            chart1 = chart1.height(options.height);
        }

        var selectedChart = d3.select(options.container).datum(data).transition().duration(1200);
        if (options.width) {
            selectedChart = selectedChart.attr('width', options.width);
        }
        if (options.height) {
            selectedChart = selectedChart.attr('height', options.height);
        }

        //d3.select(options.container).append("text").attr("x", options.titleStyle.x).attr("y", options.titleStyle.y).attr("font-family", options.titleStyle.fontFamily).attr("font-size", options.titleStyle.fontSize).attr("fill", options.titleStyle.fill).text(options.title);
        $(options.container).css({
            height: options.height || 400,
            width: options.width || "100%",
            "max-width": "100%",
            "max-height": "100%",
            "margin": "auto"
        }).parent().css("text-align", "center").prepend('<h5 class="text-center" >' + options.title + '</h5>');


        selectedChart = selectedChart.call(chart1);

        chart1.dispatch.on('stateChange', function (e) {
            console.log('New State:', e);
        });

        nv.utils.windowResize(function () {
            chart1.update();
        });

        return chart1;
    });

    return chart1;
};

function generateBarChart(data, options) {
    if (!$.isArray(data)) {
        if (!$.isPlainObject(data)) {
            data = $.makeArray(data);
        } else {
            try {
                data = $.parseJSON(data);
            } catch (err) {
                console.error(data, "generateBarChart error", err.message, err.stack);
                data = [];
            }
        }
    }
    var chart1;

    if (!$.isPlainObject(options)) {
        options = {};
    }
    options = $.extend(true, {
        container: "#svgChart",
        title: "",
        titleStyle: {
            x: 500,
            y: 200,
            fontFamily: "sans-serif",
            fontSize: "20px",
            fill: "black"
        }
    }, options);

    nv.addGraph(function () {
        chart1 = nv.models.discreteBarChart().x(function (d) {
            return d.label || ""
        }).y(function (d) {
            return d.value || 0
        }).staggerLabels(true).tooltips(true).showValues(true);

        var selectedChart = d3.select(options.container).datum(renderAnalytics(data)).transition().duration(500);

        $(options.container).css({
            height: options.height,
            width: options.width
        }).parent().css("text-align", "center").prepend('<h5 class="text-center" >' + options.title + '</h5>');

        selectedChart = selectedChart.call(chart1);

        nv.utils.windowResize(function () {
            chart1.update();
        });

        return chart1;
    });

    return chart1;
};

function generateStackedAreaChart(data, options) {
    // Generate a stacked Area chart : Expected data http://jsfiddle.net/4A6F5/7/
    if (!$.isArray(data)) {
        if ($.isPlainObject(data)) {
            data = $.makeArray(data);
        } else {
            try {
                data = $.parseJSON(data);
            } catch (err) {
                console.error(data, "generateStackedAreaChart data error", err.message, err.stack);
                data = [];
            }
        }
    }

    var chart1;

    if (!$.isPlainObject(options)) {
        options = {};
    }
    options = $.extend(true, {
        width: 500,
        height: 300,
        container: "#svgChart",
        title: "",
        titleStyle: {
            x: 100,
            y: 40,
            fontFamily: "sans-serif",
            fontSize: "20px",
            fill: "black"
        },
        debug: false,
        xDataLabel: 0,
        yDataLabel: 1,
        xAxisTickFormatter: function (d) {
            var date = new Date(d);
            options.debug && console.log("generateStackedAreaChart: xAxis tick", d, date);
            return d3.time.format('%d %b %y')(date);
        },
        yAxisTickFormatter: function (d) {
            options.debug && console.log("generateStackedAreaChart: yAxis tick", d);
            return '$' + d3.format(',.2f').apply(this, arguments);
        }
    }, options);

    options.debug && console.log("generateStackedAreaChart: data", data);
    options.debug && console.log("generateStackedAreaChart: options", options);

    nv.addGraph(function () {

        chart1 = nv.models.stackedAreaChart().x(function (d) {
            options.debug && console.log("generateStackedAreaChart: xAxis", d);
            return d[options.xDataLabel];
        }).y(function (d) {
            options.debug && console.log("generateStackedAreaChart: yAxis", d);
            return d[options.yDataLabel];
        }).clipEdge(true);

        chart1.xAxis.tickFormat(options.xAxisTickFormatter);
        chart1.yAxis.tickFormat(options.yAxisTickFormatter);

        var selectedChart = d3.select(options.container).datum(data).transition().duration(450);

        if (options.width) {
            chart1 = chart1.width(options.width);
            selectedChart = selectedChart.attr('width', options.width);
        }
        if (options.height) {
            chart1 = chart1.height(options.height);
            selectedChart = selectedChart.attr('height', options.height);
        }

        selectedChart = selectedChart.call(chart1);

        var $parent = $(options.container).css({
            height: options.height || "auto",
            width: options.width || "100%",
            "max-width": "100%",
            "max-height": "100%",
            "margin": "auto"
        }).parent().css("text-align", "center");

        var $chartTitle = $parent.children('.js-chart-title');
        if ($chartTitle.length === 0) {
            $parent.prepend('<h5 class="text-center js-chart-title" >' + options.title + '</h5>');
        } else {
            $chartTitle.text(options.title);
        }

        nv.utils.windowResize(function () {
            chart1.update();
        });

        return chart1;
    });

    return chart1;
};

function generateMultibarChart(data, options) {
    // Generate a multibar chart
    if (!$.isArray(data)) {
        if ($.isPlainObject(data)) {
            data = $.makeArray(data);
        } else {
            try {
                data = $.parseJSON(data);
            } catch (err) {
                console.error(data, "generateMultibarChart data error", err.message, err.stack);
                data = [];
            }
        }
    }

    var chart1;

    if (!$.isPlainObject(options)) {
        options = {};
    }
    options = $.extend(true, {
        width: 500,
        height: 600,
        container: "svg",
        title: "",
        titleStyle: {
            x: 100,
            y: 40,
            fontFamily: "sans-serif",
            fontSize: "20px",
            fill: "black"
        },
        debug: false,
        xDataLabel: 'x',
        yDataLabel: 'y',
        xAxisTickFormatter: function (d) {
            options.debug && console.log("generateMultibarChart: xAxis tick", d, new Date(d));
            return d3.time.format('%d %b \'%y')(new Date(d));
        },
        yAxisTickFormatter: function (d) {
            options.debug && console.log("generateMultibarChart: yAxis tick", d);
            return '$' + d3.format(',.2f').apply(this, arguments);
        }
    }, options);

    options.debug && console.log("generateMultibarChart: data", data);
    options.debug && console.log("generateMultibarChart: options", options);

    nv.addGraph(function () {

        chart1 = nv.models.multiBarChart().x(function (d) {
            options.debug && console.log("generateMultibarChart: xAxis", d);
            return parseFloat(d[options.xDataLabel]) || 0;
        }).y(function (d) {
            options.debug && console.log("generateMultibarChart: yAxis", d);
            return parseFloat(d[options.yDataLabel]) || 0;
        }).stacked(true).reduceXTicks(true);

        chart1.xAxis.tickFormat(options.xAxisTickFormatter);
        chart1.yAxis.tickFormat(options.yAxisTickFormatter);

        var selectedChart = d3.select(options.container).datum(data).transition().duration(450);

        if (options.width) {
            chart1 = chart1.width(options.width);
            selectedChart = selectedChart.attr('width', options.width);
        }
        if (options.height) {
            chart1 = chart1.height(options.height);
            selectedChart = selectedChart.attr('height', options.height);
        }
        selectedChart = selectedChart.call(chart1);

        $(options.container).css({
            height: options.height || "auto",
            width: options.width || "100%",
            "max-width": "100%",
            "max-height": "100%",
            "margin": "auto"
        }).parent().css("text-align", "center").prepend('<h5 class="text-center" >' + options.title + '</h5>');

        nv.utils.windowResize(function () {
            chart1.update();
        });

        return chart1;
    });

    return chart1;
};
window.renderAnalytics = function (data) {
    return [{
        key: "Server Cost",
        values: data
    }];
};

function getFormattedDateFromTimestamp(timestamp, format, isSeconds) {
    format = format || "dd/MM/yyyy";
    timestamp = parseInt(timestamp, 10);
    if (isSeconds)
        timestamp = timestamp * 1000;
    return (new Date(timestamp)).toString(format);
};

function generateRandomID() {
    "use strict";
    return Math.round(Math.random() * 1000000000);
};


!(function ($) {
    "use strict";

    function setupToggleColumnsContextMenu(selector, options) {
        // Create a context-menu for showing &/ hiding columns
        var $table, contextMenuData, prototype;
        prototype = this;
        $table = $(selector);
        if (!$table.is("table")) {
            $table = $table.closest("table");
        }
        if ($table.length === 0) {
            // Throw an error
            throw new Error("setupToggleColumnsContextMenu: The given element is not a table - " + selector);
        }
        if (!$.isPlainObject(options)) {
            options = {};
        }
        prototype.options = $.extend(true, {
            headerRow: false
        }, options);
        prototype.id = $table.prop("id") || generateRandomID();

        // Create context menu
        contextMenuData = '<div id="tableContext_' + prototype.id + '" class="table-context-menu-holder"><ul class="dropdown-menu" role="menu" style="z-index: 99999;">';
        prototype.$headerRow = $table.find(prototype.options.headerRow);
        if (prototype.$headerRow.length === 0) {
            prototype.$headerRow = $table.find("tr").first();
        }
        prototype.$headerRow.children().each(function (index) {
            var $th = $(this),
                title = $th.text() || "Column_" + index;
            contextMenuData += '<li><a tabindex="-1" class="table-context-heading" data-column-index="' + (index + 1) + '"><i class="pull-left table-context-visiblity-mark icon-eye-open"></i> ' + title + '</a></li>';
        });
        contextMenuData += '</ul></div>';
        prototype.$contextMenu = $(contextMenuData).appendTo("body").css("z-index", 99999);
        $table.contextmenu({
            target: "#tableContext_" + prototype.id
        });

        // Setup show/hide column
        prototype.$contextMenu.find(".table-context-heading").on("click", function () {
            try {
                var $this = $(this),
                    column = $this.attr("data-column-index");
                $table.find('td:nth-child(' + column + '),th:nth-child(' + column + ')').fadeToggle("fast");
                $this.find(".table-context-visiblity-mark").toggleClass("icon-eye-open").toggleClass("icon-eye-close");
            } catch (err) {
                console.error("setupToggleColumnsContextMenu: Context action error", err.message, err.stack);
            }
        });

        return prototype;
    }


    $.fn.setupToggleColumnsContextMenu = function (options) {
        return this.each(function () {
            var prototypeObject = new setupToggleColumnsContextMenu(this, options);
            $(this).data("setupToggleColumnsContextMenu", prototypeObject)
        });
    };
})(jQuery);

function makeObject(obj) {
    return $.isPlainObject(obj) ? obj : {};
};

function generateFormFromJSON(data, formAttributes) {
    "use strict";
    // Return the markup for the form based on this data
    /*
     * Data is an array of field descriptors
     *
     * Common:
     *  attributes - plain object mapping of attr
     *  type - string
     *  fields - for radio & checkbox
     *
     */

    if ($.type(data) === 'string') {
        data = $.parseJSON(data);
    }

    function makeAttributeString(attributes) {
        attributes = $.isPlainObject(attributes) ? attributes : {};
        var attr = [];
        for (var attribute in attributes) {
            attributes.hasOwnProperty(attribute) && attr.push(attribute + '=' + JSON.stringify(attributes[attribute]));
        }
        return " " + attr.join(" ") + " ";
    }

    data = $.makeArray(data);
    var markup = "";
    formAttributes = $.extend(true, {
        class: "form-horizontal text-left"
    }, makeObject(formAttributes));
    markup += '<form ' + makeAttributeString(formAttributes) + '><fieldset>';
    for (var i = 0, len = data.length; i < len; i++) {
        var field = data[i];
        field = makeObject(field);
        field.attributes = makeObject(field.attributes || $.extend({}, field));
        var id = field.attributes.id || ("field_" + field.type + "_" + generateRandomID());
        switch (field.type) {
        case 'legend':
            markup += '<legend ' + makeAttributeString(field.attributes) + ' >' + field.text + '</legend>';
        case 'select':
            var attributes = makeAttributeString($.extend(true, {
                id: id,
                class: "input-xlarge"
            }, field.attributes));
            markup += '<div class="control-group">';
            if (field.label) {
                markup += '<label class="control-label" for="' + id + '">' + field.label + '</label>';
            }
            markup += '<div class="controls">';
            markup += '<select ' + attributes + ' >';
            field.fields = $.makeArray(field.fields);
            if (field.attributes.placeholder) {
                markup += '<option value="" >' + field.attributes.placeholder + '</option>';
            }
            var thisField = makeObject(field.fields);
            thisField.attributes = makeObject(thisField.attributes || $.extend({}, thisField));
            thisField.attributes.value = $.makeArray(thisField.attributes.value);
            for (var j = 0, atLen = field.fields.length; j < atLen; j++) {
                var thisField = makeObject(field.fields[j]);
                thisField.attributes = makeObject(thisField.attributes || $.extend({}, thisField));
                var thisId = thisField.attributes.id || id + "_option_" + j;
                var attributes = makeAttributeString($.extend(true, {}, field.attributes, {
                    id: thisId,
                    class: ""
                }, thisField.attributes));
                if ($.inArray(field.attributes.value, thisField.attributes.value) > -1) {
                    attributes.selected = 'selected';
                }
                markup += '<option ' + attributes + ' >' + thisField.label + '</option>';
            }
            markup += '</select>';
            if (field.helpText) {
                markup += '<p class="help-block">' + helpText + '</p>';
            }
            markup += '</div></div>';
            break;
        case 'textarea':
            var attributes = makeAttributeString($.extend(true, {
                id: id,
                class: "input-xlarge"
            }, field.attributes));
            markup += '<div class="control-group">';
            if (field.label) {
                markup += '<label class="control-label" for="' + id + '">' + field.label + '</label>';
            }
            markup += '<div class="controls">';
            markup += '<textarea ' + attributes + ' >' + (field.attributes.placeholder || "") + '</textarea>';
            if (field.helpText) {
                markup += '<p class="help-block">' + helpText + '</p>';
            }
            markup += '</div></div>';
            break;
        case 'radio':
        case 'checkbox':
            markup += '<div class="control-group">';
            if (field.label) {
                markup += '<label class="control-label" for="' + id + '">' + field.label + '</label>';
            }
            markup += '<div class="controls">';
            field.fields = $.makeArray(field.fields);
            for (var j = 0, atLen = field.fields.length; j < atLen; j++) {
                var thisField = makeObject(field.fields[j]);
                thisField.attributes = makeObject(thisField.attributes || $.extend({}, thisField));
                thisField.attributes.type = thisField.attributes.type || field.attributes.type || field.type;
                var thisId = thisField.attributes.id || thisField.attributes.type + "_" + j + "_" + generateRandomID();
                var attributes = makeAttributeString($.extend(true, {}, field.attributes, {
                    id: thisId,
                    class: "",
                    type: field.type
                }, thisField.attributes));
                markup += '<label class="' + field.type + '" for="' + thisId + '"><input ' + attributes + ' >' + thisField.label + '</label>';
            }
            if (field.helpText) {
                markup += '<p class="help-block">' + helpText + '</p>';
            }
            markup += '</div></div>';
            break;
        case 'buttons':
            markup += '<div class="control-group">';
            if (field.label) {
                markup += '<label class="control-label" for="' + id + '">' + field.label + '</label>';
            }
            markup += '<div class="controls">';
            field.fields = $.makeArray(field.fields);
            for (var j = 0, atLen = field.fields.length; j < atLen; j++) {
                var thisField = makeObject(field.fields[j]);
                thisField.attributes = makeObject(thisField.attributes || $.extend({}, thisField));
                thisField.attributes.type = thisField.attributes.type || field.attributes.type || field.type;
                var thisId = thisField.attributes.id || thisField.attributes.type + "_" + j + "_" + generateRandomID();
                var attributes = makeAttributeString($.extend(true, {}, field.attributes, {
                    id: thisId,
                    class: "btn",
                    type: field.type
                }, thisField.attributes));
                markup += '<input ' + attributes + ' >';
            }
            if (field.helpText) {
                markup += '<p class="help-block">' + helpText + '</p>';
            }
            markup += '</div></div>';
            break;
        case 'text':
        case 'password':
        default:
            var attributes = makeAttributeString($.extend(true, {
                id: id,
                type: field.type,
                class: "input-xlarge"
            }, field.attributes));
            markup += '<div class="control-group">';
            if (field.label) {
                markup += '<label class="control-label" for="' + id + '">' + field.label + '</label>';
            }
            markup += '<div class="controls">';
            markup += '<input ' + attributes + ' >';
            if (field.helpText) {
                markup += '<p class="help-block">' + helpText + '</p>';
            }
            markup += '</div></div>';
            break;
        }
    }
    markup += '</fieldset></form>';
    return markup;
};

function generateMultiRowForm(selector, rowFormat, data, jsonInputName, deleteButtonFormat) {
    "use strict";
    // A multi-row form

    //console.log("generateMultiRowForm", arguments);

    if ($.type(rowFormat) === 'string') {
        rowFormat = $.parseJSON(rowFormat);
    }
    rowFormat = $.makeArray(rowFormat);
    // Add delete-row button
    rowFormat.push($.extend({
        label: '',
        type: 'buttons',
        fields: [{
            class: 'deleteSG',
            role: 'button',
            type: 'button'
        }]
    }, deleteButtonFormat || {}));

    if ($.type(data) === 'string') {
        data = $.parseJSON(data);
    }
    data = $.makeArray(data || []);

    function addNewRow() {
        return generateFormFromJSON(rowFormat, {
            class: 'form-table-row js-row'
        }).replace(/(\<\s*\/?\s*)form(\s+)/ig, '$1div$2');
    }

    var $container = $(selector),
        markup = '<div>';
    markup += '<div class="form-table js-row-wrapper">';
    markup += '</div>';
    markup += '<div class="text-center">';
    markup += '<button type="button role="button" class="btn js-add-row">Add Row</button>';
    markup += '</div>';
    if (jsonInputName) {
        markup += '<input type="hidden" name="' + jsonInputName + '" value="' + JSON.stringify(data) + '" class="js-json-formatted-data" />'
    }
    markup += '</div>';
    $container.html(markup);

    // Add existing data
    for (var i = 0, l = data.length; i < l; i++) {
        var row = makeObject(data[i]);
        var $row = $(addNewRow()).appendTo($container.find('.js-row-wrapper'));
        for (var name in row) {
            if (!row.hasOwnProperty(name)) {
                continue;
            }
            var $input = $row.find('[name="' + name + '"]');
            if ($input.length === 0) {
                $input = $row.find('[label="' + name + '"]');
            }
            $input.val(row[name]);
        }
    }

    // New empty row
    $container.find('.js-row-wrapper').append(addNewRow());

    $container.on("click", ".js-add-row", function (e) {
        e.preventDefault();
        $container.find('.js-row-wrapper').append(addNewRow());
        return false;
    }).on("click", ".deleteSG", function (e) {
        e.preventDefault();
        var $this = $(this);
        bootbox.confirm('Are you sure you want to delete these fields?', function (yes) {
            if (yes) {
                $this.closest('.js-row').remove();
            }
        });
        return false;
    });

    if (jsonInputName) {
        $container.on("change", ":input", function () {
            //console.log("Form Input changed", this);
            var $rows = $container.find('.js-row');
            var data = [];
            $rows.each(function () {
                var $row = $(this),
                    row = {};
                $row.find(":input").each(function () {
                    var $input = $(this),
                        key = $input.prop("name") || $input.attr("label");
                    row[key] = $input.val();
                });
                data.push(row);
            });
            //console.log("Form data", data);
            $container.find('.js-json-formatted-data').val(JSON.stringify(data));
        });
    }
};

(function ($) {
    "use strict";
    $.fn.setFormValues = function (data) {
        // Set values to form elements
        if ($.type(data) === 'string') {
            data = $.parseJSON(data);
        }
        return $(this).each(function () {
            var $this = $(this);
            for (var name in data) {
                if (!data.hasOwnProperty(name)) {
                    continue;
                }
                var value = data[name];
                $this.find('[name="' + name + '"]').each(function () {
                    var $field = $(this);
                    if ($field.is('[type="radio"]') || $field.is('[type="checkbox"]') || $field.is('option')) {
                        var thisVal = $field.val();
                        $field.prop('selected', !!(thisVal === value || $.inArray(thisVal, value) > -1));
                        $field.prop('checked', $field.prop('selected'));
                    } else {
                        $field.val(value);
                    }
                }).trigger('change');
            }
        });
    }
})(jQuery);

/* For sorting and Pagination*/
function sorting() {

    var asInitVals = new Array();
    var oTable1 = $('#TableContainer1').dataTable({
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sSearch": "Search all columns:"
        },
        "iDisplayLength": 5
    });

    $("th input").keyup(function () {
        /* Filter on the column (the index) of this element */
        oTable1.fnFilter(this.value, $("th input").index(this));
    });

    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
     * the footer
     */
    $("th input").each(function (i) {
        asInitVals[i] = this.value;
    });

    $("th input").focus(function () {
        if (this.className == "search_init") {
            this.className = "";
            this.value = "";
        }
    });

    $("th input").blur(function (i) {
        if (this.value == "") {
            this.className = "search_init";
            this.value = asInitVals[$("th input").index(this)];
        }
    });

    $('.showmoreBtn').toggle(function show() {
        oSettings1 = oTable1.fnSettings();
        oSettings1._iDisplayLength = -1;
        oTable1.fnDraw();
        $(this).addClass('hideBtn');
    }, function hide() {
        oSettings1._iDisplayLength = 5;
        oTable1.fnDraw();
        $(this).removeClass('hideBtn');
    });

    $(".paginate_button_disabled.first,.paginate_button.next,.paginate_button_disabled.previous,.paginate_button.last").text("");

};

var show_message = function (title, message, type, redirectUrl) {
    // Show a pnotify message and optionally redirect the user
    var ret = '';
    var redirectFlag = false;
    type = type || 'warning';
    switch (type.toLowerCase()) {
    case 'error':
        ret = 'icon-error-sign';
        break;
    case 'success':
    case 'ok':
        ret = 'icon-success-sign';
        redirectFlag = true;
        type = "success";
        break;
    case 'info':
        ret = 'icon-info-sign';
        redirectFlag = true;
        break;
    }
    $.pnotify({
        title: title,
        text: message,
        type: type,
        icon: ret
    });
    if (redirectUrl && redirectFlag) {
        setTimeout(function () {
            window.location = redirectUrl;
        }, 1200);
    }
};

window.show_confirm_modal = function (title, message, confirm, cancel, callback) {
    // Bootbox.confirm with xervmon logo in title
    var $wrapper = $('<div style="margin:-15px"/>'),
        $modalBody = $('<div class="modal-body" />'),
        $message = $(message).appendTo($modalBody);
    var titleMarkup = '<div class="modal-header" style="background: #0D3144;color: white;">';
    // titleMarkup += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    titleMarkup += '<h3><img alt="Xervmon" class="img-tiny" src="' + SITE_URL + '../addons/shared_addons/themes/xervmon/img/logo.png"> &nbsp; ' + title + '</h3>';
    titleMarkup += '</div>';
    $wrapper.append(titleMarkup);
    $wrapper.append($modalBody);
    var btns = [];
    if (cancel) {
        btns.push({
            label: cancel,
            class: "btn-default",
            callback: function () {
                try {
                    callback(false);
                } catch (err) {
                    console.warn(err.message, err.stack);
                }
            }
        });
    }
    if (confirm) {
        btns.push({
            label: confirm,
            class: "btn-success",
            callback: function () {
                try {
                    callback(true);
                } catch (err) {
                    console.warn(err.message, err.stack);
                }
            }
        });
    }
    return bootbox.dialog($wrapper, btns);
};
window.show_alert_modal = function (title, message, callback) {
    // Bootbox.alert with xervmon logo in title
    var $wrapper = $('<div style="margin:-15px"/>'),
        $modalBody = $('<div class="modal-body" />'),
        $message = $(message).appendTo($modalBody);
    var titleMarkup = '<div class="modal-header" style="background: #0D3144;color: white;">';
    // titleMarkup += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    titleMarkup += '<h3><img alt="Xervmon" class="img-tiny" src="' + SITE_URL + '../addons/shared_addons/themes/xervmon/img/logo.png"> &nbsp; ' + title + '</h3>';
    titleMarkup += '</div>';
    $wrapper.append(titleMarkup);
    $wrapper.append($modalBody);
    return bootbox.dialog($wrapper, [{
        label: "Close",
        class: "btn-default",
        callback: function () {
            try {
                callback();
            } catch (err) {
                console.warn(err.message, err.stack);
            }
        }
    }]);
};

;
!(function ($) {
    "use strict";
    // Wrapper around the jQuery validate plugin to support Chosen selects
    $.fn.validateForm = function (ignore) {
        return this.each(function () {
            var $this = $(this);
            if (!$this.is("form")) {
                $this = $this.closest("form");
            }
            try {
                $.validator.setDefaults({
                    ignore: ignore || ":hidden:not(select)"
                });
                $this.validate({
                    ignore: ignore || ":hidden:not(select)"
                });
            } catch (e) {
                console.warn("Validate failed", e.message, e.stack);
            }
            $this.on("submit", function (e) {
                if (!$(this).valid()) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    };
})(jQuery);

function setupBubbleMap(container, data, options, popupTemplate) {
    "use strict";
    // Setup a bubble map on the given container
    options = $.extend(true, {
        element: $(container).html("").get(0),
        scope: 'world',
        geographyConfig: {
            popupOnHover: false,
            highlightOnHover: false
        },
        fills: {
            defaultFill: 'rgba(240,240,240,0.8)'
        },
        data: {}
    }, options || {});
    var bombMap = new Datamap(options);
    //draw bubbles
    bombMap.bubbles(data, {
        popupTemplate: popupTemplate || (function (geo, data) {
            return JSON.stringify(data);
        })
    });
    $(window).on("resize orientationchange", function () {
        setupBubbleMap(container, data, options, popupTemplate);
    });
    return bombMap;
};

function str_ireplace(search, replace, subject) {
    var i, k = '';
    var searchl = 0;
    var reg;

    var escapeRegex = function (s) {
        return s.replace(/([\\\^\$*+\[\]?{}.=!:(|)])/g, '\\$1');
    };

    search += '';
    searchl = search.length;
    if (Object.prototype.toString.call(replace) !== '[object Array]') {
        replace = [replace];
        if (Object.prototype.toString.call(search) === '[object Array]') {
            // If search is an array and replace is a string,
            // then this replacement string is used for every value of search
            while (searchl > replace.length) {
                replace[replace.length] = replace[0];
            }
        }
    }

    if (Object.prototype.toString.call(search) !== '[object Array]') {
        search = [search];
    }
    while (search.length > replace.length) {
        // If replace has fewer values than search,
        // then an empty string is used for the rest of replacement values
        replace[replace.length] = '';
    }

    if (Object.prototype.toString.call(subject) === '[object Array]') {
        // If subject is an array, then the search and replace is performed
        // with every entry of subject , and the return value is an array as well.
        for (k in subject) {
            if (subject.hasOwnProperty(k)) {
                subject[k] = str_ireplace(search, replace, subject[k]);
            }
        }
        return subject;
    }

    searchl = search.length;
    for (i = 0; i < searchl; i++) {
        reg = new RegExp(escapeRegex(search[i]), 'gi');
        subject = subject.replace(reg, replace[i]);
    }

    return subject;
};

;
!(function ($) {
    "use strict";
    // jQuery Sparkline helpers
    window.storage = window.storage || {};
    window.storage.sparkLines = window.storage.sparkLines || {};
    window.extendSparklineWithKey = function (key, values, options) {
        // Extend previous sparkline or add new one
        var $this;
        if (key instanceof jQuery) {
            $this = key;
            key = $this.data('sparkline-key');
        } else {
            $this = $('[data-sparkline-key="' + key + '"]');
        }
        window.storage.sparkLines[key] = window.storage.sparkLines[key] || {};
        if (!$.isArray(values)) {
            values = [0];
        }
        if (!$.isPlainObject(options)) {
            options = {};
        }
        var oldValues = window.storage.sparkLines[key].values || [values[0], values[0], values[0]],
            oldOptions = window.storage.sparkLines[key].options || {};
        values = $.merge($.merge([], oldValues), values)
        options = $.extend({}, oldOptions, options);
        $this.sparkline(values, options);
        window.storage.sparkLines[key].values = values;
        window.storage.sparkLines[key].options = options;
        $this.find("canvas").css("min-width", "100%");
    };
    window.prefChartSparklineWithKey = function (key, prefString, options) {
        // Generate a pref chart sparkline given the pref-string
        if ($.type(prefString) !== "string") {
            return false;
        }
        if (!$.isPlainObject(options)) {
            options = {};
        }
        var parts = prefString.split(";");
        var labelAndValue = parts[0].split('=');
        extendSparklineWithKey(key, [parseFloat(labelAndValue[1]) || 0], $.extend(options, {
            tooltipPrefix: labelAndValue[0] + ": ",
            chartRangeMin: parts[3],
            chartRangeMax: parts[4],
            normalRangeMax: parts[1],
            thresholdValue: parts[2]
        }));
    };
    window.prefChartSparklineMultipleFromHolder = function (holder, key) {
        var $this = $(holder),
            prefs = $this.text().trim().split(/\s+/);
        key = key || $this.data('sparkline-key');
        $this.empty();
        for (var i = 0, l = prefs.length; i < l; i++) {
            var thisKey = key + "_" + i;
            $this.append('<label>' + prefs[i].split('=')[0] + ': </label>');
            prefChartSparklineWithKey($('<div data-sparkline-key="' + thisKey + '"></div>').appendTo($this), prefs[i], {
                width: '100%'
            });
        }
        return $this;
    };
})(jQuery);

window.sanitizeID = function (id) {
    "use strict";
    // Return the sanitized form of the given id string
    return (id || '').replace(/\W/ig, '_');
};

window.IsNumeric = function (input) {
    return (input - 0) == input && (input + '').replace(/^\s+|\s+$/g, "").length > 0;
};
// Encode/decode htmlentities : http://css-tricks.com/snippets/javascript/htmlentities-for-javascript/
function krEncodeEntities(s) {
    return $("<div/>").text(s).html();
};

function krDencodeEntities(s) {
    return $("<div/>").html(s).text();
}

;
(function ($) {
    "use strict";

    function cometLoadConstructor($container, options) {
        // Comet load new stuff into the given container
        var prototype = this;
        prototype.lastRequest = false;

        $container = $($container);

        prototype.options = options = $.extend({
            url: null,
            templater: function (obj) {
                // A function that takes an individual message object and returns the message maekup
                return '<p>' + JSON.stringify(obj) + '</p>';
            },
            initialDateToken: 0,
            requestTokenKey: 'token',
            shouldAppend: false,
            method: 'get',
            responseDataKey: 'message',
            responseTokenKey: 'token',
            pollTime: 5000,
            additionalData: {},
            defaultEmptyMessage: '<div class="alert alert-block alert-warning"> No data available </div>',
            highlightClass: 'highlight',
            highlightTime: 5000,
            requestLoadOlderKey: 'loadOlder',
            loadOlderButton: '',
            getTokenAsInteger: function (key) {
                return parseInt(key, 10) || 0;
            }
        }, options || {});

        prototype.lastDateToken = options.initialDateToken;
        prototype.firstDateToken = options.initialDateToken;

        prototype.loadOlder = function () {
            prototype.update(false, true);
        };

        prototype.update = function (firstRun, loadOlder) {
            // Do an ajax call and load new data
            $container.trigger("update.cometLoad", [firstRun, loadOlder]);
            var requestData = $.extend({}, options.additionalData || {});
            requestData[options.requestTokenKey] = prototype.lastDateToken || 0;
            if (loadOlder) {
                requestData[options.requestTokenKey] = prototype.firstDateToken || 0;
                requestData[options.requestLoadOlderKey] = 'true';
            }
            prototype.lastRequest = $.ajax({
                type: options.method,
                url: options.url,
                dataType: 'json',
                data: requestData
            }).always(function () {
                prototype.lastRequest = false;
            }).done(function (response) {
                // Add recieved data into the container
                if ($.isArray(response[options.responseDataKey])) {

                    if (response[options.responseDataKey].length === 0) {
                        if (firstRun) {
                            $container.append(options.defaultEmptyMessage);
                        }
                        if (loadOlder) {
                            $loadOlderButton.prop("disabled", true).addClass("disabled").off("click.cometLoad");
                        }
                    }

                    // if (response[options.responseTokenKey]) {
                    // prototype.lastDateToken = options.getTokenAsInteger(response[options.responseTokenKey]) || prototype.lastDateToken;
                    // }

                    var markup = [],
                        $markup;
                    for (var i = 0, l = response[options.responseDataKey].length; i < l; i++) {
                        try {
                            var item = response[options.responseDataKey][i];
                            markup.push(options.templater.apply(prototype, [item, i]));

                            var intToken = options.getTokenAsInteger(item[options.responseTokenKey]);
                            if (firstRun || loadOlder) {
                                prototype.firstDateToken = (prototype.firstDateToken ? Math.min(intToken, prototype.firstDateToken) : intToken) || prototype.firstDateToken;
                            }
                            if (!response[options.responseTokenKey]) {
                                prototype.lastDateToken = Math.max(intToken, prototype.lastDateToken) || prototype.lastDateToken;
                            }
                        } catch (err) {
                            console.error("cometLoad: Failed to add item", item, err.message, err.stack);
                        }
                    }
                    if (loadOlder ? !options.shouldAppend : options.shouldAppend) {
                        // markup = markup.reverse();
                        console.debug("cometLoad: Appending new data", markup, $container.attr("id"));
                        $markup = $(markup.join('\n')).appendTo($container);
                    } else {
                        console.debug("cometLoad: Prepending new data", markup, $container.attr("id"));
                        $markup = $(markup.join('\n')).prependTo($container);
                    }
                    $markup.addClass('js-comet-item');
                    if (!firstRun) {
                        $markup.addClass(options.highlightClass);
                        setTimeout(function () {
                            $markup.removeClass(options.highlightClass);
                        }, options.highlightTime);
                    }
                    if (response[options.responseDataKey].length) {
                        $container.trigger("updated.cometLoad", [firstRun, loadOlder, response, prototype]);
                    }
                } else {
                    prototype.lastRequest = false;
                    throw new Error("cometLoad: Data from server is not an array");
                }
            }).fail(function () {
                prototype.lastRequest = false;
                throw new Error("cometLoad: Failed to get data from server");
            });
            return prototype.lastRequest;
        };

        // Btn for loading older stuff
        var $loadOlderButton = $(options.loadOlderButton).on('click.cometLoad', prototype.loadOlder);

        // First load
        prototype.update(true);

        // Timer call for loading new ones
        var timerFunc = function () {
            try {
                clearTimeout(prototype.timer);
            } catch (err) {
                console.warn("cometLoad: Failed to clear timeout", item, err.message, err.stack);
            }
            if (!prototype.lastRequest && $container.is(":visible")) {
                // Update only if no requests are pending & container is visible
                prototype.update();
            }
            prototype.timer = setTimeout(timerFunc, options.pollTime);
        };
        prototype.timer = setTimeout(timerFunc, options.pollTime);
    };


    $.fn.cometLoad = function (method, options) {
        // Make it a jQuery plugin
        var isMethod, globalArgs;

        // Check if the first parameter is a method
        isMethod = $.type(method) === "string";

        globalArgs = arguments;

        // Support for chaining
        return this.each(function () {
            var $this, cometObj

            // Convert to jQuery object
            $this = $(this);

            // Get the cometObj object if already created
            cometObj = $this.data("cometLoad");

            if (isMethod) {
                // The first argument is a string => Assume to be a method name
                if (!cometObj) {
                    throw new Error("cometLoad: Cannot call " + method + " before cometLoad is setup");
                } else {
                    switch (method) {
                    case "destroy":
                        // Destroy the slider
                        cometObj = null;
                        // Remove events
                        $this.off(".cometLoad");
                    default:
                        // Call a public method of the cometLoad object
                        if (cometObj.hasOwnProperty(method)) {
                            // Call the selected method with the given parameters
                            cometObj[method].apply(this, Array.prototype.slice.call(globalArgs, 1));
                        } else {
                            // Throw an error
                            throw new Error("cometLoad: Unknown method - " + method);
                        }
                        break;
                    }
                }
            } else {
                // Assume the first parameter to be the options if it isn't a method
                options = method || {};
                // Create a new cometLoad object
                cometObj = new cometLoadConstructor($this, options);
                $this.data("cometLoad", cometObj);
            }

            //Update the object
            $this.data("cometLoad", cometObj);
        });
    };

})(jQuery);

// Count boxes generator
(function ($) {
    "use strict";
    $.fn.generateCountBoxes = function (data, options) {
        if (!$.isArray(data)) {
            if (!$.isPlainObject(data)) {
                data = $.makeArray(data);
            } else {
                try {
                    data = $.parseJSON(data);
                } catch (err) {
                    console.error(data, "generateCountBoxes error", err.message, err.stack);
                    data = [];
                }
            }
        }
        if (!$.isPlainObject(options)) {
            options = {};
        }
        options = $.extend({
            titleKey: 'title',
            descriptionKey: 'description',
            countKey: 'total',
            iconKey: 'icon',
            colorKey: 'color',
            backgroundColorKey: 'background',
            linkKey: 'link',
            itemFormatter: function (item, index, options) {
                var thisMarkup = '<a href="' + (item[options.linkKey] || '#') + '" class="text-center inline-block btn btn-large btn-info margin-5" style="color: ' + (item[options.colorKey] || '') + '; background: ' + (item[options.backgroundColorKey] || '') + '; max-width:150px; min-height: 125px;">';
                thisMarkup += '<h3><b>' + (item[options.countKey] || '0') + '</b></h3>';
                if (item[options.iconKey]) {
                    thisMarkup += '<span class="pull-left margin-5">';
                    thisMarkup += '<img class="media-object img-tiny" src="' + item[options.iconKey] + '">';
                    thisMarkup += '</span>';
                }
                thisMarkup += '<div class="text-left margin-5">';
                thisMarkup += '<p>' + (item[options.titleKey] || '') + '</p>';
                thisMarkup += '<p><small>' + (item[options.descriptionKey] || '') + '</small></p>';
                thisMarkup += '</div>';
                thisMarkup += '</a>';
                return thisMarkup;
            },
            itemWrapperStart: '<div class="row-fluid text-center">',
            itemWrapperEnd: '</div>',
            emptyMessage: '<legend class="span12 text-center">No Data Available</legend>'
        }, options);
        var markup = options.itemWrapperStart;
        if (data.length === 0) {
            markup += options.emptyMessage;
        } else {
            for (var i = 0, l = data.length; i < l; i++) {
                markup += options.itemFormatter(data[i], i, options);
            }
        }
        markup += options.itemWrapperEnd;
        return this.each(function () {
            $(this).html(markup);
        });
    };
})(jQuery);

function strip_tags(input, allowed) {
    //  discuss at: http://phpjs.org/functions/strip_tags/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Luke Godfrey
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //    input by: Pul
    //    input by: Alex
    //    input by: Marc Palau
    //    input by: Brett Zamir (http://brett-zamir.me)
    //    input by: Bobby Drake
    //    input by: Evertjan Garretsen
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Onno Marsman
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Eric Nagel
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Tomasz Wesolowski
    //  revised by: Rafał Kukawski (http://blog.kukawski.pl/)
    //   example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>');
    //   returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
    //   example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
    //   returns 2: '<p>Kevin van Zonneveld</p>'
    //   example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
    //   returns 3: "<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>"
    //   example 4: strip_tags('1 < 5 5 > 1');
    //   returns 4: '1 < 5 5 > 1'
    //   example 5: strip_tags('1 <br/> 1');
    //   returns 5: '1  1'
    //   example 6: strip_tags('1 <br/> 1', '<br>');
    //   returns 6: '1 <br/> 1'
    //   example 7: strip_tags('1 <br/> 1', '<br><br/>');
    //   returns 7: '1 <br/> 1'

    allowed = (((allowed || '') + '')
        .toLowerCase()
        .match(/<[a-z][a-z0-9]*>/g) || [])
        .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '')
        .replace(tags, function ($0, $1) {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        });
};
