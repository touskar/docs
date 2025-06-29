﻿<?php
require_once 'conf.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:site_name" content="PayTech">
    <meta property="og:title" content="PayTech">
    <meta property="og:description"
          content="PayTech | Boutique Démo">
    <meta property="og:image" content="https://sample.paytech.sn/img/snapshoot.png">
    <meta property="og:url" content="https://sample.paytech.sn/">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta name="generator"
          content="PayTech | Boutique Démo"/>

    <title>PayTech | Boutique Démo</title>

    <script src="https://paytech.sn/cdn/paytech.min.js"></script>


    <script>
        function buy(btn) {
            //   $('.close-modal').click();
            loader('add');

            setTimeout(function () {
                var selector = $(btn);

                (new PayTech({
                    item_id: 1,
                })).withOption({
                    requestTokenUrl: '<?php echo BASE_URL ?>/paiement.php',
                    method: 'POST',
                    headers: {
                        "Accept": "text/html"
                    },
                    prensentationMode: PayTech.OPEN_IN_POPUP,
                    didPopupClosed: function (is_completed, success_url, cancel_url) {
                        window.location.href = is_completed === true ? success_url : cancel_url;
                    },
                    willGetToken: function () {
                        console.log("Je me prepare a obtenir un token");
                        selector.prop('disabled', true);
                    },
                    didGetToken: function (token, redirectUrl) {
                        console.log("Mon token est : " + token + ' et url est ' + redirectUrl);
                        selector.prop('disabled', false);
                        loader('remove');
                    },
                    didReceiveError: function (error) {
                        alert('erreur inconnu', error.toString());
                        selector.prop('disabled', false);
                        loader('remove');
                    },
                    didReceiveNonSuccessResponse: function (jsonResponse) {
                        console.log('non success response ', jsonResponse);
                        alert(jsonResponse.errors);
                        selector.prop('disabled', false);
                        loader('remove');
                    }
                }).send();
            }, 500)

        }
    </script>

    <script>
        function loader(removeAdd) {
            var html = `<div id="loader" style='z-index:99999999;width:100%;height:100%;position:fixed;top:0;left:0;background:rgba(0,0,0,0.2);'>
	<div style='width:50px;height:50px;margin: 25% auto;'><img src='<?php echo BASE_URL ?>/img/loader.gif' style='width:100%;height:100%'></div>
	  </div>`;
            if (removeAdd === 'add') {
                $("html").append(html);
                //$('.loading-wrap').css('display', 'block');
            }
            else {
                $("#loader").remove();
                //$('.loading-wrap').css('display', 'none');
            }
        }


    </script>

    <link rel="stylesheet" href="<?php echo BASE_URL ?>/css/bootstrap.min.css">
    <style>

       *{
            overflow: hidden !important;
        }

       @media only screen and (max-width: 800px) {
            #main-header{
                padding-top: 0;
            }

            .img-responsive{
                display: none;
            }
        }
    </style>
    <!-- Bootstrap -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


<body>
<!-- Pre Loader Starts -->
<div class="loading-wrap" style="display: none;">
    <div class="loading">
        <span>Loading</span>
    </div>
</div>
<!-- Pre Loader Ends -->
<!-- Navbar Starts -->

<!-- Navbar Ends -->
<!-- Main Header Starts -->
<header id="main-header" class="main-header parallax">
    <!-- Nested Container Starts -->
    <div class="container">
        <div class="row">
            <!-- Image Starts -->
            <div class="col-sm-4 col-xs-12">
                <img src="<?php echo BASE_URL ?>/img/phone.png"
                     alt="App Mockup" class="img-center img-responsive">
            </div>
            <!-- Image Ends -->
            <!-- Starts -->
            <div class="col-sm-8 col-xs-12 text-center">
                <h1 class="text-light">
                    Meilleure moyens de recevoir<br> vos paiement
                </h1>
                <h4 class="text-normal" Plusieurs moyens de paiement
                </h4>

                <p class="text-normal">
                    PayTech la meilleure plateforme de paiement en ligne Web & Mobile
                </p>
                <a href="#" onclick="buy(this)" class="btn btn-lg btn-secondary animation">

                    Acheter
                </a>
            </div>
            <!-- Ends -->
        </div>
    </div>
    <!-- Nested Container Ends -->
    <!-- Scroll Starts -->
    <a href="#welcome" id="scroll" class="animation">
        <i class="fa fa-angle-double-down"></i>
    </a>
    <!-- Scroll Ends -->
</header>
<!-- Main Header Ends -->
<!-- Welcome Section Starts -->
<section style="display: none" id="welcome" class="section section-inverse welcome">
    <!-- Nested Container Starts -->
    <div class="container text-center">
        <h2 class="text-normal">Welcome To <span>Khana Mobile App</span></h2>
        <p class="text-normal">
            Duis sed odio sit amet nibh vulputate cursus a sit amet mauris.<br>
            Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Mauris vitae consequat
            auctor eu in elit.
        </p>
    </div>
    <!-- Nested Container Ends -->
</section>
<!-- Contact Section Ends -->
<!-- Footer Starts -->

<!-- Footer Ends -->
<!-- Template JS Files -->
<script>//<![CDATA[
    /*! jQuery v1.11.3 | (c) 2005, 2015 jQuery Foundation, Inc. | jquery.org/license */
    !function (a, b) {
        "object" == typeof module && "object" == typeof module.exports ? module.exports = a.document ? b(a, !0) : function (a) {
            if (!a.document) throw new Error("jQuery requires a window with a document");
            return b(a)
        } : b(a)
    }("undefined" != typeof window ? window : this, function (a, b) {
        var c = [], d = c.slice, e = c.concat, f = c.push, g = c.indexOf, h = {}, i = h.toString, j = h.hasOwnProperty,
            k = {}, l = "1.11.3", m = function (a, b) {
                return new m.fn.init(a, b)
            }, n = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, o = /^-ms-/, p = /-([\da-z])/gi, q = function (a, b) {
                return b.toUpperCase()
            };
        m.fn = m.prototype = {
            jquery: l, constructor: m, selector: "", length: 0, toArray: function () {
                return d.call(this)
            }, get: function (a) {
                return null != a ? 0 > a ? this[a + this.length] : this[a] : d.call(this)
            }, pushStack: function (a) {
                var b = m.merge(this.constructor(), a);
                return b.prevObject = this, b.context = this.context, b
            }, each: function (a, b) {
                return m.each(this, a, b)
            }, map: function (a) {
                return this.pushStack(m.map(this, function (b, c) {
                    return a.call(b, c, b)
                }))
            }, slice: function () {
                return this.pushStack(d.apply(this, arguments))
            }, first: function () {
                return this.eq(0)
            }, last: function () {
                return this.eq(-1)
            }, eq: function (a) {
                var b = this.length, c = +a + (0 > a ? b : 0);
                return this.pushStack(c >= 0 && b > c ? [this[c]] : [])
            }, end: function () {
                return this.prevObject || this.constructor(null)
            }, push: f, sort: c.sort, splice: c.splice
        }, m.extend = m.fn.extend = function () {
            var a, b, c, d, e, f, g = arguments[0] || {}, h = 1, i = arguments.length, j = !1;
            for ("boolean" == typeof g && (j = g, g = arguments[h] || {}, h++), "object" == typeof g || m.isFunction(g) || (g = {}), h === i && (g = this, h--); i > h; h++) if (null != (e = arguments[h])) for (d in e) a = g[d], c = e[d], g !== c && (j && c && (m.isPlainObject(c) || (b = m.isArray(c))) ? (b ? (b = !1, f = a && m.isArray(a) ? a : []) : f = a && m.isPlainObject(a) ? a : {}, g[d] = m.extend(j, f, c)) : void 0 !== c && (g[d] = c));
            return g
        }, m.extend({
            expando: "jQuery" + (l + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (a) {
                throw new Error(a)
            }, noop: function () {
            }, isFunction: function (a) {
                return "function" === m.type(a)
            }, isArray: Array.isArray || function (a) {
                return "array" === m.type(a)
            }, isWindow: function (a) {
                return null != a && a == a.window
            }, isNumeric: function (a) {
                return !m.isArray(a) && a - parseFloat(a) + 1 >= 0
            }, isEmptyObject: function (a) {
                var b;
                for (b in a) return !1;
                return !0
            }, isPlainObject: function (a) {
                var b;
                if (!a || "object" !== m.type(a) || a.nodeType || m.isWindow(a)) return !1;
                try {
                    if (a.constructor && !j.call(a, "constructor") && !j.call(a.constructor.prototype, "isPrototypeOf")) return !1
                } catch (c) {
                    return !1
                }
                if (k.ownLast) for (b in a) return j.call(a, b);
                for (b in a) ;
                return void 0 === b || j.call(a, b)
            }, type: function (a) {
                return null == a ? a + "" : "object" == typeof a || "function" == typeof a ? h[i.call(a)] || "object" : typeof a
            }, globalEval: function (b) {
                b && m.trim(b) && (a.execScript || function (b) {
                    a.eval.call(a, b)
                })(b)
            }, camelCase: function (a) {
                return a.replace(o, "ms-").replace(p, q)
            }, nodeName: function (a, b) {
                return a.nodeName && a.nodeName.toLowerCase() === b.toLowerCase()
            }, each: function (a, b, c) {
                var d, e = 0, f = a.length, g = r(a);
                if (c) {
                    if (g) {
                        for (; f > e; e++) if (d = b.apply(a[e], c), d === !1) break
                    } else for (e in a) if (d = b.apply(a[e], c), d === !1) break
                } else if (g) {
                    for (; f > e; e++) if (d = b.call(a[e], e, a[e]), d === !1) break
                } else for (e in a) if (d = b.call(a[e], e, a[e]), d === !1) break;
                return a
            }, trim: function (a) {
                return null == a ? "" : (a + "").replace(n, "")
            }, makeArray: function (a, b) {
                var c = b || [];
                return null != a && (r(Object(a)) ? m.merge(c, "string" == typeof a ? [a] : a) : f.call(c, a)), c
            }, inArray: function (a, b, c) {
                var d;
                if (b) {
                    if (g) return g.call(b, a, c);
                    for (d = b.length, c = c ? 0 > c ? Math.max(0, d + c) : c : 0; d > c; c++) if (c in b && b[c] === a) return c
                }
                return -1
            }, merge: function (a, b) {
                var c = +b.length, d = 0, e = a.length;
                while (c > d) a[e++] = b[d++];
                if (c !== c) while (void 0 !== b[d]) a[e++] = b[d++];
                return a.length = e, a
            }, grep: function (a, b, c) {
                for (var d, e = [], f = 0, g = a.length, h = !c; g > f; f++) d = !b(a[f], f), d !== h && e.push(a[f]);
                return e
            }, map: function (a, b, c) {
                var d, f = 0, g = a.length, h = r(a), i = [];
                if (h) for (; g > f; f++) d = b(a[f], f, c), null != d && i.push(d); else for (f in a) d = b(a[f], f, c), null != d && i.push(d);
                return e.apply([], i)
            }, guid: 1, proxy: function (a, b) {
                var c, e, f;
                return "string" == typeof b && (f = a[b], b = a, a = f), m.isFunction(a) ? (c = d.call(arguments, 2), e = function () {
                    return a.apply(b || this, c.concat(d.call(arguments)))
                }, e.guid = a.guid = a.guid || m.guid++, e) : void 0
            }, now: function () {
                return +new Date
            }, support: k
        }), m.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (a, b) {
            h["[object " + b + "]"] = b.toLowerCase()
        });

        function r(a) {
            var b = "length" in a && a.length, c = m.type(a);
            return "function" === c || m.isWindow(a) ? !1 : 1 === a.nodeType && b ? !0 : "array" === c || 0 === b || "number" == typeof b && b > 0 && b - 1 in a
        }

        var s = function (a) {
            var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u = "sizzle" + 1 * new Date, v = a.document,
                w = 0, x = 0, y = ha(), z = ha(), A = ha(), B = function (a, b) {
                    return a === b && (l = !0), 0
                }, C = 1 << 31, D = {}.hasOwnProperty, E = [], F = E.pop, G = E.push, H = E.push, I = E.slice,
                J = function (a, b) {
                    for (var c = 0, d = a.length; d > c; c++) if (a[c] === b) return c;
                    return -1
                },
                K = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
                L = "[\\x20\\t\\r\\n\\f]", M = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", N = M.replace("w", "w#"),
                O = "\\[" + L + "*(" + M + ")(?:" + L + "*([*^$|!~]?=)" + L + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + N + "))|)" + L + "*\\]",
                P = ":(" + M + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + O + ")*)|.*)\\)|)",
                Q = new RegExp(L + "+", "g"), R = new RegExp("^" + L + "+|((?:^|[^\\\\])(?:\\\\.)*)" + L + "+$", "g"),
                S = new RegExp("^" + L + "*," + L + "*"), T = new RegExp("^" + L + "*([>+~]|" + L + ")" + L + "*"),
                U = new RegExp("=" + L + "*([^\\]'\"]*?)" + L + "*\\]", "g"), V = new RegExp(P),
                W = new RegExp("^" + N + "$"), X = {
                    ID: new RegExp("^#(" + M + ")"),
                    CLASS: new RegExp("^\\.(" + M + ")"),
                    TAG: new RegExp("^(" + M.replace("w", "w*") + ")"),
                    ATTR: new RegExp("^" + O),
                    PSEUDO: new RegExp("^" + P),
                    CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + L + "*(even|odd|(([+-]|)(\\d*)n|)" + L + "*(?:([+-]|)" + L + "*(\\d+)|))" + L + "*\\)|)", "i"),
                    bool: new RegExp("^(?:" + K + ")$", "i"),
                    needsContext: new RegExp("^" + L + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + L + "*((?:-\\d)?\\d*)" + L + "*\\)|)(?=[^-]|$)", "i")
                }, Y = /^(?:input|select|textarea|button)$/i, Z = /^h\d$/i, $ = /^[^{]+\{\s*\[native \w/,
                _ = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, aa = /[+~]/, ba = /'|\\/g,
                ca = new RegExp("\\\\([\\da-f]{1,6}" + L + "?|(" + L + ")|.)", "ig"), da = function (a, b, c) {
                    var d = "0x" + b - 65536;
                    return d !== d || c ? b : 0 > d ? String.fromCharCode(d + 65536) : String.fromCharCode(d >> 10 | 55296, 1023 & d | 56320)
                }, ea = function () {
                    m()
                };
            try {
                H.apply(E = I.call(v.childNodes), v.childNodes), E[v.childNodes.length].nodeType
            } catch (fa) {
                H = {
                    apply: E.length ? function (a, b) {
                        G.apply(a, I.call(b))
                    } : function (a, b) {
                        var c = a.length, d = 0;
                        while (a[c++] = b[d++]) ;
                        a.length = c - 1
                    }
                }
            }

            function ga(a, b, d, e) {
                var f, h, j, k, l, o, r, s, w, x;
                if ((b ? b.ownerDocument || b : v) !== n && m(b), b = b || n, d = d || [], k = b.nodeType, "string" != typeof a || !a || 1 !== k && 9 !== k && 11 !== k) return d;
                if (!e && p) {
                    if (11 !== k && (f = _.exec(a))) if (j = f[1]) {
                        if (9 === k) {
                            if (h = b.getElementById(j), !h || !h.parentNode) return d;
                            if (h.id === j) return d.push(h), d
                        } else if (b.ownerDocument && (h = b.ownerDocument.getElementById(j)) && t(b, h) && h.id === j) return d.push(h), d
                    } else {
                        if (f[2]) return H.apply(d, b.getElementsByTagName(a)), d;
                        if ((j = f[3]) && c.getElementsByClassName) return H.apply(d, b.getElementsByClassName(j)), d
                    }
                    if (c.qsa && (!q || !q.test(a))) {
                        if (s = r = u, w = b, x = 1 !== k && a, 1 === k && "object" !== b.nodeName.toLowerCase()) {
                            o = g(a), (r = b.getAttribute("id")) ? s = r.replace(ba, "\\$&") : b.setAttribute("id", s), s = "[id='" + s + "'] ", l = o.length;
                            while (l--) o[l] = s + ra(o[l]);
                            w = aa.test(a) && pa(b.parentNode) || b, x = o.join(",")
                        }
                        if (x) try {
                            return H.apply(d, w.querySelectorAll(x)), d
                        } catch (y) {
                        } finally {
                            r || b.removeAttribute("id")
                        }
                    }
                }
                return i(a.replace(R, "$1"), b, d, e)
            }

            function ha() {
                var a = [];

                function b(c, e) {
                    return a.push(c + " ") > d.cacheLength && delete b[a.shift()], b[c + " "] = e
                }

                return b
            }

            function ia(a) {
                return a[u] = !0, a
            }

            function ja(a) {
                var b = n.createElement("div");
                try {
                    return !!a(b)
                } catch (c) {
                    return !1
                } finally {
                    b.parentNode && b.parentNode.removeChild(b), b = null
                }
            }

            function ka(a, b) {
                var c = a.split("|"), e = a.length;
                while (e--) d.attrHandle[c[e]] = b
            }

            function la(a, b) {
                var c = b && a,
                    d = c && 1 === a.nodeType && 1 === b.nodeType && (~b.sourceIndex || C) - (~a.sourceIndex || C);
                if (d) return d;
                if (c) while (c = c.nextSibling) if (c === b) return -1;
                return a ? 1 : -1
            }

            function ma(a) {
                return function (b) {
                    var c = b.nodeName.toLowerCase();
                    return "input" === c && b.type === a
                }
            }

            function na(a) {
                return function (b) {
                    var c = b.nodeName.toLowerCase();
                    return ("input" === c || "button" === c) && b.type === a
                }
            }

            function oa(a) {
                return ia(function (b) {
                    return b = +b, ia(function (c, d) {
                        var e, f = a([], c.length, b), g = f.length;
                        while (g--) c[e = f[g]] && (c[e] = !(d[e] = c[e]))
                    })
                })
            }

            function pa(a) {
                return a && "undefined" != typeof a.getElementsByTagName && a
            }

            c = ga.support = {}, f = ga.isXML = function (a) {
                var b = a && (a.ownerDocument || a).documentElement;
                return b ? "HTML" !== b.nodeName : !1
            }, m = ga.setDocument = function (a) {
                var b, e, g = a ? a.ownerDocument || a : v;
                return g !== n && 9 === g.nodeType && g.documentElement ? (n = g, o = g.documentElement, e = g.defaultView, e && e !== e.top && (e.addEventListener ? e.addEventListener("unload", ea, !1) : e.attachEvent && e.attachEvent("onunload", ea)), p = !f(g), c.attributes = ja(function (a) {
                    return a.className = "i", !a.getAttribute("className")
                }), c.getElementsByTagName = ja(function (a) {
                    return a.appendChild(g.createComment("")), !a.getElementsByTagName("*").length
                }), c.getElementsByClassName = $.test(g.getElementsByClassName), c.getById = ja(function (a) {
                    return o.appendChild(a).id = u, !g.getElementsByName || !g.getElementsByName(u).length
                }), c.getById ? (d.find.ID = function (a, b) {
                    if ("undefined" != typeof b.getElementById && p) {
                        var c = b.getElementById(a);
                        return c && c.parentNode ? [c] : []
                    }
                }, d.filter.ID = function (a) {
                    var b = a.replace(ca, da);
                    return function (a) {
                        return a.getAttribute("id") === b
                    }
                }) : (delete d.find.ID, d.filter.ID = function (a) {
                    var b = a.replace(ca, da);
                    return function (a) {
                        var c = "undefined" != typeof a.getAttributeNode && a.getAttributeNode("id");
                        return c && c.value === b
                    }
                }), d.find.TAG = c.getElementsByTagName ? function (a, b) {
                    return "undefined" != typeof b.getElementsByTagName ? b.getElementsByTagName(a) : c.qsa ? b.querySelectorAll(a) : void 0
                } : function (a, b) {
                    var c, d = [], e = 0, f = b.getElementsByTagName(a);
                    if ("*" === a) {
                        while (c = f[e++]) 1 === c.nodeType && d.push(c);
                        return d
                    }
                    return f
                }, d.find.CLASS = c.getElementsByClassName && function (a, b) {
                    return p ? b.getElementsByClassName(a) : void 0
                }, r = [], q = [], (c.qsa = $.test(g.querySelectorAll)) && (ja(function (a) {
                    o.appendChild(a).innerHTML = "<a id='" + u + "'></a><select id='" + u + "-\f]' msallowcapture=''><option selected=''></option></select>", a.querySelectorAll("[msallowcapture^='']").length && q.push("[*^$]=" + L + "*(?:''|\"\")"), a.querySelectorAll("[selected]").length || q.push("\\[" + L + "*(?:value|" + K + ")"), a.querySelectorAll("[id~=" + u + "-]").length || q.push("~="), a.querySelectorAll(":checked").length || q.push(":checked"), a.querySelectorAll("a#" + u + "+*").length || q.push(".#.+[+~]")
                }), ja(function (a) {
                    var b = g.createElement("input");
                    b.setAttribute("type", "hidden"), a.appendChild(b).setAttribute("name", "D"), a.querySelectorAll("[name=d]").length && q.push("name" + L + "*[*^$|!~]?="), a.querySelectorAll(":enabled").length || q.push(":enabled", ":disabled"), a.querySelectorAll("*,:x"), q.push(",.*:")
                })), (c.matchesSelector = $.test(s = o.matches || o.webkitMatchesSelector || o.mozMatchesSelector || o.oMatchesSelector || o.msMatchesSelector)) && ja(function (a) {
                    c.disconnectedMatch = s.call(a, "div"), s.call(a, "[s!='']:x"), r.push("!=", P)
                }), q = q.length && new RegExp(q.join("|")), r = r.length && new RegExp(r.join("|")), b = $.test(o.compareDocumentPosition), t = b || $.test(o.contains) ? function (a, b) {
                    var c = 9 === a.nodeType ? a.documentElement : a, d = b && b.parentNode;
                    return a === d || !(!d || 1 !== d.nodeType || !(c.contains ? c.contains(d) : a.compareDocumentPosition && 16 & a.compareDocumentPosition(d)))
                } : function (a, b) {
                    if (b) while (b = b.parentNode) if (b === a) return !0;
                    return !1
                }, B = b ? function (a, b) {
                    if (a === b) return l = !0, 0;
                    var d = !a.compareDocumentPosition - !b.compareDocumentPosition;
                    return d ? d : (d = (a.ownerDocument || a) === (b.ownerDocument || b) ? a.compareDocumentPosition(b) : 1, 1 & d || !c.sortDetached && b.compareDocumentPosition(a) === d ? a === g || a.ownerDocument === v && t(v, a) ? -1 : b === g || b.ownerDocument === v && t(v, b) ? 1 : k ? J(k, a) - J(k, b) : 0 : 4 & d ? -1 : 1)
                } : function (a, b) {
                    if (a === b) return l = !0, 0;
                    var c, d = 0, e = a.parentNode, f = b.parentNode, h = [a], i = [b];
                    if (!e || !f) return a === g ? -1 : b === g ? 1 : e ? -1 : f ? 1 : k ? J(k, a) - J(k, b) : 0;
                    if (e === f) return la(a, b);
                    c = a;
                    while (c = c.parentNode) h.unshift(c);
                    c = b;
                    while (c = c.parentNode) i.unshift(c);
                    while (h[d] === i[d]) d++;
                    return d ? la(h[d], i[d]) : h[d] === v ? -1 : i[d] === v ? 1 : 0
                }, g) : n
            }, ga.matches = function (a, b) {
                return ga(a, null, null, b)
            }, ga.matchesSelector = function (a, b) {
                if ((a.ownerDocument || a) !== n && m(a), b = b.replace(U, "='$1']"), !(!c.matchesSelector || !p || r && r.test(b) || q && q.test(b))) try {
                    var d = s.call(a, b);
                    if (d || c.disconnectedMatch || a.document && 11 !== a.document.nodeType) return d
                } catch (e) {
                }
                return ga(b, n, null, [a]).length > 0
            }, ga.contains = function (a, b) {
                return (a.ownerDocument || a) !== n && m(a), t(a, b)
            }, ga.attr = function (a, b) {
                (a.ownerDocument || a) !== n && m(a);
                var e = d.attrHandle[b.toLowerCase()],
                    f = e && D.call(d.attrHandle, b.toLowerCase()) ? e(a, b, !p) : void 0;
                return void 0 !== f ? f : c.attributes || !p ? a.getAttribute(b) : (f = a.getAttributeNode(b)) && f.specified ? f.value : null
            }, ga.error = function (a) {
                throw new Error("Syntax error, unrecognized expression: " + a)
            }, ga.uniqueSort = function (a) {
                var b, d = [], e = 0, f = 0;
                if (l = !c.detectDuplicates, k = !c.sortStable && a.slice(0), a.sort(B), l) {
                    while (b = a[f++]) b === a[f] && (e = d.push(f));
                    while (e--) a.splice(d[e], 1)
                }
                return k = null, a
            }, e = ga.getText = function (a) {
                var b, c = "", d = 0, f = a.nodeType;
                if (f) {
                    if (1 === f || 9 === f || 11 === f) {
                        if ("string" == typeof a.textContent) return a.textContent;
                        for (a = a.firstChild; a; a = a.nextSibling) c += e(a)
                    } else if (3 === f || 4 === f) return a.nodeValue
                } else while (b = a[d++]) c += e(b);
                return c
            }, d = ga.selectors = {
                cacheLength: 50,
                createPseudo: ia,
                match: X,
                attrHandle: {},
                find: {},
                relative: {
                    ">": {dir: "parentNode", first: !0},
                    " ": {dir: "parentNode"},
                    "+": {dir: "previousSibling", first: !0},
                    "~": {dir: "previousSibling"}
                },
                preFilter: {
                    ATTR: function (a) {
                        return a[1] = a[1].replace(ca, da), a[3] = (a[3] || a[4] || a[5] || "").replace(ca, da), "~=" === a[2] && (a[3] = " " + a[3] + " "), a.slice(0, 4)
                    }, CHILD: function (a) {
                        return a[1] = a[1].toLowerCase(), "nth" === a[1].slice(0, 3) ? (a[3] || ga.error(a[0]), a[4] = +(a[4] ? a[5] + (a[6] || 1) : 2 * ("even" === a[3] || "odd" === a[3])), a[5] = +(a[7] + a[8] || "odd" === a[3])) : a[3] && ga.error(a[0]), a
                    }, PSEUDO: function (a) {
                        var b, c = !a[6] && a[2];
                        return X.CHILD.test(a[0]) ? null : (a[3] ? a[2] = a[4] || a[5] || "" : c && V.test(c) && (b = g(c, !0)) && (b = c.indexOf(")", c.length - b) - c.length) && (a[0] = a[0].slice(0, b), a[2] = c.slice(0, b)), a.slice(0, 3))
                    }
                },
                filter: {
                    TAG: function (a) {
                        var b = a.replace(ca, da).toLowerCase();
                        return "*" === a ? function () {
                            return !0
                        } : function (a) {
                            return a.nodeName && a.nodeName.toLowerCase() === b
                        }
                    }, CLASS: function (a) {
                        var b = y[a + " "];
                        return b || (b = new RegExp("(^|" + L + ")" + a + "(" + L + "|$)")) && y(a, function (a) {
                            return b.test("string" == typeof a.className && a.className || "undefined" != typeof a.getAttribute && a.getAttribute("class") || "")
                        })
                    }, ATTR: function (a, b, c) {
                        return function (d) {
                            var e = ga.attr(d, a);
                            return null == e ? "!=" === b : b ? (e += "", "=" === b ? e === c : "!=" === b ? e !== c : "^=" === b ? c && 0 === e.indexOf(c) : "*=" === b ? c && e.indexOf(c) > -1 : "$=" === b ? c && e.slice(-c.length) === c : "~=" === b ? (" " + e.replace(Q, " ") + " ").indexOf(c) > -1 : "|=" === b ? e === c || e.slice(0, c.length + 1) === c + "-" : !1) : !0
                        }
                    }, CHILD: function (a, b, c, d, e) {
                        var f = "nth" !== a.slice(0, 3), g = "last" !== a.slice(-4), h = "of-type" === b;
                        return 1 === d && 0 === e ? function (a) {
                            return !!a.parentNode
                        } : function (b, c, i) {
                            var j, k, l, m, n, o, p = f !== g ? "nextSibling" : "previousSibling", q = b.parentNode,
                                r = h && b.nodeName.toLowerCase(), s = !i && !h;
                            if (q) {
                                if (f) {
                                    while (p) {
                                        l = b;
                                        while (l = l[p]) if (h ? l.nodeName.toLowerCase() === r : 1 === l.nodeType) return !1;
                                        o = p = "only" === a && !o && "nextSibling"
                                    }
                                    return !0
                                }
                                if (o = [g ? q.firstChild : q.lastChild], g && s) {
                                    k = q[u] || (q[u] = {}), j = k[a] || [], n = j[0] === w && j[1], m = j[0] === w && j[2], l = n && q.childNodes[n];
                                    while (l = ++n && l && l[p] || (m = n = 0) || o.pop()) if (1 === l.nodeType && ++m && l === b) {
                                        k[a] = [w, n, m];
                                        break
                                    }
                                } else if (s && (j = (b[u] || (b[u] = {}))[a]) && j[0] === w) m = j[1]; else while (l = ++n && l && l[p] || (m = n = 0) || o.pop()) if ((h ? l.nodeName.toLowerCase() === r : 1 === l.nodeType) && ++m && (s && ((l[u] || (l[u] = {}))[a] = [w, m]), l === b)) break;
                                return m -= e, m === d || m % d === 0 && m / d >= 0
                            }
                        }
                    }, PSEUDO: function (a, b) {
                        var c,
                            e = d.pseudos[a] || d.setFilters[a.toLowerCase()] || ga.error("unsupported pseudo: " + a);
                        return e[u] ? e(b) : e.length > 1 ? (c = [a, a, "", b], d.setFilters.hasOwnProperty(a.toLowerCase()) ? ia(function (a, c) {
                            var d, f = e(a, b), g = f.length;
                            while (g--) d = J(a, f[g]), a[d] = !(c[d] = f[g])
                        }) : function (a) {
                            return e(a, 0, c)
                        }) : e
                    }
                },
                pseudos: {
                    not: ia(function (a) {
                        var b = [], c = [], d = h(a.replace(R, "$1"));
                        return d[u] ? ia(function (a, b, c, e) {
                            var f, g = d(a, null, e, []), h = a.length;
                            while (h--) (f = g[h]) && (a[h] = !(b[h] = f))
                        }) : function (a, e, f) {
                            return b[0] = a, d(b, null, f, c), b[0] = null, !c.pop()
                        }
                    }), has: ia(function (a) {
                        return function (b) {
                            return ga(a, b).length > 0
                        }
                    }), contains: ia(function (a) {
                        return a = a.replace(ca, da), function (b) {
                            return (b.textContent || b.innerText || e(b)).indexOf(a) > -1
                        }
                    }), lang: ia(function (a) {
                        return W.test(a || "") || ga.error("unsupported lang: " + a), a = a.replace(ca, da).toLowerCase(), function (b) {
                            var c;
                            do if (c = p ? b.lang : b.getAttribute("xml:lang") || b.getAttribute("lang")) return c = c.toLowerCase(), c === a || 0 === c.indexOf(a + "-"); while ((b = b.parentNode) && 1 === b.nodeType);
                            return !1
                        }
                    }), target: function (b) {
                        var c = a.location && a.location.hash;
                        return c && c.slice(1) === b.id
                    }, root: function (a) {
                        return a === o
                    }, focus: function (a) {
                        return a === n.activeElement && (!n.hasFocus || n.hasFocus()) && !!(a.type || a.href || ~a.tabIndex)
                    }, enabled: function (a) {
                        return a.disabled === !1
                    }, disabled: function (a) {
                        return a.disabled === !0
                    }, checked: function (a) {
                        var b = a.nodeName.toLowerCase();
                        return "input" === b && !!a.checked || "option" === b && !!a.selected
                    }, selected: function (a) {
                        return a.parentNode && a.parentNode.selectedIndex, a.selected === !0
                    }, empty: function (a) {
                        for (a = a.firstChild; a; a = a.nextSibling) if (a.nodeType < 6) return !1;
                        return !0
                    }, parent: function (a) {
                        return !d.pseudos.empty(a)
                    }, header: function (a) {
                        return Z.test(a.nodeName)
                    }, input: function (a) {
                        return Y.test(a.nodeName)
                    }, button: function (a) {
                        var b = a.nodeName.toLowerCase();
                        return "input" === b && "button" === a.type || "button" === b
                    }, text: function (a) {
                        var b;
                        return "input" === a.nodeName.toLowerCase() && "text" === a.type && (null == (b = a.getAttribute("type")) || "text" === b.toLowerCase())
                    }, first: oa(function () {
                        return [0]
                    }), last: oa(function (a, b) {
                        return [b - 1]
                    }), eq: oa(function (a, b, c) {
                        return [0 > c ? c + b : c]
                    }), even: oa(function (a, b) {
                        for (var c = 0; b > c; c += 2) a.push(c);
                        return a
                    }), odd: oa(function (a, b) {
                        for (var c = 1; b > c; c += 2) a.push(c);
                        return a
                    }), lt: oa(function (a, b, c) {
                        for (var d = 0 > c ? c + b : c; --d >= 0;) a.push(d);
                        return a
                    }), gt: oa(function (a, b, c) {
                        for (var d = 0 > c ? c + b : c; ++d < b;) a.push(d);
                        return a
                    })
                }
            }, d.pseudos.nth = d.pseudos.eq;
            for (b in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0}) d.pseudos[b] = ma(b);
            for (b in{submit: !0, reset: !0}) d.pseudos[b] = na(b);

            function qa() {
            }

            qa.prototype = d.filters = d.pseudos, d.setFilters = new qa, g = ga.tokenize = function (a, b) {
                var c, e, f, g, h, i, j, k = z[a + " "];
                if (k) return b ? 0 : k.slice(0);
                h = a, i = [], j = d.preFilter;
                while (h) {
                    (!c || (e = S.exec(h))) && (e && (h = h.slice(e[0].length) || h), i.push(f = [])), c = !1, (e = T.exec(h)) && (c = e.shift(), f.push({
                        value: c,
                        type: e[0].replace(R, " ")
                    }), h = h.slice(c.length));
                    for (g in d.filter) !(e = X[g].exec(h)) || j[g] && !(e = j[g](e)) || (c = e.shift(), f.push({
                        value: c,
                        type: g,
                        matches: e
                    }), h = h.slice(c.length));
                    if (!c) break
                }
                return b ? h.length : h ? ga.error(a) : z(a, i).slice(0)
            };

            function ra(a) {
                for (var b = 0, c = a.length, d = ""; c > b; b++) d += a[b].value;
                return d
            }

            function sa(a, b, c) {
                var d = b.dir, e = c && "parentNode" === d, f = x++;
                return b.first ? function (b, c, f) {
                    while (b = b[d]) if (1 === b.nodeType || e) return a(b, c, f)
                } : function (b, c, g) {
                    var h, i, j = [w, f];
                    if (g) {
                        while (b = b[d]) if ((1 === b.nodeType || e) && a(b, c, g)) return !0
                    } else while (b = b[d]) if (1 === b.nodeType || e) {
                        if (i = b[u] || (b[u] = {}), (h = i[d]) && h[0] === w && h[1] === f) return j[2] = h[2];
                        if (i[d] = j, j[2] = a(b, c, g)) return !0
                    }
                }
            }

            function ta(a) {
                return a.length > 1 ? function (b, c, d) {
                    var e = a.length;
                    while (e--) if (!a[e](b, c, d)) return !1;
                    return !0
                } : a[0]
            }

            function ua(a, b, c) {
                for (var d = 0, e = b.length; e > d; d++) ga(a, b[d], c);
                return c
            }

            function va(a, b, c, d, e) {
                for (var f, g = [], h = 0, i = a.length, j = null != b; i > h; h++) (f = a[h]) && (!c || c(f, d, e)) && (g.push(f), j && b.push(h));
                return g
            }

            function wa(a, b, c, d, e, f) {
                return d && !d[u] && (d = wa(d)), e && !e[u] && (e = wa(e, f)), ia(function (f, g, h, i) {
                    var j, k, l, m = [], n = [], o = g.length, p = f || ua(b || "*", h.nodeType ? [h] : h, []),
                        q = !a || !f && b ? p : va(p, m, a, h, i), r = c ? e || (f ? a : o || d) ? [] : g : q;
                    if (c && c(q, r, h, i), d) {
                        j = va(r, n), d(j, [], h, i), k = j.length;
                        while (k--) (l = j[k]) && (r[n[k]] = !(q[n[k]] = l))
                    }
                    if (f) {
                        if (e || a) {
                            if (e) {
                                j = [], k = r.length;
                                while (k--) (l = r[k]) && j.push(q[k] = l);
                                e(null, r = [], j, i)
                            }
                            k = r.length;
                            while (k--) (l = r[k]) && (j = e ? J(f, l) : m[k]) > -1 && (f[j] = !(g[j] = l))
                        }
                    } else r = va(r === g ? r.splice(o, r.length) : r), e ? e(null, g, r, i) : H.apply(g, r)
                })
            }

            function xa(a) {
                for (var b, c, e, f = a.length, g = d.relative[a[0].type], h = g || d.relative[" "], i = g ? 1 : 0, k = sa(function (a) {
                    return a === b
                }, h, !0), l = sa(function (a) {
                    return J(b, a) > -1
                }, h, !0), m = [function (a, c, d) {
                    var e = !g && (d || c !== j) || ((b = c).nodeType ? k(a, c, d) : l(a, c, d));
                    return b = null, e
                }]; f > i; i++) if (c = d.relative[a[i].type]) m = [sa(ta(m), c)]; else {
                    if (c = d.filter[a[i].type].apply(null, a[i].matches), c[u]) {
                        for (e = ++i; f > e; e++) if (d.relative[a[e].type]) break;
                        return wa(i > 1 && ta(m), i > 1 && ra(a.slice(0, i - 1).concat({value: " " === a[i - 2].type ? "*" : ""})).replace(R, "$1"), c, e > i && xa(a.slice(i, e)), f > e && xa(a = a.slice(e)), f > e && ra(a))
                    }
                    m.push(c)
                }
                return ta(m)
            }

            function ya(a, b) {
                var c = b.length > 0, e = a.length > 0, f = function (f, g, h, i, k) {
                    var l, m, o, p = 0, q = "0", r = f && [], s = [], t = j, u = f || e && d.find.TAG("*", k),
                        v = w += null == t ? 1 : Math.random() || .1, x = u.length;
                    for (k && (j = g !== n && g); q !== x && null != (l = u[q]); q++) {
                        if (e && l) {
                            m = 0;
                            while (o = a[m++]) if (o(l, g, h)) {
                                i.push(l);
                                break
                            }
                            k && (w = v)
                        }
                        c && ((l = !o && l) && p--, f && r.push(l))
                    }
                    if (p += q, c && q !== p) {
                        m = 0;
                        while (o = b[m++]) o(r, s, g, h);
                        if (f) {
                            if (p > 0) while (q--) r[q] || s[q] || (s[q] = F.call(i));
                            s = va(s)
                        }
                        H.apply(i, s), k && !f && s.length > 0 && p + b.length > 1 && ga.uniqueSort(i)
                    }
                    return k && (w = v, j = t), r
                };
                return c ? ia(f) : f
            }

            return h = ga.compile = function (a, b) {
                var c, d = [], e = [], f = A[a + " "];
                if (!f) {
                    b || (b = g(a)), c = b.length;
                    while (c--) f = xa(b[c]), f[u] ? d.push(f) : e.push(f);
                    f = A(a, ya(e, d)), f.selector = a
                }
                return f
            }, i = ga.select = function (a, b, e, f) {
                var i, j, k, l, m, n = "function" == typeof a && a, o = !f && g(a = n.selector || a);
                if (e = e || [], 1 === o.length) {
                    if (j = o[0] = o[0].slice(0), j.length > 2 && "ID" === (k = j[0]).type && c.getById && 9 === b.nodeType && p && d.relative[j[1].type]) {
                        if (b = (d.find.ID(k.matches[0].replace(ca, da), b) || [])[0], !b) return e;
                        n && (b = b.parentNode), a = a.slice(j.shift().value.length)
                    }
                    i = X.needsContext.test(a) ? 0 : j.length;
                    while (i--) {
                        if (k = j[i], d.relative[l = k.type]) break;
                        if ((m = d.find[l]) && (f = m(k.matches[0].replace(ca, da), aa.test(j[0].type) && pa(b.parentNode) || b))) {
                            if (j.splice(i, 1), a = f.length && ra(j), !a) return H.apply(e, f), e;
                            break
                        }
                    }
                }
                return (n || h(a, o))(f, b, !p, e, aa.test(a) && pa(b.parentNode) || b), e
            }, c.sortStable = u.split("").sort(B).join("") === u, c.detectDuplicates = !!l, m(), c.sortDetached = ja(function (a) {
                return 1 & a.compareDocumentPosition(n.createElement("div"))
            }), ja(function (a) {
                return a.innerHTML = "<a href='#'></a>", "#" === a.firstChild.getAttribute("href")
            }) || ka("type|href|height|width", function (a, b, c) {
                return c ? void 0 : a.getAttribute(b, "type" === b.toLowerCase() ? 1 : 2)
            }), c.attributes && ja(function (a) {
                return a.innerHTML = "<input/>", a.firstChild.setAttribute("value", ""), "" === a.firstChild.getAttribute("value")
            }) || ka("value", function (a, b, c) {
                return c || "input" !== a.nodeName.toLowerCase() ? void 0 : a.defaultValue
            }), ja(function (a) {
                return null == a.getAttribute("disabled")
            }) || ka(K, function (a, b, c) {
                var d;
                return c ? void 0 : a[b] === !0 ? b.toLowerCase() : (d = a.getAttributeNode(b)) && d.specified ? d.value : null
            }), ga
        }(a);
        m.find = s, m.expr = s.selectors, m.expr[":"] = m.expr.pseudos, m.unique = s.uniqueSort, m.text = s.getText, m.isXMLDoc = s.isXML, m.contains = s.contains;
        var t = m.expr.match.needsContext, u = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, v = /^.[^:#\[\.,]*$/;

        function w(a, b, c) {
            if (m.isFunction(b)) return m.grep(a, function (a, d) {
                return !!b.call(a, d, a) !== c
            });
            if (b.nodeType) return m.grep(a, function (a) {
                return a === b !== c
            });
            if ("string" == typeof b) {
                if (v.test(b)) return m.filter(b, a, c);
                b = m.filter(b, a)
            }
            return m.grep(a, function (a) {
                return m.inArray(a, b) >= 0 !== c
            })
        }

        m.filter = function (a, b, c) {
            var d = b[0];
            return c && (a = ":not(" + a + ")"), 1 === b.length && 1 === d.nodeType ? m.find.matchesSelector(d, a) ? [d] : [] : m.find.matches(a, m.grep(b, function (a) {
                return 1 === a.nodeType
            }))
        }, m.fn.extend({
            find: function (a) {
                var b, c = [], d = this, e = d.length;
                if ("string" != typeof a) return this.pushStack(m(a).filter(function () {
                    for (b = 0; e > b; b++) if (m.contains(d[b], this)) return !0
                }));
                for (b = 0; e > b; b++) m.find(a, d[b], c);
                return c = this.pushStack(e > 1 ? m.unique(c) : c), c.selector = this.selector ? this.selector + " " + a : a, c
            }, filter: function (a) {
                return this.pushStack(w(this, a || [], !1))
            }, not: function (a) {
                return this.pushStack(w(this, a || [], !0))
            }, is: function (a) {
                return !!w(this, "string" == typeof a && t.test(a) ? m(a) : a || [], !1).length
            }
        });
        var x, y = a.document, z = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, A = m.fn.init = function (a, b) {
            var c, d;
            if (!a) return this;
            if ("string" == typeof a) {
                if (c = "<" === a.charAt(0) && ">" === a.charAt(a.length - 1) && a.length >= 3 ? [null, a, null] : z.exec(a), !c || !c[1] && b) return !b || b.jquery ? (b || x).find(a) : this.constructor(b).find(a);
                if (c[1]) {
                    if (b = b instanceof m ? b[0] : b, m.merge(this, m.parseHTML(c[1], b && b.nodeType ? b.ownerDocument || b : y, !0)), u.test(c[1]) && m.isPlainObject(b)) for (c in b) m.isFunction(this[c]) ? this[c](b[c]) : this.attr(c, b[c]);
                    return this
                }
                if (d = y.getElementById(c[2]), d && d.parentNode) {
                    if (d.id !== c[2]) return x.find(a);
                    this.length = 1, this[0] = d
                }
                return this.context = y, this.selector = a, this
            }
            return a.nodeType ? (this.context = this[0] = a, this.length = 1, this) : m.isFunction(a) ? "undefined" != typeof x.ready ? x.ready(a) : a(m) : (void 0 !== a.selector && (this.selector = a.selector, this.context = a.context), m.makeArray(a, this))
        };
        A.prototype = m.fn, x = m(y);
        var B = /^(?:parents|prev(?:Until|All))/, C = {children: !0, contents: !0, next: !0, prev: !0};
        m.extend({
            dir: function (a, b, c) {
                var d = [], e = a[b];
                while (e && 9 !== e.nodeType && (void 0 === c || 1 !== e.nodeType || !m(e).is(c))) 1 === e.nodeType && d.push(e), e = e[b];
                return d
            }, sibling: function (a, b) {
                for (var c = []; a; a = a.nextSibling) 1 === a.nodeType && a !== b && c.push(a);
                return c
            }
        }), m.fn.extend({
            has: function (a) {
                var b, c = m(a, this), d = c.length;
                return this.filter(function () {
                    for (b = 0; d > b; b++) if (m.contains(this, c[b])) return !0
                })
            }, closest: function (a, b) {
                for (var c, d = 0, e = this.length, f = [], g = t.test(a) || "string" != typeof a ? m(a, b || this.context) : 0; e > d; d++) for (c = this[d]; c && c !== b; c = c.parentNode) if (c.nodeType < 11 && (g ? g.index(c) > -1 : 1 === c.nodeType && m.find.matchesSelector(c, a))) {
                    f.push(c);
                    break
                }
                return this.pushStack(f.length > 1 ? m.unique(f) : f)
            }, index: function (a) {
                return a ? "string" == typeof a ? m.inArray(this[0], m(a)) : m.inArray(a.jquery ? a[0] : a, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
            }, add: function (a, b) {
                return this.pushStack(m.unique(m.merge(this.get(), m(a, b))))
            }, addBack: function (a) {
                return this.add(null == a ? this.prevObject : this.prevObject.filter(a))
            }
        });

        function D(a, b) {
            do a = a[b]; while (a && 1 !== a.nodeType);
            return a
        }

        m.each({
            parent: function (a) {
                var b = a.parentNode;
                return b && 11 !== b.nodeType ? b : null
            }, parents: function (a) {
                return m.dir(a, "parentNode")
            }, parentsUntil: function (a, b, c) {
                return m.dir(a, "parentNode", c)
            }, next: function (a) {
                return D(a, "nextSibling")
            }, prev: function (a) {
                return D(a, "previousSibling")
            }, nextAll: function (a) {
                return m.dir(a, "nextSibling")
            }, prevAll: function (a) {
                return m.dir(a, "previousSibling")
            }, nextUntil: function (a, b, c) {
                return m.dir(a, "nextSibling", c)
            }, prevUntil: function (a, b, c) {
                return m.dir(a, "previousSibling", c)
            }, siblings: function (a) {
                return m.sibling((a.parentNode || {}).firstChild, a)
            }, children: function (a) {
                return m.sibling(a.firstChild)
            }, contents: function (a) {
                return m.nodeName(a, "iframe") ? a.contentDocument || a.contentWindow.document : m.merge([], a.childNodes)
            }
        }, function (a, b) {
            m.fn[a] = function (c, d) {
                var e = m.map(this, b, c);
                return "Until" !== a.slice(-5) && (d = c), d && "string" == typeof d && (e = m.filter(d, e)), this.length > 1 && (C[a] || (e = m.unique(e)), B.test(a) && (e = e.reverse())), this.pushStack(e)
            }
        });
        var E = /\S+/g, F = {};

        function G(a) {
            var b = F[a] = {};
            return m.each(a.match(E) || [], function (a, c) {
                b[c] = !0
            }), b
        }

        m.Callbacks = function (a) {
            a = "string" == typeof a ? F[a] || G(a) : m.extend({}, a);
            var b, c, d, e, f, g, h = [], i = !a.once && [], j = function (l) {
                for (c = a.memory && l, d = !0, f = g || 0, g = 0, e = h.length, b = !0; h && e > f; f++) if (h[f].apply(l[0], l[1]) === !1 && a.stopOnFalse) {
                    c = !1;
                    break
                }
                b = !1, h && (i ? i.length && j(i.shift()) : c ? h = [] : k.disable())
            }, k = {
                add: function () {
                    if (h) {
                        var d = h.length;
                        !function f(b) {
                            m.each(b, function (b, c) {
                                var d = m.type(c);
                                "function" === d ? a.unique && k.has(c) || h.push(c) : c && c.length && "string" !== d && f(c)
                            })
                        }(arguments), b ? e = h.length : c && (g = d, j(c))
                    }
                    return this
                }, remove: function () {
                    return h && m.each(arguments, function (a, c) {
                        var d;
                        while ((d = m.inArray(c, h, d)) > -1) h.splice(d, 1), b && (e >= d && e--, f >= d && f--)
                    }), this
                }, has: function (a) {
                    return a ? m.inArray(a, h) > -1 : !(!h || !h.length)
                }, empty: function () {
                    return h = [], e = 0, this
                }, disable: function () {
                    return h = i = c = void 0, this
                }, disabled: function () {
                    return !h
                }, lock: function () {
                    return i = void 0, c || k.disable(), this
                }, locked: function () {
                    return !i
                }, fireWith: function (a, c) {
                    return !h || d && !i || (c = c || [], c = [a, c.slice ? c.slice() : c], b ? i.push(c) : j(c)), this
                }, fire: function () {
                    return k.fireWith(this, arguments), this
                }, fired: function () {
                    return !!d
                }
            };
            return k
        }, m.extend({
            Deferred: function (a) {
                var b = [["resolve", "done", m.Callbacks("once memory"), "resolved"], ["reject", "fail", m.Callbacks("once memory"), "rejected"], ["notify", "progress", m.Callbacks("memory")]],
                    c = "pending", d = {
                        state: function () {
                            return c
                        }, always: function () {
                            return e.done(arguments).fail(arguments), this
                        }, then: function () {
                            var a = arguments;
                            return m.Deferred(function (c) {
                                m.each(b, function (b, f) {
                                    var g = m.isFunction(a[b]) && a[b];
                                    e[f[1]](function () {
                                        var a = g && g.apply(this, arguments);
                                        a && m.isFunction(a.promise) ? a.promise().done(c.resolve).fail(c.reject).progress(c.notify) : c[f[0] + "With"](this === d ? c.promise() : this, g ? [a] : arguments)
                                    })
                                }), a = null
                            }).promise()
                        }, promise: function (a) {
                            return null != a ? m.extend(a, d) : d
                        }
                    }, e = {};
                return d.pipe = d.then, m.each(b, function (a, f) {
                    var g = f[2], h = f[3];
                    d[f[1]] = g.add, h && g.add(function () {
                        c = h
                    }, b[1 ^ a][2].disable, b[2][2].lock), e[f[0]] = function () {
                        return e[f[0] + "With"](this === e ? d : this, arguments), this
                    }, e[f[0] + "With"] = g.fireWith
                }), d.promise(e), a && a.call(e, e), e
            }, when: function (a) {
                var b = 0, c = d.call(arguments), e = c.length, f = 1 !== e || a && m.isFunction(a.promise) ? e : 0,
                    g = 1 === f ? a : m.Deferred(), h = function (a, b, c) {
                        return function (e) {
                            b[a] = this, c[a] = arguments.length > 1 ? d.call(arguments) : e, c === i ? g.notifyWith(b, c) : --f || g.resolveWith(b, c)
                        }
                    }, i, j, k;
                if (e > 1) for (i = new Array(e), j = new Array(e), k = new Array(e); e > b; b++) c[b] && m.isFunction(c[b].promise) ? c[b].promise().done(h(b, k, c)).fail(g.reject).progress(h(b, j, i)) : --f;
                return f || g.resolveWith(k, c), g.promise()
            }
        });
        var H;
        m.fn.ready = function (a) {
            return m.ready.promise().done(a), this
        }, m.extend({
            isReady: !1, readyWait: 1, holdReady: function (a) {
                a ? m.readyWait++ : m.ready(!0)
            }, ready: function (a) {
                if (a === !0 ? !--m.readyWait : !m.isReady) {
                    if (!y.body) return setTimeout(m.ready);
                    m.isReady = !0, a !== !0 && --m.readyWait > 0 || (H.resolveWith(y, [m]), m.fn.triggerHandler && (m(y).triggerHandler("ready"), m(y).off("ready")))
                }
            }
        });

        function I() {
            y.addEventListener ? (y.removeEventListener("DOMContentLoaded", J, !1), a.removeEventListener("load", J, !1)) : (y.detachEvent("onreadystatechange", J), a.detachEvent("onload", J))
        }

        function J() {
            (y.addEventListener || "load" === event.type || "complete" === y.readyState) && (I(), m.ready())
        }

        m.ready.promise = function (b) {
            if (!H) if (H = m.Deferred(), "complete" === y.readyState) setTimeout(m.ready); else if (y.addEventListener) y.addEventListener("DOMContentLoaded", J, !1), a.addEventListener("load", J, !1); else {
                y.attachEvent("onreadystatechange", J), a.attachEvent("onload", J);
                var c = !1;
                try {
                    c = null == a.frameElement && y.documentElement
                } catch (d) {
                }
                c && c.doScroll && !function e() {
                    if (!m.isReady) {
                        try {
                            c.doScroll("left")
                        } catch (a) {
                            return setTimeout(e, 50)
                        }
                        I(), m.ready()
                    }
                }()
            }
            return H.promise(b)
        };
        var K = "undefined", L;
        for (L in m(k)) break;
        k.ownLast = "0" !== L, k.inlineBlockNeedsLayout = !1, m(function () {
            var a, b, c, d;
            c = y.getElementsByTagName("body")[0], c && c.style && (b = y.createElement("div"), d = y.createElement("div"), d.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", c.appendChild(d).appendChild(b), typeof b.style.zoom !== K && (b.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1", k.inlineBlockNeedsLayout = a = 3 === b.offsetWidth, a && (c.style.zoom = 1)), c.removeChild(d))
        }), function () {
            var a = y.createElement("div");
            if (null == k.deleteExpando) {
                k.deleteExpando = !0;
                try {
                    delete a.test
                } catch (b) {
                    k.deleteExpando = !1
                }
            }
            a = null
        }(), m.acceptData = function (a) {
            var b = m.noData[(a.nodeName + " ").toLowerCase()], c = +a.nodeType || 1;
            return 1 !== c && 9 !== c ? !1 : !b || b !== !0 && a.getAttribute("classid") === b
        };
        var M = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, N = /([A-Z])/g;

        function O(a, b, c) {
            if (void 0 === c && 1 === a.nodeType) {
                var d = "data-" + b.replace(N, "-$1").toLowerCase();
                if (c = a.getAttribute(d), "string" == typeof c) {
                    try {
                        c = "true" === c ? !0 : "false" === c ? !1 : "null" === c ? null : +c + "" === c ? +c : M.test(c) ? m.parseJSON(c) : c
                    } catch (e) {
                    }
                    m.data(a, b, c)
                } else c = void 0
            }
            return c
        }

        function P(a) {
            var b;
            for (b in a) if (("data" !== b || !m.isEmptyObject(a[b])) && "toJSON" !== b) return !1;

            return !0
        }

        function Q(a, b, d, e) {
            if (m.acceptData(a)) {
                var f, g, h = m.expando, i = a.nodeType, j = i ? m.cache : a, k = i ? a[h] : a[h] && h;
                if (k && j[k] && (e || j[k].data) || void 0 !== d || "string" != typeof b) return k || (k = i ? a[h] = c.pop() || m.guid++ : h), j[k] || (j[k] = i ? {} : {toJSON: m.noop}), ("object" == typeof b || "function" == typeof b) && (e ? j[k] = m.extend(j[k], b) : j[k].data = m.extend(j[k].data, b)), g = j[k], e || (g.data || (g.data = {}), g = g.data), void 0 !== d && (g[m.camelCase(b)] = d), "string" == typeof b ? (f = g[b], null == f && (f = g[m.camelCase(b)])) : f = g, f
            }
        }

        function R(a, b, c) {
            if (m.acceptData(a)) {
                var d, e, f = a.nodeType, g = f ? m.cache : a, h = f ? a[m.expando] : m.expando;
                if (g[h]) {
                    if (b && (d = c ? g[h] : g[h].data)) {
                        m.isArray(b) ? b = b.concat(m.map(b, m.camelCase)) : b in d ? b = [b] : (b = m.camelCase(b), b = b in d ? [b] : b.split(" ")), e = b.length;
                        while (e--) delete d[b[e]];
                        if (c ? !P(d) : !m.isEmptyObject(d)) return
                    }
                    (c || (delete g[h].data, P(g[h]))) && (f ? m.cleanData([a], !0) : k.deleteExpando || g != g.window ? delete g[h] : g[h] = null)
                }
            }
        }

        m.extend({
            cache: {},
            noData: {"applet ": !0, "embed ": !0, "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"},
            hasData: function (a) {
                return a = a.nodeType ? m.cache[a[m.expando]] : a[m.expando], !!a && !P(a)
            },
            data: function (a, b, c) {
                return Q(a, b, c)
            },
            removeData: function (a, b) {
                return R(a, b)
            },
            _data: function (a, b, c) {
                return Q(a, b, c, !0)
            },
            _removeData: function (a, b) {
                return R(a, b, !0)
            }
        }), m.fn.extend({
            data: function (a, b) {
                var c, d, e, f = this[0], g = f && f.attributes;
                if (void 0 === a) {
                    if (this.length && (e = m.data(f), 1 === f.nodeType && !m._data(f, "parsedAttrs"))) {
                        c = g.length;
                        while (c--) g[c] && (d = g[c].name, 0 === d.indexOf("data-") && (d = m.camelCase(d.slice(5)), O(f, d, e[d])));
                        m._data(f, "parsedAttrs", !0)
                    }
                    return e
                }
                return "object" == typeof a ? this.each(function () {
                    m.data(this, a)
                }) : arguments.length > 1 ? this.each(function () {
                    m.data(this, a, b)
                }) : f ? O(f, a, m.data(f, a)) : void 0
            }, removeData: function (a) {
                return this.each(function () {
                    m.removeData(this, a)
                })
            }
        }), m.extend({
            queue: function (a, b, c) {
                var d;
                return a ? (b = (b || "fx") + "queue", d = m._data(a, b), c && (!d || m.isArray(c) ? d = m._data(a, b, m.makeArray(c)) : d.push(c)), d || []) : void 0
            }, dequeue: function (a, b) {
                b = b || "fx";
                var c = m.queue(a, b), d = c.length, e = c.shift(), f = m._queueHooks(a, b), g = function () {
                    m.dequeue(a, b)
                };
                "inprogress" === e && (e = c.shift(), d--), e && ("fx" === b && c.unshift("inprogress"), delete f.stop, e.call(a, g, f)), !d && f && f.empty.fire()
            }, _queueHooks: function (a, b) {
                var c = b + "queueHooks";
                return m._data(a, c) || m._data(a, c, {
                    empty: m.Callbacks("once memory").add(function () {
                        m._removeData(a, b + "queue"), m._removeData(a, c)
                    })
                })
            }
        }), m.fn.extend({
            queue: function (a, b) {
                var c = 2;
                return "string" != typeof a && (b = a, a = "fx", c--), arguments.length < c ? m.queue(this[0], a) : void 0 === b ? this : this.each(function () {
                    var c = m.queue(this, a, b);
                    m._queueHooks(this, a), "fx" === a && "inprogress" !== c[0] && m.dequeue(this, a)
                })
            }, dequeue: function (a) {
                return this.each(function () {
                    m.dequeue(this, a)
                })
            }, clearQueue: function (a) {
                return this.queue(a || "fx", [])
            }, promise: function (a, b) {
                var c, d = 1, e = m.Deferred(), f = this, g = this.length, h = function () {
                    --d || e.resolveWith(f, [f])
                };
                "string" != typeof a && (b = a, a = void 0), a = a || "fx";
                while (g--) c = m._data(f[g], a + "queueHooks"), c && c.empty && (d++, c.empty.add(h));
                return h(), e.promise(b)
            }
        });
        var S = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, T = ["Top", "Right", "Bottom", "Left"],
            U = function (a, b) {
                return a = b || a, "none" === m.css(a, "display") || !m.contains(a.ownerDocument, a)
            }, V = m.access = function (a, b, c, d, e, f, g) {
                var h = 0, i = a.length, j = null == c;
                if ("object" === m.type(c)) {
                    e = !0;
                    for (h in c) m.access(a, b, h, c[h], !0, f, g)
                } else if (void 0 !== d && (e = !0, m.isFunction(d) || (g = !0), j && (g ? (b.call(a, d), b = null) : (j = b, b = function (a, b, c) {
                    return j.call(m(a), c)
                })), b)) for (; i > h; h++) b(a[h], c, g ? d : d.call(a[h], h, b(a[h], c)));
                return e ? a : j ? b.call(a) : i ? b(a[0], c) : f
            }, W = /^(?:checkbox|radio)$/i;
        !function () {
            var a = y.createElement("input"), b = y.createElement("div"), c = y.createDocumentFragment();
            if (b.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", k.leadingWhitespace = 3 === b.firstChild.nodeType, k.tbody = !b.getElementsByTagName("tbody").length, k.htmlSerialize = !!b.getElementsByTagName("link").length, k.html5Clone = "<:nav></:nav>" !== y.createElement("nav").cloneNode(!0).outerHTML, a.type = "checkbox", a.checked = !0, c.appendChild(a), k.appendChecked = a.checked, b.innerHTML = "<textarea>x</textarea>", k.noCloneChecked = !!b.cloneNode(!0).lastChild.defaultValue, c.appendChild(b), b.innerHTML = "<input type='radio' checked='checked' name='t'/>", k.checkClone = b.cloneNode(!0).cloneNode(!0).lastChild.checked, k.noCloneEvent = !0, b.attachEvent && (b.attachEvent("onclick", function () {
                k.noCloneEvent = !1
            }), b.cloneNode(!0).click()), null == k.deleteExpando) {
                k.deleteExpando = !0;
                try {
                    delete b.test
                } catch (d) {
                    k.deleteExpando = !1
                }
            }
        }(), function () {
            var b, c, d = y.createElement("div");
            for (b in{
                submit: !0,
                change: !0,
                focusin: !0
            }) c = "on" + b, (k[b + "Bubbles"] = c in a) || (d.setAttribute(c, "t"), k[b + "Bubbles"] = d.attributes[c].expando === !1);
            d = null
        }();
        var X = /^(?:input|select|textarea)$/i, Y = /^key/, Z = /^(?:mouse|pointer|contextmenu)|click/,
            $ = /^(?:focusinfocus|focusoutblur)$/, _ = /^([^.]*)(?:\.(.+)|)$/;

        function aa() {
            return !0
        }

        function ba() {
            return !1
        }

        function ca() {
            try {
                return y.activeElement
            } catch (a) {
            }
        }

        m.event = {
            global: {},
            add: function (a, b, c, d, e) {
                var f, g, h, i, j, k, l, n, o, p, q, r = m._data(a);
                if (r) {
                    c.handler && (i = c, c = i.handler, e = i.selector), c.guid || (c.guid = m.guid++), (g = r.events) || (g = r.events = {}), (k = r.handle) || (k = r.handle = function (a) {
                        return typeof m === K || a && m.event.triggered === a.type ? void 0 : m.event.dispatch.apply(k.elem, arguments)
                    }, k.elem = a), b = (b || "").match(E) || [""], h = b.length;
                    while (h--) f = _.exec(b[h]) || [], o = q = f[1], p = (f[2] || "").split(".").sort(), o && (j = m.event.special[o] || {}, o = (e ? j.delegateType : j.bindType) || o, j = m.event.special[o] || {}, l = m.extend({
                        type: o,
                        origType: q,
                        data: d,
                        handler: c,
                        guid: c.guid,
                        selector: e,
                        needsContext: e && m.expr.match.needsContext.test(e),
                        namespace: p.join(".")
                    }, i), (n = g[o]) || (n = g[o] = [], n.delegateCount = 0, j.setup && j.setup.call(a, d, p, k) !== !1 || (a.addEventListener ? a.addEventListener(o, k, !1) : a.attachEvent && a.attachEvent("on" + o, k))), j.add && (j.add.call(a, l), l.handler.guid || (l.handler.guid = c.guid)), e ? n.splice(n.delegateCount++, 0, l) : n.push(l), m.event.global[o] = !0);
                    a = null
                }
            },
            remove: function (a, b, c, d, e) {
                var f, g, h, i, j, k, l, n, o, p, q, r = m.hasData(a) && m._data(a);
                if (r && (k = r.events)) {
                    b = (b || "").match(E) || [""], j = b.length;
                    while (j--) if (h = _.exec(b[j]) || [], o = q = h[1], p = (h[2] || "").split(".").sort(), o) {
                        l = m.event.special[o] || {}, o = (d ? l.delegateType : l.bindType) || o, n = k[o] || [], h = h[2] && new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)"), i = f = n.length;
                        while (f--) g = n[f], !e && q !== g.origType || c && c.guid !== g.guid || h && !h.test(g.namespace) || d && d !== g.selector && ("**" !== d || !g.selector) || (n.splice(f, 1), g.selector && n.delegateCount--, l.remove && l.remove.call(a, g));
                        i && !n.length && (l.teardown && l.teardown.call(a, p, r.handle) !== !1 || m.removeEvent(a, o, r.handle), delete k[o])
                    } else for (o in k) m.event.remove(a, o + b[j], c, d, !0);
                    m.isEmptyObject(k) && (delete r.handle, m._removeData(a, "events"))
                }
            },
            trigger: function (b, c, d, e) {
                var f, g, h, i, k, l, n, o = [d || y], p = j.call(b, "type") ? b.type : b,
                    q = j.call(b, "namespace") ? b.namespace.split(".") : [];
                if (h = l = d = d || y, 3 !== d.nodeType && 8 !== d.nodeType && !$.test(p + m.event.triggered) && (p.indexOf(".") >= 0 && (q = p.split("."), p = q.shift(), q.sort()), g = p.indexOf(":") < 0 && "on" + p, b = b[m.expando] ? b : new m.Event(p, "object" == typeof b && b), b.isTrigger = e ? 2 : 3, b.namespace = q.join("."), b.namespace_re = b.namespace ? new RegExp("(^|\\.)" + q.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, b.result = void 0, b.target || (b.target = d), c = null == c ? [b] : m.makeArray(c, [b]), k = m.event.special[p] || {}, e || !k.trigger || k.trigger.apply(d, c) !== !1)) {
                    if (!e && !k.noBubble && !m.isWindow(d)) {
                        for (i = k.delegateType || p, $.test(i + p) || (h = h.parentNode); h; h = h.parentNode) o.push(h), l = h;
                        l === (d.ownerDocument || y) && o.push(l.defaultView || l.parentWindow || a)
                    }
                    n = 0;
                    while ((h = o[n++]) && !b.isPropagationStopped()) b.type = n > 1 ? i : k.bindType || p, f = (m._data(h, "events") || {})[b.type] && m._data(h, "handle"), f && f.apply(h, c), f = g && h[g], f && f.apply && m.acceptData(h) && (b.result = f.apply(h, c), b.result === !1 && b.preventDefault());
                    if (b.type = p, !e && !b.isDefaultPrevented() && (!k._default || k._default.apply(o.pop(), c) === !1) && m.acceptData(d) && g && d[p] && !m.isWindow(d)) {
                        l = d[g], l && (d[g] = null), m.event.triggered = p;
                        try {
                            d[p]()
                        } catch (r) {
                        }
                        m.event.triggered = void 0, l && (d[g] = l)
                    }
                    return b.result
                }
            },
            dispatch: function (a) {
                a = m.event.fix(a);
                var b, c, e, f, g, h = [], i = d.call(arguments), j = (m._data(this, "events") || {})[a.type] || [],
                    k = m.event.special[a.type] || {};
                if (i[0] = a, a.delegateTarget = this, !k.preDispatch || k.preDispatch.call(this, a) !== !1) {
                    h = m.event.handlers.call(this, a, j), b = 0;
                    while ((f = h[b++]) && !a.isPropagationStopped()) {
                        a.currentTarget = f.elem, g = 0;
                        while ((e = f.handlers[g++]) && !a.isImmediatePropagationStopped()) (!a.namespace_re || a.namespace_re.test(e.namespace)) && (a.handleObj = e, a.data = e.data, c = ((m.event.special[e.origType] || {}).handle || e.handler).apply(f.elem, i), void 0 !== c && (a.result = c) === !1 && (a.preventDefault(), a.stopPropagation()))
                    }
                    return k.postDispatch && k.postDispatch.call(this, a), a.result
                }
            },
            handlers: function (a, b) {
                var c, d, e, f, g = [], h = b.delegateCount, i = a.target;
                if (h && i.nodeType && (!a.button || "click" !== a.type)) for (; i != this; i = i.parentNode || this) if (1 === i.nodeType && (i.disabled !== !0 || "click" !== a.type)) {
                    for (e = [], f = 0; h > f; f++) d = b[f], c = d.selector + " ", void 0 === e[c] && (e[c] = d.needsContext ? m(c, this).index(i) >= 0 : m.find(c, this, null, [i]).length), e[c] && e.push(d);
                    e.length && g.push({elem: i, handlers: e})
                }
                return h < b.length && g.push({elem: this, handlers: b.slice(h)}), g
            },
            fix: function (a) {
                if (a[m.expando]) return a;
                var b, c, d, e = a.type, f = a, g = this.fixHooks[e];
                g || (this.fixHooks[e] = g = Z.test(e) ? this.mouseHooks : Y.test(e) ? this.keyHooks : {}), d = g.props ? this.props.concat(g.props) : this.props, a = new m.Event(f), b = d.length;
                while (b--) c = d[b], a[c] = f[c];
                return a.target || (a.target = f.srcElement || y), 3 === a.target.nodeType && (a.target = a.target.parentNode), a.metaKey = !!a.metaKey, g.filter ? g.filter(a, f) : a
            },
            props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
            fixHooks: {},
            keyHooks: {
                props: "char charCode key keyCode".split(" "), filter: function (a, b) {
                    return null == a.which && (a.which = null != b.charCode ? b.charCode : b.keyCode), a
                }
            },
            mouseHooks: {
                props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
                filter: function (a, b) {
                    var c, d, e, f = b.button, g = b.fromElement;
                    return null == a.pageX && null != b.clientX && (d = a.target.ownerDocument || y, e = d.documentElement, c = d.body, a.pageX = b.clientX + (e && e.scrollLeft || c && c.scrollLeft || 0) - (e && e.clientLeft || c && c.clientLeft || 0), a.pageY = b.clientY + (e && e.scrollTop || c && c.scrollTop || 0) - (e && e.clientTop || c && c.clientTop || 0)), !a.relatedTarget && g && (a.relatedTarget = g === a.target ? b.toElement : g), a.which || void 0 === f || (a.which = 1 & f ? 1 : 2 & f ? 3 : 4 & f ? 2 : 0), a
                }
            },
            special: {
                load: {noBubble: !0}, focus: {
                    trigger: function () {
                        if (this !== ca() && this.focus) try {
                            return this.focus(), !1
                        } catch (a) {
                        }
                    }, delegateType: "focusin"
                }, blur: {
                    trigger: function () {
                        return this === ca() && this.blur ? (this.blur(), !1) : void 0
                    }, delegateType: "focusout"
                }, click: {
                    trigger: function () {
                        return m.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                    }, _default: function (a) {
                        return m.nodeName(a.target, "a")
                    }
                }, beforeunload: {
                    postDispatch: function (a) {
                        void 0 !== a.result && a.originalEvent && (a.originalEvent.returnValue = a.result)
                    }
                }
            },
            simulate: function (a, b, c, d) {
                var e = m.extend(new m.Event, c, {type: a, isSimulated: !0, originalEvent: {}});
                d ? m.event.trigger(e, null, b) : m.event.dispatch.call(b, e), e.isDefaultPrevented() && c.preventDefault()
            }
        }, m.removeEvent = y.removeEventListener ? function (a, b, c) {
            a.removeEventListener && a.removeEventListener(b, c, !1)
        } : function (a, b, c) {
            var d = "on" + b;
            a.detachEvent && (typeof a[d] === K && (a[d] = null), a.detachEvent(d, c))
        }, m.Event = function (a, b) {
            return this instanceof m.Event ? (a && a.type ? (this.originalEvent = a, this.type = a.type, this.isDefaultPrevented = a.defaultPrevented || void 0 === a.defaultPrevented && a.returnValue === !1 ? aa : ba) : this.type = a, b && m.extend(this, b), this.timeStamp = a && a.timeStamp || m.now(), void(this[m.expando] = !0)) : new m.Event(a, b)
        }, m.Event.prototype = {
            isDefaultPrevented: ba,
            isPropagationStopped: ba,
            isImmediatePropagationStopped: ba,
            preventDefault: function () {
                var a = this.originalEvent;
                this.isDefaultPrevented = aa, a && (a.preventDefault ? a.preventDefault() : a.returnValue = !1)
            },
            stopPropagation: function () {
                var a = this.originalEvent;
                this.isPropagationStopped = aa, a && (a.stopPropagation && a.stopPropagation(), a.cancelBubble = !0)
            },
            stopImmediatePropagation: function () {
                var a = this.originalEvent;
                this.isImmediatePropagationStopped = aa, a && a.stopImmediatePropagation && a.stopImmediatePropagation(), this.stopPropagation()
            }
        }, m.each({
            mouseenter: "mouseover",
            mouseleave: "mouseout",
            pointerenter: "pointerover",
            pointerleave: "pointerout"
        }, function (a, b) {
            m.event.special[a] = {
                delegateType: b, bindType: b, handle: function (a) {
                    var c, d = this, e = a.relatedTarget, f = a.handleObj;
                    return (!e || e !== d && !m.contains(d, e)) && (a.type = f.origType, c = f.handler.apply(this, arguments), a.type = b), c
                }
            }
        }), k.submitBubbles || (m.event.special.submit = {
            setup: function () {
                return m.nodeName(this, "form") ? !1 : void m.event.add(this, "click._submit keypress._submit", function (a) {
                    var b = a.target, c = m.nodeName(b, "input") || m.nodeName(b, "button") ? b.form : void 0;
                    c && !m._data(c, "submitBubbles") && (m.event.add(c, "submit._submit", function (a) {
                        a._submit_bubble = !0
                    }), m._data(c, "submitBubbles", !0))
                })
            }, postDispatch: function (a) {
                a._submit_bubble && (delete a._submit_bubble, this.parentNode && !a.isTrigger && m.event.simulate("submit", this.parentNode, a, !0))
            }, teardown: function () {
                return m.nodeName(this, "form") ? !1 : void m.event.remove(this, "._submit")
            }
        }), k.changeBubbles || (m.event.special.change = {
            setup: function () {
                return X.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (m.event.add(this, "propertychange._change", function (a) {
                    "checked" === a.originalEvent.propertyName && (this._just_changed = !0)
                }), m.event.add(this, "click._change", function (a) {
                    this._just_changed && !a.isTrigger && (this._just_changed = !1), m.event.simulate("change", this, a, !0)
                })), !1) : void m.event.add(this, "beforeactivate._change", function (a) {
                    var b = a.target;
                    X.test(b.nodeName) && !m._data(b, "changeBubbles") && (m.event.add(b, "change._change", function (a) {
                        !this.parentNode || a.isSimulated || a.isTrigger || m.event.simulate("change", this.parentNode, a, !0)
                    }), m._data(b, "changeBubbles", !0))
                })
            }, handle: function (a) {
                var b = a.target;
                return this !== b || a.isSimulated || a.isTrigger || "radio" !== b.type && "checkbox" !== b.type ? a.handleObj.handler.apply(this, arguments) : void 0
            }, teardown: function () {
                return m.event.remove(this, "._change"), !X.test(this.nodeName)
            }
        }), k.focusinBubbles || m.each({focus: "focusin", blur: "focusout"}, function (a, b) {
            var c = function (a) {
                m.event.simulate(b, a.target, m.event.fix(a), !0)
            };
            m.event.special[b] = {
                setup: function () {
                    var d = this.ownerDocument || this, e = m._data(d, b);
                    e || d.addEventListener(a, c, !0), m._data(d, b, (e || 0) + 1)
                }, teardown: function () {
                    var d = this.ownerDocument || this, e = m._data(d, b) - 1;
                    e ? m._data(d, b, e) : (d.removeEventListener(a, c, !0), m._removeData(d, b))
                }
            }
        }), m.fn.extend({
            on: function (a, b, c, d, e) {
                var f, g;
                if ("object" == typeof a) {
                    "string" != typeof b && (c = c || b, b = void 0);
                    for (f in a) this.on(f, b, c, a[f], e);
                    return this
                }
                if (null == c && null == d ? (d = b, c = b = void 0) : null == d && ("string" == typeof b ? (d = c, c = void 0) : (d = c, c = b, b = void 0)), d === !1) d = ba; else if (!d) return this;
                return 1 === e && (g = d, d = function (a) {
                    return m().off(a), g.apply(this, arguments)
                }, d.guid = g.guid || (g.guid = m.guid++)), this.each(function () {
                    m.event.add(this, a, d, c, b)
                })
            }, one: function (a, b, c, d) {
                return this.on(a, b, c, d, 1)
            }, off: function (a, b, c) {
                var d, e;
                if (a && a.preventDefault && a.handleObj) return d = a.handleObj, m(a.delegateTarget).off(d.namespace ? d.origType + "." + d.namespace : d.origType, d.selector, d.handler), this;
                if ("object" == typeof a) {
                    for (e in a) this.off(e, b, a[e]);
                    return this
                }
                return (b === !1 || "function" == typeof b) && (c = b, b = void 0), c === !1 && (c = ba), this.each(function () {
                    m.event.remove(this, a, c, b)
                })
            }, trigger: function (a, b) {
                return this.each(function () {
                    m.event.trigger(a, b, this)
                })
            }, triggerHandler: function (a, b) {
                var c = this[0];
                return c ? m.event.trigger(a, b, c, !0) : void 0
            }
        });

        function da(a) {
            var b = ea.split("|"), c = a.createDocumentFragment();
            if (c.createElement) while (b.length) c.createElement(b.pop());
            return c
        }

        var ea = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
            fa = / jQuery\d+="(?:null|\d+)"/g, ga = new RegExp("<(?:" + ea + ")[\\s/>]", "i"), ha = /^\s+/,
            ia = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, ja = /<([\w:]+)/,
            ka = /<tbody/i, la = /<|&#?\w+;/, ma = /<(?:script|style|link)/i, na = /checked\s*(?:[^=]|=\s*.checked.)/i,
            oa = /^$|\/(?:java|ecma)script/i, pa = /^true\/(.*)/, qa = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
            ra = {
                option: [1, "<select multiple='multiple'>", "</select>"],
                legend: [1, "<fieldset>", "</fieldset>"],
                area: [1, "<map>", "</map>"],
                param: [1, "<object>", "</object>"],
                thead: [1, "<table>", "</table>"],
                tr: [2, "<table><tbody>", "</tbody></table>"],
                col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
                td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
                _default: k.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
            }, sa = da(y), ta = sa.appendChild(y.createElement("div"));
        ra.optgroup = ra.option, ra.tbody = ra.tfoot = ra.colgroup = ra.caption = ra.thead, ra.th = ra.td;

        function ua(a, b) {
            var c, d, e = 0,
                f = typeof a.getElementsByTagName !== K ? a.getElementsByTagName(b || "*") : typeof a.querySelectorAll !== K ? a.querySelectorAll(b || "*") : void 0;
            if (!f) for (f = [], c = a.childNodes || a; null != (d = c[e]); e++) !b || m.nodeName(d, b) ? f.push(d) : m.merge(f, ua(d, b));
            return void 0 === b || b && m.nodeName(a, b) ? m.merge([a], f) : f
        }

        function va(a) {
            W.test(a.type) && (a.defaultChecked = a.checked)
        }

        function wa(a, b) {
            return m.nodeName(a, "table") && m.nodeName(11 !== b.nodeType ? b : b.firstChild, "tr") ? a.getElementsByTagName("tbody")[0] || a.appendChild(a.ownerDocument.createElement("tbody")) : a
        }

        function xa(a) {
            return a.type = (null !== m.find.attr(a, "type")) + "/" + a.type, a
        }

        function ya(a) {
            var b = pa.exec(a.type);
            return b ? a.type = b[1] : a.removeAttribute("type"), a
        }

        function za(a, b) {
            for (var c, d = 0; null != (c = a[d]); d++) m._data(c, "globalEval", !b || m._data(b[d], "globalEval"))
        }

        function Aa(a, b) {
            if (1 === b.nodeType && m.hasData(a)) {
                var c, d, e, f = m._data(a), g = m._data(b, f), h = f.events;
                if (h) {
                    delete g.handle, g.events = {};
                    for (c in h) for (d = 0, e = h[c].length; e > d; d++) m.event.add(b, c, h[c][d])
                }
                g.data && (g.data = m.extend({}, g.data))
            }
        }

        function Ba(a, b) {
            var c, d, e;
            if (1 === b.nodeType) {
                if (c = b.nodeName.toLowerCase(), !k.noCloneEvent && b[m.expando]) {
                    e = m._data(b);
                    for (d in e.events) m.removeEvent(b, d, e.handle);
                    b.removeAttribute(m.expando)
                }
                "script" === c && b.text !== a.text ? (xa(b).text = a.text, ya(b)) : "object" === c ? (b.parentNode && (b.outerHTML = a.outerHTML), k.html5Clone && a.innerHTML && !m.trim(b.innerHTML) && (b.innerHTML = a.innerHTML)) : "input" === c && W.test(a.type) ? (b.defaultChecked = b.checked = a.checked, b.value !== a.value && (b.value = a.value)) : "option" === c ? b.defaultSelected = b.selected = a.defaultSelected : ("input" === c || "textarea" === c) && (b.defaultValue = a.defaultValue)
            }
        }

        m.extend({
            clone: function (a, b, c) {
                var d, e, f, g, h, i = m.contains(a.ownerDocument, a);
                if (k.html5Clone || m.isXMLDoc(a) || !ga.test("<" + a.nodeName + ">") ? f = a.cloneNode(!0) : (ta.innerHTML = a.outerHTML, ta.removeChild(f = ta.firstChild)), !(k.noCloneEvent && k.noCloneChecked || 1 !== a.nodeType && 11 !== a.nodeType || m.isXMLDoc(a))) for (d = ua(f), h = ua(a), g = 0; null != (e = h[g]); ++g) d[g] && Ba(e, d[g]);
                if (b) if (c) for (h = h || ua(a), d = d || ua(f), g = 0; null != (e = h[g]); g++) Aa(e, d[g]); else Aa(a, f);
                return d = ua(f, "script"), d.length > 0 && za(d, !i && ua(a, "script")), d = h = e = null, f
            }, buildFragment: function (a, b, c, d) {
                for (var e, f, g, h, i, j, l, n = a.length, o = da(b), p = [], q = 0; n > q; q++) if (f = a[q], f || 0 === f) if ("object" === m.type(f)) m.merge(p, f.nodeType ? [f] : f); else if (la.test(f)) {
                    h = h || o.appendChild(b.createElement("div")), i = (ja.exec(f) || ["", ""])[1].toLowerCase(), l = ra[i] || ra._default, h.innerHTML = l[1] + f.replace(ia, "<$1></$2>") + l[2], e = l[0];
                    while (e--) h = h.lastChild;
                    if (!k.leadingWhitespace && ha.test(f) && p.push(b.createTextNode(ha.exec(f)[0])), !k.tbody) {
                        f = "table" !== i || ka.test(f) ? "<table>" !== l[1] || ka.test(f) ? 0 : h : h.firstChild, e = f && f.childNodes.length;
                        while (e--) m.nodeName(j = f.childNodes[e], "tbody") && !j.childNodes.length && f.removeChild(j)
                    }
                    m.merge(p, h.childNodes), h.textContent = "";
                    while (h.firstChild) h.removeChild(h.firstChild);
                    h = o.lastChild
                } else p.push(b.createTextNode(f));
                h && o.removeChild(h), k.appendChecked || m.grep(ua(p, "input"), va), q = 0;
                while (f = p[q++]) if ((!d || -1 === m.inArray(f, d)) && (g = m.contains(f.ownerDocument, f), h = ua(o.appendChild(f), "script"), g && za(h), c)) {
                    e = 0;
                    while (f = h[e++]) oa.test(f.type || "") && c.push(f)
                }
                return h = null, o
            }, cleanData: function (a, b) {
                for (var d, e, f, g, h = 0, i = m.expando, j = m.cache, l = k.deleteExpando, n = m.event.special; null != (d = a[h]); h++) if ((b || m.acceptData(d)) && (f = d[i], g = f && j[f])) {
                    if (g.events) for (e in g.events) n[e] ? m.event.remove(d, e) : m.removeEvent(d, e, g.handle);
                    j[f] && (delete j[f], l ? delete d[i] : typeof d.removeAttribute !== K ? d.removeAttribute(i) : d[i] = null, c.push(f))
                }
            }
        }), m.fn.extend({
            text: function (a) {
                return V(this, function (a) {
                    return void 0 === a ? m.text(this) : this.empty().append((this[0] && this[0].ownerDocument || y).createTextNode(a))
                }, null, a, arguments.length)
            }, append: function () {
                return this.domManip(arguments, function (a) {
                    if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                        var b = wa(this, a);
                        b.appendChild(a)
                    }
                })
            }, prepend: function () {
                return this.domManip(arguments, function (a) {
                    if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                        var b = wa(this, a);
                        b.insertBefore(a, b.firstChild)
                    }
                })
            }, before: function () {
                return this.domManip(arguments, function (a) {
                    this.parentNode && this.parentNode.insertBefore(a, this)
                })
            }, after: function () {
                return this.domManip(arguments, function (a) {
                    this.parentNode && this.parentNode.insertBefore(a, this.nextSibling)
                })
            }, remove: function (a, b) {
                for (var c, d = a ? m.filter(a, this) : this, e = 0; null != (c = d[e]); e++) b || 1 !== c.nodeType || m.cleanData(ua(c)), c.parentNode && (b && m.contains(c.ownerDocument, c) && za(ua(c, "script")), c.parentNode.removeChild(c));
                return this
            }, empty: function () {
                for (var a, b = 0; null != (a = this[b]); b++) {
                    1 === a.nodeType && m.cleanData(ua(a, !1));
                    while (a.firstChild) a.removeChild(a.firstChild);
                    a.options && m.nodeName(a, "select") && (a.options.length = 0)
                }
                return this
            }, clone: function (a, b) {
                return a = null == a ? !1 : a, b = null == b ? a : b, this.map(function () {
                    return m.clone(this, a, b)
                })
            }, html: function (a) {
                return V(this, function (a) {
                    var b = this[0] || {}, c = 0, d = this.length;
                    if (void 0 === a) return 1 === b.nodeType ? b.innerHTML.replace(fa, "") : void 0;
                    if (!("string" != typeof a || ma.test(a) || !k.htmlSerialize && ga.test(a) || !k.leadingWhitespace && ha.test(a) || ra[(ja.exec(a) || ["", ""])[1].toLowerCase()])) {
                        a = a.replace(ia, "<$1></$2>");
                        try {
                            for (; d > c; c++) b = this[c] || {}, 1 === b.nodeType && (m.cleanData(ua(b, !1)), b.innerHTML = a);
                            b = 0
                        } catch (e) {
                        }
                    }
                    b && this.empty().append(a)
                }, null, a, arguments.length)
            }, replaceWith: function () {
                var a = arguments[0];
                return this.domManip(arguments, function (b) {
                    a = this.parentNode, m.cleanData(ua(this)), a && a.replaceChild(b, this)
                }), a && (a.length || a.nodeType) ? this : this.remove()
            }, detach: function (a) {
                return this.remove(a, !0)
            }, domManip: function (a, b) {
                a = e.apply([], a);
                var c, d, f, g, h, i, j = 0, l = this.length, n = this, o = l - 1, p = a[0], q = m.isFunction(p);
                if (q || l > 1 && "string" == typeof p && !k.checkClone && na.test(p)) return this.each(function (c) {
                    var d = n.eq(c);
                    q && (a[0] = p.call(this, c, d.html())), d.domManip(a, b)
                });
                if (l && (i = m.buildFragment(a, this[0].ownerDocument, !1, this), c = i.firstChild, 1 === i.childNodes.length && (i = c), c)) {
                    for (g = m.map(ua(i, "script"), xa), f = g.length; l > j; j++) d = i, j !== o && (d = m.clone(d, !0, !0), f && m.merge(g, ua(d, "script"))), b.call(this[j], d, j);
                    if (f) for (h = g[g.length - 1].ownerDocument, m.map(g, ya), j = 0; f > j; j++) d = g[j], oa.test(d.type || "") && !m._data(d, "globalEval") && m.contains(h, d) && (d.src ? m._evalUrl && m._evalUrl(d.src) : m.globalEval((d.text || d.textContent || d.innerHTML || "").replace(qa, "")));
                    i = c = null
                }
                return this
            }
        }), m.each({
            appendTo: "append",
            prependTo: "prepend",
            insertBefore: "before",
            insertAfter: "after",
            replaceAll: "replaceWith"
        }, function (a, b) {
            m.fn[a] = function (a) {
                for (var c, d = 0, e = [], g = m(a), h = g.length - 1; h >= d; d++) c = d === h ? this : this.clone(!0), m(g[d])[b](c), f.apply(e, c.get());
                return this.pushStack(e)
            }
        });
        var Ca, Da = {};

        function Ea(b, c) {
            var d, e = m(c.createElement(b)).appendTo(c.body),
                f = a.getDefaultComputedStyle && (d = a.getDefaultComputedStyle(e[0])) ? d.display : m.css(e[0], "display");
            return e.detach(), f
        }

        function Fa(a) {
            var b = y, c = Da[a];
            return c || (c = Ea(a, b), "none" !== c && c || (Ca = (Ca || m("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement), b = (Ca[0].contentWindow || Ca[0].contentDocument).document, b.write(), b.close(), c = Ea(a, b), Ca.detach()), Da[a] = c), c
        }

        !function () {
            var a;
            k.shrinkWrapBlocks = function () {
                if (null != a) return a;
                a = !1;
                var b, c, d;
                return c = y.getElementsByTagName("body")[0], c && c.style ? (b = y.createElement("div"), d = y.createElement("div"), d.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", c.appendChild(d).appendChild(b), typeof b.style.zoom !== K && (b.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1", b.appendChild(y.createElement("div")).style.width = "5px", a = 3 !== b.offsetWidth), c.removeChild(d), a) : void 0
            }
        }();
        var Ga = /^margin/, Ha = new RegExp("^(" + S + ")(?!px)[a-z%]+$", "i"), Ia, Ja,
            Ka = /^(top|right|bottom|left)$/;
        a.getComputedStyle ? (Ia = function (b) {
            return b.ownerDocument.defaultView.opener ? b.ownerDocument.defaultView.getComputedStyle(b, null) : a.getComputedStyle(b, null)
        }, Ja = function (a, b, c) {
            var d, e, f, g, h = a.style;
            return c = c || Ia(a), g = c ? c.getPropertyValue(b) || c[b] : void 0, c && ("" !== g || m.contains(a.ownerDocument, a) || (g = m.style(a, b)), Ha.test(g) && Ga.test(b) && (d = h.width, e = h.minWidth, f = h.maxWidth, h.minWidth = h.maxWidth = h.width = g, g = c.width, h.width = d, h.minWidth = e, h.maxWidth = f)), void 0 === g ? g : g + ""
        }) : y.documentElement.currentStyle && (Ia = function (a) {
            return a.currentStyle
        }, Ja = function (a, b, c) {
            var d, e, f, g, h = a.style;
            return c = c || Ia(a), g = c ? c[b] : void 0, null == g && h && h[b] && (g = h[b]), Ha.test(g) && !Ka.test(b) && (d = h.left, e = a.runtimeStyle, f = e && e.left, f && (e.left = a.currentStyle.left), h.left = "fontSize" === b ? "1em" : g, g = h.pixelLeft + "px", h.left = d, f && (e.left = f)), void 0 === g ? g : g + "" || "auto"
        });

        function La(a, b) {
            return {
                get: function () {
                    var c = a();
                    if (null != c) return c ? void delete this.get : (this.get = b).apply(this, arguments)
                }
            }
        }

        !function () {
            var b, c, d, e, f, g, h;
            if (b = y.createElement("div"), b.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", d = b.getElementsByTagName("a")[0], c = d && d.style) {
                c.cssText = "float:left;opacity:.5", k.opacity = "0.5" === c.opacity, k.cssFloat = !!c.cssFloat, b.style.backgroundClip = "content-box", b.cloneNode(!0).style.backgroundClip = "", k.clearCloneStyle = "content-box" === b.style.backgroundClip, k.boxSizing = "" === c.boxSizing || "" === c.MozBoxSizing || "" === c.WebkitBoxSizing, m.extend(k, {
                    reliableHiddenOffsets: function () {
                        return null == g && i(), g
                    }, boxSizingReliable: function () {
                        return null == f && i(), f
                    }, pixelPosition: function () {
                        return null == e && i(), e
                    }, reliableMarginRight: function () {
                        return null == h && i(), h
                    }
                });

                function i() {
                    var b, c, d, i;
                    c = y.getElementsByTagName("body")[0], c && c.style && (b = y.createElement("div"), d = y.createElement("div"), d.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", c.appendChild(d).appendChild(b), b.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", e = f = !1, h = !0, a.getComputedStyle && (e = "1%" !== (a.getComputedStyle(b, null) || {}).top, f = "4px" === (a.getComputedStyle(b, null) || {width: "4px"}).width, i = b.appendChild(y.createElement("div")), i.style.cssText = b.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", i.style.marginRight = i.style.width = "0", b.style.width = "1px", h = !parseFloat((a.getComputedStyle(i, null) || {}).marginRight), b.removeChild(i)), b.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", i = b.getElementsByTagName("td"), i[0].style.cssText = "margin:0;border:0;padding:0;display:none", g = 0 === i[0].offsetHeight, g && (i[0].style.display = "", i[1].style.display = "none", g = 0 === i[0].offsetHeight), c.removeChild(d))
                }
            }
        }(), m.swap = function (a, b, c, d) {
            var e, f, g = {};
            for (f in b) g[f] = a.style[f], a.style[f] = b[f];
            e = c.apply(a, d || []);
            for (f in b) a.style[f] = g[f];
            return e
        };
        var Ma = /alpha\([^)]*\)/i, Na = /opacity\s*=\s*([^)]*)/, Oa = /^(none|table(?!-c[ea]).+)/,
            Pa = new RegExp("^(" + S + ")(.*)$", "i"), Qa = new RegExp("^([+-])=(" + S + ")", "i"),
            Ra = {position: "absolute", visibility: "hidden", display: "block"},
            Sa = {letterSpacing: "0", fontWeight: "400"}, Ta = ["Webkit", "O", "Moz", "ms"];

        function Ua(a, b) {
            if (b in a) return b;
            var c = b.charAt(0).toUpperCase() + b.slice(1), d = b, e = Ta.length;
            while (e--) if (b = Ta[e] + c, b in a) return b;
            return d
        }

        function Va(a, b) {
            for (var c, d, e, f = [], g = 0, h = a.length; h > g; g++) d = a[g], d.style && (f[g] = m._data(d, "olddisplay"), c = d.style.display, b ? (f[g] || "none" !== c || (d.style.display = ""), "" === d.style.display && U(d) && (f[g] = m._data(d, "olddisplay", Fa(d.nodeName)))) : (e = U(d), (c && "none" !== c || !e) && m._data(d, "olddisplay", e ? c : m.css(d, "display"))));
            for (g = 0; h > g; g++) d = a[g], d.style && (b && "none" !== d.style.display && "" !== d.style.display || (d.style.display = b ? f[g] || "" : "none"));
            return a
        }

        function Wa(a, b, c) {
            var d = Pa.exec(b);
            return d ? Math.max(0, d[1] - (c || 0)) + (d[2] || "px") : b
        }

        function Xa(a, b, c, d, e) {
            for (var f = c === (d ? "border" : "content") ? 4 : "width" === b ? 1 : 0, g = 0; 4 > f; f += 2) "margin" === c && (g += m.css(a, c + T[f], !0, e)), d ? ("content" === c && (g -= m.css(a, "padding" + T[f], !0, e)), "margin" !== c && (g -= m.css(a, "border" + T[f] + "Width", !0, e))) : (g += m.css(a, "padding" + T[f], !0, e), "padding" !== c && (g += m.css(a, "border" + T[f] + "Width", !0, e)));
            return g
        }

        function Ya(a, b, c) {
            var d = !0, e = "width" === b ? a.offsetWidth : a.offsetHeight, f = Ia(a),
                g = k.boxSizing && "border-box" === m.css(a, "boxSizing", !1, f);
            if (0 >= e || null == e) {
                if (e = Ja(a, b, f), (0 > e || null == e) && (e = a.style[b]), Ha.test(e)) return e;
                d = g && (k.boxSizingReliable() || e === a.style[b]), e = parseFloat(e) || 0
            }
            return e + Xa(a, b, c || (g ? "border" : "content"), d, f) + "px"
        }

        m.extend({
            cssHooks: {
                opacity: {
                    get: function (a, b) {
                        if (b) {
                            var c = Ja(a, "opacity");
                            return "" === c ? "1" : c
                        }
                    }
                }
            },
            cssNumber: {
                columnCount: !0,
                fillOpacity: !0,
                flexGrow: !0,
                flexShrink: !0,
                fontWeight: !0,
                lineHeight: !0,
                opacity: !0,
                order: !0,
                orphans: !0,
                widows: !0,
                zIndex: !0,
                zoom: !0
            },
            cssProps: {"float": k.cssFloat ? "cssFloat" : "styleFloat"},
            style: function (a, b, c, d) {
                if (a && 3 !== a.nodeType && 8 !== a.nodeType && a.style) {
                    var e, f, g, h = m.camelCase(b), i = a.style;
                    if (b = m.cssProps[h] || (m.cssProps[h] = Ua(i, h)), g = m.cssHooks[b] || m.cssHooks[h], void 0 === c) return g && "get" in g && void 0 !== (e = g.get(a, !1, d)) ? e : i[b];
                    if (f = typeof c, "string" === f && (e = Qa.exec(c)) && (c = (e[1] + 1) * e[2] + parseFloat(m.css(a, b)), f = "number"), null != c && c === c && ("number" !== f || m.cssNumber[h] || (c += "px"), k.clearCloneStyle || "" !== c || 0 !== b.indexOf("background") || (i[b] = "inherit"), !(g && "set" in g && void 0 === (c = g.set(a, c, d))))) try {
                        i[b] = c
                    } catch (j) {
                    }
                }
            },
            css: function (a, b, c, d) {
                var e, f, g, h = m.camelCase(b);
                return b = m.cssProps[h] || (m.cssProps[h] = Ua(a.style, h)), g = m.cssHooks[b] || m.cssHooks[h], g && "get" in g && (f = g.get(a, !0, c)), void 0 === f && (f = Ja(a, b, d)), "normal" === f && b in Sa && (f = Sa[b]), "" === c || c ? (e = parseFloat(f), c === !0 || m.isNumeric(e) ? e || 0 : f) : f
            }
        }), m.each(["height", "width"], function (a, b) {
            m.cssHooks[b] = {
                get: function (a, c, d) {
                    return c ? Oa.test(m.css(a, "display")) && 0 === a.offsetWidth ? m.swap(a, Ra, function () {
                        return Ya(a, b, d)
                    }) : Ya(a, b, d) : void 0
                }, set: function (a, c, d) {
                    var e = d && Ia(a);
                    return Wa(a, c, d ? Xa(a, b, d, k.boxSizing && "border-box" === m.css(a, "boxSizing", !1, e), e) : 0)
                }
            }
        }), k.opacity || (m.cssHooks.opacity = {
            get: function (a, b) {
                return Na.test((b && a.currentStyle ? a.currentStyle.filter : a.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : b ? "1" : ""
            }, set: function (a, b) {
                var c = a.style, d = a.currentStyle, e = m.isNumeric(b) ? "alpha(opacity=" + 100 * b + ")" : "",
                    f = d && d.filter || c.filter || "";
                c.zoom = 1, (b >= 1 || "" === b) && "" === m.trim(f.replace(Ma, "")) && c.removeAttribute && (c.removeAttribute("filter"), "" === b || d && !d.filter) || (c.filter = Ma.test(f) ? f.replace(Ma, e) : f + " " + e)
            }
        }), m.cssHooks.marginRight = La(k.reliableMarginRight, function (a, b) {
            return b ? m.swap(a, {display: "inline-block"}, Ja, [a, "marginRight"]) : void 0
        }), m.each({margin: "", padding: "", border: "Width"}, function (a, b) {
            m.cssHooks[a + b] = {
                expand: function (c) {
                    for (var d = 0, e = {}, f = "string" == typeof c ? c.split(" ") : [c]; 4 > d; d++) e[a + T[d] + b] = f[d] || f[d - 2] || f[0];
                    return e
                }
            }, Ga.test(a) || (m.cssHooks[a + b].set = Wa)
        }), m.fn.extend({
            css: function (a, b) {
                return V(this, function (a, b, c) {
                    var d, e, f = {}, g = 0;
                    if (m.isArray(b)) {
                        for (d = Ia(a), e = b.length; e > g; g++) f[b[g]] = m.css(a, b[g], !1, d);
                        return f
                    }
                    return void 0 !== c ? m.style(a, b, c) : m.css(a, b)
                }, a, b, arguments.length > 1)
            }, show: function () {
                return Va(this, !0)
            }, hide: function () {
                return Va(this)
            }, toggle: function (a) {
                return "boolean" == typeof a ? a ? this.show() : this.hide() : this.each(function () {
                    U(this) ? m(this).show() : m(this).hide()
                })
            }
        });

        function Za(a, b, c, d, e) {
            return new Za.prototype.init(a, b, c, d, e)
        }

        m.Tween = Za, Za.prototype = {
            constructor: Za, init: function (a, b, c, d, e, f) {
                this.elem = a, this.prop = c, this.easing = e || "swing", this.options = b, this.start = this.now = this.cur(), this.end = d, this.unit = f || (m.cssNumber[c] ? "" : "px")
            }, cur: function () {
                var a = Za.propHooks[this.prop];
                return a && a.get ? a.get(this) : Za.propHooks._default.get(this)
            }, run: function (a) {
                var b, c = Za.propHooks[this.prop];
                return this.options.duration ? this.pos = b = m.easing[this.easing](a, this.options.duration * a, 0, 1, this.options.duration) : this.pos = b = a, this.now = (this.end - this.start) * b + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), c && c.set ? c.set(this) : Za.propHooks._default.set(this), this
            }
        }, Za.prototype.init.prototype = Za.prototype, Za.propHooks = {
            _default: {
                get: function (a) {
                    var b;
                    return null == a.elem[a.prop] || a.elem.style && null != a.elem.style[a.prop] ? (b = m.css(a.elem, a.prop, ""), b && "auto" !== b ? b : 0) : a.elem[a.prop]
                }, set: function (a) {
                    m.fx.step[a.prop] ? m.fx.step[a.prop](a) : a.elem.style && (null != a.elem.style[m.cssProps[a.prop]] || m.cssHooks[a.prop]) ? m.style(a.elem, a.prop, a.now + a.unit) : a.elem[a.prop] = a.now
                }
            }
        }, Za.propHooks.scrollTop = Za.propHooks.scrollLeft = {
            set: function (a) {
                a.elem.nodeType && a.elem.parentNode && (a.elem[a.prop] = a.now)
            }
        }, m.easing = {
            linear: function (a) {
                return a
            }, swing: function (a) {
                return .5 - Math.cos(a * Math.PI) / 2
            }
        }, m.fx = Za.prototype.init, m.fx.step = {};
        var $a, _a, ab = /^(?:toggle|show|hide)$/, bb = new RegExp("^(?:([+-])=|)(" + S + ")([a-z%]*)$", "i"),
            cb = /queueHooks$/, db = [ib], eb = {
                "*": [function (a, b) {
                    var c = this.createTween(a, b), d = c.cur(), e = bb.exec(b),
                        f = e && e[3] || (m.cssNumber[a] ? "" : "px"),
                        g = (m.cssNumber[a] || "px" !== f && +d) && bb.exec(m.css(c.elem, a)), h = 1, i = 20;
                    if (g && g[3] !== f) {
                        f = f || g[3], e = e || [], g = +d || 1;
                        do h = h || ".5", g /= h, m.style(c.elem, a, g + f); while (h !== (h = c.cur() / d) && 1 !== h && --i)
                    }
                    return e && (g = c.start = +g || +d || 0, c.unit = f, c.end = e[1] ? g + (e[1] + 1) * e[2] : +e[2]), c
                }]
            };

        function fb() {
            return setTimeout(function () {
                $a = void 0
            }), $a = m.now()
        }

        function gb(a, b) {
            var c, d = {height: a}, e = 0;
            for (b = b ? 1 : 0; 4 > e; e += 2 - b) c = T[e], d["margin" + c] = d["padding" + c] = a;
            return b && (d.opacity = d.width = a), d
        }

        function hb(a, b, c) {
            for (var d, e = (eb[b] || []).concat(eb["*"]), f = 0, g = e.length; g > f; f++) if (d = e[f].call(c, b, a)) return d
        }

        function ib(a, b, c) {
            var d, e, f, g, h, i, j, l, n = this, o = {}, p = a.style, q = a.nodeType && U(a), r = m._data(a, "fxshow");
            c.queue || (h = m._queueHooks(a, "fx"), null == h.unqueued && (h.unqueued = 0, i = h.empty.fire, h.empty.fire = function () {
                h.unqueued || i()
            }), h.unqueued++, n.always(function () {
                n.always(function () {
                    h.unqueued--, m.queue(a, "fx").length || h.empty.fire()
                })
            })), 1 === a.nodeType && ("height" in b || "width" in b) && (c.overflow = [p.overflow, p.overflowX, p.overflowY], j = m.css(a, "display"), l = "none" === j ? m._data(a, "olddisplay") || Fa(a.nodeName) : j, "inline" === l && "none" === m.css(a, "float") && (k.inlineBlockNeedsLayout && "inline" !== Fa(a.nodeName) ? p.zoom = 1 : p.display = "inline-block")), c.overflow && (p.overflow = "hidden", k.shrinkWrapBlocks() || n.always(function () {
                p.overflow = c.overflow[0], p.overflowX = c.overflow[1], p.overflowY = c.overflow[2]
            }));
            for (d in b) if (e = b[d], ab.exec(e)) {
                if (delete b[d], f = f || "toggle" === e, e === (q ? "hide" : "show")) {
                    if ("show" !== e || !r || void 0 === r[d]) continue;
                    q = !0
                }
                o[d] = r && r[d] || m.style(a, d)
            } else j = void 0;
            if (m.isEmptyObject(o)) "inline" === ("none" === j ? Fa(a.nodeName) : j) && (p.display = j); else {
                r ? "hidden" in r && (q = r.hidden) : r = m._data(a, "fxshow", {}), f && (r.hidden = !q), q ? m(a).show() : n.done(function () {
                    m(a).hide()
                }), n.done(function () {
                    var b;
                    m._removeData(a, "fxshow");
                    for (b in o) m.style(a, b, o[b])
                });
                for (d in o) g = hb(q ? r[d] : 0, d, n), d in r || (r[d] = g.start, q && (g.end = g.start, g.start = "width" === d || "height" === d ? 1 : 0))
            }
        }

        function jb(a, b) {
            var c, d, e, f, g;
            for (c in a) if (d = m.camelCase(c), e = b[d], f = a[c], m.isArray(f) && (e = f[1], f = a[c] = f[0]), c !== d && (a[d] = f, delete a[c]), g = m.cssHooks[d], g && "expand" in g) {
                f = g.expand(f), delete a[d];
                for (c in f) c in a || (a[c] = f[c], b[c] = e)
            } else b[d] = e
        }

        function kb(a, b, c) {
            var d, e, f = 0, g = db.length, h = m.Deferred().always(function () {
                delete i.elem
            }), i = function () {
                if (e) return !1;
                for (var b = $a || fb(), c = Math.max(0, j.startTime + j.duration - b), d = c / j.duration || 0, f = 1 - d, g = 0, i = j.tweens.length; i > g; g++) j.tweens[g].run(f);
                return h.notifyWith(a, [j, f, c]), 1 > f && i ? c : (h.resolveWith(a, [j]), !1)
            }, j = h.promise({
                elem: a,
                props: m.extend({}, b),
                opts: m.extend(!0, {specialEasing: {}}, c),
                originalProperties: b,
                originalOptions: c,
                startTime: $a || fb(),
                duration: c.duration,
                tweens: [],
                createTween: function (b, c) {
                    var d = m.Tween(a, j.opts, b, c, j.opts.specialEasing[b] || j.opts.easing);
                    return j.tweens.push(d), d
                },
                stop: function (b) {
                    var c = 0, d = b ? j.tweens.length : 0;
                    if (e) return this;
                    for (e = !0; d > c; c++) j.tweens[c].run(1);
                    return b ? h.resolveWith(a, [j, b]) : h.rejectWith(a, [j, b]), this
                }
            }), k = j.props;
            for (jb(k, j.opts.specialEasing); g > f; f++) if (d = db[f].call(j, a, k, j.opts)) return d;
            return m.map(k, hb, j), m.isFunction(j.opts.start) && j.opts.start.call(a, j), m.fx.timer(m.extend(i, {
                elem: a,
                anim: j,
                queue: j.opts.queue
            })), j.progress(j.opts.progress).done(j.opts.done, j.opts.complete).fail(j.opts.fail).always(j.opts.always)
        }

        m.Animation = m.extend(kb, {
            tweener: function (a, b) {
                m.isFunction(a) ? (b = a, a = ["*"]) : a = a.split(" ");
                for (var c, d = 0, e = a.length; e > d; d++) c = a[d], eb[c] = eb[c] || [], eb[c].unshift(b)
            }, prefilter: function (a, b) {
                b ? db.unshift(a) : db.push(a)
            }
        }), m.speed = function (a, b, c) {
            var d = a && "object" == typeof a ? m.extend({}, a) : {
                complete: c || !c && b || m.isFunction(a) && a,
                duration: a,
                easing: c && b || b && !m.isFunction(b) && b
            };
            return d.duration = m.fx.off ? 0 : "number" == typeof d.duration ? d.duration : d.duration in m.fx.speeds ? m.fx.speeds[d.duration] : m.fx.speeds._default, (null == d.queue || d.queue === !0) && (d.queue = "fx"), d.old = d.complete, d.complete = function () {
                m.isFunction(d.old) && d.old.call(this), d.queue && m.dequeue(this, d.queue)
            }, d
        }, m.fn.extend({
            fadeTo: function (a, b, c, d) {
                return this.filter(U).css("opacity", 0).show().end().animate({opacity: b}, a, c, d)
            }, animate: function (a, b, c, d) {
                var e = m.isEmptyObject(a), f = m.speed(b, c, d), g = function () {
                    var b = kb(this, m.extend({}, a), f);
                    (e || m._data(this, "finish")) && b.stop(!0)
                };
                return g.finish = g, e || f.queue === !1 ? this.each(g) : this.queue(f.queue, g)
            }, stop: function (a, b, c) {
                var d = function (a) {
                    var b = a.stop;
                    delete a.stop, b(c)
                };
                return "string" != typeof a && (c = b, b = a, a = void 0), b && a !== !1 && this.queue(a || "fx", []), this.each(function () {
                    var b = !0, e = null != a && a + "queueHooks", f = m.timers, g = m._data(this);
                    if (e) g[e] && g[e].stop && d(g[e]); else for (e in g) g[e] && g[e].stop && cb.test(e) && d(g[e]);
                    for (e = f.length; e--;) f[e].elem !== this || null != a && f[e].queue !== a || (f[e].anim.stop(c), b = !1, f.splice(e, 1));
                    (b || !c) && m.dequeue(this, a)
                })
            }, finish: function (a) {
                return a !== !1 && (a = a || "fx"), this.each(function () {
                    var b, c = m._data(this), d = c[a + "queue"], e = c[a + "queueHooks"], f = m.timers,
                        g = d ? d.length : 0;
                    for (c.finish = !0, m.queue(this, a, []), e && e.stop && e.stop.call(this, !0), b = f.length; b--;) f[b].elem === this && f[b].queue === a && (f[b].anim.stop(!0), f.splice(b, 1));
                    for (b = 0; g > b; b++) d[b] && d[b].finish && d[b].finish.call(this);
                    delete c.finish
                })
            }
        }), m.each(["toggle", "show", "hide"], function (a, b) {
            var c = m.fn[b];
            m.fn[b] = function (a, d, e) {
                return null == a || "boolean" == typeof a ? c.apply(this, arguments) : this.animate(gb(b, !0), a, d, e)
            }
        }), m.each({
            slideDown: gb("show"),
            slideUp: gb("hide"),
            slideToggle: gb("toggle"),
            fadeIn: {opacity: "show"},
            fadeOut: {opacity: "hide"},
            fadeToggle: {opacity: "toggle"}
        }, function (a, b) {
            m.fn[a] = function (a, c, d) {
                return this.animate(b, a, c, d)
            }
        }), m.timers = [], m.fx.tick = function () {
            var a, b = m.timers, c = 0;
            for ($a = m.now(); c < b.length; c++) a = b[c], a() || b[c] !== a || b.splice(c--, 1);
            b.length || m.fx.stop(), $a = void 0
        }, m.fx.timer = function (a) {
            m.timers.push(a), a() ? m.fx.start() : m.timers.pop()
        }, m.fx.interval = 13, m.fx.start = function () {
            _a || (_a = setInterval(m.fx.tick, m.fx.interval))
        }, m.fx.stop = function () {
            clearInterval(_a), _a = null
        }, m.fx.speeds = {slow: 600, fast: 200, _default: 400}, m.fn.delay = function (a, b) {
            return a = m.fx ? m.fx.speeds[a] || a : a, b = b || "fx", this.queue(b, function (b, c) {
                var d = setTimeout(b, a);
                c.stop = function () {
                    clearTimeout(d)
                }
            })
        }, function () {
            var a, b, c, d, e;
            b = y.createElement("div"), b.setAttribute("className", "t"), b.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", d = b.getElementsByTagName("a")[0], c = y.createElement("select"), e = c.appendChild(y.createElement("option")), a = b.getElementsByTagName("input")[0], d.style.cssText = "top:1px", k.getSetAttribute = "t" !== b.className, k.style = /top/.test(d.getAttribute("style")), k.hrefNormalized = "/a" === d.getAttribute("href"), k.checkOn = !!a.value, k.optSelected = e.selected, k.enctype = !!y.createElement("form").enctype, c.disabled = !0, k.optDisabled = !e.disabled, a = y.createElement("input"), a.setAttribute("value", ""), k.input = "" === a.getAttribute("value"), a.value = "t", a.setAttribute("type", "radio"), k.radioValue = "t" === a.value
        }();
        var lb = /\r/g;
        m.fn.extend({
            val: function (a) {
                var b, c, d, e = this[0];
                {
                    if (arguments.length) return d = m.isFunction(a), this.each(function (c) {
                        var e;
                        1 === this.nodeType && (e = d ? a.call(this, c, m(this).val()) : a, null == e ? e = "" : "number" == typeof e ? e += "" : m.isArray(e) && (e = m.map(e, function (a) {
                            return null == a ? "" : a + ""
                        })), b = m.valHooks[this.type] || m.valHooks[this.nodeName.toLowerCase()], b && "set" in b && void 0 !== b.set(this, e, "value") || (this.value = e))
                    });
                    if (e) return b = m.valHooks[e.type] || m.valHooks[e.nodeName.toLowerCase()], b && "get" in b && void 0 !== (c = b.get(e, "value")) ? c : (c = e.value, "string" == typeof c ? c.replace(lb, "") : null == c ? "" : c)
                }
            }
        }), m.extend({
            valHooks: {
                option: {
                    get: function (a) {
                        var b = m.find.attr(a, "value");
                        return null != b ? b : m.trim(m.text(a))
                    }
                }, select: {
                    get: function (a) {
                        for (var b, c, d = a.options, e = a.selectedIndex, f = "select-one" === a.type || 0 > e, g = f ? null : [], h = f ? e + 1 : d.length, i = 0 > e ? h : f ? e : 0; h > i; i++) if (c = d[i], !(!c.selected && i !== e || (k.optDisabled ? c.disabled : null !== c.getAttribute("disabled")) || c.parentNode.disabled && m.nodeName(c.parentNode, "optgroup"))) {
                            if (b = m(c).val(), f) return b;
                            g.push(b)
                        }
                        return g
                    }, set: function (a, b) {
                        var c, d, e = a.options, f = m.makeArray(b), g = e.length;
                        while (g--) if (d = e[g], m.inArray(m.valHooks.option.get(d), f) >= 0) try {
                            d.selected = c = !0
                        } catch (h) {
                            d.scrollHeight
                        } else d.selected = !1;
                        return c || (a.selectedIndex = -1), e
                    }
                }
            }
        }), m.each(["radio", "checkbox"], function () {
            m.valHooks[this] = {
                set: function (a, b) {
                    return m.isArray(b) ? a.checked = m.inArray(m(a).val(), b) >= 0 : void 0
                }
            }, k.checkOn || (m.valHooks[this].get = function (a) {
                return null === a.getAttribute("value") ? "on" : a.value
            })
        });
        var mb, nb, ob = m.expr.attrHandle, pb = /^(?:checked|selected)$/i, qb = k.getSetAttribute, rb = k.input;
        m.fn.extend({
            attr: function (a, b) {
                return V(this, m.attr, a, b, arguments.length > 1)
            }, removeAttr: function (a) {
                return this.each(function () {
                    m.removeAttr(this, a)
                })
            }
        }), m.extend({
            attr: function (a, b, c) {
                var d, e, f = a.nodeType;
                if (a && 3 !== f && 8 !== f && 2 !== f) return typeof a.getAttribute === K ? m.prop(a, b, c) : (1 === f && m.isXMLDoc(a) || (b = b.toLowerCase(), d = m.attrHooks[b] || (m.expr.match.bool.test(b) ? nb : mb)), void 0 === c ? d && "get" in d && null !== (e = d.get(a, b)) ? e : (e = m.find.attr(a, b), null == e ? void 0 : e) : null !== c ? d && "set" in d && void 0 !== (e = d.set(a, c, b)) ? e : (a.setAttribute(b, c + ""), c) : void m.removeAttr(a, b))
            }, removeAttr: function (a, b) {
                var c, d, e = 0, f = b && b.match(E);
                if (f && 1 === a.nodeType) while (c = f[e++]) d = m.propFix[c] || c, m.expr.match.bool.test(c) ? rb && qb || !pb.test(c) ? a[d] = !1 : a[m.camelCase("default-" + c)] = a[d] = !1 : m.attr(a, c, ""), a.removeAttribute(qb ? c : d)
            }, attrHooks: {
                type: {
                    set: function (a, b) {
                        if (!k.radioValue && "radio" === b && m.nodeName(a, "input")) {
                            var c = a.value;
                            return a.setAttribute("type", b), c && (a.value = c), b
                        }
                    }
                }
            }
        }), nb = {
            set: function (a, b, c) {
                return b === !1 ? m.removeAttr(a, c) : rb && qb || !pb.test(c) ? a.setAttribute(!qb && m.propFix[c] || c, c) : a[m.camelCase("default-" + c)] = a[c] = !0, c
            }
        }, m.each(m.expr.match.bool.source.match(/\w+/g), function (a, b) {
            var c = ob[b] || m.find.attr;
            ob[b] = rb && qb || !pb.test(b) ? function (a, b, d) {
                var e, f;
                return d || (f = ob[b], ob[b] = e, e = null != c(a, b, d) ? b.toLowerCase() : null, ob[b] = f), e
            } : function (a, b, c) {
                return c ? void 0 : a[m.camelCase("default-" + b)] ? b.toLowerCase() : null
            }
        }), rb && qb || (m.attrHooks.value = {
            set: function (a, b, c) {
                return m.nodeName(a, "input") ? void(a.defaultValue = b) : mb && mb.set(a, b, c)
            }
        }), qb || (mb = {
            set: function (a, b, c) {
                var d = a.getAttributeNode(c);
                return d || a.setAttributeNode(d = a.ownerDocument.createAttribute(c)), d.value = b += "", "value" === c || b === a.getAttribute(c) ? b : void 0
            }
        }, ob.id = ob.name = ob.coords = function (a, b, c) {
            var d;
            return c ? void 0 : (d = a.getAttributeNode(b)) && "" !== d.value ? d.value : null
        }, m.valHooks.button = {
            get: function (a, b) {
                var c = a.getAttributeNode(b);
                return c && c.specified ? c.value : void 0
            }, set: mb.set
        }, m.attrHooks.contenteditable = {
            set: function (a, b, c) {
                mb.set(a, "" === b ? !1 : b, c)
            }
        }, m.each(["width", "height"], function (a, b) {
            m.attrHooks[b] = {
                set: function (a, c) {
                    return "" === c ? (a.setAttribute(b, "auto"), c) : void 0
                }
            }
        })), k.style || (m.attrHooks.style = {
            get: function (a) {
                return a.style.cssText || void 0
            }, set: function (a, b) {
                return a.style.cssText = b + ""
            }
        });
        var sb = /^(?:input|select|textarea|button|object)$/i, tb = /^(?:a|area)$/i;
        m.fn.extend({
            prop: function (a, b) {
                return V(this, m.prop, a, b, arguments.length > 1)
            }, removeProp: function (a) {
                return a = m.propFix[a] || a, this.each(function () {
                    try {
                        this[a] = void 0, delete this[a]
                    } catch (b) {
                    }
                })
            }
        }), m.extend({
            propFix: {"for": "htmlFor", "class": "className"}, prop: function (a, b, c) {
                var d, e, f, g = a.nodeType;
                if (a && 3 !== g && 8 !== g && 2 !== g) return f = 1 !== g || !m.isXMLDoc(a), f && (b = m.propFix[b] || b, e = m.propHooks[b]), void 0 !== c ? e && "set" in e && void 0 !== (d = e.set(a, c, b)) ? d : a[b] = c : e && "get" in e && null !== (d = e.get(a, b)) ? d : a[b]
            }, propHooks: {
                tabIndex: {
                    get: function (a) {
                        var b = m.find.attr(a, "tabindex");
                        return b ? parseInt(b, 10) : sb.test(a.nodeName) || tb.test(a.nodeName) && a.href ? 0 : -1
                    }
                }
            }
        }), k.hrefNormalized || m.each(["href", "src"], function (a, b) {
            m.propHooks[b] = {
                get: function (a) {
                    return a.getAttribute(b, 4)
                }
            }
        }), k.optSelected || (m.propHooks.selected = {
            get: function (a) {
                var b = a.parentNode;
                return b && (b.selectedIndex, b.parentNode && b.parentNode.selectedIndex), null
            }
        }), m.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
            m.propFix[this.toLowerCase()] = this
        }), k.enctype || (m.propFix.enctype = "encoding");
        var ub = /[\t\r\n\f]/g;
        m.fn.extend({
            addClass: function (a) {
                var b, c, d, e, f, g, h = 0, i = this.length, j = "string" == typeof a && a;
                if (m.isFunction(a)) return this.each(function (b) {
                    m(this).addClass(a.call(this, b, this.className))
                });
                if (j) for (b = (a || "").match(E) || []; i > h; h++) if (c = this[h], d = 1 === c.nodeType && (c.className ? (" " + c.className + " ").replace(ub, " ") : " ")) {
                    f = 0;
                    while (e = b[f++]) d.indexOf(" " + e + " ") < 0 && (d += e + " ");
                    g = m.trim(d), c.className !== g && (c.className = g)
                }
                return this
            }, removeClass: function (a) {
                var b, c, d, e, f, g, h = 0, i = this.length, j = 0 === arguments.length || "string" == typeof a && a;
                if (m.isFunction(a)) return this.each(function (b) {
                    m(this).removeClass(a.call(this, b, this.className))
                });
                if (j) for (b = (a || "").match(E) || []; i > h; h++) if (c = this[h], d = 1 === c.nodeType && (c.className ? (" " + c.className + " ").replace(ub, " ") : "")) {
                    f = 0;
                    while (e = b[f++]) while (d.indexOf(" " + e + " ") >= 0) d = d.replace(" " + e + " ", " ");
                    g = a ? m.trim(d) : "", c.className !== g && (c.className = g)
                }
                return this
            }, toggleClass: function (a, b) {
                var c = typeof a;
                return "boolean" == typeof b && "string" === c ? b ? this.addClass(a) : this.removeClass(a) : this.each(m.isFunction(a) ? function (c) {
                    m(this).toggleClass(a.call(this, c, this.className, b), b)
                } : function () {
                    if ("string" === c) {
                        var b, d = 0, e = m(this), f = a.match(E) || [];
                        while (b = f[d++]) e.hasClass(b) ? e.removeClass(b) : e.addClass(b)
                    } else (c === K || "boolean" === c) && (this.className && m._data(this, "__className__", this.className), this.className = this.className || a === !1 ? "" : m._data(this, "__className__") || "")
                })
            }, hasClass: function (a) {
                for (var b = " " + a + " ", c = 0, d = this.length; d > c; c++) if (1 === this[c].nodeType && (" " + this[c].className + " ").replace(ub, " ").indexOf(b) >= 0) return !0;
                return !1
            }
        }), m.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (a, b) {
            m.fn[b] = function (a, c) {
                return arguments.length > 0 ? this.on(b, null, a, c) : this.trigger(b)
            }
        }), m.fn.extend({
            hover: function (a, b) {
                return this.mouseenter(a).mouseleave(b || a)
            }, bind: function (a, b, c) {
                return this.on(a, null, b, c)
            }, unbind: function (a, b) {
                return this.off(a, null, b)
            }, delegate: function (a, b, c, d) {
                return this.on(b, a, c, d)
            }, undelegate: function (a, b, c) {
                return 1 === arguments.length ? this.off(a, "**") : this.off(b, a || "**", c)
            }
        });
        var vb = m.now(), wb = /\?/,
            xb = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
        m.parseJSON = function (b) {
            if (a.JSON && a.JSON.parse) return a.JSON.parse(b + "");
            var c, d = null, e = m.trim(b + "");
            return e && !m.trim(e.replace(xb, function (a, b, e, f) {
                return c && b && (d = 0), 0 === d ? a : (c = e || b, d += !f - !e, "")
            })) ? Function("return " + e)() : m.error("Invalid JSON: " + b)
        }, m.parseXML = function (b) {
            var c, d;
            if (!b || "string" != typeof b) return null;
            try {
                a.DOMParser ? (d = new DOMParser, c = d.parseFromString(b, "text/xml")) : (c = new ActiveXObject("Microsoft.XMLDOM"), c.async = "false", c.loadXML(b))
            } catch (e) {
                c = void 0
            }
            return c && c.documentElement && !c.getElementsByTagName("parsererror").length || m.error("Invalid XML: " + b), c
        };
        var yb, zb, Ab = /#.*$/, Bb = /([?&])_=[^&]*/, Cb = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
            Db = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, Eb = /^(?:GET|HEAD)$/, Fb = /^\/\//,
            Gb = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, Hb = {}, Ib = {}, Jb = "*/".concat("*");
        try {
            zb = location.href
        } catch (Kb) {
            zb = y.createElement("a"), zb.href = "", zb = zb.href
        }
        yb = Gb.exec(zb.toLowerCase()) || [];

        function Lb(a) {
            return function (b, c) {
                "string" != typeof b && (c = b, b = "*");
                var d, e = 0, f = b.toLowerCase().match(E) || [];
                if (m.isFunction(c)) while (d = f[e++]) "+" === d.charAt(0) ? (d = d.slice(1) || "*", (a[d] = a[d] || []).unshift(c)) : (a[d] = a[d] || []).push(c)
            }
        }

        function Mb(a, b, c, d) {
            var e = {}, f = a === Ib;

            function g(h) {
                var i;
                return e[h] = !0, m.each(a[h] || [], function (a, h) {
                    var j = h(b, c, d);
                    return "string" != typeof j || f || e[j] ? f ? !(i = j) : void 0 : (b.dataTypes.unshift(j), g(j), !1)
                }), i
            }

            return g(b.dataTypes[0]) || !e["*"] && g("*")
        }

        function Nb(a, b) {
            var c, d, e = m.ajaxSettings.flatOptions || {};
            for (d in b) void 0 !== b[d] && ((e[d] ? a : c || (c = {}))[d] = b[d]);
            return c && m.extend(!0, a, c), a
        }

        function Ob(a, b, c) {
            var d, e, f, g, h = a.contents, i = a.dataTypes;
            while ("*" === i[0]) i.shift(), void 0 === e && (e = a.mimeType || b.getResponseHeader("Content-Type"));
            if (e) for (g in h) if (h[g] && h[g].test(e)) {
                i.unshift(g);
                break
            }
            if (i[0] in c) f = i[0]; else {
                for (g in c) {
                    if (!i[0] || a.converters[g + " " + i[0]]) {
                        f = g;
                        break
                    }
                    d || (d = g)
                }
                f = f || d
            }
            return f ? (f !== i[0] && i.unshift(f), c[f]) : void 0
        }

        function Pb(a, b, c, d) {
            var e, f, g, h, i, j = {}, k = a.dataTypes.slice();
            if (k[1]) for (g in a.converters) j[g.toLowerCase()] = a.converters[g];
            f = k.shift();
            while (f) if (a.responseFields[f] && (c[a.responseFields[f]] = b), !i && d && a.dataFilter && (b = a.dataFilter(b, a.dataType)), i = f, f = k.shift()) if ("*" === f) f = i; else if ("*" !== i && i !== f) {
                if (g = j[i + " " + f] || j["* " + f], !g) for (e in j) if (h = e.split(" "), h[1] === f && (g = j[i + " " + h[0]] || j["* " + h[0]])) {
                    g === !0 ? g = j[e] : j[e] !== !0 && (f = h[0], k.unshift(h[1]));
                    break
                }
                if (g !== !0) if (g && a["throws"]) b = g(b); else try {
                    b = g(b)
                } catch (l) {
                    return {state: "parsererror", error: g ? l : "No conversion from " + i + " to " + f}
                }
            }
            return {state: "success", data: b}
        }

        m.extend({
            active: 0,
            lastModified: {},
            etag: {},
            ajaxSettings: {
                url: zb,
                type: "GET",
                isLocal: Db.test(yb[1]),
                global: !0,
                processData: !0,
                async: !0,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                accepts: {
                    "*": Jb,
                    text: "text/plain",
                    html: "text/html",
                    xml: "application/xml, text/xml",
                    json: "application/json, text/javascript"
                },
                contents: {xml: /xml/, html: /html/, json: /json/},
                responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
                converters: {"* text": String, "text html": !0, "text json": m.parseJSON, "text xml": m.parseXML},
                flatOptions: {url: !0, context: !0}
            },
            ajaxSetup: function (a, b) {
                return b ? Nb(Nb(a, m.ajaxSettings), b) : Nb(m.ajaxSettings, a)
            },
            ajaxPrefilter: Lb(Hb),
            ajaxTransport: Lb(Ib),
            ajax: function (a, b) {
                "object" == typeof a && (b = a, a = void 0), b = b || {};
                var c, d, e, f, g, h, i, j, k = m.ajaxSetup({}, b), l = k.context || k,
                    n = k.context && (l.nodeType || l.jquery) ? m(l) : m.event, o = m.Deferred(),
                    p = m.Callbacks("once memory"), q = k.statusCode || {}, r = {}, s = {}, t = 0, u = "canceled", v = {
                        readyState: 0, getResponseHeader: function (a) {
                            var b;
                            if (2 === t) {
                                if (!j) {
                                    j = {};
                                    while (b = Cb.exec(f)) j[b[1].toLowerCase()] = b[2]
                                }
                                b = j[a.toLowerCase()]
                            }
                            return null == b ? null : b
                        }, getAllResponseHeaders: function () {
                            return 2 === t ? f : null
                        }, setRequestHeader: function (a, b) {
                            var c = a.toLowerCase();
                            return t || (a = s[c] = s[c] || a, r[a] = b), this
                        }, overrideMimeType: function (a) {
                            return t || (k.mimeType = a), this
                        }, statusCode: function (a) {
                            var b;
                            if (a) if (2 > t) for (b in a) q[b] = [q[b], a[b]]; else v.always(a[v.status]);
                            return this
                        }, abort: function (a) {
                            var b = a || u;
                            return i && i.abort(b), x(0, b), this
                        }
                    };
                if (o.promise(v).complete = p.add, v.success = v.done, v.error = v.fail, k.url = ((a || k.url || zb) + "").replace(Ab, "").replace(Fb, yb[1] + "//"), k.type = b.method || b.type || k.method || k.type, k.dataTypes = m.trim(k.dataType || "*").toLowerCase().match(E) || [""], null == k.crossDomain && (c = Gb.exec(k.url.toLowerCase()), k.crossDomain = !(!c || c[1] === yb[1] && c[2] === yb[2] && (c[3] || ("http:" === c[1] ? "80" : "443")) === (yb[3] || ("http:" === yb[1] ? "80" : "443")))), k.data && k.processData && "string" != typeof k.data && (k.data = m.param(k.data, k.traditional)), Mb(Hb, k, b, v), 2 === t) return v;
                h = m.event && k.global, h && 0 === m.active++ && m.event.trigger("ajaxStart"), k.type = k.type.toUpperCase(), k.hasContent = !Eb.test(k.type), e = k.url, k.hasContent || (k.data && (e = k.url += (wb.test(e) ? "&" : "?") + k.data, delete k.data), k.cache === !1 && (k.url = Bb.test(e) ? e.replace(Bb, "$1_=" + vb++) : e + (wb.test(e) ? "&" : "?") + "_=" + vb++)), k.ifModified && (m.lastModified[e] && v.setRequestHeader("If-Modified-Since", m.lastModified[e]), m.etag[e] && v.setRequestHeader("If-None-Match", m.etag[e])), (k.data && k.hasContent && k.contentType !== !1 || b.contentType) && v.setRequestHeader("Content-Type", k.contentType), v.setRequestHeader("Accept", k.dataTypes[0] && k.accepts[k.dataTypes[0]] ? k.accepts[k.dataTypes[0]] + ("*" !== k.dataTypes[0] ? ", " + Jb + "; q=0.01" : "") : k.accepts["*"]);
                for (d in k.headers) v.setRequestHeader(d, k.headers[d]);
                if (k.beforeSend && (k.beforeSend.call(l, v, k) === !1 || 2 === t)) return v.abort();
                u = "abort";
                for (d in{success: 1, error: 1, complete: 1}) v[d](k[d]);
                if (i = Mb(Ib, k, b, v)) {
                    v.readyState = 1, h && n.trigger("ajaxSend", [v, k]), k.async && k.timeout > 0 && (g = setTimeout(function () {
                        v.abort("timeout")
                    }, k.timeout));
                    try {
                        t = 1, i.send(r, x)
                    } catch (w) {
                        if (!(2 > t)) throw w;
                        x(-1, w)
                    }
                } else x(-1, "No Transport");

                function x(a, b, c, d) {
                    var j, r, s, u, w, x = b;
                    2 !== t && (t = 2, g && clearTimeout(g), i = void 0, f = d || "", v.readyState = a > 0 ? 4 : 0, j = a >= 200 && 300 > a || 304 === a, c && (u = Ob(k, v, c)), u = Pb(k, u, v, j), j ? (k.ifModified && (w = v.getResponseHeader("Last-Modified"), w && (m.lastModified[e] = w), w = v.getResponseHeader("etag"), w && (m.etag[e] = w)), 204 === a || "HEAD" === k.type ? x = "nocontent" : 304 === a ? x = "notmodified" : (x = u.state, r = u.data, s = u.error, j = !s)) : (s = x, (a || !x) && (x = "error", 0 > a && (a = 0))), v.status = a, v.statusText = (b || x) + "", j ? o.resolveWith(l, [r, x, v]) : o.rejectWith(l, [v, x, s]), v.statusCode(q), q = void 0, h && n.trigger(j ? "ajaxSuccess" : "ajaxError", [v, k, j ? r : s]), p.fireWith(l, [v, x]), h && (n.trigger("ajaxComplete", [v, k]), --m.active || m.event.trigger("ajaxStop")))
                }

                return v
            },
            getJSON: function (a, b, c) {
                return m.get(a, b, c, "json")
            },
            getScript: function (a, b) {
                return m.get(a, void 0, b, "script")
            }
        }), m.each(["get", "post"], function (a, b) {
            m[b] = function (a, c, d, e) {
                return m.isFunction(c) && (e = e || d, d = c, c = void 0), m.ajax({
                    url: a,
                    type: b,
                    dataType: e,
                    data: c,
                    success: d
                })
            }
        }), m._evalUrl = function (a) {
            return m.ajax({url: a, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
        }, m.fn.extend({
            wrapAll: function (a) {
                if (m.isFunction(a)) return this.each(function (b) {
                    m(this).wrapAll(a.call(this, b))
                });
                if (this[0]) {
                    var b = m(a, this[0].ownerDocument).eq(0).clone(!0);
                    this[0].parentNode && b.insertBefore(this[0]), b.map(function () {
                        var a = this;
                        while (a.firstChild && 1 === a.firstChild.nodeType) a = a.firstChild;
                        return a
                    }).append(this)
                }
                return this
            }, wrapInner: function (a) {
                return this.each(m.isFunction(a) ? function (b) {
                    m(this).wrapInner(a.call(this, b))
                } : function () {
                    var b = m(this), c = b.contents();
                    c.length ? c.wrapAll(a) : b.append(a)
                })
            }, wrap: function (a) {
                var b = m.isFunction(a);
                return this.each(function (c) {
                    m(this).wrapAll(b ? a.call(this, c) : a)
                })
            }, unwrap: function () {
                return this.parent().each(function () {
                    m.nodeName(this, "body") || m(this).replaceWith(this.childNodes)
                }).end()
            }
        }), m.expr.filters.hidden = function (a) {
            return a.offsetWidth <= 0 && a.offsetHeight <= 0 || !k.reliableHiddenOffsets() && "none" === (a.style && a.style.display || m.css(a, "display"))
        }, m.expr.filters.visible = function (a) {
            return !m.expr.filters.hidden(a)
        };
        var Qb = /%20/g, Rb = /\[\]$/, Sb = /\r?\n/g, Tb = /^(?:submit|button|image|reset|file)$/i,
            Ub = /^(?:input|select|textarea|keygen)/i;

        function Vb(a, b, c, d) {
            var e;
            if (m.isArray(b)) m.each(b, function (b, e) {
                c || Rb.test(a) ? d(a, e) : Vb(a + "[" + ("object" == typeof e ? b : "") + "]", e, c, d)
            }); else if (c || "object" !== m.type(b)) d(a, b); else for (e in b) Vb(a + "[" + e + "]", b[e], c, d)
        }

        m.param = function (a, b) {
            var c, d = [], e = function (a, b) {
                b = m.isFunction(b) ? b() : null == b ? "" : b, d[d.length] = encodeURIComponent(a) + "=" + encodeURIComponent(b)
            };
            if (void 0 === b && (b = m.ajaxSettings && m.ajaxSettings.traditional), m.isArray(a) || a.jquery && !m.isPlainObject(a)) m.each(a, function () {
                e(this.name, this.value)
            }); else for (c in a) Vb(c, a[c], b, e);
            return d.join("&").replace(Qb, "+")
        }, m.fn.extend({
            serialize: function () {
                return m.param(this.serializeArray())
            }, serializeArray: function () {
                return this.map(function () {
                    var a = m.prop(this, "elements");
                    return a ? m.makeArray(a) : this
                }).filter(function () {
                    var a = this.type;
                    return this.name && !m(this).is(":disabled") && Ub.test(this.nodeName) && !Tb.test(a) && (this.checked || !W.test(a))
                }).map(function (a, b) {
                    var c = m(this).val();
                    return null == c ? null : m.isArray(c) ? m.map(c, function (a) {
                        return {name: b.name, value: a.replace(Sb, "\r\n")}
                    }) : {name: b.name, value: c.replace(Sb, "\r\n")}
                }).get()
            }
        }), m.ajaxSettings.xhr = void 0 !== a.ActiveXObject ? function () {
            return !this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && Zb() || $b()
        } : Zb;
        var Wb = 0, Xb = {}, Yb = m.ajaxSettings.xhr();
        a.attachEvent && a.attachEvent("onunload", function () {
            for (var a in Xb) Xb[a](void 0, !0)
        }), k.cors = !!Yb && "withCredentials" in Yb, Yb = k.ajax = !!Yb, Yb && m.ajaxTransport(function (a) {
            if (!a.crossDomain || k.cors) {
                var b;
                return {
                    send: function (c, d) {
                        var e, f = a.xhr(), g = ++Wb;
                        if (f.open(a.type, a.url, a.async, a.username, a.password), a.xhrFields) for (e in a.xhrFields) f[e] = a.xhrFields[e];
                        a.mimeType && f.overrideMimeType && f.overrideMimeType(a.mimeType), a.crossDomain || c["X-Requested-With"] || (c["X-Requested-With"] = "XMLHttpRequest");
                        for (e in c) void 0 !== c[e] && f.setRequestHeader(e, c[e] + "");
                        f.send(a.hasContent && a.data || null), b = function (c, e) {
                            var h, i, j;
                            if (b && (e || 4 === f.readyState)) if (delete Xb[g], b = void 0, f.onreadystatechange = m.noop, e) 4 !== f.readyState && f.abort(); else {
                                j = {}, h = f.status, "string" == typeof f.responseText && (j.text = f.responseText);
                                try {
                                    i = f.statusText
                                } catch (k) {
                                    i = ""
                                }
                                h || !a.isLocal || a.crossDomain ? 1223 === h && (h = 204) : h = j.text ? 200 : 404
                            }
                            j && d(h, i, j, f.getAllResponseHeaders())
                        }, a.async ? 4 === f.readyState ? setTimeout(b) : f.onreadystatechange = Xb[g] = b : b()
                    }, abort: function () {
                        b && b(void 0, !0)
                    }
                }
            }
        });

        function Zb() {
            try {
                return new a.XMLHttpRequest
            } catch (b) {
            }
        }

        function $b() {
            try {
                return new a.ActiveXObject("Microsoft.XMLHTTP")
            } catch (b) {
            }
        }

        m.ajaxSetup({
            accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
            contents: {script: /(?:java|ecma)script/},
            converters: {
                "text script": function (a) {
                    return m.globalEval(a), a
                }
            }
        }), m.ajaxPrefilter("script", function (a) {
            void 0 === a.cache && (a.cache = !1), a.crossDomain && (a.type = "GET", a.global = !1)
        }), m.ajaxTransport("script", function (a) {
            if (a.crossDomain) {
                var b, c = y.head || m("head")[0] || y.documentElement;
                return {
                    send: function (d, e) {
                        b = y.createElement("script"), b.async = !0, a.scriptCharset && (b.charset = a.scriptCharset), b.src = a.url, b.onload = b.onreadystatechange = function (a, c) {
                            (c || !b.readyState || /loaded|complete/.test(b.readyState)) && (b.onload = b.onreadystatechange = null, b.parentNode && b.parentNode.removeChild(b), b = null, c || e(200, "success"))
                        }, c.insertBefore(b, c.firstChild)
                    }, abort: function () {
                        b && b.onload(void 0, !0)
                    }
                }
            }
        });
        var _b = [], ac = /(=)\?(?=&|$)|\?\?/;
        m.ajaxSetup({
            jsonp: "callback", jsonpCallback: function () {
                var a = _b.pop() || m.expando + "_" + vb++;
                return this[a] = !0, a
            }
        }), m.ajaxPrefilter("json jsonp", function (b, c, d) {
            var e, f, g,
                h = b.jsonp !== !1 && (ac.test(b.url) ? "url" : "string" == typeof b.data && !(b.contentType || "").indexOf("application/x-www-form-urlencoded") && ac.test(b.data) && "data");
            return h || "jsonp" === b.dataTypes[0] ? (e = b.jsonpCallback = m.isFunction(b.jsonpCallback) ? b.jsonpCallback() : b.jsonpCallback, h ? b[h] = b[h].replace(ac, "$1" + e) : b.jsonp !== !1 && (b.url += (wb.test(b.url) ? "&" : "?") + b.jsonp + "=" + e), b.converters["script json"] = function () {
                return g || m.error(e + " was not called"), g[0]
            }, b.dataTypes[0] = "json", f = a[e], a[e] = function () {
                g = arguments
            }, d.always(function () {
                a[e] = f, b[e] && (b.jsonpCallback = c.jsonpCallback, _b.push(e)), g && m.isFunction(f) && f(g[0]), g = f = void 0
            }), "script") : void 0
        }), m.parseHTML = function (a, b, c) {
            if (!a || "string" != typeof a) return null;
            "boolean" == typeof b && (c = b, b = !1), b = b || y;
            var d = u.exec(a), e = !c && [];
            return d ? [b.createElement(d[1])] : (d = m.buildFragment([a], b, e), e && e.length && m(e).remove(), m.merge([], d.childNodes))
        };
        var bc = m.fn.load;
        m.fn.load = function (a, b, c) {
            if ("string" != typeof a && bc) return bc.apply(this, arguments);
            var d, e, f, g = this, h = a.indexOf(" ");
            return h >= 0 && (d = m.trim(a.slice(h, a.length)), a = a.slice(0, h)), m.isFunction(b) ? (c = b, b = void 0) : b && "object" == typeof b && (f = "POST"), g.length > 0 && m.ajax({
                url: a,
                type: f,
                dataType: "html",
                data: b
            }).done(function (a) {
                e = arguments, g.html(d ? m("<div>").append(m.parseHTML(a)).find(d) : a)
            }).complete(c && function (a, b) {
                g.each(c, e || [a.responseText, b, a])
            }), this
        }, m.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (a, b) {
            m.fn[b] = function (a) {
                return this.on(b, a)
            }
        }), m.expr.filters.animated = function (a) {
            return m.grep(m.timers, function (b) {
                return a === b.elem
            }).length
        };
        var cc = a.document.documentElement;

        function dc(a) {
            return m.isWindow(a) ? a : 9 === a.nodeType ? a.defaultView || a.parentWindow : !1
        }

        m.offset = {
            setOffset: function (a, b, c) {
                var d, e, f, g, h, i, j, k = m.css(a, "position"), l = m(a), n = {};
                "static" === k && (a.style.position = "relative"), h = l.offset(), f = m.css(a, "top"), i = m.css(a, "left"), j = ("absolute" === k || "fixed" === k) && m.inArray("auto", [f, i]) > -1, j ? (d = l.position(), g = d.top, e = d.left) : (g = parseFloat(f) || 0, e = parseFloat(i) || 0), m.isFunction(b) && (b = b.call(a, c, h)), null != b.top && (n.top = b.top - h.top + g), null != b.left && (n.left = b.left - h.left + e), "using" in b ? b.using.call(a, n) : l.css(n)
            }
        }, m.fn.extend({
            offset: function (a) {
                if (arguments.length) return void 0 === a ? this : this.each(function (b) {
                    m.offset.setOffset(this, a, b)
                });
                var b, c, d = {top: 0, left: 0}, e = this[0], f = e && e.ownerDocument;
                if (f) return b = f.documentElement, m.contains(b, e) ? (typeof e.getBoundingClientRect !== K && (d = e.getBoundingClientRect()), c = dc(f), {
                    top: d.top + (c.pageYOffset || b.scrollTop) - (b.clientTop || 0),
                    left: d.left + (c.pageXOffset || b.scrollLeft) - (b.clientLeft || 0)
                }) : d
            }, position: function () {
                if (this[0]) {
                    var a, b, c = {top: 0, left: 0}, d = this[0];
                    return "fixed" === m.css(d, "position") ? b = d.getBoundingClientRect() : (a = this.offsetParent(), b = this.offset(), m.nodeName(a[0], "html") || (c = a.offset()), c.top += m.css(a[0], "borderTopWidth", !0), c.left += m.css(a[0], "borderLeftWidth", !0)), {
                        top: b.top - c.top - m.css(d, "marginTop", !0),
                        left: b.left - c.left - m.css(d, "marginLeft", !0)
                    }
                }
            }, offsetParent: function () {
                return this.map(function () {
                    var a = this.offsetParent || cc;
                    while (a && !m.nodeName(a, "html") && "static" === m.css(a, "position")) a = a.offsetParent;
                    return a || cc
                })
            }
        }), m.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (a, b) {
            var c = /Y/.test(b);
            m.fn[a] = function (d) {
                return V(this, function (a, d, e) {
                    var f = dc(a);
                    return void 0 === e ? f ? b in f ? f[b] : f.document.documentElement[d] : a[d] : void(f ? f.scrollTo(c ? m(f).scrollLeft() : e, c ? e : m(f).scrollTop()) : a[d] = e)
                }, a, d, arguments.length, null)
            }
        }), m.each(["top", "left"], function (a, b) {
            m.cssHooks[b] = La(k.pixelPosition, function (a, c) {
                return c ? (c = Ja(a, b), Ha.test(c) ? m(a).position()[b] + "px" : c) : void 0
            })
        }), m.each({Height: "height", Width: "width"}, function (a, b) {
            m.each({padding: "inner" + a, content: b, "": "outer" + a}, function (c, d) {
                m.fn[d] = function (d, e) {
                    var f = arguments.length && (c || "boolean" != typeof d),
                        g = c || (d === !0 || e === !0 ? "margin" : "border");
                    return V(this, function (b, c, d) {
                        var e;
                        return m.isWindow(b) ? b.document.documentElement["client" + a] : 9 === b.nodeType ? (e = b.documentElement, Math.max(b.body["scroll" + a], e["scroll" + a], b.body["offset" + a], e["offset" + a], e["client" + a])) : void 0 === d ? m.css(b, c, g) : m.style(b, c, d, g)
                    }, b, f ? d : void 0, f, null)
                }
            })
        }), m.fn.size = function () {
            return this.length
        }, m.fn.andSelf = m.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function () {
            return m
        });
        var ec = a.jQuery, fc = a.$;
        return m.noConflict = function (b) {
            return a.$ === m && (a.$ = fc), b && a.jQuery === m && (a.jQuery = ec), m
        }, typeof b === K && (a.jQuery = a.$ = m), m
    });

    //]]></script>
<script>//<![CDATA[
    /*! jQuery Migrate v1.2.1 | (c) 2005, 2013 jQuery Foundation, Inc. and other contributors | jquery.org/license */
    jQuery.migrateMute === void 0 && (jQuery.migrateMute = !0), function (e, t, n) {
        function r(n) {
            var r = t.console;
            i[n] || (i[n] = !0, e.migrateWarnings.push(n), r && r.warn && !e.migrateMute && (r.warn("JQMIGRATE: " + n), e.migrateTrace && r.trace && r.trace()))
        }

        function a(t, a, i, o) {
            if (Object.defineProperty) try {
                return Object.defineProperty(t, a, {
                    configurable: !0, enumerable: !0, get: function () {
                        return r(o), i
                    }, set: function (e) {
                        r(o), i = e
                    }
                }), n
            } catch (s) {
            }
            e._definePropertyBroken = !0, t[a] = i
        }

        var i = {};
        e.migrateWarnings = [], !e.migrateMute && t.console && t.console.log && t.console.log("JQMIGRATE: Logging is active"), e.migrateTrace === n && (e.migrateTrace = !0), e.migrateReset = function () {
            i = {}, e.migrateWarnings.length = 0
        }, "BackCompat" === document.compatMode && r("jQuery is not compatible with Quirks Mode");
        var o = e("<input/>", {size: 1}).attr("size") && e.attrFn, s = e.attr,
            u = e.attrHooks.value && e.attrHooks.value.get || function () {
                return null
            }, c = e.attrHooks.value && e.attrHooks.value.set || function () {
                return n
            }, l = /^(?:input|button)$/i, d = /^[238]$/,
            p = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,
            f = /^(?:checked|selected)$/i;
        a(e, "attrFn", o || {}, "jQuery.attrFn is deprecated"), e.attr = function (t, a, i, u) {
            var c = a.toLowerCase(), g = t && t.nodeType;
            return u && (4 > s.length && r("jQuery.fn.attr( props, pass ) is deprecated"), t && !d.test(g) && (o ? a in o : e.isFunction(e.fn[a]))) ? e(t)[a](i) : ("type" === a && i !== n && l.test(t.nodeName) && t.parentNode && r("Can't change the 'type' of an input or button in IE 6/7/8"), !e.attrHooks[c] && p.test(c) && (e.attrHooks[c] = {
                get: function (t, r) {
                    var a, i = e.prop(t, r);
                    return i === !0 || "boolean" != typeof i && (a = t.getAttributeNode(r)) && a.nodeValue !== !1 ? r.toLowerCase() : n
                }, set: function (t, n, r) {
                    var a;
                    return n === !1 ? e.removeAttr(t, r) : (a = e.propFix[r] || r, a in t && (t[a] = !0), t.setAttribute(r, r.toLowerCase())), r
                }
            }, f.test(c) && r("jQuery.fn.attr('" + c + "') may use property instead of attribute")), s.call(e, t, a, i))
        }, e.attrHooks.value = {
            get: function (e, t) {
                var n = (e.nodeName || "").toLowerCase();
                return "button" === n ? u.apply(this, arguments) : ("input" !== n && "option" !== n && r("jQuery.fn.attr('value') no longer gets properties"), t in e ? e.value : null)
            }, set: function (e, t) {
                var a = (e.nodeName || "").toLowerCase();
                return "button" === a ? c.apply(this, arguments) : ("input" !== a && "option" !== a && r("jQuery.fn.attr('value', val) no longer sets properties"), e.value = t, n)
            }
        };
        var g, h, v = e.fn.init, m = e.parseJSON, y = /^([^<]*)(<[\w\W]+>)([^>]*)$/;
        e.fn.init = function (t, n, a) {
            var i;
            return t && "string" == typeof t && !e.isPlainObject(n) && (i = y.exec(e.trim(t))) && i[0] && ("<" !== t.charAt(0) && r("$(html) HTML strings must start with '<' character"), i[3] && r("$(html) HTML text after last tag is ignored"), "#" === i[0].charAt(0) && (r("HTML string cannot start with a '#' character"), e.error("JQMIGRATE: Invalid selector string (XSS)")), n && n.context && (n = n.context), e.parseHTML) ? v.call(this, e.parseHTML(i[2], n, !0), n, a) : v.apply(this, arguments)
        }, e.fn.init.prototype = e.fn, e.parseJSON = function (e) {
            return e || null === e ? m.apply(this, arguments) : (r("jQuery.parseJSON requires a valid JSON string"), null)
        }, e.uaMatch = function (e) {
            e = e.toLowerCase();
            var t = /(chrome)[ \/]([\w.]+)/.exec(e) || /(webkit)[ \/]([\w.]+)/.exec(e) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e) || /(msie) ([\w.]+)/.exec(e) || 0 > e.indexOf("compatible") && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e) || [];
            return {browser: t[1] || "", version: t[2] || "0"}
        }, e.browser || (g = e.uaMatch(navigator.userAgent), h = {}, g.browser && (h[g.browser] = !0, h.version = g.version), h.chrome ? h.webkit = !0 : h.webkit && (h.safari = !0), e.browser = h), a(e, "browser", e.browser, "jQuery.browser is deprecated"), e.sub = function () {
            function t(e, n) {
                return new t.fn.init(e, n)
            }

            e.extend(!0, t, this), t.superclass = this, t.fn = t.prototype = this(), t.fn.constructor = t, t.sub = this.sub, t.fn.init = function (r, a) {
                return a && a instanceof e && !(a instanceof t) && (a = t(a)), e.fn.init.call(this, r, a, n)
            }, t.fn.init.prototype = t.fn;
            var n = t(document);
            return r("jQuery.sub() is deprecated"), t
        }, e.ajaxSetup({converters: {"text json": e.parseJSON}});
        var b = e.fn.data;
        e.fn.data = function (t) {
            var a, i, o = this[0];
            return !o || "events" !== t || 1 !== arguments.length || (a = e.data(o, t), i = e._data(o, t), a !== n && a !== i || i === n) ? b.apply(this, arguments) : (r("Use of jQuery.fn.data('events') is deprecated"), i)
        };
        var j = /\/(java|ecma)script/i, w = e.fn.andSelf || e.fn.addBack;
        e.fn.andSelf = function () {
            return r("jQuery.fn.andSelf() replaced by jQuery.fn.addBack()"), w.apply(this, arguments)
        }, e.clean || (e.clean = function (t, a, i, o) {
            a = a || document, a = !a.nodeType && a[0] || a, a = a.ownerDocument || a, r("jQuery.clean() is deprecated");
            var s, u, c, l, d = [];
            if (e.merge(d, e.buildFragment(t, a).childNodes), i) for (c = function (e) {
                return !e.type || j.test(e.type) ? o ? o.push(e.parentNode ? e.parentNode.removeChild(e) : e) : i.appendChild(e) : n
            }, s = 0; null != (u = d[s]); s++) e.nodeName(u, "script") && c(u) || (i.appendChild(u), u.getElementsByTagName !== n && (l = e.grep(e.merge([], u.getElementsByTagName("script")), c), d.splice.apply(d, [s + 1, 0].concat(l)), s += l.length));
            return d
        });
        var Q = e.event.add, x = e.event.remove, k = e.event.trigger, N = e.fn.toggle, T = e.fn.live, M = e.fn.die,
            S = "ajaxStart|ajaxStop|ajaxSend|ajaxComplete|ajaxError|ajaxSuccess", C = RegExp("\\b(?:" + S + ")\\b"),
            H = /(?:^|\s)hover(\.\S+|)\b/, A = function (t) {
                return "string" != typeof t || e.event.special.hover ? t : (H.test(t) && r("'hover' pseudo-event is deprecated, use 'mouseenter mouseleave'"), t && t.replace(H, "mouseenter$1 mouseleave$1"))
            };
        e.event.props && "attrChange" !== e.event.props[0] && e.event.props.unshift("attrChange", "attrName", "relatedNode", "srcElement"), e.event.dispatch && a(e.event, "handle", e.event.dispatch, "jQuery.event.handle is undocumented and deprecated"), e.event.add = function (e, t, n, a, i) {
            e !== document && C.test(t) && r("AJAX events should be attached to document: " + t), Q.call(this, e, A(t || ""), n, a, i)
        }, e.event.remove = function (e, t, n, r, a) {
            x.call(this, e, A(t) || "", n, r, a)
        }, e.fn.error = function () {
            var e = Array.prototype.slice.call(arguments, 0);
            return r("jQuery.fn.error() is deprecated"), e.splice(0, 0, "error"), arguments.length ? this.bind.apply(this, e) : (this.triggerHandler.apply(this, e), this)
        }, e.fn.toggle = function (t, n) {
            if (!e.isFunction(t) || !e.isFunction(n)) return N.apply(this, arguments);
            r("jQuery.fn.toggle(handler, handler...) is deprecated");
            var a = arguments, i = t.guid || e.guid++, o = 0, s = function (n) {
                var r = (e._data(this, "lastToggle" + t.guid) || 0) % o;
                return e._data(this, "lastToggle" + t.guid, r + 1), n.preventDefault(), a[r].apply(this, arguments) || !1
            };
            for (s.guid = i; a.length > o;) a[o++].guid = i;
            return this.click(s)
        }, e.fn.live = function (t, n, a) {
            return r("jQuery.fn.live() is deprecated"), T ? T.apply(this, arguments) : (e(this.context).on(t, this.selector, n, a), this)
        }, e.fn.die = function (t, n) {
            return r("jQuery.fn.die() is deprecated"), M ? M.apply(this, arguments) : (e(this.context).off(t, this.selector || "**", n), this)
        }, e.event.trigger = function (e, t, n, a) {
            return n || C.test(e) || r("Global events are undocumented and deprecated"), k.call(this, e, t, n || document, a)
        }, e.each(S.split("|"), function (t, n) {
            e.event.special[n] = {
                setup: function () {
                    var t = this;
                    return t !== document && (e.event.add(document, n + "." + e.guid, function () {
                        e.event.trigger(n, null, t, !0)
                    }), e._data(this, n, e.guid++)), !1
                }, teardown: function () {
                    return this !== document && e.event.remove(document, n + "." + e._data(this, n)), !1
                }
            }
        })
    }(jQuery, window);
    //]]></script>
<script>//<![CDATA[
    /*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under the MIT license
 */
    if ("undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");
    +function (a) {
        "use strict";
        var b = a.fn.jquery.split(" ")[0].split(".");
        if (b[0] < 2 && b[1] < 9 || 1 == b[0] && 9 == b[1] && b[2] < 1 || b[0] > 3) throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")
    }(jQuery), +function (a) {
        "use strict";

        function b() {
            var a = document.createElement("bootstrap"), b = {
                WebkitTransition: "webkitTransitionEnd",
                MozTransition: "transitionend",
                OTransition: "oTransitionEnd otransitionend",
                transition: "transitionend"
            };
            for (var c in b) if (void 0 !== a.style[c]) return {end: b[c]};
            return !1
        }

        a.fn.emulateTransitionEnd = function (b) {
            var c = !1, d = this;
            a(this).one("bsTransitionEnd", function () {
                c = !0
            });
            var e = function () {
                c || a(d).trigger(a.support.transition.end)
            };
            return setTimeout(e, b), this
        }, a(function () {
            a.support.transition = b(), a.support.transition && (a.event.special.bsTransitionEnd = {
                bindType: a.support.transition.end,
                delegateType: a.support.transition.end,
                handle: function (b) {
                    if (a(b.target).is(this)) return b.handleObj.handler.apply(this, arguments)
                }
            })
        })
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            return this.each(function () {
                var c = a(this), e = c.data("bs.alert");
                e || c.data("bs.alert", e = new d(this)), "string" == typeof b && e[b].call(c)
            })
        }

        var c = '[data-dismiss="alert"]', d = function (b) {
            a(b).on("click", c, this.close)
        };
        d.VERSION = "3.3.7", d.TRANSITION_DURATION = 150, d.prototype.close = function (b) {
            function c() {
                g.detach().trigger("closed.bs.alert").remove()
            }

            var e = a(this), f = e.attr("data-target");
            f || (f = e.attr("href"), f = f && f.replace(/.*(?=#[^\s]*$)/, ""));
            var g = a("#" === f ? [] : f);
            b && b.preventDefault(), g.length || (g = e.closest(".alert")), g.trigger(b = a.Event("close.bs.alert")), b.isDefaultPrevented() || (g.removeClass("in"), a.support.transition && g.hasClass("fade") ? g.one("bsTransitionEnd", c).emulateTransitionEnd(d.TRANSITION_DURATION) : c())
        };
        var e = a.fn.alert;
        a.fn.alert = b, a.fn.alert.Constructor = d, a.fn.alert.noConflict = function () {
            return a.fn.alert = e, this
        }, a(document).on("click.bs.alert.data-api", c, d.prototype.close)
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            return this.each(function () {
                var d = a(this), e = d.data("bs.button"), f = "object" == typeof b && b;
                e || d.data("bs.button", e = new c(this, f)), "toggle" == b ? e.toggle() : b && e.setState(b)
            })
        }

        var c = function (b, d) {
            this.$element = a(b), this.options = a.extend({}, c.DEFAULTS, d), this.isLoading = !1
        };
        c.VERSION = "3.3.7", c.DEFAULTS = {loadingText: "loading..."}, c.prototype.setState = function (b) {
            var c = "disabled", d = this.$element, e = d.is("input") ? "val" : "html", f = d.data();
            b += "Text", null == f.resetText && d.data("resetText", d[e]()), setTimeout(a.proxy(function () {
                d[e](null == f[b] ? this.options[b] : f[b]), "loadingText" == b ? (this.isLoading = !0, d.addClass(c).attr(c, c).prop(c, !0)) : this.isLoading && (this.isLoading = !1, d.removeClass(c).removeAttr(c).prop(c, !1))
            }, this), 0)
        }, c.prototype.toggle = function () {
            var a = !0, b = this.$element.closest('[data-toggle="buttons"]');
            if (b.length) {
                var c = this.$element.find("input");
                "radio" == c.prop("type") ? (c.prop("checked") && (a = !1), b.find(".active").removeClass("active"), this.$element.addClass("active")) : "checkbox" == c.prop("type") && (c.prop("checked") !== this.$element.hasClass("active") && (a = !1), this.$element.toggleClass("active")), c.prop("checked", this.$element.hasClass("active")), a && c.trigger("change")
            } else this.$element.attr("aria-pressed", !this.$element.hasClass("active")), this.$element.toggleClass("active")
        };
        var d = a.fn.button;
        a.fn.button = b, a.fn.button.Constructor = c, a.fn.button.noConflict = function () {
            return a.fn.button = d, this
        }, a(document).on("click.bs.button.data-api", '[data-toggle^="button"]', function (c) {
            var d = a(c.target).closest(".btn");
            b.call(d, "toggle"), a(c.target).is('input[type="radio"], input[type="checkbox"]') || (c.preventDefault(), d.is("input,button") ? d.trigger("focus") : d.find("input:visible,button:visible").first().trigger("focus"))
        }).on("focus.bs.button.data-api blur.bs.button.data-api", '[data-toggle^="button"]', function (b) {
            a(b.target).closest(".btn").toggleClass("focus", /^focus(in)?$/.test(b.type))
        })
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            return this.each(function () {
                var d = a(this), e = d.data("bs.carousel"),
                    f = a.extend({}, c.DEFAULTS, d.data(), "object" == typeof b && b),
                    g = "string" == typeof b ? b : f.slide;
                e || d.data("bs.carousel", e = new c(this, f)), "number" == typeof b ? e.to(b) : g ? e[g]() : f.interval && e.pause().cycle()
            })
        }

        var c = function (b, c) {
            this.$element = a(b), this.$indicators = this.$element.find(".carousel-indicators"), this.options = c, this.paused = null, this.sliding = null, this.interval = null, this.$active = null, this.$items = null, this.options.keyboard && this.$element.on("keydown.bs.carousel", a.proxy(this.keydown, this)), "hover" == this.options.pause && !("ontouchstart" in document.documentElement) && this.$element.on("mouseenter.bs.carousel", a.proxy(this.pause, this)).on("mouseleave.bs.carousel", a.proxy(this.cycle, this))
        };
        c.VERSION = "3.3.7", c.TRANSITION_DURATION = 600, c.DEFAULTS = {
            interval: 5e3,
            pause: "hover",
            wrap: !0,
            keyboard: !0
        }, c.prototype.keydown = function (a) {
            if (!/input|textarea/i.test(a.target.tagName)) {
                switch (a.which) {
                    case 37:
                        this.prev();
                        break;
                    case 39:
                        this.next();
                        break;
                    default:
                        return
                }
                a.preventDefault()
            }
        }, c.prototype.cycle = function (b) {
            return b || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(a.proxy(this.next, this), this.options.interval)), this
        }, c.prototype.getItemIndex = function (a) {
            return this.$items = a.parent().children(".item"), this.$items.index(a || this.$active)
        }, c.prototype.getItemForDirection = function (a, b) {
            var c = this.getItemIndex(b), d = "prev" == a && 0 === c || "next" == a && c == this.$items.length - 1;
            if (d && !this.options.wrap) return b;
            var e = "prev" == a ? -1 : 1, f = (c + e) % this.$items.length;
            return this.$items.eq(f)
        }, c.prototype.to = function (a) {
            var b = this, c = this.getItemIndex(this.$active = this.$element.find(".item.active"));
            if (!(a > this.$items.length - 1 || a < 0)) return this.sliding ? this.$element.one("slid.bs.carousel", function () {
                b.to(a)
            }) : c == a ? this.pause().cycle() : this.slide(a > c ? "next" : "prev", this.$items.eq(a))
        }, c.prototype.pause = function (b) {
            return b || (this.paused = !0), this.$element.find(".next, .prev").length && a.support.transition && (this.$element.trigger(a.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
        }, c.prototype.next = function () {
            if (!this.sliding) return this.slide("next")
        }, c.prototype.prev = function () {
            if (!this.sliding) return this.slide("prev")
        }, c.prototype.slide = function (b, d) {
            var e = this.$element.find(".item.active"), f = d || this.getItemForDirection(b, e), g = this.interval,
                h = "next" == b ? "left" : "right", i = this;
            if (f.hasClass("active")) return this.sliding = !1;
            var j = f[0], k = a.Event("slide.bs.carousel", {relatedTarget: j, direction: h});
            if (this.$element.trigger(k), !k.isDefaultPrevented()) {
                if (this.sliding = !0, g && this.pause(), this.$indicators.length) {
                    this.$indicators.find(".active").removeClass("active");
                    var l = a(this.$indicators.children()[this.getItemIndex(f)]);
                    l && l.addClass("active")
                }
                var m = a.Event("slid.bs.carousel", {relatedTarget: j, direction: h});
                return a.support.transition && this.$element.hasClass("slide") ? (f.addClass(b), f[0].offsetWidth, e.addClass(h), f.addClass(h), e.one("bsTransitionEnd", function () {
                    f.removeClass([b, h].join(" ")).addClass("active"), e.removeClass(["active", h].join(" ")), i.sliding = !1, setTimeout(function () {
                        i.$element.trigger(m)
                    }, 0)
                }).emulateTransitionEnd(c.TRANSITION_DURATION)) : (e.removeClass("active"), f.addClass("active"), this.sliding = !1, this.$element.trigger(m)), g && this.cycle(), this
            }
        };
        var d = a.fn.carousel;
        a.fn.carousel = b, a.fn.carousel.Constructor = c, a.fn.carousel.noConflict = function () {
            return a.fn.carousel = d, this
        };
        var e = function (c) {
            var d, e = a(this), f = a(e.attr("data-target") || (d = e.attr("href")) && d.replace(/.*(?=#[^\s]+$)/, ""));
            if (f.hasClass("carousel")) {
                var g = a.extend({}, f.data(), e.data()), h = e.attr("data-slide-to");
                h && (g.interval = !1), b.call(f, g), h && f.data("bs.carousel").to(h), c.preventDefault()
            }
        };
        a(document).on("click.bs.carousel.data-api", "[data-slide]", e).on("click.bs.carousel.data-api", "[data-slide-to]", e), a(window).on("load", function () {
            a('[data-ride="carousel"]').each(function () {
                var c = a(this);
                b.call(c, c.data())
            })
        })
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            var c, d = b.attr("data-target") || (c = b.attr("href")) && c.replace(/.*(?=#[^\s]+$)/, "");
            return a(d)
        }

        function c(b) {
            return this.each(function () {
                var c = a(this), e = c.data("bs.collapse"),
                    f = a.extend({}, d.DEFAULTS, c.data(), "object" == typeof b && b);
                !e && f.toggle && /show|hide/.test(b) && (f.toggle = !1), e || c.data("bs.collapse", e = new d(this, f)), "string" == typeof b && e[b]()
            })
        }

        var d = function (b, c) {
            this.$element = a(b), this.options = a.extend({}, d.DEFAULTS, c), this.$trigger = a('[data-toggle="collapse"][href="#' + b.id + '"],[data-toggle="collapse"][data-target="#' + b.id + '"]'), this.transitioning = null, this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger), this.options.toggle && this.toggle()
        };
        d.VERSION = "3.3.7", d.TRANSITION_DURATION = 350, d.DEFAULTS = {toggle: !0}, d.prototype.dimension = function () {
            var a = this.$element.hasClass("width");
            return a ? "width" : "height"
        }, d.prototype.show = function () {
            if (!this.transitioning && !this.$element.hasClass("in")) {
                var b, e = this.$parent && this.$parent.children(".panel").children(".in, .collapsing");
                if (!(e && e.length && (b = e.data("bs.collapse"), b && b.transitioning))) {
                    var f = a.Event("show.bs.collapse");
                    if (this.$element.trigger(f), !f.isDefaultPrevented()) {
                        e && e.length && (c.call(e, "hide"), b || e.data("bs.collapse", null));
                        var g = this.dimension();
                        this.$element.removeClass("collapse").addClass("collapsing")[g](0).attr("aria-expanded", !0), this.$trigger.removeClass("collapsed").attr("aria-expanded", !0), this.transitioning = 1;
                        var h = function () {
                            this.$element.removeClass("collapsing").addClass("collapse in")[g](""), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
                        };
                        if (!a.support.transition) return h.call(this);
                        var i = a.camelCase(["scroll", g].join("-"));
                        this.$element.one("bsTransitionEnd", a.proxy(h, this)).emulateTransitionEnd(d.TRANSITION_DURATION)[g](this.$element[0][i])
                    }
                }
            }
        }, d.prototype.hide = function () {
            if (!this.transitioning && this.$element.hasClass("in")) {
                var b = a.Event("hide.bs.collapse");
                if (this.$element.trigger(b), !b.isDefaultPrevented()) {
                    var c = this.dimension();
                    this.$element[c](this.$element[c]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1), this.$trigger.addClass("collapsed").attr("aria-expanded", !1), this.transitioning = 1;
                    var e = function () {
                        this.transitioning = 0, this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
                    };
                    return a.support.transition ? void this.$element[c](0).one("bsTransitionEnd", a.proxy(e, this)).emulateTransitionEnd(d.TRANSITION_DURATION) : e.call(this)
                }
            }
        }, d.prototype.toggle = function () {
            this[this.$element.hasClass("in") ? "hide" : "show"]()
        }, d.prototype.getParent = function () {
            return a(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(a.proxy(function (c, d) {
                var e = a(d);
                this.addAriaAndCollapsedClass(b(e), e)
            }, this)).end()
        }, d.prototype.addAriaAndCollapsedClass = function (a, b) {
            var c = a.hasClass("in");
            a.attr("aria-expanded", c), b.toggleClass("collapsed", !c).attr("aria-expanded", c)
        };
        var e = a.fn.collapse;
        a.fn.collapse = c, a.fn.collapse.Constructor = d, a.fn.collapse.noConflict = function () {
            return a.fn.collapse = e, this
        }, a(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function (d) {
            var e = a(this);
            e.attr("data-target") || d.preventDefault();
            var f = b(e), g = f.data("bs.collapse"), h = g ? "toggle" : e.data();
            c.call(f, h)
        })
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            var c = b.attr("data-target");
            c || (c = b.attr("href"), c = c && /#[A-Za-z]/.test(c) && c.replace(/.*(?=#[^\s]*$)/, ""));
            var d = c && a(c);
            return d && d.length ? d : b.parent()
        }

        function c(c) {
            c && 3 === c.which || (a(e).remove(), a(f).each(function () {
                var d = a(this), e = b(d), f = {relatedTarget: this};
                e.hasClass("open") && (c && "click" == c.type && /input|textarea/i.test(c.target.tagName) && a.contains(e[0], c.target) || (e.trigger(c = a.Event("hide.bs.dropdown", f)), c.isDefaultPrevented() || (d.attr("aria-expanded", "false"), e.removeClass("open").trigger(a.Event("hidden.bs.dropdown", f)))))
            }))
        }

        function d(b) {
            return this.each(function () {
                var c = a(this), d = c.data("bs.dropdown");
                d || c.data("bs.dropdown", d = new g(this)), "string" == typeof b && d[b].call(c)
            })
        }

        var e = ".dropdown-backdrop", f = '[data-toggle="dropdown"]', g = function (b) {
            a(b).on("click.bs.dropdown", this.toggle)
        };
        g.VERSION = "3.3.7", g.prototype.toggle = function (d) {
            var e = a(this);
            if (!e.is(".disabled, :disabled")) {
                var f = b(e), g = f.hasClass("open");
                if (c(), !g) {
                    "ontouchstart" in document.documentElement && !f.closest(".navbar-nav").length && a(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(a(this)).on("click", c);
                    var h = {relatedTarget: this};
                    if (f.trigger(d = a.Event("show.bs.dropdown", h)), d.isDefaultPrevented()) return;
                    e.trigger("focus").attr("aria-expanded", "true"), f.toggleClass("open").trigger(a.Event("shown.bs.dropdown", h))
                }
                return !1
            }
        }, g.prototype.keydown = function (c) {
            if (/(38|40|27|32)/.test(c.which) && !/input|textarea/i.test(c.target.tagName)) {
                var d = a(this);
                if (c.preventDefault(), c.stopPropagation(), !d.is(".disabled, :disabled")) {
                    var e = b(d), g = e.hasClass("open");
                    if (!g && 27 != c.which || g && 27 == c.which) return 27 == c.which && e.find(f).trigger("focus"), d.trigger("click");
                    var h = " li:not(.disabled):visible a", i = e.find(".dropdown-menu" + h);
                    if (i.length) {
                        var j = i.index(c.target);
                        38 == c.which && j > 0 && j--, 40 == c.which && j < i.length - 1 && j++, ~j || (j = 0), i.eq(j).trigger("focus")
                    }
                }
            }
        };
        var h = a.fn.dropdown;
        a.fn.dropdown = d, a.fn.dropdown.Constructor = g, a.fn.dropdown.noConflict = function () {
            return a.fn.dropdown = h, this
        }, a(document).on("click.bs.dropdown.data-api", c).on("click.bs.dropdown.data-api", ".dropdown form", function (a) {
            a.stopPropagation()
        }).on("click.bs.dropdown.data-api", f, g.prototype.toggle).on("keydown.bs.dropdown.data-api", f, g.prototype.keydown).on("keydown.bs.dropdown.data-api", ".dropdown-menu", g.prototype.keydown)
    }(jQuery), +function (a) {
        "use strict";

        function b(b, d) {
            return this.each(function () {
                var e = a(this), f = e.data("bs.modal"),
                    g = a.extend({}, c.DEFAULTS, e.data(), "object" == typeof b && b);
                f || e.data("bs.modal", f = new c(this, g)), "string" == typeof b ? f[b](d) : g.show && f.show(d)
            })
        }

        var c = function (b, c) {
            this.options = c, this.$body = a(document.body), this.$element = a(b), this.$dialog = this.$element.find(".modal-dialog"), this.$backdrop = null, this.isShown = null, this.originalBodyPad = null, this.scrollbarWidth = 0, this.ignoreBackdropClick = !1, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, a.proxy(function () {
                this.$element.trigger("loaded.bs.modal")
            }, this))
        };
        c.VERSION = "3.3.7", c.TRANSITION_DURATION = 300, c.BACKDROP_TRANSITION_DURATION = 150, c.DEFAULTS = {
            backdrop: !0,
            keyboard: !0,
            show: !0
        }, c.prototype.toggle = function (a) {
            return this.isShown ? this.hide() : this.show(a)
        }, c.prototype.show = function (b) {
            var d = this, e = a.Event("show.bs.modal", {relatedTarget: b});
            this.$element.trigger(e), this.isShown || e.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', a.proxy(this.hide, this)), this.$dialog.on("mousedown.dismiss.bs.modal", function () {
                d.$element.one("mouseup.dismiss.bs.modal", function (b) {
                    a(b.target).is(d.$element) && (d.ignoreBackdropClick = !0)
                })
            }), this.backdrop(function () {
                var e = a.support.transition && d.$element.hasClass("fade");
                d.$element.parent().length || d.$element.appendTo(d.$body), d.$element.show().scrollTop(0), d.adjustDialog(), e && d.$element[0].offsetWidth, d.$element.addClass("in"), d.enforceFocus();
                var f = a.Event("shown.bs.modal", {relatedTarget: b});
                e ? d.$dialog.one("bsTransitionEnd", function () {
                    d.$element.trigger("focus").trigger(f)
                }).emulateTransitionEnd(c.TRANSITION_DURATION) : d.$element.trigger("focus").trigger(f)
            }))
        }, c.prototype.hide = function (b) {
            b && b.preventDefault(), b = a.Event("hide.bs.modal"), this.$element.trigger(b), this.isShown && !b.isDefaultPrevented() && (this.isShown = !1, this.escape(), this.resize(), a(document).off("focusin.bs.modal"), this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"), this.$dialog.off("mousedown.dismiss.bs.modal"), a.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", a.proxy(this.hideModal, this)).emulateTransitionEnd(c.TRANSITION_DURATION) : this.hideModal())
        }, c.prototype.enforceFocus = function () {
            a(document).off("focusin.bs.modal").on("focusin.bs.modal", a.proxy(function (a) {
                document === a.target || this.$element[0] === a.target || this.$element.has(a.target).length || this.$element.trigger("focus")
            }, this))
        }, c.prototype.escape = function () {
            this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", a.proxy(function (a) {
                27 == a.which && this.hide()
            }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
        }, c.prototype.resize = function () {
            this.isShown ? a(window).on("resize.bs.modal", a.proxy(this.handleUpdate, this)) : a(window).off("resize.bs.modal")
        }, c.prototype.hideModal = function () {
            var a = this;
            this.$element.hide(), this.backdrop(function () {
                a.$body.removeClass("modal-open"), a.resetAdjustments(), a.resetScrollbar(), a.$element.trigger("hidden.bs.modal")
            })
        }, c.prototype.removeBackdrop = function () {
            this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
        }, c.prototype.backdrop = function (b) {
            var d = this, e = this.$element.hasClass("fade") ? "fade" : "";
            if (this.isShown && this.options.backdrop) {
                var f = a.support.transition && e;
                if (this.$backdrop = a(document.createElement("div")).addClass("modal-backdrop " + e).appendTo(this.$body), this.$element.on("click.dismiss.bs.modal", a.proxy(function (a) {
                    return this.ignoreBackdropClick ? void(this.ignoreBackdropClick = !1) : void(a.target === a.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide()))
                }, this)), f && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !b) return;
                f ? this.$backdrop.one("bsTransitionEnd", b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION) : b()
            } else if (!this.isShown && this.$backdrop) {
                this.$backdrop.removeClass("in");
                var g = function () {
                    d.removeBackdrop(), b && b()
                };
                a.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION) : g()
            } else b && b()
        }, c.prototype.handleUpdate = function () {
            this.adjustDialog()
        }, c.prototype.adjustDialog = function () {
            var a = this.$element[0].scrollHeight > document.documentElement.clientHeight;
            this.$element.css({
                paddingLeft: !this.bodyIsOverflowing && a ? this.scrollbarWidth : "",
                paddingRight: this.bodyIsOverflowing && !a ? this.scrollbarWidth : ""
            })
        }, c.prototype.resetAdjustments = function () {
            this.$element.css({paddingLeft: "", paddingRight: ""})
        }, c.prototype.checkScrollbar = function () {
            var a = window.innerWidth;
            if (!a) {
                var b = document.documentElement.getBoundingClientRect();
                a = b.right - Math.abs(b.left)
            }
            this.bodyIsOverflowing = document.body.clientWidth < a, this.scrollbarWidth = this.measureScrollbar()
        }, c.prototype.setScrollbar = function () {
            var a = parseInt(this.$body.css("padding-right") || 0, 10);
            this.originalBodyPad = document.body.style.paddingRight || "", this.bodyIsOverflowing && this.$body.css("padding-right", a + this.scrollbarWidth)
        }, c.prototype.resetScrollbar = function () {
            this.$body.css("padding-right", this.originalBodyPad)
        }, c.prototype.measureScrollbar = function () {
            var a = document.createElement("div");
            a.className = "modal-scrollbar-measure", this.$body.append(a);
            var b = a.offsetWidth - a.clientWidth;
            return this.$body[0].removeChild(a), b
        };
        var d = a.fn.modal;
        a.fn.modal = b, a.fn.modal.Constructor = c, a.fn.modal.noConflict = function () {
            return a.fn.modal = d, this
        }, a(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function (c) {
            var d = a(this), e = d.attr("href"), f = a(d.attr("data-target") || e && e.replace(/.*(?=#[^\s]+$)/, "")),
                g = f.data("bs.modal") ? "toggle" : a.extend({remote: !/#/.test(e) && e}, f.data(), d.data());
            d.is("a") && c.preventDefault(), f.one("show.bs.modal", function (a) {
                a.isDefaultPrevented() || f.one("hidden.bs.modal", function () {
                    d.is(":visible") && d.trigger("focus")
                })
            }), b.call(f, g, this)
        })
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            return this.each(function () {
                var d = a(this), e = d.data("bs.tooltip"), f = "object" == typeof b && b;
                !e && /destroy|hide/.test(b) || (e || d.data("bs.tooltip", e = new c(this, f)), "string" == typeof b && e[b]())
            })
        }

        var c = function (a, b) {
            this.type = null, this.options = null, this.enabled = null, this.timeout = null, this.hoverState = null, this.$element = null, this.inState = null, this.init("tooltip", a, b)
        };
        c.VERSION = "3.3.7", c.TRANSITION_DURATION = 150, c.DEFAULTS = {
            animation: !0,
            placement: "top",
            selector: !1,
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
            trigger: "hover focus",
            title: "",
            delay: 0,
            html: !1,
            container: !1,
            viewport: {selector: "body", padding: 0}
        }, c.prototype.init = function (b, c, d) {
            if (this.enabled = !0, this.type = b, this.$element = a(c), this.options = this.getOptions(d), this.$viewport = this.options.viewport && a(a.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : this.options.viewport.selector || this.options.viewport), this.inState = {
                click: !1,
                hover: !1,
                focus: !1
            }, this.$element[0] instanceof document.constructor && !this.options.selector) throw new Error("`selector` option must be specified when initializing " + this.type + " on the window.document object!");
            for (var e = this.options.trigger.split(" "), f = e.length; f--;) {
                var g = e[f];
                if ("click" == g) this.$element.on("click." + this.type, this.options.selector, a.proxy(this.toggle, this)); else if ("manual" != g) {
                    var h = "hover" == g ? "mouseenter" : "focusin", i = "hover" == g ? "mouseleave" : "focusout";
                    this.$element.on(h + "." + this.type, this.options.selector, a.proxy(this.enter, this)), this.$element.on(i + "." + this.type, this.options.selector, a.proxy(this.leave, this))
                }
            }
            this.options.selector ? this._options = a.extend({}, this.options, {
                trigger: "manual",
                selector: ""
            }) : this.fixTitle()
        }, c.prototype.getDefaults = function () {
            return c.DEFAULTS
        }, c.prototype.getOptions = function (b) {
            return b = a.extend({}, this.getDefaults(), this.$element.data(), b), b.delay && "number" == typeof b.delay && (b.delay = {
                show: b.delay,
                hide: b.delay
            }), b
        }, c.prototype.getDelegateOptions = function () {
            var b = {}, c = this.getDefaults();
            return this._options && a.each(this._options, function (a, d) {
                c[a] != d && (b[a] = d)
            }), b
        }, c.prototype.enter = function (b) {
            var c = b instanceof this.constructor ? b : a(b.currentTarget).data("bs." + this.type);
            return c || (c = new this.constructor(b.currentTarget, this.getDelegateOptions()), a(b.currentTarget).data("bs." + this.type, c)), b instanceof a.Event && (c.inState["focusin" == b.type ? "focus" : "hover"] = !0), c.tip().hasClass("in") || "in" == c.hoverState ? void(c.hoverState = "in") : (clearTimeout(c.timeout), c.hoverState = "in", c.options.delay && c.options.delay.show ? void(c.timeout = setTimeout(function () {
                "in" == c.hoverState && c.show()
            }, c.options.delay.show)) : c.show())
        }, c.prototype.isInStateTrue = function () {
            for (var a in this.inState) if (this.inState[a]) return !0;
            return !1
        }, c.prototype.leave = function (b) {
            var c = b instanceof this.constructor ? b : a(b.currentTarget).data("bs." + this.type);
            if (c || (c = new this.constructor(b.currentTarget, this.getDelegateOptions()), a(b.currentTarget).data("bs." + this.type, c)), b instanceof a.Event && (c.inState["focusout" == b.type ? "focus" : "hover"] = !1), !c.isInStateTrue()) return clearTimeout(c.timeout), c.hoverState = "out", c.options.delay && c.options.delay.hide ? void(c.timeout = setTimeout(function () {
                "out" == c.hoverState && c.hide()
            }, c.options.delay.hide)) : c.hide()
        }, c.prototype.show = function () {
            var b = a.Event("show.bs." + this.type);
            if (this.hasContent() && this.enabled) {
                this.$element.trigger(b);
                var d = a.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
                if (b.isDefaultPrevented() || !d) return;
                var e = this, f = this.tip(), g = this.getUID(this.type);
                this.setContent(), f.attr("id", g), this.$element.attr("aria-describedby", g), this.options.animation && f.addClass("fade");
                var h = "function" == typeof this.options.placement ? this.options.placement.call(this, f[0], this.$element[0]) : this.options.placement,
                    i = /\s?auto?\s?/i, j = i.test(h);
                j && (h = h.replace(i, "") || "top"), f.detach().css({
                    top: 0,
                    left: 0,
                    display: "block"
                }).addClass(h).data("bs." + this.type, this), this.options.container ? f.appendTo(this.options.container) : f.insertAfter(this.$element), this.$element.trigger("inserted.bs." + this.type);
                var k = this.getPosition(), l = f[0].offsetWidth, m = f[0].offsetHeight;
                if (j) {
                    var n = h, o = this.getPosition(this.$viewport);
                    h = "bottom" == h && k.bottom + m > o.bottom ? "top" : "top" == h && k.top - m < o.top ? "bottom" : "right" == h && k.right + l > o.width ? "left" : "left" == h && k.left - l < o.left ? "right" : h, f.removeClass(n).addClass(h)
                }
                var p = this.getCalculatedOffset(h, k, l, m);
                this.applyPlacement(p, h);
                var q = function () {
                    var a = e.hoverState;
                    e.$element.trigger("shown.bs." + e.type), e.hoverState = null, "out" == a && e.leave(e)
                };
                a.support.transition && this.$tip.hasClass("fade") ? f.one("bsTransitionEnd", q).emulateTransitionEnd(c.TRANSITION_DURATION) : q()
            }
        }, c.prototype.applyPlacement = function (b, c) {
            var d = this.tip(), e = d[0].offsetWidth, f = d[0].offsetHeight, g = parseInt(d.css("margin-top"), 10),
                h = parseInt(d.css("margin-left"), 10);
            isNaN(g) && (g = 0), isNaN(h) && (h = 0), b.top += g, b.left += h, a.offset.setOffset(d[0], a.extend({
                using: function (a) {
                    d.css({top: Math.round(a.top), left: Math.round(a.left)})
                }
            }, b), 0), d.addClass("in");
            var i = d[0].offsetWidth, j = d[0].offsetHeight;
            "top" == c && j != f && (b.top = b.top + f - j);
            var k = this.getViewportAdjustedDelta(c, b, i, j);
            k.left ? b.left += k.left : b.top += k.top;
            var l = /top|bottom/.test(c), m = l ? 2 * k.left - e + i : 2 * k.top - f + j,
                n = l ? "offsetWidth" : "offsetHeight";
            d.offset(b), this.replaceArrow(m, d[0][n], l)
        }, c.prototype.replaceArrow = function (a, b, c) {
            this.arrow().css(c ? "left" : "top", 50 * (1 - a / b) + "%").css(c ? "top" : "left", "")
        }, c.prototype.setContent = function () {
            var a = this.tip(), b = this.getTitle();
            a.find(".tooltip-inner")[this.options.html ? "html" : "text"](b), a.removeClass("fade in top bottom left right")
        }, c.prototype.hide = function (b) {
            function d() {
                "in" != e.hoverState && f.detach(), e.$element && e.$element.removeAttr("aria-describedby").trigger("hidden.bs." + e.type), b && b()
            }

            var e = this, f = a(this.$tip), g = a.Event("hide.bs." + this.type);
            if (this.$element.trigger(g), !g.isDefaultPrevented()) return f.removeClass("in"), a.support.transition && f.hasClass("fade") ? f.one("bsTransitionEnd", d).emulateTransitionEnd(c.TRANSITION_DURATION) : d(), this.hoverState = null, this
        }, c.prototype.fixTitle = function () {
            var a = this.$element;
            (a.attr("title") || "string" != typeof a.attr("data-original-title")) && a.attr("data-original-title", a.attr("title") || "").attr("title", "")
        }, c.prototype.hasContent = function () {
            return this.getTitle()
        }, c.prototype.getPosition = function (b) {
            b = b || this.$element;
            var c = b[0], d = "BODY" == c.tagName, e = c.getBoundingClientRect();
            null == e.width && (e = a.extend({}, e, {width: e.right - e.left, height: e.bottom - e.top}));
            var f = window.SVGElement && c instanceof window.SVGElement,
                g = d ? {top: 0, left: 0} : f ? null : b.offset(),
                h = {scroll: d ? document.documentElement.scrollTop || document.body.scrollTop : b.scrollTop()},
                i = d ? {width: a(window).width(), height: a(window).height()} : null;
            return a.extend({}, e, h, i, g)
        }, c.prototype.getCalculatedOffset = function (a, b, c, d) {
            return "bottom" == a ? {
                top: b.top + b.height,
                left: b.left + b.width / 2 - c / 2
            } : "top" == a ? {
                top: b.top - d,
                left: b.left + b.width / 2 - c / 2
            } : "left" == a ? {
                top: b.top + b.height / 2 - d / 2,
                left: b.left - c
            } : {top: b.top + b.height / 2 - d / 2, left: b.left + b.width}
        }, c.prototype.getViewportAdjustedDelta = function (a, b, c, d) {
            var e = {top: 0, left: 0};
            if (!this.$viewport) return e;
            var f = this.options.viewport && this.options.viewport.padding || 0, g = this.getPosition(this.$viewport);
            if (/right|left/.test(a)) {
                var h = b.top - f - g.scroll, i = b.top + f - g.scroll + d;
                h < g.top ? e.top = g.top - h : i > g.top + g.height && (e.top = g.top + g.height - i)
            } else {
                var j = b.left - f, k = b.left + f + c;
                j < g.left ? e.left = g.left - j : k > g.right && (e.left = g.left + g.width - k)
            }
            return e
        }, c.prototype.getTitle = function () {
            var a, b = this.$element, c = this.options;
            return a = b.attr("data-original-title") || ("function" == typeof c.title ? c.title.call(b[0]) : c.title)
        }, c.prototype.getUID = function (a) {
            do a += ~~(1e6 * Math.random()); while (document.getElementById(a));
            return a
        }, c.prototype.tip = function () {
            if (!this.$tip && (this.$tip = a(this.options.template), 1 != this.$tip.length)) throw new Error(this.type + " `template` option must consist of exactly 1 top-level element!");
            return this.$tip
        }, c.prototype.arrow = function () {
            return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
        }, c.prototype.enable = function () {
            this.enabled = !0
        }, c.prototype.disable = function () {
            this.enabled = !1
        }, c.prototype.toggleEnabled = function () {
            this.enabled = !this.enabled
        }, c.prototype.toggle = function (b) {
            var c = this;
            b && (c = a(b.currentTarget).data("bs." + this.type), c || (c = new this.constructor(b.currentTarget, this.getDelegateOptions()), a(b.currentTarget).data("bs." + this.type, c))), b ? (c.inState.click = !c.inState.click, c.isInStateTrue() ? c.enter(c) : c.leave(c)) : c.tip().hasClass("in") ? c.leave(c) : c.enter(c)
        }, c.prototype.destroy = function () {
            var a = this;
            clearTimeout(this.timeout), this.hide(function () {
                a.$element.off("." + a.type).removeData("bs." + a.type), a.$tip && a.$tip.detach(), a.$tip = null, a.$arrow = null, a.$viewport = null, a.$element = null
            })
        };
        var d = a.fn.tooltip;
        a.fn.tooltip = b, a.fn.tooltip.Constructor = c, a.fn.tooltip.noConflict = function () {
            return a.fn.tooltip = d, this
        }
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            return this.each(function () {
                var d = a(this), e = d.data("bs.popover"), f = "object" == typeof b && b;
                !e && /destroy|hide/.test(b) || (e || d.data("bs.popover", e = new c(this, f)), "string" == typeof b && e[b]())
            })
        }

        var c = function (a, b) {
            this.init("popover", a, b)
        };
        if (!a.fn.tooltip) throw new Error("Popover requires tooltip.js");
        c.VERSION = "3.3.7", c.DEFAULTS = a.extend({}, a.fn.tooltip.Constructor.DEFAULTS, {
            placement: "right",
            trigger: "click",
            content: "",
            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
        }), c.prototype = a.extend({}, a.fn.tooltip.Constructor.prototype), c.prototype.constructor = c, c.prototype.getDefaults = function () {
            return c.DEFAULTS
        }, c.prototype.setContent = function () {
            var a = this.tip(), b = this.getTitle(), c = this.getContent();
            a.find(".popover-title")[this.options.html ? "html" : "text"](b), a.find(".popover-content").children().detach().end()[this.options.html ? "string" == typeof c ? "html" : "append" : "text"](c), a.removeClass("fade top bottom left right in"), a.find(".popover-title").html() || a.find(".popover-title").hide()
        }, c.prototype.hasContent = function () {
            return this.getTitle() || this.getContent()
        }, c.prototype.getContent = function () {
            var a = this.$element, b = this.options;
            return a.attr("data-content") || ("function" == typeof b.content ? b.content.call(a[0]) : b.content)
        }, c.prototype.arrow = function () {
            return this.$arrow = this.$arrow || this.tip().find(".arrow")
        };
        var d = a.fn.popover;
        a.fn.popover = b, a.fn.popover.Constructor = c, a.fn.popover.noConflict = function () {
            return a.fn.popover = d, this
        }
    }(jQuery), +function (a) {
        "use strict";

        function b(c, d) {
            this.$body = a(document.body), this.$scrollElement = a(a(c).is(document.body) ? window : c), this.options = a.extend({}, b.DEFAULTS, d), this.selector = (this.options.target || "") + " .nav li > a", this.offsets = [], this.targets = [], this.activeTarget = null, this.scrollHeight = 0, this.$scrollElement.on("scroll.bs.scrollspy", a.proxy(this.process, this)), this.refresh(), this.process()
        }

        function c(c) {
            return this.each(function () {
                var d = a(this), e = d.data("bs.scrollspy"), f = "object" == typeof c && c;
                e || d.data("bs.scrollspy", e = new b(this, f)), "string" == typeof c && e[c]()
            })
        }

        b.VERSION = "3.3.7", b.DEFAULTS = {offset: 10}, b.prototype.getScrollHeight = function () {
            return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
        }, b.prototype.refresh = function () {
            var b = this, c = "offset", d = 0;
            this.offsets = [], this.targets = [], this.scrollHeight = this.getScrollHeight(), a.isWindow(this.$scrollElement[0]) || (c = "position", d = this.$scrollElement.scrollTop()), this.$body.find(this.selector).map(function () {
                var b = a(this), e = b.data("target") || b.attr("href"), f = /^#./.test(e) && a(e);
                return f && f.length && f.is(":visible") && [[f[c]().top + d, e]] || null
            }).sort(function (a, b) {
                return a[0] - b[0]
            }).each(function () {
                b.offsets.push(this[0]), b.targets.push(this[1])
            })
        }, b.prototype.process = function () {
            var a, b = this.$scrollElement.scrollTop() + this.options.offset, c = this.getScrollHeight(),
                d = this.options.offset + c - this.$scrollElement.height(), e = this.offsets, f = this.targets,
                g = this.activeTarget;
            if (this.scrollHeight != c && this.refresh(), b >= d) return g != (a = f[f.length - 1]) && this.activate(a);
            if (g && b < e[0]) return this.activeTarget = null, this.clear();
            for (a = e.length; a--;) g != f[a] && b >= e[a] && (void 0 === e[a + 1] || b < e[a + 1]) && this.activate(f[a])
        }, b.prototype.activate = function (b) {
            this.activeTarget = b, this.clear();
            var c = this.selector + '[data-target="' + b + '"],' + this.selector + '[href="' + b + '"]',
                d = a(c).parents("li").addClass("active");
            d.parent(".dropdown-menu").length && (d = d.closest("li.dropdown").addClass("active")), d.trigger("activate.bs.scrollspy")
        }, b.prototype.clear = function () {
            a(this.selector).parentsUntil(this.options.target, ".active").removeClass("active")
        };
        var d = a.fn.scrollspy;
        a.fn.scrollspy = c, a.fn.scrollspy.Constructor = b, a.fn.scrollspy.noConflict = function () {
            return a.fn.scrollspy = d, this
        }, a(window).on("load.bs.scrollspy.data-api", function () {
            a('[data-spy="scroll"]').each(function () {
                var b = a(this);
                c.call(b, b.data())
            })
        })
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            return this.each(function () {
                var d = a(this), e = d.data("bs.tab");
                e || d.data("bs.tab", e = new c(this)), "string" == typeof b && e[b]()
            })
        }

        var c = function (b) {
            this.element = a(b)
        };
        c.VERSION = "3.3.7", c.TRANSITION_DURATION = 150, c.prototype.show = function () {
            var b = this.element, c = b.closest("ul:not(.dropdown-menu)"), d = b.data("target");
            if (d || (d = b.attr("href"), d = d && d.replace(/.*(?=#[^\s]*$)/, "")), !b.parent("li").hasClass("active")) {
                var e = c.find(".active:last a"), f = a.Event("hide.bs.tab", {relatedTarget: b[0]}),
                    g = a.Event("show.bs.tab", {relatedTarget: e[0]});
                if (e.trigger(f), b.trigger(g), !g.isDefaultPrevented() && !f.isDefaultPrevented()) {
                    var h = a(d);
                    this.activate(b.closest("li"), c), this.activate(h, h.parent(), function () {
                        e.trigger({type: "hidden.bs.tab", relatedTarget: b[0]}), b.trigger({
                            type: "shown.bs.tab",
                            relatedTarget: e[0]
                        })
                    })
                }
            }
        }, c.prototype.activate = function (b, d, e) {
            function f() {
                g.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1), b.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0), h ? (b[0].offsetWidth, b.addClass("in")) : b.removeClass("fade"), b.parent(".dropdown-menu").length && b.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0), e && e()
            }

            var g = d.find("> .active"),
                h = e && a.support.transition && (g.length && g.hasClass("fade") || !!d.find("> .fade").length);
            g.length && h ? g.one("bsTransitionEnd", f).emulateTransitionEnd(c.TRANSITION_DURATION) : f(), g.removeClass("in")
        };
        var d = a.fn.tab;
        a.fn.tab = b, a.fn.tab.Constructor = c, a.fn.tab.noConflict = function () {
            return a.fn.tab = d, this
        };
        var e = function (c) {
            c.preventDefault(), b.call(a(this), "show")
        };
        a(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', e).on("click.bs.tab.data-api", '[data-toggle="pill"]', e)
    }(jQuery), +function (a) {
        "use strict";

        function b(b) {
            return this.each(function () {
                var d = a(this), e = d.data("bs.affix"), f = "object" == typeof b && b;
                e || d.data("bs.affix", e = new c(this, f)), "string" == typeof b && e[b]()
            })
        }

        var c = function (b, d) {
            this.options = a.extend({}, c.DEFAULTS, d), this.$target = a(this.options.target).on("scroll.bs.affix.data-api", a.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", a.proxy(this.checkPositionWithEventLoop, this)), this.$element = a(b), this.affixed = null, this.unpin = null, this.pinnedOffset = null, this.checkPosition()
        };
        c.VERSION = "3.3.7", c.RESET = "affix affix-top affix-bottom", c.DEFAULTS = {
            offset: 0,
            target: window
        }, c.prototype.getState = function (a, b, c, d) {
            var e = this.$target.scrollTop(), f = this.$element.offset(), g = this.$target.height();
            if (null != c && "top" == this.affixed) return e < c && "top";
            if ("bottom" == this.affixed) return null != c ? !(e + this.unpin <= f.top) && "bottom" : !(e + g <= a - d) && "bottom";
            var h = null == this.affixed, i = h ? e : f.top, j = h ? g : b;
            return null != c && e <= c ? "top" : null != d && i + j >= a - d && "bottom"
        }, c.prototype.getPinnedOffset = function () {
            if (this.pinnedOffset) return this.pinnedOffset;
            this.$element.removeClass(c.RESET).addClass("affix");
            var a = this.$target.scrollTop(), b = this.$element.offset();
            return this.pinnedOffset = b.top - a
        }, c.prototype.checkPositionWithEventLoop = function () {
            setTimeout(a.proxy(this.checkPosition, this), 1)
        }, c.prototype.checkPosition = function () {
            if (this.$element.is(":visible")) {
                var b = this.$element.height(), d = this.options.offset, e = d.top, f = d.bottom,
                    g = Math.max(a(document).height(), a(document.body).height());
                "object" != typeof d && (f = e = d), "function" == typeof e && (e = d.top(this.$element)), "function" == typeof f && (f = d.bottom(this.$element));
                var h = this.getState(g, b, e, f);
                if (this.affixed != h) {
                    null != this.unpin && this.$element.css("top", "");
                    var i = "affix" + (h ? "-" + h : ""), j = a.Event(i + ".bs.affix");
                    if (this.$element.trigger(j), j.isDefaultPrevented()) return;
                    this.affixed = h, this.unpin = "bottom" == h ? this.getPinnedOffset() : null, this.$element.removeClass(c.RESET).addClass(i).trigger(i.replace("affix", "affixed") + ".bs.affix")
                }
                "bottom" == h && this.$element.offset({top: g - b - f})
            }
        };
        var d = a.fn.affix;
        a.fn.affix = b, a.fn.affix.Constructor = c, a.fn.affix.noConflict = function () {
            return a.fn.affix = d, this
        }, a(window).on("load", function () {
            a('[data-spy="affix"]').each(function () {
                var c = a(this), d = c.data();
                d.offset = d.offset || {}, null != d.offsetBottom && (d.offset.bottom = d.offsetBottom), null != d.offsetTop && (d.offset.top = d.offsetTop), b.call(c, d)
            })
        })
    }(jQuery);
    //]]></script>
<script>//<![CDATA[
    /*
 * jQuery One Page Nav Plugin
 * http://github.com/davist11/jQuery-One-Page-Nav
 *
 * Copyright (c) 2010 Trevor Davis (http://trevordavis.net)
 * Dual licensed under the MIT and GPL licenses.
 * Uses the same license as jQuery, see:
 * http://jquery.org/license
 *
 * @version 3.0.0
 *
 * Example usage:
 * $('#nav').onePageNav({
 *   currentClass: 'current',
 *   changeHash: false,
 *   scrollSpeed: 750
 * });
 */

    ;(function ($, window, document, undefined) {

        // our plugin constructor
        var OnePageNav = function (elem, options) {
            this.elem = elem;
            this.$elem = $(elem);
            this.options = options;
            this.metadata = this.$elem.data('plugin-options');
            this.$win = $(window);
            this.sections = {};
            this.didScroll = false;
            this.$doc = $(document);
            this.docHeight = this.$doc.height();
        };

        // the plugin prototype
        OnePageNav.prototype = {
            defaults: {
                navItems: 'a',
                currentClass: 'current',
                changeHash: false,
                easing: 'swing',
                filter: '',
                scrollSpeed: 750,
                scrollThreshold: 0.5,
                begin: false,
                end: false,
                scrollChange: false
            },

            init: function () {
                // Introduce defaults that can be extended either
                // globally or using an object literal.
                this.config = $.extend({}, this.defaults, this.options, this.metadata);

                this.$nav = this.$elem.find(this.config.navItems);

                //Filter any links out of the nav
                if (this.config.filter !== '') {
                    this.$nav = this.$nav.filter(this.config.filter);
                }

                //Handle clicks on the nav
                this.$nav.on('click.onePageNav', $.proxy(this.handleClick, this));

                //Get the section positions
                this.getPositions();

                //Handle scroll changes
                this.bindInterval();

                //Update the positions on resize too
                this.$win.on('resize.onePageNav', $.proxy(this.getPositions, this));

                return this;
            },

            adjustNav: function (self, $parent) {
                self.$elem.find('.' + self.config.currentClass).removeClass(self.config.currentClass);
                $parent.addClass(self.config.currentClass);
            },

            bindInterval: function () {
                var self = this;
                var docHeight;

                self.$win.on('scroll.onePageNav', function () {
                    self.didScroll = true;
                });

                self.t = setInterval(function () {
                    docHeight = self.$doc.height();

                    //If it was scrolled
                    if (self.didScroll) {
                        self.didScroll = false;
                        self.scrollChange();
                    }

                    //If the document height changes
                    if (docHeight !== self.docHeight) {
                        self.docHeight = docHeight;
                        self.getPositions();
                    }
                }, 250);
            },

            getHash: function ($link) {
                return $link.attr('href').split('#')[1];
            },

            getPositions: function () {
                var self = this;
                var linkHref;
                var topPos;
                var $target;

                self.$nav.each(function () {
                    linkHref = self.getHash($(this));
                    $target = $('#' + linkHref);

                    if ($target.length) {
                        topPos = $target.offset().top;
                        self.sections[linkHref] = Math.round(topPos);
                    }
                });
            },

            getSection: function (windowPos) {
                var returnValue = null;
                var windowHeight = Math.round(this.$win.height() * this.config.scrollThreshold);

                for (var section in this.sections) {
                    if ((this.sections[section] - windowHeight) < windowPos) {
                        returnValue = section;
                    }
                }

                return returnValue;
            },

            handleClick: function (e) {
                var self = this;
                var $link = $(e.currentTarget);
                var $parent = $link.parent();
                var newLoc = '#' + self.getHash($link);

                if (!$parent.hasClass(self.config.currentClass)) {
                    //Start callback
                    if (self.config.begin) {
                        self.config.begin();
                    }

                    //Change the highlighted nav item
                    self.adjustNav(self, $parent);

                    //Removing the auto-adjust on scroll
                    self.unbindInterval();

                    //Scroll to the correct position
                    self.scrollTo(newLoc, function () {
                        //Do we need to change the hash?
                        if (self.config.changeHash) {
                            window.location.hash = newLoc;
                        }

                        //Add the auto-adjust on scroll back in
                        self.bindInterval();

                        //End callback
                        if (self.config.end) {
                            self.config.end();
                        }
                    });
                }

                e.preventDefault();
            },

            scrollChange: function () {
                var windowTop = this.$win.scrollTop();
                var position = this.getSection(windowTop);
                var $parent;

                //If the position is set
                if (position !== null) {
                    $parent = this.$elem.find('a[href$="#' + position + '"]').parent();

                    //If it's not already the current section
                    if (!$parent.hasClass(this.config.currentClass)) {
                        //Change the highlighted nav item
                        this.adjustNav(this, $parent);

                        //If there is a scrollChange callback
                        if (this.config.scrollChange) {
                            this.config.scrollChange($parent);
                        }
                    }
                }
            },

            scrollTo: function (target, callback) {
                //var offset = $(target).offset().top;
                if ($(window).scrollTop() > 0) {
                    var offset = $(target).offset().top - 70;
                } else {
                    var offset = $(target).offset().top - 170;
                }

                $('html, body').animate({
                    scrollTop: offset
                    //scrollTop: (offset - 80)
                }, this.config.scrollSpeed, this.config.easing, callback);
            },

            unbindInterval: function () {
                clearInterval(this.t);
                this.$win.unbind('scroll.onePageNav');
            }
        };

        OnePageNav.defaults = OnePageNav.prototype.defaults;

        $.fn.onePageNav = function (options) {
            return this.each(function () {
                new OnePageNav(this, options).init();
            });
        };

    })(jQuery, window, document);
    //]]></script>
<script>//<![CDATA[
    /*! Magnific Popup - v0.9.9 - 2013-11-15
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2013 Dmitry Semenov; */
    (function (e) {
        var t, n, i, o, r, a, s, l = "Close", c = "BeforeClose", d = "AfterClose", u = "BeforeAppend",
            p = "MarkupParse", f = "Open", m = "Change", g = "mfp", v = "." + g, h = "mfp-ready", C = "mfp-removing",
            y = "mfp-prevent-close", w = function () {
            }, b = !!window.jQuery, I = e(window), x = function (e, n) {
                t.ev.on(g + e + v, n)
            }, k = function (t, n, i, o) {
                var r = document.createElement("div");
                return r.className = "mfp-" + t, i && (r.innerHTML = i), o ? n && n.appendChild(r) : (r = e(r), n && r.appendTo(n)), r
            }, T = function (n, i) {
                t.ev.triggerHandler(g + n, i), t.st.callbacks && (n = n.charAt(0).toLowerCase() + n.slice(1), t.st.callbacks[n] && t.st.callbacks[n].apply(t, e.isArray(i) ? i : [i]))
            }, E = function (n) {
                return n === s && t.currTemplate.closeBtn || (t.currTemplate.closeBtn = e(t.st.closeMarkup.replace("%title%", t.st.tClose)), s = n), t.currTemplate.closeBtn
            }, _ = function () {
                e.magnificPopup.instance || (t = new w, t.init(), e.magnificPopup.instance = t)
            }, S = function () {
                var e = document.createElement("p").style, t = ["ms", "O", "Moz", "Webkit"];
                if (void 0 !== e.transition) return !0;
                for (; t.length;) if (t.pop() + "Transition" in e) return !0;
                return !1
            };
        w.prototype = {
            constructor: w, init: function () {
                var n = navigator.appVersion;
                t.isIE7 = -1 !== n.indexOf("MSIE 7."), t.isIE8 = -1 !== n.indexOf("MSIE 8."), t.isLowIE = t.isIE7 || t.isIE8, t.isAndroid = /android/gi.test(n), t.isIOS = /iphone|ipad|ipod/gi.test(n), t.supportsTransition = S(), t.probablyMobile = t.isAndroid || t.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent), i = e(document.body), o = e(document), t.popupsCache = {}
            }, open: function (n) {
                var i;
                if (n.isObj === !1) {
                    t.items = n.items.toArray(), t.index = 0;
                    var r, s = n.items;
                    for (i = 0; s.length > i; i++) if (r = s[i], r.parsed && (r = r.el[0]), r === n.el[0]) {
                        t.index = i;
                        break
                    }
                } else t.items = e.isArray(n.items) ? n.items : [n.items], t.index = n.index || 0;
                if (t.isOpen) return t.updateItemHTML(), void 0;
                t.types = [], a = "", t.ev = n.mainEl && n.mainEl.length ? n.mainEl.eq(0) : o, n.key ? (t.popupsCache[n.key] || (t.popupsCache[n.key] = {}), t.currTemplate = t.popupsCache[n.key]) : t.currTemplate = {}, t.st = e.extend(!0, {}, e.magnificPopup.defaults, n), t.fixedContentPos = "auto" === t.st.fixedContentPos ? !t.probablyMobile : t.st.fixedContentPos, t.st.modal && (t.st.closeOnContentClick = !1, t.st.closeOnBgClick = !1, t.st.showCloseBtn = !1, t.st.enableEscapeKey = !1), t.bgOverlay || (t.bgOverlay = k("bg").on("click" + v, function () {
                    t.close()
                }), t.wrap = k("wrap").attr("tabindex", -1).on("click" + v, function (e) {
                    t._checkIfClose(e.target) && t.close()
                }), t.container = k("container", t.wrap)), t.contentContainer = k("content"), t.st.preloader && (t.preloader = k("preloader", t.container, t.st.tLoading));
                var l = e.magnificPopup.modules;
                for (i = 0; l.length > i; i++) {
                    var c = l[i];
                    c = c.charAt(0).toUpperCase() + c.slice(1), t["init" + c].call(t)
                }
                T("BeforeOpen"), t.st.showCloseBtn && (t.st.closeBtnInside ? (x(p, function (e, t, n, i) {
                    n.close_replaceWith = E(i.type)
                }), a += " mfp-close-btn-in") : t.wrap.append(E())), t.st.alignTop && (a += " mfp-align-top"), t.fixedContentPos ? t.wrap.css({
                    overflow: t.st.overflowY,
                    overflowX: "hidden",
                    overflowY: t.st.overflowY
                }) : t.wrap.css({
                    top: I.scrollTop(),
                    position: "absolute"
                }), (t.st.fixedBgPos === !1 || "auto" === t.st.fixedBgPos && !t.fixedContentPos) && t.bgOverlay.css({
                    height: o.height(),
                    position: "absolute"
                }), t.st.enableEscapeKey && o.on("keyup" + v, function (e) {
                    27 === e.keyCode && t.close()
                }), I.on("resize" + v, function () {
                    t.updateSize()
                }), t.st.closeOnContentClick || (a += " mfp-auto-cursor"), a && t.wrap.addClass(a);
                var d = t.wH = I.height(), u = {};
                if (t.fixedContentPos && t._hasScrollBar(d)) {
                    var m = t._getScrollbarSize();
                    m && (u.marginRight = m)
                }
                t.fixedContentPos && (t.isIE7 ? e("body, html").css("overflow", "hidden") : u.overflow = "hidden");
                var g = t.st.mainClass;
                return t.isIE7 && (g += " mfp-ie7"), g && t._addClassToMFP(g), t.updateItemHTML(), T("BuildControls"), e("html").css(u), t.bgOverlay.add(t.wrap).prependTo(document.body), t._lastFocusedEl = document.activeElement, setTimeout(function () {
                    t.content ? (t._addClassToMFP(h), t._setFocus()) : t.bgOverlay.addClass(h), o.on("focusin" + v, t._onFocusIn)
                }, 16), t.isOpen = !0, t.updateSize(d), T(f), n
            }, close: function () {
                t.isOpen && (T(c), t.isOpen = !1, t.st.removalDelay && !t.isLowIE && t.supportsTransition ? (t._addClassToMFP(C), setTimeout(function () {
                    t._close()
                }, t.st.removalDelay)) : t._close())
            }, _close: function () {
                T(l);
                var n = C + " " + h + " ";
                if (t.bgOverlay.detach(), t.wrap.detach(), t.container.empty(), t.st.mainClass && (n += t.st.mainClass + " "), t._removeClassFromMFP(n), t.fixedContentPos) {
                    var i = {marginRight: ""};
                    t.isIE7 ? e("body, html").css("overflow", "") : i.overflow = "", e("html").css(i)
                }
                o.off("keyup" + v + " focusin" + v), t.ev.off(v), t.wrap.attr("class", "mfp-wrap").removeAttr("style"), t.bgOverlay.attr("class", "mfp-bg"), t.container.attr("class", "mfp-container"), !t.st.showCloseBtn || t.st.closeBtnInside && t.currTemplate[t.currItem.type] !== !0 || t.currTemplate.closeBtn && t.currTemplate.closeBtn.detach(), t._lastFocusedEl && e(t._lastFocusedEl).focus(), t.currItem = null, t.content = null, t.currTemplate = null, t.prevHeight = 0, T(d)
            }, updateSize: function (e) {
                if (t.isIOS) {
                    var n = document.documentElement.clientWidth / window.innerWidth, i = window.innerHeight * n;
                    t.wrap.css("height", i), t.wH = i
                } else t.wH = e || I.height();
                t.fixedContentPos || t.wrap.css("height", t.wH), T("Resize")
            }, updateItemHTML: function () {
                var n = t.items[t.index];
                t.contentContainer.detach(), t.content && t.content.detach(), n.parsed || (n = t.parseEl(t.index));
                var i = n.type;
                if (T("BeforeChange", [t.currItem ? t.currItem.type : "", i]), t.currItem = n, !t.currTemplate[i]) {
                    var o = t.st[i] ? t.st[i].markup : !1;
                    T("FirstMarkupParse", o), t.currTemplate[i] = o ? e(o) : !0
                }
                r && r !== n.type && t.container.removeClass("mfp-" + r + "-holder");
                var a = t["get" + i.charAt(0).toUpperCase() + i.slice(1)](n, t.currTemplate[i]);
                t.appendContent(a, i), n.preloaded = !0, T(m, n), r = n.type, t.container.prepend(t.contentContainer), T("AfterChange")
            }, appendContent: function (e, n) {
                t.content = e, e ? t.st.showCloseBtn && t.st.closeBtnInside && t.currTemplate[n] === !0 ? t.content.find(".mfp-close").length || t.content.append(E()) : t.content = e : t.content = "", T(u), t.container.addClass("mfp-" + n + "-holder"), t.contentContainer.append(t.content)
            }, parseEl: function (n) {
                var i = t.items[n], o = i.type;
                if (i = i.tagName ? {el: e(i)} : {data: i, src: i.src}, i.el) {
                    for (var r = t.types, a = 0; r.length > a; a++) if (i.el.hasClass("mfp-" + r[a])) {
                        o = r[a];
                        break
                    }
                    i.src = i.el.attr("data-mfp-src"), i.src || (i.src = i.el.attr("href"))
                }
                return i.type = o || t.st.type || "inline", i.index = n, i.parsed = !0, t.items[n] = i, T("ElementParse", i), t.items[n]
            }, addGroup: function (e, n) {
                var i = function (i) {
                    i.mfpEl = this, t._openClick(i, e, n)
                };
                n || (n = {});
                var o = "click.magnificPopup";
                n.mainEl = e, n.items ? (n.isObj = !0, e.off(o).on(o, i)) : (n.isObj = !1, n.delegate ? e.off(o).on(o, n.delegate, i) : (n.items = e, e.off(o).on(o, i)))
            }, _openClick: function (n, i, o) {
                var r = void 0 !== o.midClick ? o.midClick : e.magnificPopup.defaults.midClick;
                if (r || 2 !== n.which && !n.ctrlKey && !n.metaKey) {
                    var a = void 0 !== o.disableOn ? o.disableOn : e.magnificPopup.defaults.disableOn;
                    if (a) if (e.isFunction(a)) {
                        if (!a.call(t)) return !0
                    } else if (a > I.width()) return !0;
                    n.type && (n.preventDefault(), t.isOpen && n.stopPropagation()), o.el = e(n.mfpEl), o.delegate && (o.items = i.find(o.delegate)), t.open(o)
                }
            }, updateStatus: function (e, i) {
                if (t.preloader) {
                    n !== e && t.container.removeClass("mfp-s-" + n), i || "loading" !== e || (i = t.st.tLoading);
                    var o = {status: e, text: i};
                    T("UpdateStatus", o), e = o.status, i = o.text, t.preloader.html(i), t.preloader.find("a").on("click", function (e) {
                        e.stopImmediatePropagation()
                    }), t.container.addClass("mfp-s-" + e), n = e
                }
            }, _checkIfClose: function (n) {
                if (!e(n).hasClass(y)) {
                    var i = t.st.closeOnContentClick, o = t.st.closeOnBgClick;
                    if (i && o) return !0;
                    if (!t.content || e(n).hasClass("mfp-close") || t.preloader && n === t.preloader[0]) return !0;
                    if (n === t.content[0] || e.contains(t.content[0], n)) {
                        if (i) return !0
                    } else if (o && e.contains(document, n)) return !0;
                    return !1
                }
            }, _addClassToMFP: function (e) {
                t.bgOverlay.addClass(e), t.wrap.addClass(e)
            }, _removeClassFromMFP: function (e) {
                this.bgOverlay.removeClass(e), t.wrap.removeClass(e)
            }, _hasScrollBar: function (e) {
                return (t.isIE7 ? o.height() : document.body.scrollHeight) > (e || I.height())
            }, _setFocus: function () {
                (t.st.focus ? t.content.find(t.st.focus).eq(0) : t.wrap).focus()
            }, _onFocusIn: function (n) {
                return n.target === t.wrap[0] || e.contains(t.wrap[0], n.target) ? void 0 : (t._setFocus(), !1)
            }, _parseMarkup: function (t, n, i) {
                var o;
                i.data && (n = e.extend(i.data, n)), T(p, [t, n, i]), e.each(n, function (e, n) {
                    if (void 0 === n || n === !1) return !0;
                    if (o = e.split("_"), o.length > 1) {
                        var i = t.find(v + "-" + o[0]);
                        if (i.length > 0) {
                            var r = o[1];
                            "replaceWith" === r ? i[0] !== n[0] && i.replaceWith(n) : "img" === r ? i.is("img") ? i.attr("src", n) : i.replaceWith('<img src="' + n + '" class="' + i.attr("class") + '" />') : i.attr(o[1], n)
                        }
                    } else t.find(v + "-" + e).html(n)
                })
            }, _getScrollbarSize: function () {
                if (void 0 === t.scrollbarSize) {
                    var e = document.createElement("div");
                    e.id = "mfp-sbm", e.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;", document.body.appendChild(e), t.scrollbarSize = e.offsetWidth - e.clientWidth, document.body.removeChild(e)
                }
                return t.scrollbarSize
            }
        }, e.magnificPopup = {
            instance: null,
            proto: w.prototype,
            modules: [],
            open: function (t, n) {
                return _(), t = t ? e.extend(!0, {}, t) : {}, t.isObj = !0, t.index = n || 0, this.instance.open(t)
            },
            close: function () {
                return e.magnificPopup.instance && e.magnificPopup.instance.close()
            },
            registerModule: function (t, n) {
                n.options && (e.magnificPopup.defaults[t] = n.options), e.extend(this.proto, n.proto), this.modules.push(t)
            },
            defaults: {
                disableOn: 0,
                key: null,
                midClick: !1,
                mainClass: "",
                preloader: !0,
                focus: "",
                closeOnContentClick: !1,
                closeOnBgClick: !0,
                closeBtnInside: !0,
                showCloseBtn: !0,
                enableEscapeKey: !0,
                modal: !1,
                alignTop: !1,
                removalDelay: 0,
                fixedContentPos: "auto",
                fixedBgPos: "auto",
                overflowY: "auto",
                closeMarkup: '<button title="%title%" type="button" class="mfp-close">&times;</button>',
                tClose: "Close (Esc)",
                tLoading: "Loading..."
            }
        }, e.fn.magnificPopup = function (n) {
            _();
            var i = e(this);
            if ("string" == typeof n) if ("open" === n) {
                var o, r = b ? i.data("magnificPopup") : i[0].magnificPopup, a = parseInt(arguments[1], 10) || 0;
                r.items ? o = r.items[a] : (o = i, r.delegate && (o = o.find(r.delegate)), o = o.eq(a)), t._openClick({mfpEl: o}, i, r)
            } else t.isOpen && t[n].apply(t, Array.prototype.slice.call(arguments, 1)); else n = e.extend(!0, {}, n), b ? i.data("magnificPopup", n) : i[0].magnificPopup = n, t.addGroup(i, n);
            return i
        };
        var P, O, z, M = "inline", B = function () {
            z && (O.after(z.addClass(P)).detach(), z = null)
        };
        e.magnificPopup.registerModule(M, {
            options: {hiddenClass: "hide", markup: "", tNotFound: "Content not found"},
            proto: {
                initInline: function () {
                    t.types.push(M), x(l + "." + M, function () {
                        B()
                    })
                }, getInline: function (n, i) {
                    if (B(), n.src) {
                        var o = t.st.inline, r = e(n.src);
                        if (r.length) {
                            var a = r[0].parentNode;
                            a && a.tagName && (O || (P = o.hiddenClass, O = k(P), P = "mfp-" + P), z = r.after(O).detach().removeClass(P)), t.updateStatus("ready")
                        } else t.updateStatus("error", o.tNotFound), r = e("<div>");
                        return n.inlineElement = r, r
                    }
                    return t.updateStatus("ready"), t._parseMarkup(i, {}, n), i
                }
            }
        });
        var F, H = "ajax", L = function () {
            F && i.removeClass(F)
        }, A = function () {
            L(), t.req && t.req.abort()
        };
        e.magnificPopup.registerModule(H, {
            options: {
                settings: null,
                cursor: "mfp-ajax-cur",
                tError: '<a href="%url%">The content</a> could not be loaded.'
            }, proto: {
                initAjax: function () {
                    t.types.push(H), F = t.st.ajax.cursor, x(l + "." + H, A), x("BeforeChange." + H, A)
                }, getAjax: function (n) {
                    F && i.addClass(F), t.updateStatus("loading");
                    var o = e.extend({
                        url: n.src, success: function (i, o, r) {
                            var a = {data: i, xhr: r};
                            T("ParseAjax", a), t.appendContent(e(a.data), H), n.finished = !0, L(), t._setFocus(), setTimeout(function () {
                                t.wrap.addClass(h)
                            }, 16), t.updateStatus("ready"), T("AjaxContentAdded")
                        }, error: function () {
                            L(), n.finished = n.loadError = !0, t.updateStatus("error", t.st.ajax.tError.replace("%url%", n.src))
                        }
                    }, t.st.ajax.settings);
                    return t.req = e.ajax(o), ""
                }
            }
        });
        var j, N = function (n) {
            if (n.data && void 0 !== n.data.title) return n.data.title;
            var i = t.st.image.titleSrc;
            if (i) {
                if (e.isFunction(i)) return i.call(t, n);
                if (n.el) return n.el.attr(i) || ""
            }
            return ""
        };
        e.magnificPopup.registerModule("image", {
            options: {
                markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
                cursor: "mfp-zoom-out-cur",
                titleSrc: "title",
                verticalFit: !0,
                tError: '<a href="%url%">The image</a> could not be loaded.'
            }, proto: {
                initImage: function () {
                    var e = t.st.image, n = ".image";
                    t.types.push("image"), x(f + n, function () {
                        "image" === t.currItem.type && e.cursor && i.addClass(e.cursor)
                    }), x(l + n, function () {
                        e.cursor && i.removeClass(e.cursor), I.off("resize" + v)
                    }), x("Resize" + n, t.resizeImage), t.isLowIE && x("AfterChange", t.resizeImage)
                }, resizeImage: function () {
                    var e = t.currItem;
                    if (e && e.img && t.st.image.verticalFit) {
                        var n = 0;
                        t.isLowIE && (n = parseInt(e.img.css("padding-top"), 10) + parseInt(e.img.css("padding-bottom"), 10)), e.img.css("max-height", t.wH - n)
                    }
                }, _onImageHasSize: function (e) {
                    e.img && (e.hasSize = !0, j && clearInterval(j), e.isCheckingImgSize = !1, T("ImageHasSize", e), e.imgHidden && (t.content && t.content.removeClass("mfp-loading"), e.imgHidden = !1))
                }, findImageSize: function (e) {
                    var n = 0, i = e.img[0], o = function (r) {
                        j && clearInterval(j), j = setInterval(function () {
                            return i.naturalWidth > 0 ? (t._onImageHasSize(e), void 0) : (n > 200 && clearInterval(j), n++, 3 === n ? o(10) : 40 === n ? o(50) : 100 === n && o(500), void 0)
                        }, r)
                    };
                    o(1)
                }, getImage: function (n, i) {
                    var o = 0, r = function () {
                        n && (n.img[0].complete ? (n.img.off(".mfploader"), n === t.currItem && (t._onImageHasSize(n), t.updateStatus("ready")), n.hasSize = !0, n.loaded = !0, T("ImageLoadComplete")) : (o++, 200 > o ? setTimeout(r, 100) : a()))
                    }, a = function () {
                        n && (n.img.off(".mfploader"), n === t.currItem && (t._onImageHasSize(n), t.updateStatus("error", s.tError.replace("%url%", n.src))), n.hasSize = !0, n.loaded = !0, n.loadError = !0)
                    }, s = t.st.image, l = i.find(".mfp-img");
                    if (l.length) {
                        var c = document.createElement("img");
                        c.className = "mfp-img", n.img = e(c).on("load.mfploader", r).on("error.mfploader", a), c.src = n.src, l.is("img") && (n.img = n.img.clone()), n.img[0].naturalWidth > 0 && (n.hasSize = !0)
                    }
                    return t._parseMarkup(i, {
                        title: N(n),
                        img_replaceWith: n.img
                    }, n), t.resizeImage(), n.hasSize ? (j && clearInterval(j), n.loadError ? (i.addClass("mfp-loading"), t.updateStatus("error", s.tError.replace("%url%", n.src))) : (i.removeClass("mfp-loading"), t.updateStatus("ready")), i) : (t.updateStatus("loading"), n.loading = !0, n.hasSize || (n.imgHidden = !0, i.addClass("mfp-loading"), t.findImageSize(n)), i)
                }
            }
        });
        var W, R = function () {
            return void 0 === W && (W = void 0 !== document.createElement("p").style.MozTransform), W
        };
        e.magnificPopup.registerModule("zoom", {
            options: {
                enabled: !1,
                easing: "ease-in-out",
                duration: 300,
                opener: function (e) {
                    return e.is("img") ? e : e.find("img")
                }
            }, proto: {
                initZoom: function () {
                    var e, n = t.st.zoom, i = ".zoom";
                    if (n.enabled && t.supportsTransition) {
                        var o, r, a = n.duration, s = function (e) {
                            var t = e.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),
                                i = "all " + n.duration / 1e3 + "s " + n.easing, o = {
                                    position: "fixed",
                                    zIndex: 9999,
                                    left: 0,
                                    top: 0,
                                    "-webkit-backface-visibility": "hidden"
                                }, r = "transition";
                            return o["-webkit-" + r] = o["-moz-" + r] = o["-o-" + r] = o[r] = i, t.css(o), t
                        }, d = function () {
                            t.content.css("visibility", "visible")
                        };
                        x("BuildControls" + i, function () {
                            if (t._allowZoom()) {
                                if (clearTimeout(o), t.content.css("visibility", "hidden"), e = t._getItemToZoom(), !e) return d(), void 0;
                                r = s(e), r.css(t._getOffset()), t.wrap.append(r), o = setTimeout(function () {
                                    r.css(t._getOffset(!0)), o = setTimeout(function () {
                                        d(), setTimeout(function () {
                                            r.remove(), e = r = null, T("ZoomAnimationEnded")
                                        }, 16)
                                    }, a)
                                }, 16)
                            }
                        }), x(c + i, function () {
                            if (t._allowZoom()) {
                                if (clearTimeout(o), t.st.removalDelay = a, !e) {
                                    if (e = t._getItemToZoom(), !e) return;
                                    r = s(e)
                                }
                                r.css(t._getOffset(!0)), t.wrap.append(r), t.content.css("visibility", "hidden"), setTimeout(function () {
                                    r.css(t._getOffset())
                                }, 16)
                            }
                        }), x(l + i, function () {
                            t._allowZoom() && (d(), r && r.remove(), e = null)
                        })
                    }
                }, _allowZoom: function () {
                    return "image" === t.currItem.type
                }, _getItemToZoom: function () {
                    return t.currItem.hasSize ? t.currItem.img : !1
                }, _getOffset: function (n) {
                    var i;
                    i = n ? t.currItem.img : t.st.zoom.opener(t.currItem.el || t.currItem);
                    var o = i.offset(), r = parseInt(i.css("padding-top"), 10),
                        a = parseInt(i.css("padding-bottom"), 10);
                    o.top -= e(window).scrollTop() - r;
                    var s = {width: i.width(), height: (b ? i.innerHeight() : i[0].offsetHeight) - a - r};
                    return R() ? s["-moz-transform"] = s.transform = "translate(" + o.left + "px," + o.top + "px)" : (s.left = o.left, s.top = o.top), s
                }
            }
        });
        var Z = "iframe", q = "//about:blank", D = function (e) {
            if (t.currTemplate[Z]) {
                var n = t.currTemplate[Z].find("iframe");
                n.length && (e || (n[0].src = q), t.isIE8 && n.css("display", e ? "block" : "none"))
            }
        };
        e.magnificPopup.registerModule(Z, {
            options: {
                markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
                srcAction: "iframe_src",
                patterns: {
                    youtube: {index: "youtube.com", id: "v=", src: "//www.youtube.com/embed/%id%?autoplay=1"},
                    vimeo: {index: "vimeo.com/", id: "/", src: "//player.vimeo.com/video/%id%?autoplay=1"},
                    gmaps: {index: "//maps.google.", src: "%id%&output=embed"}
                }
            }, proto: {
                initIframe: function () {
                    t.types.push(Z), x("BeforeChange", function (e, t, n) {
                        t !== n && (t === Z ? D() : n === Z && D(!0))
                    }), x(l + "." + Z, function () {
                        D()
                    })
                }, getIframe: function (n, i) {
                    var o = n.src, r = t.st.iframe;
                    e.each(r.patterns, function () {
                        return o.indexOf(this.index) > -1 ? (this.id && (o = "string" == typeof this.id ? o.substr(o.lastIndexOf(this.id) + this.id.length, o.length) : this.id.call(this, o)), o = this.src.replace("%id%", o), !1) : void 0
                    });
                    var a = {};
                    return r.srcAction && (a[r.srcAction] = o), t._parseMarkup(i, a, n), t.updateStatus("ready"), i
                }
            }
        });
        var K = function (e) {
            var n = t.items.length;
            return e > n - 1 ? e - n : 0 > e ? n + e : e
        }, Y = function (e, t, n) {
            return e.replace(/%curr%/gi, t + 1).replace(/%total%/gi, n)
        };
        e.magnificPopup.registerModule("gallery", {
            options: {
                enabled: !1,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                preload: [0, 2],
                navigateByImgClick: !0,
                arrows: !0,
                tPrev: "Previous (Left arrow key)",
                tNext: "Next (Right arrow key)",
                tCounter: "%curr% of %total%"
            }, proto: {
                initGallery: function () {
                    var n = t.st.gallery, i = ".mfp-gallery", r = Boolean(e.fn.mfpFastClick);
                    return t.direction = !0, n && n.enabled ? (a += " mfp-gallery", x(f + i, function () {
                        n.navigateByImgClick && t.wrap.on("click" + i, ".mfp-img", function () {
                            return t.items.length > 1 ? (t.next(), !1) : void 0
                        }), o.on("keydown" + i, function (e) {
                            37 === e.keyCode ? t.prev() : 39 === e.keyCode && t.next()
                        })
                    }), x("UpdateStatus" + i, function (e, n) {
                        n.text && (n.text = Y(n.text, t.currItem.index, t.items.length))
                    }), x(p + i, function (e, i, o, r) {
                        var a = t.items.length;
                        o.counter = a > 1 ? Y(n.tCounter, r.index, a) : ""
                    }), x("BuildControls" + i, function () {
                        if (t.items.length > 1 && n.arrows && !t.arrowLeft) {
                            var i = n.arrowMarkup,
                                o = t.arrowLeft = e(i.replace(/%title%/gi, n.tPrev).replace(/%dir%/gi, "left")).addClass(y),
                                a = t.arrowRight = e(i.replace(/%title%/gi, n.tNext).replace(/%dir%/gi, "right")).addClass(y),
                                s = r ? "mfpFastClick" : "click";
                            o[s](function () {
                                t.prev()
                            }), a[s](function () {
                                t.next()
                            }), t.isIE7 && (k("b", o[0], !1, !0), k("a", o[0], !1, !0), k("b", a[0], !1, !0), k("a", a[0], !1, !0)), t.container.append(o.add(a))
                        }
                    }), x(m + i, function () {
                        t._preloadTimeout && clearTimeout(t._preloadTimeout), t._preloadTimeout = setTimeout(function () {
                            t.preloadNearbyImages(), t._preloadTimeout = null
                        }, 16)
                    }), x(l + i, function () {
                        o.off(i), t.wrap.off("click" + i), t.arrowLeft && r && t.arrowLeft.add(t.arrowRight).destroyMfpFastClick(), t.arrowRight = t.arrowLeft = null
                    }), void 0) : !1
                }, next: function () {
                    t.direction = !0, t.index = K(t.index + 1), t.updateItemHTML()
                }, prev: function () {
                    t.direction = !1, t.index = K(t.index - 1), t.updateItemHTML()
                }, goTo: function (e) {
                    t.direction = e >= t.index, t.index = e, t.updateItemHTML()
                }, preloadNearbyImages: function () {
                    var e, n = t.st.gallery.preload, i = Math.min(n[0], t.items.length),
                        o = Math.min(n[1], t.items.length);
                    for (e = 1; (t.direction ? o : i) >= e; e++) t._preloadItem(t.index + e);
                    for (e = 1; (t.direction ? i : o) >= e; e++) t._preloadItem(t.index - e)
                }, _preloadItem: function (n) {
                    if (n = K(n), !t.items[n].preloaded) {
                        var i = t.items[n];
                        i.parsed || (i = t.parseEl(n)), T("LazyLoad", i), "image" === i.type && (i.img = e('<img class="mfp-img" />').on("load.mfploader", function () {
                            i.hasSize = !0
                        }).on("error.mfploader", function () {
                            i.hasSize = !0, i.loadError = !0, T("LazyLoadError", i)
                        }).attr("src", i.src)), i.preloaded = !0
                    }
                }
            }
        });
        var U = "retina";
        e.magnificPopup.registerModule(U, {
            options: {
                replaceSrc: function (e) {
                    return e.src.replace(/\.\w+$/, function (e) {
                        return "@2x" + e
                    })
                }, ratio: 1
            }, proto: {
                initRetina: function () {
                    if (window.devicePixelRatio > 1) {
                        var e = t.st.retina, n = e.ratio;
                        n = isNaN(n) ? n() : n, n > 1 && (x("ImageHasSize." + U, function (e, t) {
                            t.img.css({"max-width": t.img[0].naturalWidth / n, width: "100%"})
                        }), x("ElementParse." + U, function (t, i) {
                            i.src = e.replaceSrc(i, n)
                        }))
                    }
                }
            }
        }), function () {
            var t = 1e3, n = "ontouchstart" in window, i = function () {
                I.off("touchmove" + r + " touchend" + r)
            }, o = "mfpFastClick", r = "." + o;
            e.fn.mfpFastClick = function (o) {
                return e(this).each(function () {
                    var a, s = e(this);
                    if (n) {
                        var l, c, d, u, p, f;
                        s.on("touchstart" + r, function (e) {
                            u = !1, f = 1, p = e.originalEvent ? e.originalEvent.touches[0] : e.touches[0], c = p.clientX, d = p.clientY, I.on("touchmove" + r, function (e) {
                                p = e.originalEvent ? e.originalEvent.touches : e.touches, f = p.length, p = p[0], (Math.abs(p.clientX - c) > 10 || Math.abs(p.clientY - d) > 10) && (u = !0, i())
                            }).on("touchend" + r, function (e) {
                                i(), u || f > 1 || (a = !0, e.preventDefault(), clearTimeout(l), l = setTimeout(function () {
                                    a = !1
                                }, t), o())
                            })
                        })
                    }
                    s.on("click" + r, function () {
                        a || o()
                    })
                })
            }, e.fn.destroyMfpFastClick = function () {
                e(this).off("touchstart" + r + " click" + r), n && I.off("touchmove" + r + " touchend" + r)
            }
        }(), _()
    })(window.jQuery || window.Zepto);
    //]]></script>
<script>//<![CDATA[
    $(function () {

        "use strict";

        // REMOVE # FROM URL
        $('a[href="#"]').click(function (e) {
            e.preventDefault();
        });

        // PreLoader
        $(window).load(function () {
            $(".loading-wrap").fadeOut(400);
        });

        // STICKY NAV
        //var stickyHeaderTop = $(window).height();
        var stickyHeaderTop = $("#main-header").height();
        $(window).scroll(function () {
            if ($(window).scrollTop() > stickyHeaderTop) {
                $(".sticky-nav").css({position: "fixed", top: "0px"});
                $(".sticky-nav").css("display", "block");
                $(".sticky-nav").addClass("fixednav");
            } else {
                $(".sticky-nav").css({position: "static", top: "0px"});
                $(".sticky-nav").removeClass("fixednav");
            }
        });

        // ONE PAGE NAV
        $("#nav").onePageNav({
            currentClass: 'current',
            changeHash: false,
            scrollSpeed: 750,
            scrollThreshold: 0.5,
            filter: '',
            easing: 'swing',
            begin: function () {
                //I get fired when the animation is starting
            },
            end: function () {
                //I get fired when the animation is ending
            },
            scrollChange: function ($currentListItem) {
                //I get fired when you enter a section and I pass the list item of the section
            }
        });

        // Scroll
        $("#scroll").click(function () {
            $('html, body').animate({
                scrollTop: $($.attr(this, 'href')).offset().top - 180
            }, 500);
            return false;
        });

        // About CAROUSEL
        $("#about-carousel").carousel({
            interval: false
        });

        // BADGES COUNTER
        function count($this) {
            var current = parseInt($this.html(), 10);
            $this.html(++current);
            if (current !== $this.data('count')) {
                setTimeout(function () {
                    count($this)
                }, 50);
            }
        }

        $(".badges-counter").each(function () {
            $(this).data('count', parseInt($(this).html(), 10));
            $(this).html('0');
            count($(this));
        });

        //MAGNIFIC POPUP
        $("#screenshot-carousel").magnificPopup({
            delegate: 'a.screenshot-zoom',
            type: 'image',
            gallery: {
                enabled: true
            }
        });

        //AJAX CONTACT FORM
        $(".contact-form").submit(function () {
            var rd = this;
            var url = "sendemail.php"; // the script where you handle the form input.
            $.ajax({
                type: "POST",
                url: url,
                data: $(".contact-form").serialize(), // serializes the form's elements.
                success: function (data) {
                    //alert("Mail sent!"); // show response from the php script.
                    $(rd).prev().text(data.message).fadeIn().delay(3000).fadeOut();
                }
            });
            return false; // avoid to execute the actual submit of the form.
        });

    });
    //]]></script>

</body>
</html>
