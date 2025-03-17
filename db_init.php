<?php
try {
    $db = new SQLite3('applications.db');
    
    // Создание таблицы заявок
    $db->exec('CREATE TABLE IF NOT EXISTS applications (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        age INTEGER NOT NULL,
        tariff TEXT NOT NULL,
        comments TEXT,
        notification_sent INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    
    echo "База данных успешно инициализирована";
} catch (Exception $e) {
    echo "Ошибка при создании базы данных: " . $e->getMessage();
}
?> 