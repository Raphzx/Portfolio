<?php
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Menghindari error SSL lokal

    $response = curl_exec($ch);

    echo "<script>
            alert('Pesan berhasil dikirim!');
            window.location.href = '../index.php#contact';
          </script>";
}
?>