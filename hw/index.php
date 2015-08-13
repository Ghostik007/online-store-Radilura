<?
$server = 'localhost';
$user = 'levk';
$password = '5BjpWZ6CHEFnZ5W5';
$database = 'levk';

$connect = mysql_pconnect($server, $user, $password);
mysql_select_db($database, $connect);
mysql_query("SET NAMES utf8");

$fullName = '';
$age = '';
$phone = '';
$profession = array(
	'---',
	'Системный администратор',
	'Программист',
	'Тестировщик'
);


if ($_POST['submit'])
{
	if ($_POST['fullName']){
		$pattern = '/[А-Я][а-я]{1,18}+\s[А-Я][а-я]{1,18}+\s[А-Я][а-я]{1,18}+$/u';
		if (preg_match($pattern, $_POST['fullName'])){
			$fullName = $_POST['fullName'];
		}
		else {
			$error .= 'Полное имя введено неверно<br>';
		}
	}
	else {
		$error .= 'Введите полное имя<br>';
	}

	if ($_POST['age'] > 1 && $_POST['age'] < 200){
		$age = $_POST['age'];
	}
	else{
		$error .= 'Укажите свой реальный возраст<br>';
	}

	if ($_POST['phone']){

		$pattern = '/^(\+7)|(8)\([0-9]{3,4}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/';
		if (preg_match($pattern,$_POST['phone'])){
			$phone = $_POST['phone'];
		}
		else{
			$error .= 'Введите телефон в формате +7(ххх)ххх-хх-хх<br>';
		}
	}
	else{
		$error .= 'Введите ваш телефон<br>';
	}

	if ($_POST['profession'] == '---'){
		$error .= 'Выберите вашу профессию<br>';
	}
	else{
	
		$userProfession = $_POST['profession'];
		echo $userProfession;
	}


	if ($_POST['hobbies'])
	{
		$pattern = '/^Люблю.+/iu';
		if (preg_match($pattern, $_POST['hobbies'])){
			$hobbies = $_POST['hobbies'];
		}
		else{
			$error .= 'Ваши увлечения должны начинаться со слова "Люблю"';
		}
	}
	else {
		$error .= 'Опишите ваши увлечения';
	}

	if (isset($_POST['browser'])){
		if($_POST['browser']=='opera'){
		$opera = 'checked';
		$browser = 'Opera';
		}
		elseif($_POST['browser']=='chrome'){
		$chrome = 'checked';
		$browser = 'Google Chrome';
		}
		else{
		$firefox = 'checked';
		$browser = 'Firefox';
		}
	}

	if($_POST['win']){
		$win = 'checked';
		$os = 'Windows';
	}
	if($_POST['linux']){
		$linux = 'checked';
		$os = 'Linux';
	}
	if($_POST['bsd']){
		$bsd = 'checked';
		$os = 'BSD';
	}
}
?>

<style>
td {
vertical-align: top;
}

.error {
display: inline-block;
padding: 5px;
border: 3px dotted brown;
}
.true {
display: inline-block;
padding: 5px;
border: 3px dotted green;
}
</style>
<form name="registration" action="" method="post">
<table cellspacing="10">
	<tr>
		<td>Полное имя</td>
		<td><input name="fullName" type="text" value="<?=$_POST['fullName'];?>" placeholder="Карманов Лев Андреевич"></td>
	</tr>
	<tr>
		<td>Возраст</td>
		<td><input name="age" type="number" value="<?=$_POST['age'];?>" placeholder="19"></td>
	</tr>
	<tr>
		<td>Телефон</td>
		<td><input name="phone" type="text" value="<?=$_POST['phone'];?>" placeholder="8(902)188-32-98"></td>
	</tr>
	<tr>
		<td>Профессия</td>
		<td>
			<select name="profession">

			<?
			if ($_POST['profession'] != '---'){
				$key = array_search($_POST['profession'], $profession);
				$splice = array_splice($profession, $key, true);
				echo '<option selected value="'.$splice[0].'" ">'.$splice[0].'</option>';
				foreach ($profession as $value){
					echo '<option value="'.$value.'">'.$value.'</option>';
				}
			}
			else{
				foreach ($profession as $value){
					echo '<option value="'.$value.'">'.$value.'</option>';
				}
			}
		
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Ваши увлечения</td>
		<td><textarea name="hobbies" rows="5" cols="50" placeholder="Люблю ..."><?=$_POST['hobbies'];?></textarea></td>
	</tr>
	<tr>
		<td>Знание ОС</td>
		<td>
			<input type="checkbox" name="win" <?=$win?>>Windows</input>
			<input type="checkbox" name="linux" <?=$linux?>>Linux</input>
			<input type="checkbox" name="bsd" <?=$bsd?>>BSD</input>
		</td>
	</tr>
	<tr>
	<td>Любимый браузер</td>
		<td>
			<input type="radio" name="browser" value="chrome" <?=$chrome?>>Google Chrome</input>
			<input type="radio" name="browser" value="opera" <?=$opera?>>Opera</input>
			<input type="radio" name="browser" value="firefox" <?=$firefox?>>Firefox</input>
		</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Отправить"></td>
	</tr>
</form>
</table>

<?
if ($error)
{
	echo '<div class="error">' . $error . '</div>';
}
elseif (!$error && $_POST['submit'])
{
echo 'fullname '.$fullName.' age '.$age.' phone '.$phone.' profession '.$userProfession.' 
hobbies '.$hobbies.' os '.$os.' browser '.$browser.'';
	if ($query = mysql_query("
	INSERT INTO users SET 
	fullName='".$fullName."',
	age='".$age."',
	phone='".$phone."',
	profession='".$userProfession."',
	hobbies='".$hobbies."',
	os='".$os."',
	browser='".$browser."';
	")){
		echo '<div class="true">Всё верно, информация занесена в базу данных!</div>';
	}
	else{
	trigger_error(mysql_error().' '.$query);
	}
	
	echo $query;

}

?>
