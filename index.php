<?php 
require_once 'db.php';
$uid = $_SESSION['user_id'] ?? null;
if(!$uid) { header("Location: login.php"); exit; }

$u = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$u->execute([$uid]);
$user = $u->fetch();

renderHeader('Vault');
?>

<main class="max-w-7xl mx-auto px-10 py-16 w-full">
    <div class="mb-12">
        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-500 mb-2">Welcome Back</p>
        <h2 class="text-4xl font-bold uppercase tracking-tight"><?php echo htmlspecialchars($user['name']); ?></h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-[#0d1117] border border-white/5 p-12 rounded-[3rem] relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-8 text-[10px] font-black text-white/10 uppercase italic">Yogesh Bank Secure Node</div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-4">Available Balance</p>
            <h1 class="text-7xl font-light tracking-tighter">$<?php echo number_format($user['balance'], 2); ?></h1>
            <div class="mt-12 flex gap-4">
                <a href="transfer.php" class="bg-blue-600 px-8 py-4 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-blue-500 transition shadow-xl shadow-blue-600/20">Send Capital</a>
            </div>
        </div>
        
        <div class="bg-[#0d1117] border border-white/5 p-10 rounded-[3rem] flex flex-col justify-between">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-600">Active Analytics</h3>
            <div class="h-32 flex items-end gap-1">
                <?php for($i=0;$i<12;$i++): $h=rand(20,100); ?>
                    <div class="flex-1 bg-white/5 rounded-t-sm" style="height:<?php echo $h; ?>%"></div>
                <?php endfor; ?>
            </div>
            <a href="analytics.php" class="text-[10px] font-black uppercase text-blue-500 hover:text-white transition">View Full Ledger →</a>
        </div>
    </div>
</main>

<?php renderFooter(); ?>