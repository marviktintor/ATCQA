/**
 * 
 */

INTENT_SIGNUP = "signup";
INTENT_LOGIN = "login";
INTENT_POST_QUESTION = "post_question";
INTENT_QUERY_ALL_QUESTION = "query_all_questions";
INTENT_POST_QUESTION_ANSWER = "post_question_answer";
INTENT_GET_QUESTION_ANSWERS = "get_question_answers";


INTENT_LIKE_ANSWER = "like_answer";
INTENT_UNLIKE_ANSWER = "unlike_answer";
INTENT_FAVORITE_ANSWER = "favorite_answer";

INTENT_SEARCH = "search";
window.onload = function (){
	
	localStorage.setItem("question_id", null);
	
	getAuthDetails();
	loadQuestions();
	if(window.location == "http://localhost/atcqa/login.html"){
		
	}
	if(window.location == "http://localhost/atcqa/"){
		
	}
	setEventListeners();
}

function getAuthDetails(){
	var user_id = atcGetCache("atc_qa_user");
	if(user_id==null || user_id=="-1"){
		document.getElementById('div_refresh_view').style.display = 'block';
		document.getElementById('main_parent').style.display = 'none';
		
		if(window.location != "http://localhost/atcqa/login.html"){
			window.open("login.html","_blank","width:100px;height:100px;");
			
		}
			
	}
	
	return user_id;
}
function setEventListenersForLogin(){
	
	
}
function setEventListeners(){
	
	//$('id_refresh_view').addEventListener('click',refreshView,false);
	document.getElementById('id_button_post_question').addEventListener('click',postQuestion,false);
		
	/*if($('id_button_generate_password') != null){
		document.getElementById('id_button_generate_password').style.display='none';
		$('id_button_generate_password').addEventListener('click',setPassword,false);
		$('id_button_auth').addEventListener('click',authenticate,false);
		$('authenticate_action').addEventListener('change',toggeAuthenticateUI,false);
		
		setValue('id_input_email','vmwenda.vm@gmail.com');
		setValue('id_input_password','pass123');
	}*/
	
	
}

function search(){
	var searchKey = getValue('id_input_search');
	if(searchKey != ""){
		params = "intent="+INTENT_SEARCH+"&action=query&search_key="+searchKey;
		ajaxCommit(ACTION_QUERY,METHOD_POST , URL_WORKER, params, INTENT_SEARCH);
	}
	
	if(searchKey == ""){
		loadQuestions()
	}
}
function favorite(answer_id){
	var user = atcGetCache("atc_qa_user");
	params = "intent="+INTENT_FAVORITE_ANSWER+"&action=insert&answer_id="+answer_id+"&user="+user;
	ajaxCommit(ACTION_INSERT,METHOD_POST , URL_WORKER, params, INTENT_FAVORITE_ANSWER);
}
function unlike(answer_id){
	var user = atcGetCache("atc_qa_user");
	params = "intent="+INTENT_UNLIKE_ANSWER+"&action=insert&answer_id="+answer_id+"&user="+user;
	ajaxCommit(ACTION_INSERT,METHOD_POST , URL_WORKER, params, INTENT_UNLIKE_ANSWER);
}
function  like(answer_id){
	var user = atcGetCache("atc_qa_user");
	params = "intent="+INTENT_LIKE_ANSWER+"&action=insert&answer_id="+answer_id+"&user="+user;
	ajaxCommit(ACTION_INSERT,METHOD_POST , URL_WORKER, params, INTENT_LIKE_ANSWER);
	
}
function isValidQuestion(question,tags){
	return question != "" && tags != "";
}
function postQuestion(){
	var question = getValue('question');
	var tags = getValue('id_input_question_tags');
	
	var user = atcGetCache("atc_qa_user");
	setHtml("question_errors","");
	if(isValidQuestion(question,tags)){
		
		params = "intent="+INTENT_POST_QUESTION+"&action=insert&question="+question+"&tags="+tags+"&user="+user;
		ajaxCommit(ACTION_INSERT,METHOD_POST , URL_WORKER, params, INTENT_POST_QUESTION);
	}else{
		
		var errors = "";
		var count = 0;
		if(question == ""){
			count++;
			errors += count +". Missing Question<br />";
		}
		
		if(tags == ""){
			count++;
			errors += count +". Missing Tags<br />";
		}
		
		$('question_errors').style.color="#FF0000";
		setHtml("question_errors",errors);
	}
}
function postAnswer(){
	question_id = localStorage.getItem("question_id");
	if(question_id == 'null'){
		setHtml('answer_errors',"Please click on a question to provide its answer");
		$('answer_errors').style.color="#FF0000";
	}else{
		var answer = getValue('id_answer');
		if(answer== ""){
			setHtml('answer_errors',"Please enter your answer");
			$('answer_errors').style.color="#FF0000";
		}else{
			var user = atcGetCache("atc_qa_user");
			params = "intent="+INTENT_POST_QUESTION_ANSWER+"&action=insert&question_id="+question_id+"&answer="+answer+"&user="+user;
			ajaxCommit(ACTION_INSERT,METHOD_POST , URL_WORKER, params, INTENT_POST_QUESTION_ANSWER);
		}
		
	}
	
}
function loadAnswersFor(id_question){ 
	localStorage.setItem("question_id", id_question);
	var params = "action=query&intent="+INTENT_GET_QUESTION_ANSWERS+"&question_id="+id_question;
	ajaxCommit(ACTION_QUERY,METHOD_POST , URL_WORKER, params, INTENT_GET_QUESTION_ANSWERS);
}
function loadQuestions(){
	var params = "action=query&intent="+INTENT_QUERY_ALL_QUESTION;
	ajaxCommit(ACTION_QUERY,METHOD_POST , URL_WORKER, params, INTENT_QUERY_ALL_QUESTION);
}
function refreshView(){ 
	
	/*var user_id = atcGetCache("atc_qa_user");
	
	if(user_id==null || user_id=="-1"){
		getAuthDetails();
	}else{
		document.getElementById('div_refresh_view').style.display = 'none';
		document.getElementById('main_parent').style.display = 'block';
		
	}*/
	
	document.getElementById('div_refresh_view').style.display = 'none';
	document.getElementById('main_parent').style.display = 'block';
	
	loadQuestions();
}
function getAuthenticateChoice(){
	return getValue('authenticate_action');
}
function toggeAuthenticateUI(){
	if(getAuthenticateChoice() == '1'){
		document.getElementById('id_button_generate_password').style.display='block';
		setHtml("id_button_auth", "Sign up");
	}
	if(getAuthenticateChoice() == '0'){ 
		setHtml("id_button_auth", "Log in");
		document.getElementById('id_button_generate_password').style.display='none';
	}
	
	
}

function setPassword(){
	var password = generateKeys();
	prompt("Copy Your Password", password);
	setValue('id_input_password',password);
}

function not_null(email,password){
	return email != "" && password != "";
}
function authenticate(){
	
	var email = getValue('id_input_email');
	var password = getValue('id_input_password');
	var authType = getAuthenticateChoice();
	
	if(not_null(email,password)){ 
		if(authType == '1'){ signup(email,password); }
		if(authType == '0'){ login(email,password);}
		
		
		if(document.getElementById('keep_session').checked){
			atcSetCache("email", email);
			atcSetCache("password", password);
		}
	}else{ 
		var errors = "";
		var count = 0;
		if(email == ""){
			count++;
			errors += count +". Missing Email<br />";
		}
		if(password == ""){
			count++;
			errors += count +". Missing Password<br />";
		}
		
		document.getElementById('auth_errors').style.color="#FF0000";
		setHtml('auth_errors', errors);
	}
	
}

function signup(email,password){
	var params = "action="+ACTION_INSERT+"&intent="+INTENT_SIGNUP+"&email="+email+"&password="+password;
	ajaxCommit(ACTION_INSERT,METHOD_POST , URL_WORKER, params, INTENT_SIGNUP);
}
function login(email,password){
	var params = "action="+ACTION_INSERT+"&intent="+INTENT_LOGIN+"&email="+email+"&password="+password;
	ajaxCommit(ACTION_INSERT,METHOD_POST , URL_WORKER, params, INTENT_LOGIN);
}


function onReadyStateChange(action,method,url,params,request,intent){
	
	if(request.readyState==4 && request.status==200){
		if(action == ACTION_INSERT){
			if(intent == INTENT_SIGNUP){
				if(request.responseText != "-1"){
					localStorage.setItem("atc_qa_user", request.responseText);
					window.close();
				}else{setHtml('auth_errors', request.responseText);}
			}
			
			if(intent == INTENT_LOGIN){
				if(request.responseText == "-1"){
					localStorage.setItem("atc_qa_user", request.responseText);
					window.close();
				}else{
					if(request.responseText > 0){ localStorage.setItem("atc_qa_user", request.responseText); window.close(); }
					if(request.responseText == "0"){
						setHtml('auth_errors', 'Inavlid Username or Password');
					}else{setHtml('auth_errors', request.responseText);}
				}
			}
			
			if(intent == INTENT_POST_QUESTION){
				loadQuestions();
			}
		}
		
		 
		if(intent == INTENT_LIKE_ANSWER||intent == INTENT_UNLIKE_ANSWER||intent == INTENT_FAVORITE_ANSWER){
			question_id = localStorage.getItem("question_id");
			loadAnswersFor(question_id);
		}
		if(intent == INTENT_QUERY_ALL_QUESTION){
			setHtml('posted_questions', request.responseText);
		}
		if(intent == INTENT_SEARCH){
			setHtml('posted_questions', request.responseText);
		}
		if(intent == INTENT_GET_QUESTION_ANSWERS){
			setHtml('question_answers', request.responseText);
		}
		if(intent == INTENT_POST_QUESTION_ANSWER){
			setHtml('question_answers', request.responseText);
			loadQuestions();
		}
	}
}