<?php require_once 'config.php'; if(isset($_SESSION['user_id'])) header("Location: admin.php");
 $err=null; 
if($_SERVER['REQUEST_METHOD']=='POST'){
    $stmt=$mysqli->prepare("SELECT * FROM users WHERE username=?"); $stmt->bind_param("s",$_POST['username']);
    $stmt->execute(); $u=$stmt->get_result()->fetch_assoc();
    if($u && password_verify($_POST['password'],$u['password'])){
        $_SESSION['user_id']=$u['id']; header("Location: admin.php"); exit;
    } else $err="Username/Password salah!";
}
?>
<!DOCTYPE html><html><head><title>Login</title><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
<style>:root{--bg:#0f172a;--card:#1e293b}body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:white;display:flex;height:100vh;align-items:center;justify-content:center;margin:0}
.card{background:var(--card);padding:2rem;border-radius:1rem;width:100%;max-width:400px;box-shadow:0 10px 30px rgba(0,0,0,0.3)}
input{width:100%;padding:12px;margin:10px 0;background:#334155;border:1px solid #475569;border-radius:8px;color:white;box-sizing:border-box}
button{width:100%;padding:12px;background:#2563eb;border:none;border-radius:8px;color:white;font-weight:bold;cursor:pointer;margin-top:10px}
.err{background:#ef4444;padding:10px;border-radius:5px;margin-bottom:15px;text-align:center;font-size:14px}</style></head>
<body><div class="card"><h2 style="text-align:center;margin-bottom:20px">Login Admin</h2>
<?php if($err) echo "<div class='err'>$err</div>"; ?>
<form method="POST"><input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required><button type="submit">MASUK</button></form>
<div style="text-align:center;margin-top:15px"><a href="index.php" style="color:#94a3b8;font-size:12px">← Kembali ke Display</a></div></div></body></html>