<?php
header('Content-Type: application/json');

try {
    // Подключение к БД
    $db = new SQLite3('applications.db');
    
    // Получение данных из POST запроса
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Подготовка и выполнение запроса
    $stmt = $db->prepare('INSERT INTO applications (name, email, age, tariff, comments) VALUES (:name, :email, :age, :tariff, :comments)');
    
    $stmt->bindValue(':name', htmlspecialchars($data['name']), SQLITE3_TEXT);
    $stmt->bindValue(':email', htmlspecialchars($data['email']), SQLITE3_TEXT);
    $stmt->bindValue(':age', intval($data['age']), SQLITE3_INTEGER);
    $stmt->bindValue(':tariff', htmlspecialchars($data['tariff']), SQLITE3_TEXT);
    $stmt->bindValue(':comments', htmlspecialchars($data['comments']), SQLITE3_TEXT);
    
    $result = $stmt->execute();
    
    if ($result) {
        // Отправка email администратору
        $to = "your@email.com"; // Замените на ваш email
        $subject = "Новая заявка на курс Arduino";
        
        $message = "Получена новая заявка:\n\n";
        $message .= "Имя: " . $data['name'] . "\n";
        $message .= "Контакт: " . $data['email'] . "\n";
        $message .= "Возраст: " . $data['age'] . "\n";
        $message .= "Тариф: " . $data['tariff'] . "\n";
        $message .= "Комментарии: " . $data['comments'] . "\n";
        
        $headers = "From: noreply@yoursite.com\r\n"; // Замените на ваш домен
        $headers .= "Reply-To: " . $data['email'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        mail($to, $subject, $message, $headers);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Заявка успешно сохранена'
        ]);
    } else {
        throw new Exception('Ошибка при сохранении заявки');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?> 