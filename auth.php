<?php
include('config/koneksi.php');
$user = $_POST['username'];
$pass = md5($_POST['password']);
$sql = "SELECT * from user where password='$pass' and username='$user'";
// print_r($sql);exit;
$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);
$row = mysqli_num_rows($query);
if ($row > 0) {
	if (strtolower($user)=='admin') {
		$_SESSION['logged'] = 'admin';
		$_SESSION['level'] = 'admin';
	}elseif ($data['level'] > 0) {
		$logged=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from jabatan where id='".$data['level']."'"));
		$img=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from pegawai where NIP='".$data['username']."'"));
		$_SESSION['logged'] = $logged['nama_jabatan'];
		$_SESSION['level'] = $logged['nama_jabatan'];
		$_SESSION['NIP'] = $img['NIP'];
	}else{
		$_SESSION['logged'] = null;
	}
	$_SESSION['last']= $data['last_activity'];
	$_SESSION['id_user'] = $data['id_user'];
	$_SESSION['name'] = $data['nama'];
	$_SESSION['username'] = $data['username'];
	$_SESSION['img'] = $img['img'];

	if (!empty($_POST["checkbox"])) {
		setcookie("username", $_POST['username']);
		setcookie("password", $_POST['password']);
	}
	header('location: media.php');


}
else {
	$_SESSION['flash']['class']='alert alert-danger';
	$_SESSION['flash']['label']='Username atau password salah';
	$_SESSION['flash']['iconn']='fa fa-ban';
	$_SESSION['flash']['username']=$_POST['username'];

	header('location: index.php');
}
?>