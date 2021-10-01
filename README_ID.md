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