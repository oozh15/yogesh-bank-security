<?php 
require_once 'db.php'; 
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch last 5 transactions for the profile ledger
$history = $pdo->prepare("SELECT * FROM transactions WHERE sender_id = ? OR receiver_id = ? ORDER BY t_date DESC LIMIT 5");
$history->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$logs = $history->fetchAll();

renderHeader('Identity'); 
?>
<div class="max-w-7xl mx-auto px-10 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-[#0d1117] border border-white/5 p-10 rounded-[3rem]">
            <h2 class="text-xl font-bold mb-10">Profile Data</h2>
            <div class="space-y-8">
                <div>
                    <label class="text-[9px] uppercase tracking-widest text-gray-600 font-black block mb-1">Legal Name</label>
                    <p class="font-bold text-gray-200"><?php echo htmlspecialchars($user['name']); ?></p>
                </div>
                <div>
                    <label class="text-[9px] uppercase tracking-widest text-gray-600 font-black block mb-1">Network Identity</label>
                    <p class="font-bold text-gray-200"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div>
                    <label class="text-[9px] uppercase tracking-widest text-gray-600 font-black block mb-1">Status</label>
                    <p class="text-green-500 font-bold uppercase text-xs">Active Node</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-[#0d1117] border border-white/5 p-10 rounded-[3rem]">
            <h2 class="text-xl font-bold mb-10">Security Ledger</h2>
            <div class="space-y-4">
                <?php foreach($logs as $log): ?>
                    <div class="flex justify-between items-center p-5 bg-black/40 rounded-2xl border border-white/5">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-600 mb-1"><?php echo $log['t_date']; ?></p>
                            <p class="text-xs font-bold"><?php echo ($log['sender_id'] == $_SESSION['user_id']) ? 'Outgoing Transfer' : 'Incoming Credit'; ?></p>
                        </div>
                        <p class="font-mono font-bold <?php echo ($log['sender_id'] == $_SESSION['user_id']) ? 'text-red-500' : 'text-green-500'; ?>">
                            <?php echo ($log['sender_id'] == $_SESSION['user_id']) ? '-' : '+'; ?>$<?php echo number_format($log['amount'], 2); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
                <?php if(!$logs): ?>
                    <p class="text-center text-gray-600 py-10 italic text-sm">No recent network activity detected.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body></html>