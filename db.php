<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$host = 'sql212.infinityfree.com';
$db   = 'if0_40931331_minibank'; 
$user = 'if0_40931331';
$pass = 'n1EGccTe9B2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) { die("System Offline."); }

function renderHeader($title) {
    echo '<!DOCTYPE html><html><head><title>YOGESH BANK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head>
    <body class="bg-[#05070a] text-white min-h-screen flex flex-col">
    <nav class="border-b border-white/5 h-20 flex items-center px-10 justify-between bg-[#05070a]">
        <div class="flex items-center gap-6">
            <a href="index.php?ref='.time().'" class="text-xl font-black tracking-tighter uppercase italic">Yogesh<span class="text-blue-600">Bank</span></a>
            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-600 border-l border-white/10 pl-6">'.$title.'</span>
        </div>
        <div class="flex gap-6 items-center">
            <a href="logout.php" class="text-[10px] font-bold uppercase tracking-widest text-red-500 bg-red-500/5 px-4 py-2 rounded-lg border border-red-500/10">Terminate</a>
        </div>
    </nav>';

    if(isset($_SESSION['success'])) {
        echo "<script>window.onload=()=>{Swal.fire({text:'".$_SESSION['success']."',icon:'success',background:'#0d1117',color:'#fff'});}</script>";
        unset($_SESSION['success']);
    }
}

// New Footer function to show your name on every page
function renderFooter() {
    echo '<footer class="mt-auto p-10 flex justify-end">
            <div class="flex items-center gap-3 opacity-40 hover:opacity-100 transition duration-500">
                <div class="h-[1px] w-8 bg-blue-600"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.5em] text-white">By Yogesh</span>
                <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
            </div>
          </footer>
    </body></html>';
}
?>