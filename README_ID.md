# SOLID Principles - dijelaskan dengan mudah dan sederhana

"SOLID Principles" adalah sebuah "coding standard" yang harus dipahami secara matang
konsepnya oleh semua *developer* untuk men-*develop* sebuah software dengan jalan yang tepat
untuk menghindari desain yang buruk dikemudian hari.
Konsep ini dipromosikan pertama kali oleh Robert C Martin yang kemudian digunakan lintas
bahasa dengan desain yang berorientasi objek.
Ketika digunakan dengan tepat, konsep ini akan membuat baris kode anda lebih mudah
di kembangkan, logis, dan tentunya mudah dibaca dan dipahami.

Saat seorang *developer* membuat sebuah *software* dengan desain yang buruk, kodenya akan menjadi
tidak fleksibel, dan lebih rapuh, perubahan kecil dalam *software* akan mengakibatkan bugs (kesalahan).
Untuk alasan itulah, kita harus mengikuti "SOLID Principles".

Perlu beberapa waktu untuk memahaminya, tetapi jika Anda menulis kode dengan mengikuti prinsip-prinsip tersebut, itu akan meningkatkan kualitas kode dan akan membantu untuk memahami bagaimana *software* yang dirancang dengan baik.

Untuk memahami prinsip SOLID, Anda harus mengetahui penggunaan `interface` dengan baik.
Jika Anda belum memahami konsep dari `interface` tersebut, Anda dapat membaca ini [doc](https://medium.com/@NahidulHasan/understanding-use-of-interface-and-abstract-class-9a82f5f15837)

Saya akan mencoba menjelaskan SOLID Principles dengan cara yang sederhana, jadi ini akan mudah dipahami bagi pemula. Mari kita lanjut ke pembahasannya satu per satu:

## Single Responsibility Principles:

>Sebuah *class* harus mempunyai satu, dan hanya satu alasan untuk diubah.

Sebuah *class* harus digunakan untuk satu kegunaan pula, ini tidak berarti bahwa setiap *class* hanya memiliki satu *method* tetapi semuanya harus berhubungan langsung dengan  *class* yang bertanggung jawab. Semua *method* dan *properties* nya harus bekerja untuk tujuan yang sama. Jika sebuah *class* melayani beberapa tujuan atau tanggung jawab, (multiple purposes / responsibilty) maka *class* tersebut harus dipecah menjadi *class* baru.

Mari kita lihat kode berikut ini:

```php
namespace Demo;
use DB;
class OrdersReport
{
    public function getOrdersInfo($startDate, $endDate)
    {
        $orders = $this->queryDBForOrders($startDate, $endDate);
        return $this->format($orders);
    }
    protected function queryDBForOrders($startDate, $endDate)
    {   // If we would update our persistence layer in the future,
        // we would have to do changes here too. <=> reason to change!
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
    protected function format($orders)
    {   // If we changed the way we want to format the output,
        // we would have to make changes here. <=> reason to change!
        return '<h1>Orders: ' . $orders . '</h1>';
    }
}
```

*Class* diatas melanggar prinsip "Single Responsibility". Kenapa *class* tersebut mengambil data dari database? Ini berkaitan dengan persistensi. Persistensi berhubungan dengan (menyimpan dan mengambil) data dari penyimpanan data (sepert database, misalnya). Jadi ini bukanlah tanggung jawab dari *class* ini.

Format method selanjutnya juga tidak memiliki tanggung jawab dari *class* ini. Karena kita mungkin membutuhkan format data yang berbeda seperti XML, JSON, HTML dll.

Jadi, kode yang sudah di *refactory* akan dijelaskan seperti dibawah ini:
```php
namespace Report;
use Report\Repositories\OrdersRepository;
class OrdersReport
{
    protected $repo;
    protected $formatter;
    public function __construct(OrdersRepository $repo, OrdersOutPutInterface $formatter)
    {
        $this->repo = $repo;
        $this->formatter = $formatter;
    }
    public function getOrdersInfo($startDate, $endDate)
    {
        $orders = $this->repo->getOrdersWithDate($startDate, $endDate);
        return $this->formatter->output($orders);
    }
}

namespace Report;
interface OrdersOutPutInterface
{
	public function output($orders);
}
namespace Report;
class HtmlOutput implements OrdersOutPutInterface
{
    public function output($orders)
    {
        return '<h1>Orders: ' . $orders . '</h1>';
    }
}

namespace Report\Repositories;
use DB;
class OrdersRepository
{
    public function getOrdersWithDate($startDate, $endDate)
    {
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
}
```

## Open-closed Principle :

> Entitas harus *open* untuk *ekstension*, tetapi *closed* untuk modifikasi.

Entitas dari *software* (*class*, *module*, *function*, dll.) harus bisa di *extend* tanpa harus mengubah konten dari *class* yang di*extend*. Jika kita menerapkan prinsip ini dengan kuat, maka mungkin untuk melakukan modifikasi perilaku dari kode yang kita miliki tanpa perlu menyentuh sedikitpun dari kode aslinya.

Perhatikan contoh berikut ini:

```php
class Rectangle
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
}

class Circle
{
    public $radius;
    public function __construct($radius)
    {
        $this->radius = $radius;
    }
}

class CostManager
{
    public function calculate($shape)
    {
        $costPerUnit = 1.5;
        if ($shape instanceof Rectangle) {
            $area = $shape->width * $shape->height;
        } else {
            $area = $shape->radius * $shape->radius * pi();
        }
        
        return $costPerUnit * $area;
    }
}
$circle = new Circle(5);
$rect = new Rectangle(8,5);
$obj = new CostManager();
echo $obj->calculate($circle);
```

Jika kita ingin menggunakan `calculate()` untuk menghitung persegi (*Square*), kita harus melakukan modifikasi *method* yang ada di *class* `CostManager`. Ini melanggar prinsip "*Open-closed*". Menurut prinsip ini, kita tidak bisa melakukan modifikasi, kita hanya dapat meng-*extend*. Jadi bagaimana kita mengatasi masalah ini?
Perhatikan kode berikut ini:

```php
interface AreaInterface
{
    public  function calculateArea();
}

class Rectangle implements AreaInterface
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
    public  function calculateArea(){
        $area = $this->height *  $this->width;
        return $area;
    }
}
  
class Circle implements  AreaInterface
{
    public  $radius;
    public function __construct($radius)
    {
        $this->radius = $radius;
    }
    public  function calculateArea(){
        $area = $this->radius * $this->radius * pi();
        return $area;
    }
}

class CostManager
{
    public function calculate(AreaInterface $shape)
    {
        $costPerUnit = 1.5;
        $totalCost = $costPerUnit * $shape->calculateArea();
        return $totalCost;
    }
}
$circle = new Circle(5);
$obj = new CostManager();
echo $obj->calculate($circle);
```

Sekarang, kita bisa menghitung area persegi tanpa harus melakukan modifikasi terhadap *class* `CostManager`.

*catatan: contoh diatas menggunakan `interface`.


## Liskov Substitution Principle :

Liskov Substitution Principle diperkenalkan oleh Barbara Liskov dalam acara konferensinya dengan *keynote* "Data Abstraction" pada tahun 1987. Barbara Liskov dan Jeannette Wing mem-formulasikan prinsip ini secara ringkas dalam sebuah makalah tahun 1994 sebagai berikut:

>Let φ(x) be a property provable about objects x of type T. Then φ(y) should be true for objects y of type S where S is a subtype of T.


Versi yang lebih mudah dibacanya mengulangi hampir semuanya, seperti yang dikatakan Bertrand Meyer, tetapi itu sepenuhnya bergantung pada `type-system`:

>1. Preconditions cannot be strengthened in a subtype.
>1. *Preconditions* tidak dapat diperkuat dalam *subtype*.

>2. Postconditions cannot be weakened in a subtype.
>2. *Postconditions* tidak dapat dilemahkan dalam *subtype*.

>3. Invariants of the supertype must be preserved in a subtype.
>3. *Invariants* dari *supertype* harus dipertahankan dalam *subtype*.

Robert Martin membuat definisinya terdengar lebih lancar dan ringkas pada tahun 1996 :

>Functions that use pointers of references to base classes must be able to use objects of derived classes without knowing it.
>*Function* yang menguunakan *pointers of references* ke *class* utamanya, harus dapat menggunakan *object* dari *class* turunannya tanpa mengetahuinya.

Atau sederhananya: *Subclass* atau *class* turunan, harus dapat disubstitusikan untuk kelas dasar/induk (*parent*) nya.

Ini menyatakan bahwa setiap implementasi *abstraction* (*interface*) harus dapat diganti di mana pun abstraksinya diterima. Pada dasarnya, perlu diperhatikan saat kita menggunakan *interface* di dalam kode, kita tidak hanya memiliki kontrak input yang diterima *interface* tetapi juga output yang dikembalikan oleh *Class* yang berbeda yang mengimplementasikan *interface* itu; mereka seharusnya dari tipe yang sama.

Sebuah cuplikan kode yang menunjukkan pelanggaran prinsip LSP (Liskov Substitution Principle) dan bagaimana cara memperbaikinya:

```php
interface LessonRepositoryInterface
{
    /**
     * Fetch all records.
     *
     * @return array
     */
    public function getAll();
}

class FileLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        // return through file system
        return [];
    }
}

class DbLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        /*
            Melanggar LSP karena:
              - return type nya berbeda
              - konsumen subclass ini dan FileLessonRepository tidak akan bekerja secara identik
         */
        // untuk memperbaikinya, seperti ini:
        // return Lesson::all();
        return Lesson::all()->toArray();
    }
}
```


## Interface Segregation Principle :

>Klien tidak boleh dipaksa untuk mengimplementasikan *interface* yang tidak digunakannya. 

Aturan ini berarti bahwa kita harus memecah *interface* kita menjadi banyak yang lebih kecil, sehingga mereka bisa memenuhi kebutuhan klien kita.

Mirip dengan "Single Responsibility Principle", tujuan dari "Interface Segregation Principle" adalah untuk meminimalisir dari efek samping dan pengulangan dengan membagi *software* jadi beberapa bagian, yang independen.

Mari kita lihat contoh berikut:

```php
interface workerInterface
{
    public  function work();
    public  function  sleep();
}

class HumanWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function  sleep()
    {
        var_dump('sleep');
    }

}

class RobotWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function sleep()
    {
        // No need
    }
}
```

Dari kode diatas, `RobotWorker` tidak butuh tidur, tetapi *class* tersebut harus mengimplementasikan *method* `sleep()` karena kita tahu bahwa semua *method* itu abstrak di *interface*. Itu melanggar prinsip "Interface Segregation". Bagaimana kita dapat memperbaikinya? coba lihat kode berikut ini:

```php
interface WorkAbleInterface
{
    public  function work();
}

interface SleepAbleInterface
{
    public  function  sleep();
}

class HumanWorker implements WorkAbleInterface, SleepAbleInterface
{
    public  function work()
    {
        var_dump('works');
    }
    public  function  sleep()
    {
        var_dump('sleep');
    }
}

class RobotWorker implements WorkAbleInterface
{
    public  function work()
    {
        var_dump('works');
    }
}
```


## Dependency Inversion Principle :

>High-level modules tidak boleh bergantung pada low-level modules. Keduanya harus bergantung pada abstractions.

>Abstraction tidak boleh bergantung pada detail. Detail harus bergantung pada abstractions.

Atau sederhananya: Bergantung pada Abstractions bukan pada concretions

Dengan menerapkan "Dependency Inversion", *modules* dapat dengan mudah diubah oleh *modules* lain hanya dengan mengubah *dependency* modul dan *High-level* modul tidak akan terpengaruh oleh perubahan apa pun pada Low-level modul.

Perhatikan kode berikut:

```php
class MySQLConnection
{
   /**
    * db connection
    */
    public function connect()
    {
      var_dump('MYSQL Connection');
    }
}

class PasswordReminder
{    
    /**
     * @var MySQLConnection
     */
     private $dbConnection;
     
     public function __construct(MySQLConnection $dbConnection) 
    {
      $this->dbConnection = $dbConnection;
    }
}
```

Ada kesalahpahaman umum bahwa *dependency inversion* hanyalah cara lain untuk mengatakan *dependency injection*. Namun, keduanya tidak sama.

Dalam kode di atas Meskipun *class* `MySQLConnection` melakukan *Injecting* di *class* `PasswordReminder`, tetapi itu tergantung pada `MySQLConnection`.

Modul *High-level* `PasswordReminder` tidak boleh bergantung pada modul  *low-level* `MySQLConnection`.

Jika kita ingin mengubah koneksi dari `MySQLConnection` ke `MongoBDConnection`, kita harus mengubahnya secara "*hard-code*" *constructor injection* di dalam *class* `PasswordReminder`.

*Class* `PasswordReminder` harus bergantung pada *Abstractions* (abstraksi), bukan pada *concretions* (paten). Tapi bagaimana caranya? Perhatikan contoh berikut ini:

```php
interface ConnectionInterface
{
   public function connect();
}

class DbConnection implements ConnectionInterface
{
   /**
    * db connection
    */
    public function connect()
    {
     var_dump('MYSQL Connection');
    }
}

class PasswordReminder
{
   /**
    * @var DBConnection
    */
    private $dbConnection;
    
    public function __construct(ConnectionInterface $dbConnection)
    {
      $this->dbConnection = $dbConnection;
    }
}
```

Dalam kode diatas, kita ingin mengubah koneksi dari `MySQLConnection` ke `MongoDBConnection`, kita tidak perlu mengubah *constructor injection* di *class* `PasswordReminder`. Karena di *class* `PasswordReminder` bergantung pada *Abstractions*, bukan *concretion* (paten).

Publikasi "Better Programming" sudah menerbitkan artikel ini. Jika anda menyukainya, anda bisa membacanya di halaman blog "Better Programming", silahkan menuju ke sini [link](https://medium.com/better-programming/solid-principles-simple-and-easy-explanation-f57d86c47a7f).

Terima kasih sudah membaca.


### License

Perangkat lunak Open-sourced ini dilisensikan dibawah [MIT license](http://opensource.org/licenses/MIT)
