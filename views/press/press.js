
var jsParam = function() { 
    var scripts = document.getElementsByTagName('script'); 
    var script = scripts.item(scripts.length - 1); 
    var match = script.src.match(/\?(.+)$/); 
    var get = match[1]; 
    var params = get.split('&'); 

    var data = []; 
        for (var i = 0; i < params.length; i++) { 
            var param = params[i].split('='); 
            var name = param[0]; 
            var value = param[1]; 
            data[name] = value; 
        } 
    this.get = function(oName) { return data[oName] } ;
}; 

var post_md="";
var brd_key="";
var param = new jsParam(); 
var post_md=param.get('post_md'); ;
var brd_key=param.get('brd_key'); ;
//pressid= getQuerystring('pressid');

document.write("<div id=\"hiadone_pageid"+post_md+"\"></div><scr"+"ipt type=\"text/javascr"+"ipt\">$(document).ready(function() {$('#hiadone_pageid"+post_md+"').load('http://admin.newdealpopcon.com/press/"+post_md+"/"+brd_key+"');});</scr"+"ipt>");




