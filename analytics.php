<?php 
require_once 'db.php';
$uid = $_SESSION['user_id'];

// Fetch Transactions
$stmt = $pdo->prepare("
    SELECT t.*, u.name as other_party 
    FROM transactions t 
    LEFT JOIN users u ON (t.receiver_id = u.id AND t.sender_id = ?) OR (t.sender_id = u.id AND t.receiver_id = ?)
    WHERE t.sender_id = ? OR t.receiver_id = ? 
    ORDER BY t.created_at DESC LIMIT 10
");
$stmt->execute([$uid, $uid, $uid, $uid]);
$logs = $stmt->fetchAll();

renderHeader('Market Analytics');
?>
<div class="max-w-7xl mx-auto px-10 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-[#0d1117] border border-white/5 p-10 rounded-[3rem]">
            <h3 class="text-xl font-bold mb-10 uppercase tracking-tighter">Capital Performance</h3>
            <div class="flex items-end justify-between h-48 gap-2">
                <?php for($i=0; $i<15; $i++): $h = rand(10, 100); ?>
                    <div class="flex-1 bg-blue-600/10 border-t border-blue-600/30 hover:bg-blue-600/40 transition-all rounded-t-sm" style="height: <?php echo $h; ?>%"></div>
                <?php endfor; ?>
            </div>
            <div class="flex justify-between mt-6 text-[9px] font-black text-gray-600 uppercase tracking-widest">
                <span>Node Alpha</span><span>Node Beta</span><span>Current Protocol</span>
            </div>
        </div>

        <div class="bg-blue-600 p-10 rounded-[3rem] flex flex-col justify-between shadow-2xl shadow-blue-600/20">
            <h3 class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em]">Efficiency Rating</h3>
            <div>
                <p class="text-5xl font-light mb-2">99.8%</p>
                <p class="text-xs text-white/80 leading-relaxed">System integrity verified. All nodes operating within standard parameters.</p>
            </div>
        </div>

        <div class="lg:col-span-3 bg-[#0d1117] border border-white/5 rounded-[3rem] overflow-hidden">
            <div class="p-10 border-b border-white/5">
                <h3 class="text-xl font-bold uppercase tracking-tighter">Transaction Ledger</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-gray-500 uppercase tracking-widest border-b border-white/5">
                        <th class="p-8">Reference ID</th>
                        <th class="p-8">Entity</th>
                        <th class="p-8">Date</th>
                        <th class="p-8 text-right">Amount (USD)</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php foreach($logs as $log): 
                        $isSender = ($log['sender_id'] == $uid);
                    ?>
                    <tr class="border-b border-white/5 hover:bg-white/[0.02] transition">
                        <td class="p-8 font-mono text-xs text-gray-500">#<?php echo $log['id'] + 1000; ?></td>
                        <td class="p-8 font-bold"><?php echo htmlspecialchars($log['other_party'] ?? 'System Reserve'); ?></td>
                        <td class="p-8 text-gray-400"><?php echo date('d M, H:i', strtotime($log['created_at'])); ?></td>
                        <td class="p-8 text-right font-bold <?php echo $isSender ? 'text-red-500' : 'text-emerald-500'; ?>">
                            <?php echo $isSender ? '-' : '+'; ?> $<?php echo number_format($log['amount'], 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(!$logs): ?>
                        <tr><td colspan="4" class="p-20 text-center text-gray-600 uppercase tracking-widest text-[10px]">No activity detected in ledger</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>