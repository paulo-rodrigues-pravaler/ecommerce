<?php
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app->get('/admin/users/:iduser/password', function($iduser) {
	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-password", array(
		"user"=>$user->getValues(),		
		"msgError"=>User::getError(),
		"msgSuccess"=>User::getSuccess()
	));
});



$app->post('/admin/users/:iduser/password', function($iduser) {
	
	User::verifyLogin();

	if(!isset($_POST['despassword']) || $_POST['despassword']  === ''){
		User::setError("Preencha uma nova senha.");
		header("Location: /admin/users/" . $iduser . "/password");
		exit;		
	}

	if(!isset($_POST['despassword-confirm']) || $_POST['despassword-confirm']  === ''){
		User::setError("Confirme sua nova senha.");
		header("Location: /admin/users/" . $iduser . "/password");
		exit;		
	}

	if($_POST['despassword'] !== $_POST['despassword-confirm']){
		User::setError("Confirme corretamente sua nova senha.");
		header("Location: /admin/users/" . $iduser . "/password");
		exit;	
	}

	$user = new User();

	$user->get((int)$iduser);

	$user->setPassword(User::getPasswordHash($_POST['despassword']));

	User::setSuccess("Senha alterada com Successo!");

	header("Location: /admin/users/" . $iduser . "/password");
	exit;
});


$app->get('/admin/users', function() {
	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search']: "";
	$page = (isset($_GET['page'])) ? $_GET['page']: 1;
	$userspage = (isset($_GET['users-page']) && $_GET['users-page'] != "") ? $_GET['users-page']: 15;

	$allusers = count(User::listAll());	

	if($search != ""){
		$pagination = User::getUsersPageSearch($search, $page, $userspage);
	} else{
		$pagination = User::getUsersPage($page, $userspage);
	}

	//$pagination = User::getUsersPage($page, passar o número máximo por página);
	$pages = [];

	for($x = 0; $x < $pagination["pages"]; $x++){
		array_push($pages, [
			"href"=>"/admin/users?" . http_build_query([
				"page"=>$x+1,
				"search"=>$search
			]),
			"text"=>$x+1
		]);
	}
	
	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$pagination['data'],
		"search"=>$search,
		"pagination"=>$userspage,
		"pages"=>$pages,
		"allusers"=> $allusers		
	));


});


$app->get('/admin/users/create', function() {
	User::verifyLogin();
	
	$page = new PageAdmin();

	$page->setTpl("users-create");


});

$app->get('/admin/users/:iduser/delete', function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

$app->get('/admin/users/:iduser', function($iduser) {
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);
	
	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
});


$app->post("/admin/users/create", function () {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

		"cost"=>12
	]);

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

$app->post('/admin/users/:iduser', function($iduser){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	$user->setPhoto($_FILES["file"]);	

	header("Location: /admin/users");
	exit;

});

$app->post('/admin/user-profile/:iduser', function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("user-profile", array(
		"user"=>$user->getValues(),		
		"msgError"=>User::getError(),
		"msgSuccess"=>User::getSuccess()
	));	

	exit;

});
?>