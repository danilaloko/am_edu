<?php
try {
    $db = new SQLite3('applications.db');
    
    // Получаем все заявки, которым еще не отправлено уведомление
    $results = $db->query('SELECT * FROM applications WHERE notification_sent = 0');
    
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        // Отправка email пользователю
        $to = $row['contact'];
        $subject = "Открыта запись на курс Arduino";
        
        $message = "Здравствуйте, " . $row['name'] . "!\n\n";
        $message .= "Рады сообщить, что открыта запись на новый поток курса Arduino.\n";
        $message .= "Вы оставляли заявку на тариф: " . $row['tariff'] . "\n\n";
        $message .= "Для записи на курс, пожалуйста, перейдите по ссылке: http://yoursite.com/enroll\n";
        $message .= "Или свяжитесь с нами по телефону: +7 (XXX) XXX-XX-XX\n\n";
        $message .= "С уважением,\nКоманда Arduino School";
        
        $headers = "From: noreply@yoursite.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if(mail($to, $subject, $message, $headers)) {
            // Обновляем статус отправки в базе
            $db->exec('UPDATE applications SET notification_sent = 1 WHERE id = ' . $row['id']);
        }
    }
    
    echo "Уведомления успешно отправлены";
    
} catch (Exception $e) {
    echo "Ошибка при отправке уведомлений: " . $e->getMessage();
}
?> 