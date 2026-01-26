<?php 
require_once 'db.php'; 
$msg = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $recipient_email = $_POST['email'];
    $sender_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // Check sender balance
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
        $stmt->execute([$sender_id]);
        $sender = $stmt->fetch();

        // Check if recipient exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$recipient_email]);
        $recipient = $stmt->fetch();

        if (!$recipient) {
            throw new Exception("Recipient node not found.");
        } elseif ($sender['balance'] < $amount) {
            throw new Exception("Insufficient credit for transfer.");
        } elseif ($amount <= 0) {
            throw new Exception("Invalid amount.");
        } else {
            // Deduct from sender
            $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?")->execute([$amount, $sender_id]);
            // Add to recipient
            $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")->execute([$amount, $recipient['id']]);
            // Log transaction
            $pdo->prepare("INSERT INTO transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)")->execute([$sender_id, $recipient['id'], $amount]);
            
            $pdo->commit();
            $msg = "Transfer Successful. Transaction logged.";
            $status = "success";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = $e->getMessage();
        $status = "error";
    }
}

renderHeader('Asset Transfer'); 
?>
<div class="max-w-7xl mx-auto px-10 py-12 flex justify-center">
    <div class="w-full max-w-md bg-[#0d1117] border border-white/5 p-12 rounded-[3rem]">
        <h2 class="text-2xl font-bold mb-8 text-center tracking-tight">Move Assets</h2>
        
        <?php if($msg): ?>
            <div class="mb-6 p-4 rounded-2xl text-xs font-bold text-center <?php echo $status === 'success' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="text-[9px] uppercase tracking-[0.2em] text-gray-500 font-black ml-2 mb-2 block">Recipient Email</label>
                <input name="email" type="email" placeholder="network@node.apex" class="w-full bg-black border border-white/10 p-4 rounded-2xl text-sm outline-none focus:border-blue-600 transition" required>
            </div>
            <div>
                <label class="text-[9px] uppercase tracking-[0.2em] text-gray-500 font-black ml-2 mb-2 block">Amount (USD)</label>
                <input name="amount" type="number" step="0.01" placeholder="0.00" class="w-full bg-black border border-white/10 p-4 rounded-2xl text-sm outline-none focus:border-blue-600 transition" required>
            </div>
            <button class="w-full bg-blue-600 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-blue-500 transition shadow-lg shadow-blue-600/20 mt-4">Execute Transfer</button>
        </form>
    </div>
</div>
</body></html>