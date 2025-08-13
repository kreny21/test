<?php 
use PHPUnit\Framework\TestCase;
require_once './src/DBUtils.php';
require_once 'DatabaseTestTrait.php';
class DBUtilsTest extends TestCase{
    use DatabaseTestTrait;
    public static function setUpBeforeClass(): void{
        self::initDB();
    }
    
    protected function setUp(): void {
        $this->resetDB();

    }
    protected function tearDown(): void{
        $reflection = new ReflectionClass(DBUtils::class);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }
    public function testDatabaseConnectionFailure(){
        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('could not find driver');
        self::$db = new PDO('123:123');
    }
    public function testIsUserOrQuizExistBothExist(){
        $this->assertTrue(DBUtils::isUserOrQuizExist(523,123));
    }
    public function testIsUserOrQuizExistUserMiss(){
        $this->assertFalse(DBUtils::isUserOrQuizExist(523,1));
    }
    public function testIsUserOrQuizExistQuizMiss(){
        $this->assertFalse(DBUtils::isUserOrQuizExist(1,123));
    }
    public function testIsUserOrQuizExistBothMiss(){
        $this->assertFalse(DBUtils::isUserOrQuizExist(1,1));
    }
    public function testIsUserReviewedWhenYes(){
        $this->assertTrue(DBUtils::isUserReviewed(523,123));
    }
    public function testIsUserReviewedWhenNo(){
        $this->assertFalse(DBUtils::isUserReviewed(523,142));
    }
}
?>