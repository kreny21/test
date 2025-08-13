<?php 
use PHPUnit\Framework\TestCase;
require_once 'DatabaseTestTrait.php';
require_once './src/handler.php';
class HandlerTest extends TestCase{

    use DatabaseTestTrait;
    public static function setUpBeforeClass(): void{
        self::initDB();
    }
    protected function setUp(): void{
        $this->resetDB();
    }
    public function testSuccessRequest(){
        $_GET = ['client_id' => 123, 'quiz_id' => 742];
        $_POST = ['grade' => 5, 'comment' => null];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $testResponse = json_decode(processRequest(), true);
        $this->assertEquals(200, $testResponse['code']);
    }
    public function testWrongRequestMethod(){
        $_GET = ['client_id' => 123, 'quiz_id' => 742];
        $_POST = ['grade' => 5, 'comment' => null];
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $testResponse = json_decode(processRequest(), true);
        $this->assertEquals(405, $testResponse['code']);
    }
    public function testIsClientIdMiss(){
        $_GET = ['client_id' => 1, 'quiz_id' => 742];
        $_POST = ['grade' => 5, 'comment' => null];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $testResponse = json_decode(processRequest(), true);
        $this->assertEquals(400, $testResponse['code']);
    }
    public function testIsQuizIdMiss(){
        $_GET = ['client_id' => 123, 'quiz_id' => 1];
        $_POST = ['grade' => 5, 'comment' => null];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $testResponse = json_decode(processRequest(), true);
        $this->assertEquals(400, $testResponse['code']);
    }
    public function testIsClientIdAndQuizIdMiss(){
        $_GET = ['client_id' => 1, 'quiz_id' => 1];
        $_POST = ['grade' => 5, 'comment' => null];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $testResponse = json_decode(processRequest(), true);
        $this->assertEquals(400, $testResponse['code']);
    }
    public function testIsGradeMiss(){
        $_GET = ['client_id' => 123, 'quiz_id' => 742];
        $_POST = ['grade' => 7, 'comment' => null];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $testResponse = json_decode(processRequest(), true);
        $this->assertEquals(400, $testResponse['code']);
    }
    protected function tearDown(): void{
        $_GET = [];
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = null;
    }
}
?>