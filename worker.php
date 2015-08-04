<?php

/* $_POST['action']="query";
$_POST['intent'] = "query_all_questions"; */

	
	include 'dbconfig/db_utils.php';
	
	if(isset($_POST['action']) && isset($_POST['intent'])){
		
		if($_POST['action'] == "insert"){
			
			
			if($_POST['intent'] == "login"){
				login();
			}
			if($_POST['intent'] == "signup"){
				signup();
			}
			if($_POST['intent'] == "post_question"){
				postquestion();
			}
			if($_POST['intent'] == "post_question_answer"){
				postquestionAnswer();
			}
			
			if($_POST['intent'] == "like_answer"){
				likeAnswer();
			}
			if($_POST['intent'] == "unlike_answer"){
				unlikeAnswer();
			}
			if($_POST['intent'] == "favorite_answer"){
				favorateAnswer();
			}
		}
		
		if($_POST['action']=="query"){
			if($_POST['intent'] == "query_all_questions"){
				queryAllQuestion();
			}
			if($_POST['intent'] == "get_question_answers"){
				queryQuestionAnswers();
			}
			
			if($_POST['intent'] == "search"){
				search();
			}
			
		}
	}else{
		
		if(!isset($_POST['action']) ){
			echo "UNKNOWN ACTION";
		}
		if(!isset($_POST['intent'])){
			echo "UNKNOWN INTENT";
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function signup(){
		$email= $_POST['email'];
		$password = $_POST['password'];
		$dbutils = new db_utils();
		
		$table = "users";
		$columns = array("email", "password");
		$records= array($email,$password);
		if($dbutils->is_exists($table, $columns, $records) == 0){
			$dbutils->insert_records($table, $columns, $records);
		}
	
		
		$users = $dbutils->query($table, $columns, $records);
		echo $userid = $users[0]['id_user'];
	}
	function login(){
		$email= $_POST['email'];
		$password = $_POST['password'];
		$dbutils = new db_utils();
		
		$table = "users";
		$columns = array("email", "password");
		$records= array($email,$password);
		
		if($dbutils->is_exists($table, $columns, $records)!=0){
			$users = $dbutils->query($table, $columns, $records);
			echo $userid = $users[0]['id_user'];
		}else{ echo "-1";}
		
	}
	
	function postquestion(){
		
		$dbutils = new db_utils();
		
		$question= $_POST['question'];
		$tags=$_POST['tags'];
		$user = $_POST['user'];
		
		$table = "questions";
		$columns = array("id_user", "question", "tags");
		$records= array($user,$question,$tags);
		
		if($dbutils->is_exists($table, $columns, $records) ==0){
			$dbutils->insert_records($table, $columns, $records);
		}
		
		
	}
	
	function queryQuestionAnswers(){
		$dbutils = new db_utils();
		$question_id = $_POST['question_id'];
		
		$table = "answers";
		$columns = array("id_question");
		$records= array($question_id);
		$answers = $dbutils->query($table, $columns, $records);
		
		if(count($answers)>0){
			
			for($i = 0;$i <count($answers);$i++){
				$answer_id =  $answers[$i]['id_answer'];
				$commit_time = $answers[$i]['commit_time'];
				$answer = $answers[$i]['answer'];
				$poster = get_question_poster($answers[$i]['id_user']);
				
				echo '<section class="card"><p>'.$answer.'</p><label >('.get_answer_likes($answer_id).')</label><img onclick="like('.$answer_id.');" src="images/like.png" style="width:20px;height:20px;"/>
						<label onclick="unlike('.$answer_id.');">('.get_answer_unlikes($answer_id).')</label><img onclick="unlike('.$answer_id.');" src="images/unlike.png" style="width:20px;height:20px;"/>
								<label onclick="favorite('.$answer_id.');">('.get_answer_favorites($answer_id).')</label><img onclick="favorite('.$answer_id.');" src="images/favorite.png" style="width:20px;height:20px;"/>
										<label>Poster : '.$poster.'</label><label>Time : '.$commit_time.'</label><!-- <button>Markings</button> --></section>';
				
			}
		}else{
			echo '<section class="card" ><h1>There no Answers for this Question</h1></section>';
		}
		
	}
	function queryAllQuestion(){
		$dbutils = new db_utils();
		$table = "questions";
		$columns = array();
		$records= array();
		
		$questions = $dbutils->query($table, $columns, $records);
		if(count($questions) == 0){
			echo '<section class="card" ><h1>There no Answers for this Question</h1></section>';
		}else{
			
			for($i = 0; $i <count($questions); $i++){
				$id_question = $questions[$i]['id_question'];
				$id_user =  $questions[$i]['id_user'];
				$question =  $questions[$i]['question'];
				$tags =  $questions[$i]['tags'];
				$commit_time =  $questions[$i]['commit_time'];
				
				
				echo $quiz = '<section class="card" onclick="loadAnswersFor('.$id_question.')"><h1><label>'.$question.'</label></h1><h2><label> Answers('.get_question_answer_count($id_question).')</label><label> Tags : '.$tags.'</label><label> Poster : '.get_question_poster($id_user).'</label><label> Post Time : '.$commit_time.'</label></h2></section>';
				
				
				
			}
			
		}
	}
	
	function get_question_poster($id_user){
		$table = "users";
		$columns = array("id_user");
		$records= array($id_user);
		
		$dbutils = new db_utils();
		$users = $dbutils->query($table, $columns, $records);
		
		return $users[0]['email'];
	}
	function get_question_answer_count($id_question){
				
		$table = "answers";
		$columns = array("id_question");
		$records= array($id_question);
		
		$dbutils = new db_utils();
		
		return $dbutils->is_exists($table, $columns, $records);
	}
	
	function postquestionAnswer(){

		$dbutils = new db_utils();
		$question_id = $_POST['question_id'];
		$answer =  $_POST['answer'];
		$user  =  $_POST['user'];
		
		$table = "answers";
		$columns = array("id_question", "id_user", "answer");
		$records= array($question_id,$user,$answer);
		
		if($dbutils->is_exists($table, $columns, $records) == 0){
			$dbutils->insert_records($table, $columns, $records);
		}
		$table = "answers";
		$columns = array("id_question");
		$records= array($question_id);
		$answers = $dbutils->query($table, $columns, $records);
		
		if(count($answers)>0){
			
			for($i = 0;$i <count($answers);$i++){
				$answer_id =  $answers[$i]['id_answer'];
				$commit_time = $answers[$i]['commit_time'];
				$answer = $answers[$i]['answer'];
				$poster = get_question_poster($answers[$i]['id_user']);
				
				echo '<section class="card"><h1>'.$answer.'</h1><label>('.get_answer_likes($answer_id).')</label><img onclick="like('.$answer_id.');" src="images/like.png" style="width:20px;height:20px;"/>
						<label onclick="unlike('.$answer_id.');">('.get_answer_unlikes($answer_id).')</label><img onclick="unlike('.$answer_id.');" src="images/unlike.png" style="width:20px;height:20px;"/>
								<label onclick="favorite('.$answer_id.');">('.get_answer_favorites($answer_id).')</label><img onclick="favorite('.$answer_id.');" src="images/favorite.png" style="width:20px;height:20px;"/>
										<label>Poster : '.$poster.'</label><label>Time : '.$commit_time.'</label><!-- <button>Markings</button> --></section>';
				
			}
		}else{
			echo '<section class="card" ><h1>There no Answers for this Question</h1></section>';
		}
		
		
		
		
		
	}
	
	function get_answer_unlikes($answer_id){
				
		$dbutils = new db_utils();
		
		$table = "impressions";
		$columns = array("id_answer");
		$records= array($answer_id);
		
		$unlikes = $dbutils->query($table, $columns, $records);
		$count = 0;
		for($i = 0;$i<count($unlikes);$i++){
			$unlike = $unlikes[$i]['unlikes'];
			$count += $unlike;
		}
		return $count;
	}
	function get_answer_favorites($answer_id){
		$dbutils = new db_utils();
		
		$table = "impressions";
		$columns = array("id_answer");
		$records= array($answer_id);
		
		$favorites = $dbutils->query($table, $columns, $records);
		$count = 0;
		for($i = 0;$i<count($favorites);$i++){
			$favorite = $favorites[$i]['favorite'];
			$count += $favorite;
		}
		return $count;
	}
	function get_answer_likes($answer_id){
		$dbutils = new db_utils();
		
		$table = "impressions";
		$columns = array("id_answer");
		$records= array($answer_id);
		
		$likes = $dbutils->query($table, $columns, $records);
		$count = 0;
		for($i = 0;$i<count($likes);$i++){
			$like = $likes[$i]['likes'];
			$count += $like;
		}
		return $count;
	}
	
	function likeAnswer() {
		
		$answer_id = $_POST['answer_id'];
		$user = $_POST['user'];
		
		$dbutils = new db_utils();
		
		$table = "impressions";
		$columns = array("id_answer","id_user","likes");
		$records= array($answer_id,$user,"1");
		
		if($dbutils->is_exists($table, $columns, $records) == 0){
			$table = "impressions";
			$columns = array("id_answer","id_user","likes","unlikes","favorite");
			$records= array($answer_id,$user,"1","0","0");
			
			$dbutils->insert_records($table, $columns, $records);
		}
		
		$table = "impressions";
		$columns = array("id_answer","id_user","unlikes");
		$records= array($answer_id,$user,"1");
		
		if($dbutils->is_exists($table, $columns, $records) == 1){
			$table = "impressions";
			$columns = array("id_answer","id_user","unlikes");
			$records= array($answer_id,$user,"1");
				
			$dbutils->delete_record($table, $columns, $records);
		}
		
	}
	function unlikeAnswer() {
		$answer_id = $_POST['answer_id'];
		$user = $_POST['user'];
		
		$dbutils = new db_utils();
		
		$table = "impressions";
		$columns = array("id_answer","id_user","unlikes");
		$records= array($answer_id,$user,"1");
		
		if($dbutils->is_exists($table, $columns, $records) == 0){
			$table = "impressions";
			$columns = array("id_answer","id_user","likes","unlikes","favorite");
			$records= array($answer_id,$user,"0","1","0");
				
			$dbutils->insert_records($table, $columns, $records);
		}
		
		$table = "impressions";
		$columns = array("id_answer","id_user","likes");
		$records= array($answer_id,$user,"1");
		
		if($dbutils->is_exists($table, $columns, $records) == 1){
			$table = "impressions";
			$columns = array("id_answer","id_user","likes");
			$records= array($answer_id,$user,"1");
				
			$dbutils->delete_record($table, $columns, $records);
		}
	}
	function favorateAnswer() {
		$answer_id = $_POST['answer_id'];
		$user = $_POST['user'];
		
		$dbutils = new db_utils();
		
		$table = "impressions";
		$columns = array("id_answer","id_user","favorite");
		$records= array($answer_id,$user,"1");
		
		if($dbutils->is_exists($table, $columns, $records) == 0){
			$table = "impressions";
			$columns = array("id_answer","id_user","likes","unlikes","favorite");
			$records= array($answer_id,$user,"0","0","1");
				
			$dbutils->insert_records($table, $columns, $records);
		}else{
			$dbutils->delete_record($table, $columns, $records);
		}
		
		
	}
	
	function search(){
		$search_key = $_POST['search_key'];
		
		$dbutils = new db_utils();
		$table = "questions";
		$columns = array("question","tags");
		$records= array($search_key,$search_key);
		
		$questions = $dbutils->search($table, $columns, $records);
		if(count($questions) == 0){
			echo '<section class="card" ><h1>There are no questions with the tags or words you specified</h1></section>';
		}else{
				
			for($i = 0; $i <count($questions); $i++){
				$id_question = $questions[$i]['id_question'];
				$id_user =  $questions[$i]['id_user'];
				$question =  $questions[$i]['question'];
				$tags =  $questions[$i]['tags'];
				$commit_time =  $questions[$i]['commit_time'];
		
		
				echo $quiz = '<section class="card" onclick="loadAnswersFor('.$id_question.')"><h1>'.$question.'</h1><br /><label> Answers('.get_question_answer_count($id_question).')</label><h2> Tags : '.$tags.'</h2><label> Poster : '.get_question_poster($id_user).'</label><label> Post Time : '.$commit_time.'</label></section>';
		
		
		
			}
				
		}
		
	}
?>