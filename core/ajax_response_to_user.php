<?
if(isset($_POST['response_to']) and $_POST['response_to'] != ''){
		$response = explode(' ', $_POST['response_to']);
		echo $response[2];
}
?>