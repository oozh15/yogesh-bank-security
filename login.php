<?php
require_once 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['temp_id'] = $user['id'];
        $_SESSION['user_mobile'] = $user['mobile'];
        $_SESSION['otp'] = rand(1000, 9999);
        header("Location: otp.php");
        exit;
    } else { $error = "ACCESS DENIED: CREDENTIALS INVALID"; }
}
renderHeader('Gateway');
?>

<div class="flex-grow flex items-center justify-center p-6">
    <div class="w-full max-w-sm bg-[#0d1117] border border-white/5 p-12 rounded-[3rem] shadow-2xl text-center">
        <h1 class="text-3xl font-black uppercase italic tracking-tighter mb-10">Yogesh<span class="text-blue-600">Bank</span></h1>
        
        <?php if($error): ?><p class="text-red-500 text-[10px] font-bold mb-6"><?php echo $error; ?></p><?php endif; ?>

        <form method="POST" class="space-y-4 text-left">
            <input name="email" type="email" placeholder="NODE EMAIL" class="w-full bg-black border border-white/10 p-5 rounded-2xl text-xs font-bold tracking-widest outline-none focus:border-blue-600 transition" required>
            <input name="password" type="password" placeholder="PASSKEY" class="w-full bg-black border border-white/10 p-5 rounded-2xl text-xs font-bold tracking-widest outline-none focus:border-blue-600 transition" required>
            <button class="w-full bg-white text-black py-5 rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] hover:bg-gray-200 transition mt-4">Authenticate</button>
        </form>
        <p class="mt-8 text-[9px] text-gray-600 font-bold uppercase">System node: inactive? <a href="signup.php" class="text-blue-500">Enroll</a></p>
    </div>
</div>

<?php renderFooter(); ?>