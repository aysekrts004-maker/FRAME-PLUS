<?php
// functions.php zaten index.php üzerinden çağrıldığı için doğrudan çalışır
$message = "";

if (isset($_POST["register"])) {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!$name || !$email || !$phone || !$password) {
        $message = "Please fill in all fields.";
    } else {
        $exists = false;
        foreach ($users as $user) {
            if (($user["email"] ?? "") === $email || ($user["phone"] ?? "") === $phone) {
                $exists = true;
                break;
            }
        }

        if ($exists) {
            $message = "This email or phone number is already registered.";
        } else {
            $users[] = [
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "favorites" => []
            ];

            saveUsers($users, $file);
            $message = "Registration successful. You can log in now.";
        }
    }
}