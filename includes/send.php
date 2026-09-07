<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['name'] ?? '');
    $email   = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    $webhookurl = "https://discord.com/api/webhooks/1544869646057078807/w_tYW4f2qi8MjaG82XeFwAXlqrw1rA7sNwyNVRNqDdmuKWwE_Pke35i33UxXxotKgMxZ";

    $msg = "📩 **Pesan Baru dari Portfolio!**\n"
         . "**Nama:** $name\n"
         . "**Email:** $email\n"
         . "**Subjek:** $subject\n"
         . "**Pesan:** $message";

    $json_data = json_encode([
        "content" => $msg,
        "username" => "Portfolio Notification"
    ]);

    $ch = curl_init($webhookurl);
    
    if ($ch === false) {
        die('Error: Gagal inisialisasi cURL. Ekstensi cURL dinonaktifkan oleh provider hosting Anda.');
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 

    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    
    curl_close($ch);

    if ($curl_error) {
        echo "<b>Gagal mengirim webhook!</b><br>Detail Error cURL: " . $curl_error;
    } else {
        echo "<script>
                alert('Pesan berhasil dikirim!');
                window.location.href = '../index.php#contact';
              </script>";
    }
}
?>