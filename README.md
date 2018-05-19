# SOLID Principles - simple and easy explanation

SOLID Principles is a coding standard that all developers should have a clear 
concept for developing software in a proper way to avoid a bad design. 
It was promoted by Robert C Martin and is used across the object-oriented design spectrum.
When applied properly it makes your code more extendable, logical and easier to read.

When the developer builds a software follow the bad design, the code can become 
inflexible and more brittle, small changes in the software can result in bugs. 
For these reasons, we should follow SOLID Principles.


It takes some time to understand, but coding to follow the principles that can greatly 
improve code quality and even understanding of the most well-designed applications on the web.

Understanding SOLID principles you have to know the use of the interface clearly.
If your concept is not clear about interface then you can read this [doc](https://medium.com/@NahidulHasan/understanding-use-of-interface-and-abstract-class-9a82f5f15837).

I'm going to try to explain SOLID Principles in simplest way so that it's easy 
for beginners to understand. Let's go through each principle one by one:


## Single Responsibility Principle :

>A class should have one, and only one, reason to change.

One class should only serve one purpose, this does not imply that each class should have only one method but they should all relate directly to the responsibility of the class. All the methods and properties should all work towards the same goal. When a class serves multiple purposes or responsibility then it should be made into a new class.

Please look at the following code :

```php
namespace Report;
use Auth;
use DB;
class SalesReport
{
    public function between($startDate, $endDate)
    {
        if (! Auth::check()) {
            throw new \Exception('Authentication required for reporting!');
        }
        $sales = $this->queryDBForSales($startDate, $endDate);
        return $this->format($sales);
    }

    protected function queryDBForSales($startDate, $endDate)
    {
        // If we would update our persistence layer in the future,
        // we would have to do changes here too. <=> reason to change!
        return DB::table('sales')->whereBetween('created_at', [$startDate, $endDate])->sum('charge') / 100;
    }

    protected function format($sales)
    {
        // If we changed the way we want to format the output,
        // we would have to make changes here. <=> reason to change!
        return '<h1>Sales: ' . $sales . '</h1>';
    }
}
```

Above class violates single responsibility principle. Why should this class be interested in the authenticated user? This is application logic! It should be moved to a controller.

Next method is related to the persistence layer. The persistence layer deals with persisting (storing and retrieving) data from a data store (such as a database, for example).So it is not the responsibility of this class.

Next method format is also not the responsibility of this class. Because we may need different format data such as XML, JSON, HTML etc.

So finally the refactored code will be described as below :

```php
namespace Report;
use Report\Repositories\SalesRepository;
class SalesReport
{
  protected $repo;
  protected $formatter;
  public function __construct(SalesRepository $repo, SalesOutputInterface $formatter)
  {
    $this->repo = $repo;
    $this->formatter = $formatter;
  }
  public function between($startDate, $endDate)
  {
    $sales = $this->repo->between($startDate, $endDate);
    return $this->formatter->output($sales);
  }
}


namespace Report;
interface SalesOutputInterface
{
  public function output();
}


namespace Report;
class HtmlOutput implements SalesOutputInterface
{
  public function output($sales)
  {
    return '<h1>Sales: ' . $sales . '</h1>';
  }
}

namespace Report\Repositories;
use DB;
class SalesRepository
{
    protected function between($startDate, $endDate)
    {
        return DB::table('sales')->whereBetween('created_at', [$startDate, $endDate])->sum('charge') / 100;
    }
}
```

## Open-closed Principle :

>Entities should be open for extension, but closed for modification.

Software entities (classes, modules, functions, etc.) be extendable without actually changing the contents of the class you're extending. If we could follow this principle strongly enough, it is possible to then modify the behavior of our code without ever touching a piece of original code.

Please look at the following code :

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

class AreaCalculator
{
    public function calculate($shape)
    {
       
        if ($shape instanceof Rectangle) {
            $area = $shape->width * $shape->height;
        } else {
            $area = $shape->radius * $shape->radius * pi();
        }
       
        return $area;
    }
}
$circle = new Circle(5);
$rect = new Rectangle(8,5);
$obj = new AreaCalculator();
echo $obj->calculate($circle);
```

If we want to calculate the area for Square we have to modify calculate method in AreaCalculator class. It breaks the open-closed principle. According to this principle, we can not modify we can extend. So How we fix this problem see the following code :


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

class AreaCalculator
{
    public function area($shape)
    {
        $area = 0;
        $area = $shape->calculateArea();
        return $area;
    }
}

$circle = new Circle(5);
$obj = new AreaCalculator();
echo $obj->area($circle);
```

Now we can find square's area without modifying AreaCalculator class.


## Liskov Substitution Principle :

>Derived classes must be substitutable for their base classes.
 
It states that any implementation of an abstraction (interface) should be substitutable in any place that the abstraction is accepted. Basically, it takes care that while coding using interfaces in our code, we not only have a contract of input that the interface receives but also the output returned by different Classes implementing that interface; they should be of the same type.

A code snippet to show how violates LSP and how we can fix it :

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
            Violates LSP because:
              - the return type is different
              - the consumer of this subclass and FileLessonRepository won't work identically
         */
        // return Lesson::all();
        // to fix this
        return Lesson::all()->toArray();
    }
}
```


## Interface Segregation Principle :

>A Client should not be forced to implement an interface that it doesn't use.

This rule means that when one class depends upon another, the number of members in the interface that is visible to the dependent class should be minimized.

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

class AndroidWorker implements workerInterface
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

In the above code, AnodroidWorker no needs sleep, but the class has to implement the sleep method because we know that all methods are abstract in the interface. It breaks the Interface segregation law. How we can fix it please see the following code :

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

class AndroidWorker implements WorkAbleInterface
{
    public  function work()
    {
        var_dump('works');
    }
}
```


## Dependency Inversion Principle :

> High-level modules should not depend on low-level modules. Both should depend on abstractions.

> Abstractions should not depend on details. Details should depend on abstractions.

Or simply : Depend on Abstractions not on concretions


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

There's a common misunderstanding that dependency inversion is simply another way to say dependency injection. However, the two are not the same.

In the above code In spite of Injecting MySQLConnection class in PasswordReminder class but it depends on MySQLConnection.

High-level module PasswordReminder should not depend on low-level module MySQLConnection.

If we want to change the connection from MySQLConnection to MongoDBConnection, we have to change hard-coded constructor injection in PasswordReminder class.

PasswordReminder class should depend upon on Abstractions, not on concretions. But How can we do it? Please see the following example :

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

In the above code, we want to change the connection from MySQLConnection to MongoDBConnection, we no need to change constructor injection in PasswordReminder class. Because here PasswordReminder class depends upon on Abstractions, not on concretions.

I hope I have kept this simple enough for developers to understand easily. 

Thanks for reading.