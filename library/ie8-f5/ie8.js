/**!
 * Module: rem - v1.3.2
 * Description: A polyfill to parse CSS links and rewrite pixel equivalents into head for non supporting browsers
 * Date Built: 2014-07-02
 * Copyright (c) 2014  | Chuck Carpenter <chuck.carpenter@me.com>,Lucas Serven <lserven@gmail.com>;
**/
!function(a){"use strict";var b=function(){var a=document.createElement("div");return a.style.cssText="font-size: 1rem;",/rem/.test(a.style.fontSize)},c=function(){for(var a=document.getElementsByTagName("link"),b=[],c=0;c<a.length;c++)"stylesheet"===a[c].rel.toLowerCase()&&null===a[c].getAttribute("data-norem")&&b.push(a[c].href);return b},d=function(){for(var a=0;a<m.length;a++)j(m[a],e)},e=function(a,b){if(q.push(a.responseText),r.push(b),r.length===m.length){for(var c=0;c<r.length;c++)f(q[c],r[c]);(m=n.slice(0)).length>0?(r=[],q=[],n=[],d()):g()}},f=function(a,b){for(var c,d=k(a).replace(/\/\*[\s\S]*?\*\//g,""),e=/[\w\d\s\-\/\\\[\]:,.'"*()<>+~%#^$_=|@]+\{[\w\d\s\-\/\\%#:!;,.'"*()]+\d*\.?\d+rem[\w\d\s\-\/\\%#:!;,.'"*()]*\}/g,f=d.match(e),g=/\d*\.?\d+rem/g,h=d.match(g),i=/(.*\/)/,j=i.exec(b)[0],l=/@import (?:url\()?['"]?([^'\)"]*)['"]?\)?[^;]*/gm;null!==(c=l.exec(a));)n.push(j+c[1]);null!==f&&0!==f.length&&(o=o.concat(f),p=p.concat(h))},g=function(){for(var a=/[\w\d\s\-\/\\%#:,.'"*()]+\d*\.?\d+rem[\w\d\s\-\/\\%#:!,.'"*()]*[;}]/g,b=0;b<o.length;b++){l+=o[b].substr(0,o[b].indexOf("{")+1);for(var c=o[b].match(a),d=0;d<c.length;d++)l+=c[d],d===c.length-1&&"}"!==l[l.length-1]&&(l+="\n}")}h()},h=function(){for(var a=0;a<p.length;a++)s[a]=Math.round(parseFloat(p[a].substr(0,p[a].length-3)*t))+"px";i()},i=function(){for(var a=0;a<s.length;a++)s[a]&&(l=l.replace(p[a],s[a]));var b=document.createElement("style");b.setAttribute("type","text/css"),b.id="remReplace",document.getElementsByTagName("head")[0].appendChild(b),b.styleSheet?b.styleSheet.cssText=l:b.appendChild(document.createTextNode(l))},j=function(b,c){try{var d=a.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP")||new ActiveXObject("Msxml2.XMLHTTP"):new XMLHttpRequest;d.open("GET",b,!0),d.onreadystatechange=function(){4===d.readyState&&c(d,b)},d.send(null)}catch(e){if(a.XDomainRequest){var f=new XDomainRequest;f.open("get",b),f.onload=function(){c(f,b)},f.onerror=function(){return!1},f.send()}}},k=function(b){return a.matchMedia||a.msMatchMedia||(b=b.replace(/@media[\s\S]*?\}\s*\}/g,"")),b};if(!b()){var l="",m=c(),n=[],o=[],p=[],q=[],r=[],s=[],t="";t=function(){var a,b=document,c=b.documentElement,d=b.body||b.createElement("body"),e=!b.body,f=b.createElement("div"),g=d.style.fontSize;return e&&c.appendChild(d),f.style.cssText="width:1em; position:absolute; visibility:hidden; padding: 0;",d.style.fontSize="1em",d.appendChild(f),a=f.offsetWidth,e?c.removeChild(d):(d.removeChild(f),d.style.fontSize=g),a}(),d()}}(window);
