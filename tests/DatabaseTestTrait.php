<?php 
trait DatabaseTestTrait{
    private static $db;

    public static function initDB(): void{
        self::$db = new PDO('sqlite::memory:');
        self::$db->exec('CREATE TABLE clients(client_id INTEGER PRIMARY KEY)');
        self::$db->exec('CREATE TABLE quizzes(quiz_id INTEGER PRIMARY KEY)');
        self::$db->exec('CREATE TABLE reviews (client_id INTEGER NOT NULL,  
                                grade INTEGER NOT NULL,  comment varchar(45) DEFAULT NULL,  quiz_id INTEGER NOT NULL,  
                                review_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, CONSTRAINT client_id FOREIGN KEY (client_id) 
                                REFERENCES clients (client_id),  CONSTRAINT quiz_id FOREIGN KEY (quiz_id) REFERENCES quizzes (quiz_id))');
    }
    
    protected function resetDB(): void {
        self::$db->exec('DELETE FROM clients');
        self::$db->exec('DELETE FROM quizzes');
        self::$db->exec('DELETE FROM reviews');

        self::$db->exec('INSERT INTO clients VALUES(123), (142), (315)');
        self::$db->exec('INSERT INTO quizzes VALUES(523), (742), (915)');
        self::$db->exec('INSERT INTO reviews VALUES(123, 5, null, 523, 1)');
        
        $reflection = new ReflectionClass(DBUtils::class);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue(null, self::$db);

    }
}
?>