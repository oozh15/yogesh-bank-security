<?php
require_once 'db.php';

// Check if user is in the middle of logging in
if (!isset($_SESSION['temp_id'])) {
    header("Location: login.php");
    exit;
}

$otp_code = $_SESSION['otp'];
$mobile = $_SESSION['user_mobile'];
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['code'] == $otp_code) {
        // SUCCESS: Finalize Login
        $_SESSION['user_id'] = $_SESSION['temp_id'];
        unset($_SESSION['temp_id'], $_SESSION['otp']);
        $_SESSION['success'] = "Node Verified. Welcome to Apex.";
        header("Location: index.php");
        exit;
    } else {
        $error = "E-09: SECURITY CODE MISMATCH";
    }
}

renderHeader('Security Protocol');
?>

<div class="h-[calc(100vh-80px)] flex items-center justify-center p-6">
    <div id="sms-pop" class="fixed top-8 right-8 z-[100] transform translate-x-[450px] transition-all duration-700">
        <div class="bg-white text-black p-5 rounded-[2rem] shadow-2xl flex items-center gap-4 border-l-[6px] border-blue-600 min-w-[300px]">
            <div class="bg-blue-100 p-3 rounded-2xl">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293A1 1 0 016 6h8a1 1 0 110 2H6a1 1 0 01-.707-.293z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest">Message • Just Now</p>
                <p class="text-sm font-bold">APEX SECURITY: Your code is <span class="text-blue-600 text-lg"><?php echo $otp_code; ?></span></p>
            </div>
        </div>
    </div>

    <script>
        // Trigger the "SMS" notification with a realistic delay
        setTimeout(() => {
            const pop = document.getElementById('sms-pop');
            pop.classList.remove('translate-x-[450px]');
        }, 1200);
    </script>

    <div class="max-w-sm w-full bg-[#0d1117] border border-white/5 p-12 rounded-[3rem] shadow-2xl text-center">
        <div class="mb-10">
            <h2 class="text-xl font-bold uppercase tracking-widest mb-2">Verify Node</h2>
            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em]">Sent to registered device: <br><span class="text-blue-500 font-black"><?php echo $mobile; ?></span></p>
        </div>

        <?php if($error): ?>
            <p class="text-red-500 text-[10px] font-bold mb-6 uppercase animate-pulse"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <input name="code" type="text" maxlength="4" placeholder="0000" class="w-full bg-black border border-white/10 p-5 rounded-3xl text-center text-4xl font-light tracking-[0.5em] focus:border-blue-600 outline-none transition" required autofocus>
            <button class="w-full bg-blue-600 py-5 rounded-3xl font-bold text-xs uppercase tracking-[0.3em] hover:bg-blue-500 transition shadow-lg shadow-blue-600/20">Confirm Identity</button>
        </form>
        
        <p class="mt-8 text-[9px] text-gray-600 font-bold uppercase tracking-widest">Awaiting Secure Handshake...</p>
    </div>
</div>
</body>
</html>