//API

//Methods
METHOD_POST = "POST";
METHOD_GET = "GET";


URL_WORKER ="http://localhost/atcqa/worker.php";

//Actions
ACTION_QUERY = "query";
ACTION_INSERT = "insert";
ACTION_UPDATE = "update";
ACTION_DELETE = "delete";


//Results
RESULT_SUCCESS = "success";
RESULT_ERROR = "error";


//Status Text
STATUS_TEXT_OK = "OK";


function $(id){
	return document.getElementById(id);
}
function setHtml(id,html){
	$(id).innerHTML = html;
}
function getHtml(id){
	return $(id).innerHTML;
}
function setValue(id,value){
	$(id).value=value;
}
function getValue(id){
	return $(id).value;
}
function setVisibility(id,visibility){
	$(id).style.display = "'"+visibility +"'";
}
function getVisibility(id){
	return $(id).style.display;
}

function atcSetCache(key,value){
	localStorage.setItem(key, value);
}

function atcGetCache(key) {
	return localStorage.getItem(key);
}

//Resusable
function ajaxCommit(action,method,url,params,intent) {

	if (window.XMLHttpRequest) {
		request = new XMLHttpRequest();
		
	}else{
		request = new ActieveXObject("Microsoft.XMLHTTP");
	}

	request.onreadystatechange = function(){
		onReadyStateChange(action,method,url,params,request,intent);
	}
	
	request.open(method,url);
	
	if(method.toUpperCase()=="POST"){
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		request.send(params);
	}
	if(method.toUpperCase()=="GET"){
		request.send(null);
	}
	
	
}


//Password Generator



function generateKeys() {
	var temp = '';
	var keylist = "?!@#$%^&*()abcdefghijklmnopqrstuvwxyz123456789";
	temp = '';

	for (i = 0; i < 7; i++) {
		temp += keylist.charAt(Math.floor(Math.random() * keylist.length));
	}

	return temp;
}

	
