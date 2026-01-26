<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hash = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, mobile, password, balance) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['mobile'], $hash, $_POST['balance']]);
    $_SESSION['success'] = "Account Registered. You can now authenticate.";
    header("Location: login.php"); exit;
}
renderHeader('Enrollment');
?>
<div class="h-[calc(100-80px)] flex items-center justify-center p-6">
    <div class="w-full max-w-sm bg-[#0d1117] border border-white/5 p-10 rounded-[2.5rem]">
        <h2 class="text-xl font-bold mb-8 text-center uppercase tracking-widest">Enrollment</h2>
        <form method="POST" class="space-y-4">
            <input name="name" placeholder="Full Name" class="w-full bg-black border border-white/10 p-4 rounded-2xl text-sm outline-none focus:border-blue-600" required>
            <input name="email" type="email" placeholder="Email" class="w-full bg-black border border-white/10 p-4 rounded-2xl text-sm outline-none focus:border-blue-600" required>
            <input name="mobile" placeholder="Mobile (+00...)" class="w-full bg-black border border-white/10 p-4 rounded-2xl text-sm outline-none focus:border-blue-600" required>
            <input name="balance" type="number" placeholder="Initial Deposit Amount" class="w-full bg-black border border-white/10 p-4 rounded-2xl text-sm outline-none focus:border-blue-600" required>
            <input name="pass" type="password" placeholder="Set Passkey" class="w-full bg-black border border-white/10 p-4 rounded-2xl text-sm outline-none focus:border-blue-600" required>
            <button class="w-full bg-white text-black py-4 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition mt-4">Create Node</button>
        </form>
    </div>
</div>